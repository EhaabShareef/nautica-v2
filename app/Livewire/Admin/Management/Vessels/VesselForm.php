<?php

namespace App\Livewire\Admin\Management\Vessels;

use App\Models\Vessel;
use App\Models\User;
use App\Models\AppType;
use App\Services\VesselService;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Validation\ValidationException;

class VesselForm extends Component
{
    // Form fields
    public $vesselId = null;
    public $name = '';
    public $registration_number = '';
    public $owner_client_id = null;
    public $renter_client_id = null;
    public $type = '';
    public $length = '';
    public $width = '';
    public $draft = '';
    public $specifications = [];
    public $is_active = true;

    // Component state
    public $showModal = false;
    public $isEditing = false;
    
    // Client search
    public $ownerSearch = '';
    public $renterSearch = '';
    public $showOwnerDropdown = false;
    public $showRenterDropdown = false;
    
    // Owner/Renter same toggle
    public $ownerIsSameAsRenter = false;
    
    public $eligibleOwners = [];
    public $eligibleRenters = [];
    public $vesselTypes = [];

    protected VesselService $vesselService;

    public function boot(VesselService $vesselService)
    {
        $this->vesselService = $vesselService;
    }

    protected function rules()
    {
        $vessel = $this->vesselId ? Vessel::find($this->vesselId) : new Vessel();
        return $vessel->getValidationRules($this->isEditing);
    }

    protected $messages = [
        'name.required' => 'Vessel name is required.',
        'registration_number.required' => 'Registration number is required.',
        'registration_number.unique' => 'This registration number is already in use.',
        'owner_client_id.required' => 'Owner is required.',
        'owner_client_id.exists' => 'Selected owner is invalid.',
        'renter_client_id.different' => 'Renter cannot be the same as the owner.',
        'renter_client_id.exists' => 'Selected renter is invalid.',
        'length.numeric' => 'Length must be a number.',
        'width.numeric' => 'Width must be a number.',
        'draft.numeric' => 'Draft must be a number.',
    ];

    public function mount()
    {
        $this->loadVesselTypes();
        $this->loadEligibleClients('', '');
    }

    #[On('openVesselForm')]
    public function openForm($vesselId = null)
    {
        $this->resetForm();
        $this->vesselId = $vesselId;
        $this->isEditing = !is_null($vesselId);

        if ($this->isEditing) {
            $this->loadVessel();
        }

        $this->showModal = true;
    }

    public function loadVessel()
    {
        $vessel = Vessel::with(['owner', 'renter'])->findOrFail($this->vesselId);
        
        $this->name = $vessel->name;
        $this->registration_number = $vessel->registration_number;
        $this->owner_client_id = $vessel->owner_client_id;
        $this->renter_client_id = $vessel->renter_client_id;
        $this->type = $vessel->type ?? '';
        $this->length = $vessel->length ?? '';
        $this->width = $vessel->width ?? '';
        $this->draft = $vessel->draft ?? '';
        $this->specifications = $vessel->specifications ?? [];
        $this->is_active = $vessel->is_active;
        
        // Set search values for display
        $this->ownerSearch = $vessel->owner ? $vessel->owner->display_name : '';
        $this->renterSearch = $vessel->renter ? $vessel->renter->display_name : '';
        
        // Set toggle if owner and renter are the same
        $this->ownerIsSameAsRenter = $vessel->owner_client_id && $vessel->owner_client_id === $vessel->renter_client_id;
    }

    public function resetForm()
    {
        $this->vesselId = null;
        $this->name = '';
        $this->registration_number = '';
        $this->owner_client_id = null;
        $this->renter_client_id = null;
        $this->type = '';
        $this->length = '';
        $this->width = '';
        $this->draft = '';
        $this->specifications = [];
        $this->is_active = true;
        $this->isEditing = false;
        
        $this->ownerSearch = '';
        $this->renterSearch = '';
        $this->showOwnerDropdown = false;
        $this->showRenterDropdown = false;
        $this->ownerIsSameAsRenter = false;
        
        $this->resetErrorBag();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->dispatch('vessel-form:closed');
    }

    public function updatedOwnerSearch()
    {
        $this->showOwnerDropdown = !empty($this->ownerSearch);
        $this->loadEligibleClients($this->ownerSearch, null);
        
        // Clear selected owner if search doesn't match
        if ($this->owner_client_id) {
            $selectedOwner = collect($this->eligibleOwners)->firstWhere('id', $this->owner_client_id);
            $haystack = strtolower(($selectedOwner['display_name'] ?? '') . ' ' . ($selectedOwner['id_card'] ?? ''));
            if (!$selectedOwner || !str_contains($haystack, strtolower($this->ownerSearch))) {
                $this->owner_client_id = null;
            }
        }
    }

