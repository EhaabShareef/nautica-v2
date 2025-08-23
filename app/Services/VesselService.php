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
        $validator = Validator::make($data, [
            'owner_client_id' => 'required|exists:users,id',
            'renter_client_id' => 'nullable|different:owner_client_id|exists:users,id',
            'name' => 'required|string|max:255',
            'registration_number' => 'required|string|unique:vessels,registration_number',
            'type' => 'nullable|string|max:255',
            'length' => 'nullable|numeric',
            'width' => 'nullable|numeric',
            'draft' => 'nullable|numeric',
            'specifications' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $validator->after(function ($validator) use ($data) {
            $owner = User::find($data['owner_client_id'] ?? null);
            if (!$owner || !$owner->is_active || $owner->is_blacklisted) {
                $validator->errors()->add('owner_client_id', 'Selected owner is inactive or blacklisted.');
            }

            if (!empty($data['renter_client_id'])) {
                $renter = User::find($data['renter_client_id']);
                if (!$renter || !$renter->is_active || $renter->is_blacklisted) {
                    $validator->errors()->add('renter_client_id', 'Selected renter is inactive or blacklisted.');
                }
            }
        });

        $validated = $validator->validate();

        return Vessel::create($validated);
    }
}
