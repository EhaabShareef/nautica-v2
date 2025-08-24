<?php

namespace App\Livewire\Admin\Bookings;

use App\Models\Booking;
use App\Models\BookingLog;
use App\Models\Block;
use App\Models\Property;
use App\Models\Slot;
use App\Models\User;
use App\Models\Vessel;
use App\Models\Zone;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class NewReservation extends Component
{
    public int $step = 1;
    public ?string $bookingType = null;

    public string $clientSearch = '';
    public $clientResults = [];
    public ?int $selectedClientId = null;

    public string $vesselSearch = '';
    public $vesselResults = [];
    public ?string $selectedVesselId = null;

    public ?string $selectedProperty = null;
    public ?string $selectedBlock = null;
    public ?string $selectedZone = null;
    public $properties = [];
    public $blocks = [];
    public $zones = [];

    public $startDate;
    public $startTime;
    public $endDate;
    public $endTime;
    public $duration;

    public $availableSlots;
    public ?string $selectedSlot = null;

    private const SERVICES_LIST = [
        'shore_power' => 'Shore Power',
        'water_hookup' => 'Water Hookup',
        'cleaning' => 'Cleaning',
    ];
    public array $selectedServices = [];

    /**
     * Centralized step navigation
     */
    public function goToStep(int $target): void
    {
        logger()->info('Step transition', ['from' => $this->step, 'to' => $target]);

        if ($target === 3) {
            $this->validate([
                'selectedClientId' => 'required|integer|exists:users,id',
                'selectedVesselId' => 'required|integer|exists:vessels,id',
            ]);
        }

        $this->step = $target;
    }

    public function mount(): void
    {
        $this->properties = Property::where('is_active', true)->get();
        $this->availableSlots = collect();
        
        // Initialize date/time fields with defaults
        $this->startDate = now()->format('Y-m-d');
        $this->startTime = now()->format('H:i');
        $this->endTime = now()->addHour()->format('H:i');
        $this->endDate = now()->addDay()->format('Y-m-d');
        $this->duration = 1;
    }

    public function updatedClientSearch(): void
    {
        if (strlen($this->clientSearch) < 2) {
            $this->clientResults = [];
            return;
        }

        try {
            $this->clientResults = User::clients()
                ->active()
                ->notBlacklisted()
                ->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->clientSearch . '%')
                      ->orWhere('email', 'like', '%' . $this->clientSearch . '%');
                })
                ->limit(10)
                ->get();
            logger()->info('Client search', ['term' => $this->clientSearch, 'count' => $this->clientResults->count()]);
        } catch (\Exception $e) {
            logger('Client search error: ' . $e->getMessage());
            $this->clientResults = [];
        }
    }

    public function selectClient(string $id): void
    {
        $client = User::clients()->active()->notBlacklisted()->find($id);
        if (!$client) {
            $this->addError('clientSearch', 'Selected client is not eligible.');
            return;
        }
        $this->selectedClientId = (int) $id;
        $this->clientSearch = $client->name;
        $this->clientResults = [];
        $this->vesselSearch = '';
        $this->selectedVesselId = null;
        $this->loadVessels();
    }
    public function loadVessels(): void
    {
        if (!$this->selectedClientId) {
            $this->vesselResults = [];
            return;
        }

        $query = Vessel::ownedBy($this->selectedClientId)->active();
        
        if (!empty($this->vesselSearch)) {
            $query->where('name', 'like', '%' . $this->vesselSearch . '%');
        }
        
        $this->vesselResults = $query->get();
        logger()->info('Vessel search', ['term' => $this->vesselSearch, 'count' => $this->vesselResults->count()]);
    }

    public function updatedVesselSearch(): void
    {
        $this->loadVessels();
    }

    public function selectBookingType(string $type): void
    {
        $this->bookingType = $type;
        $this->step = 2;
    }

    public function selectVessel(string $id): void
    {
        if (! $this->selectedClientId) {
            $this->addError('vesselSearch', 'Select a client first.');
            return;
        }

        $vessel = Vessel::ownedBy($this->selectedClientId)
                        ->active()
                        ->find($id);

        if (! $vessel) {
            $this->addError('vesselSearch', 'Invalid vessel for this client.');
            return;
        }

        $this->selectedVesselId = $id;
        $this->vesselSearch = $vessel->name;
        $this->vesselResults = [];
    }

    public function selectSlot(int $slotId): void
    {
        $slot = Slot::where('is_active', true)->find($slotId);
        if (! $slot) {
            $this->addError('selectedSlot', 'Invalid slot selected.');
            return;
        }

        $this->selectedSlot = (string) $slotId;
        $this->step = 6;
    }

    public function updatedSelectedProperty($value): void
    {
        $this->blocks = Block::where('property_id', $value)
            ->where('is_active', true)
            ->get();
        $this->selectedBlock = null;
        $this->zones = [];
        $this->selectedZone = null;
    }

    public function updatedSelectedBlock($value): void
    {
        $this->zones = Zone::where('block_id', $value)
            ->where('is_active', true)
            ->get();
        $this->selectedZone = null;
    }

    public function calculateAvailability(): void
    {
        $this->validate($this->rulesAvailability());

        [$start, $end] = $this->buildStartEndFromInputs();
        $now = now();

        $startTime = microtime(true);

        $slotQuery = Slot::query()
            ->with('zone.block.property')
            ->where('is_active', true);

        if ($this->selectedZone) {
            $slotQuery->where('zone_id', $this->selectedZone);
        } elseif ($this->selectedBlock) {
            $slotQuery->whereHas('zone', fn($q) => $q->where('block_id', $this->selectedBlock));
        } elseif ($this->selectedProperty) {
            $slotQuery->whereHas('zone.block', fn($q) => $q->where('property_id', $this->selectedProperty));
        }

        $slotQuery->whereDoesntHave('bookings', function ($q) use ($start, $end, $now) {
            $q->where('status', '!=', 'cancelled')
              ->where(function ($q2) use ($start, $end) {
                  $q2->where('start_date', '<', $end)
                     ->where('end_date', '>', $start);
              })
              ->where(function ($q3) use ($now) {
                  $q3->whereNull('hold_expires_at')
                     ->orWhere('hold_expires_at', '>', $now);
              });
        });

        $this->availableSlots = $slotQuery->orderBy('code')->get();

        $durationMs = (int) ((microtime(true) - $startTime) * 1000);
        logger()->info('Availability query', ['count' => $this->availableSlots->count(), 'duration_ms' => $durationMs]);

        $this->step = 5;
    }
    public function createBooking()
    {
        $this->validate();

        [$startDateTime, $endDateTime] = $this->buildStartEndFromInputs();

        $booking = null;

        DB::transaction(function () use (&$booking, $startDateTime, $endDateTime) {
            $overlap = Booking::where('slot_id', $this->selectedSlot)
                ->where('status', '!=', 'cancelled')
                ->where('start_date', '<', $endDateTime)
                ->where('end_date', '>', $startDateTime)
                ->lockForUpdate()
                ->exists();

            if ($overlap) {
                throw ValidationException::withMessages([
                    'selectedSlot' => 'The selected slot is no longer available.',
                ]);
            }

            $booking = Booking::create([
                'booking_number' => $this->generateBookingNumber(),
                'user_id' => $this->selectedClientId,
                'vessel_id' => $this->selectedVesselId,
                'slot_id' => $this->selectedSlot,
                'start_date' => $startDateTime,
                'end_date' => $endDateTime,
                'status' => 'pending',
                'booking_type' => $this->bookingType,
                'additional_data' => [
                    'services' => $this->selectedServices,
                    'preferences' => [
                        'property_id' => $this->selectedProperty,
                        'block_id' => $this->selectedBlock,
                        'zone_id' => $this->selectedZone,
                    ],
                ],
            ]);

            BookingLog::create([
                'booking_id' => $booking->id,
                'user_id' => Auth::id(),
                'action' => 'created',
                'new_status' => 'pending',
            ]);
        });

        logger()->info('Booking created', ['booking_id' => $booking->id]);

        session()->flash('success', 'Booking created successfully.');
        return redirect()->route('admin.bookings.new');
    }

    protected function rules(): array
    {
        return [
            'bookingType' => 'required|string',
            'selectedClientId' => 'required|integer|exists:users,id',
            'selectedVesselId' => 'required|integer|exists:vessels,id',
            'startDate' => 'required|date',
            'startTime' => 'required|date_format:H:i',
            'endDate' => 'required|date|after_or_equal:startDate',
            'endTime' => 'required|date_format:H:i',
            'selectedSlot' => 'required|integer|exists:slots,id',
        ];
    }

    protected function rulesAvailability(): array
    {
        return [
            'startDate' => 'required|date',
            'startTime' => 'required|date_format:H:i',
            'endDate' => 'required|date|after_or_equal:startDate',
            'endTime' => 'required|date_format:H:i',
        ];
    }

    protected function buildStartEndFromInputs(): array
    {
        $start = Carbon::createFromFormat('Y-m-d H:i', $this->startDate . ' ' . $this->startTime);
        $end = Carbon::createFromFormat('Y-m-d H:i', $this->endDate . ' ' . $this->endTime);
        
        return [$start, $end];
    }

    protected function generateBookingNumber(): string
    {
        return 'BK-' . Str::upper(Str::random(8));
    }

    public function getSelectedClientProperty(): ?User
    {
        return $this->selectedClientId ? User::find($this->selectedClientId) : null;
    }

    public function getSelectedVesselProperty(): ?Vessel
    {
        return $this->selectedVesselId ? Vessel::find($this->selectedVesselId) : null;
    }

    public function getServicesListProperty(): array
    {
        return self::SERVICES_LIST;
    }

    public function render()
    {
        return view('livewire.admin.bookings.new-reservation');
    }
}