    public function updatedRenterSearch()
    {
        $this->showRenterDropdown = !empty($this->renterSearch);
        $this->loadEligibleClients(null, $this->renterSearch);
        
        // Clear selected renter if search doesn't match
        if ($this->renter_client_id) {
            $selectedRenter = collect($this->eligibleRenters)->firstWhere('id', $this->renter_client_id);
            $haystack = strtolower(($selectedRenter['display_name'] ?? '') . ' ' . ($selectedRenter['id_card'] ?? ''));
            if (!$selectedRenter || !str_contains($haystack, strtolower($this->renterSearch))) {
                $this->renter_client_id = null;
            }
        }
    }

    public function selectOwner($clientId)
    {
        $this->owner_client_id = $clientId;
        $client = collect($this->eligibleOwners)->firstWhere('id', $clientId);
        $this->ownerSearch = $client ? $client['display_name'] : '';
        $this->showOwnerDropdown = false;
    }

    public function selectRenter($clientId)
    {
        $this->renter_client_id = $clientId;
        $client = collect($this->eligibleRenters)->firstWhere('id', $clientId);
        $this->renterSearch = $client ? $client['display_name'] : '';
        $this->showRenterDropdown = false;
    }

    public function clearRenter()
    {
        $this->renter_client_id = null;
        $this->renterSearch = '';
        $this->showRenterDropdown = false;
        $this->ownerIsSameAsRenter = false;
    }

    public function updatedOwnerIsSameAsRenter()
    {
        if ($this->ownerIsSameAsRenter) {
            // Set renter same as owner
            $this->renter_client_id = $this->owner_client_id;
            $this->renterSearch = $this->ownerSearch;
            $this->showRenterDropdown = false;
        } else {
            // Clear renter when unchecked
            $this->renter_client_id = null;
            $this->renterSearch = '';
        }
    }

    public function updatedOwnerClientId()
    {
        // Update renter if toggle is on
        if ($this->ownerIsSameAsRenter) {
            $this->renter_client_id = $this->owner_client_id;
            $this->renterSearch = $this->ownerSearch;
        }
    }

    public function loadVesselTypes()
    {
        $this->vesselTypes = AppType::where('group', 'vessel_types')
            ->orderBy('label')
            ->get(['code', 'label'])
            ->map(function ($type) {
                return [
                    'code' => $type->code,
                    'name' => $type->label
                ];
            })
            ->toArray();
    }

    public function loadEligibleClients($ownerSearch = null, $renterSearch = null)
    {
        if ($ownerSearch !== null) {
            $this->eligibleOwners = $this->vesselService->getEligibleClients($ownerSearch)
                ->map(function ($client) {
                    return [
                        'id' => $client->id,
                        'display_name' => $client->display_name,
                        'id_card' => $client->id_card,
                    ];
                })->toArray();
        }

        if ($renterSearch !== null) {
            $this->eligibleRenters = $this->vesselService->getEligibleClients($renterSearch)
                ->map(function ($client) {
                    return [
                        'id' => $client->id,
                        'display_name' => $client->display_name,
                        'id_card' => $client->id_card,
                    ];
                })->toArray();
        }
    }

    public function addSpecification()
    {
        $this->specifications[] = ['key' => '', 'value' => ''];
    }

    public function removeSpecification($index)
    {
        unset($this->specifications[$index]);
        $this->specifications = array_values($this->specifications);
    }

    public function save()
    {
        $this->validate();

        try {
            $data = [
                'name' => $this->name,
                'registration_number' => $this->registration_number,
                'owner_client_id' => $this->owner_client_id,
                'renter_client_id' => $this->renter_client_id ?: null,
                'type' => $this->type ?: null,
                'length' => $this->length ?: null,
                'width' => $this->width ?: null,
                'draft' => $this->draft ?: null,
                'specifications' => !empty($this->specifications) ? $this->specifications : null,
                'is_active' => $this->is_active,
            ];

            if ($this->isEditing) {
                $vessel = Vessel::findOrFail($this->vesselId);
                $this->vesselService->update($vessel, $data);
                $message = 'Vessel updated successfully.';
            } else {
                $this->vesselService->create($data);
                $message = 'Vessel created successfully.';
            }

            $this->dispatch('vesselSaved', message: $message);
            $this->dispatch('$refresh')->to('admin.management.vessels.index');
            $this->closeModal();

        } catch (ValidationException $e) {
            // Re-throw validation exceptions to show field errors
            throw $e;
        } catch (\Exception $e) {
            $this->addError('form', 'An error occurred while saving the vessel. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.admin.management.vessels.vessel-form');
    }
}