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
use Illuminate\Support\Str;
use Livewire\Component;

class NewReservation extends Component
{
    public int $step = 1;
    public ?string $bookingType = null;

    public string $clientSearch = '';
    public $clientResults = [];
    public ?User $selectedClient = null;

    public string $vesselSearch = '';
    public $vesselResults = [];
    public ?Vessel $selectedVessel = null;

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

    public array $servicesList = [
        'shore_power' => 'Shore Power',
        'water_hookup' => 'Water Hookup',
        'cleaning' => 'Cleaning',
    ];
    public array $selectedServices = [];

    public function mount(): void
    {
        $this->properties = Property::where('is_active', true)->get();
        $this->availableSlots = collect();
    }

    public function updatedClientSearch(): void
    {
        if (strlen($this->clientSearch) < 2) {
            $this->clientResults = [];
            return;
        }

        $this->clientResults = User::clients()
            ->active()
            ->notBlacklisted()
            ->where(function ($q) {
                $q->where('name', 'like', '%' . $this->clientSearch . '%')
                  ->orWhere('email', 'like', '%' . $this->clientSearch . '%');
            })
            ->limit(10)
            ->get();
    }

    public function selectClient(string $id): void
    {
        $client = User::clients()->active()->notBlacklisted()->find($id);
        if (!$client) {
            $this->addError('clientSearch', 'Selected client is not eligible.');
            return;
        }
        $this->selectedClient = $client;
        $this->clientSearch = $client->name;
        $this->clientResults = [];
        $this->loadVessels();
    }
    public function loadVessels(): void
    {
        if (!$this->selectedClient) {
            $this->vesselResults = [];
            return;
        }

        $this->vesselResults = Vessel::ownedBy($this->selectedClient->id)
            ->active()
            ->get();
    }

    public function selectVessel(string $id): void
    {
        $this->selectedVessel = Vessel::find($id);
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
        $start = Carbon::parse($this->startDate . ' ' . ($this->startTime ?? '00:00'));
        if ($this->bookingType === 'hourly') {
            $end = Carbon::parse($this->startDate . ' ' . ($this->endTime ?? '00:00'));
        } elseif ($this->bookingType === 'monthly') {
            $end = Carbon::parse($this->startDate)->addMonths((int)($this->duration ?? 1));
        } elseif ($this->bookingType === 'yearly') {
            $end = Carbon::parse($this->startDate)->addYears((int)($this->duration ?? 1));
        } else {
            $end = Carbon::parse($this->endDate);
        }

        $this->startDate = $start;
        $this->endDate = $end;

        $slotQuery = Slot::with('zone.block.property')
            ->where('is_active', true);

        if ($this->selectedZone) {
            $slotQuery->where('zone_id', $this->selectedZone);
        } elseif ($this->selectedBlock) {
            $slotQuery->whereHas('zone', fn($q) => $q->where('block_id', $this->selectedBlock));
        } elseif ($this->selectedProperty) {
            $slotQuery->whereHas('zone.block', fn($q) => $q->where('property_id', $this->selectedProperty));
        }

        $slots = $slotQuery->get();

        $this->availableSlots = $slots->filter(function ($slot) use ($start, $end) {
            return !Booking::where('slot_id', $slot->id)
                ->where('status', '!=', 'cancelled')
                ->where(function ($q) use ($start, $end) {
                    $q->whereBetween('start_date', [$start, $end])
                      ->orWhereBetween('end_date', [$start, $end])
                      ->orWhere(function ($q2) use ($start, $end) {
                          $q2->where('start_date', '<=', $start)
                             ->where('end_date', '>=', $end);
                      });
                })->exists();
        })->values();

        $this->step = 5;
    }

    public function createBooking()
    {
        $this->validate([
            'bookingType' => 'required|string',
            'selectedClient' => 'required',
            'selectedVessel' => 'required',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after:startDate',
            'selectedSlot' => 'required',
        ]);

        $booking = Booking::create([
            'booking_number' => 'BK-' . Str::upper(Str::random(8)),
            'user_id' => $this->selectedClient->id,
            'vessel_id' => $this->selectedVessel->id,
            'slot_id' => $this->selectedSlot,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
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

        session()->flash('success', 'Booking created successfully.');
        return redirect()->route('admin.bookings.new');
    }

    public function render()
    {
        return view('livewire.admin.bookings.new-reservation');
    }
}
