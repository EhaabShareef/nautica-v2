<?php

namespace App\Services;

use App\Models\User;
use App\Models\Vessel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class VesselService
{
    /**
     * Create a new vessel after validating business rules.
     *
     * @param array $data
     * @return Vessel
     * @throws ValidationException
     */
    public function create(array $data): Vessel
    {
        $vessel = new Vessel();
        $this->validateVesselData($data, $vessel);
        
        return Vessel::create($data);
    }

    /**
     * Update an existing vessel after validating business rules.
     *
     * @param Vessel $vessel
     * @param array $data
     * @return Vessel
     * @throws ValidationException
     */
    public function update(Vessel $vessel, array $data): Vessel
    {
        $this->validateVesselData($data, $vessel);
        
        $vessel->update($data);
        return $vessel->fresh();
    }

    /**
     * Validate vessel data with business rules.
     *
     * @param array $data
     * @param Vessel $vessel
     * @throws ValidationException
     */
    protected function validateVesselData(array $data, Vessel $vessel): void
    {
        $rules = $vessel->getValidationRules(!$vessel->exists);
        $validator = Validator::make($data, $rules);

        $validator->after(function ($validator) use ($data) {
            // Validate owner eligibility
            if (isset($data['owner_client_id'])) {
                $owner = User::find($data['owner_client_id']);
                if (!$this->isClientEligible($owner)) {
                    $validator->errors()->add('owner_client_id', 'Selected owner is inactive or blacklisted.');
                }
            }

            // Validate renter eligibility if provided
            if (!empty($data['renter_client_id'])) {
                $renter = User::find($data['renter_client_id']);
                if (!$this->isClientEligible($renter)) {
                    $validator->errors()->add('renter_client_id', 'Selected renter is inactive or blacklisted.');
                }
                
                // Prevent renter = owner
                if ($data['renter_client_id'] == $data['owner_client_id']) {
                    $validator->errors()->add('renter_client_id', 'Renter cannot be the same as the owner.');
                }
            }
        });

        $validator->validate();
    }

    /**
     * Check if a client is eligible to be assigned as owner/renter.
     *
     * @param User|null $client
     * @return bool
     */
    public function isClientEligible(?User $client): bool
    {
        if (!$client) {
            return false;
        }

        return $client->is_active && !$client->is_blacklisted;
    }

    /**
     * Get eligible clients for vessel assignment (owners/renters).
     *
     * @param string $search
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getEligibleClients(string $search = '', int $limit = 50)
    {
        $query = User::clients()
            ->active()
            ->notBlacklisted()
            ->select(['id', 'name', 'email', 'id_card'])
            ->orderBy('name');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('id_card', 'like', '%' . $search . '%');
            });
        }

        return $query->limit($limit)->get();
    }

    /**
     * Assign or change renter for a vessel.
     *
     * @param Vessel $vessel
     * @param int|null $renterClientId
     * @return Vessel
     * @throws ValidationException
     */
    public function assignRenter(Vessel $vessel, ?int $renterClientId): Vessel
    {
        if ($renterClientId) {
            $renter = User::find($renterClientId);
            if (!$this->isClientEligible($renter)) {
                throw ValidationException::withMessages([
                    'renter_client_id' => 'Selected renter is inactive or blacklisted.'
                ]);
            }

            if ($renterClientId == $vessel->owner_client_id) {
                throw ValidationException::withMessages([
                    'renter_client_id' => 'Renter cannot be the same as the owner.'
                ]);
            }
        }

        $vessel->update(['renter_client_id' => $renterClientId]);
        return $vessel->fresh();
    }

    /**
     * Get vessel statistics for dashboard.
     *
     * @return array
     */
    public function getVesselStats(): array
    {
        return [
            'total' => Vessel::count(),
            'active' => Vessel::active()->count(),
            'inactive' => Vessel::where('is_active', false)->count(),
            'with_renter' => Vessel::whereNotNull('renter_client_id')->count(),
            'without_renter' => Vessel::whereNull('renter_client_id')->count(),
        ];
    }
}
