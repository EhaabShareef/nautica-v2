<?php

namespace App\Livewire\Admin\Management\Clients;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\On;
use Livewire\Component;

class ClientForm extends Component
{
    // Form fields
    public $clientId = null;
    public $name = '';
    public $email = '';
    public $phone = '';
    public $id_card = '';
    public $address = '';
    public $password = '';
    public $password_confirmation = '';
    public $is_active = true;
    public $user_type = 'client';

    // Component state
    public $showModal = false;
    public $isEditing = false;

    protected function rules()
    {
        $client = $this->clientId ? User::find($this->clientId) : new User();
        return $client->getValidationRules($this->isEditing);
    }

    protected $messages = [
        'name.required' => 'Client name is required.',
        'email.required' => 'Email address is required.',
        'email.email' => 'Please enter a valid email address.',
        'email.unique' => 'This email address is already registered.',
        'phone.required' => 'Phone number is required.',
        'id_card.unique' => 'This ID card number is already registered.',
        'password.required' => 'Password is required for new clients.',
        'password.min' => 'Password must be at least 8 characters.',
        'password.confirmed' => 'Password confirmation does not match.',
    ];

    #[On('openClientForm')]
    public function openForm($clientId = null)
    {
        $this->resetForm();
        $this->clientId = $clientId;
        $this->isEditing = !is_null($clientId);

        if ($this->isEditing) {
            $this->loadClient();
        }

        $this->showModal = true;
    }

    public function loadClient()
    {
        $client = User::findOrFail($this->clientId);
        
        $this->name = $client->name;
        $this->email = $client->email;
        $this->phone = $client->phone ?? '';
        $this->id_card = $client->id_card ?? '';
        $this->address = $client->address ?? '';
        $this->is_active = $client->is_active;
        $this->user_type = $client->user_type;
    }

    public function resetForm()
    {
        $this->clientId = null;
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->id_card = '';
        $this->address = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->is_active = true;
        $this->user_type = 'client';
        $this->isEditing = false;
        $this->resetErrorBag();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->dispatchBrowserEvent('client-form:closed');
    }

    public function save()
    {
        $this->validate();

        try {
            $data = [
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'id_card' => $this->id_card,
                'address' => $this->address,
                'user_type' => $this->user_type,
                'is_active' => $this->is_active,
            ];

            if ($this->isEditing) {
                $client = User::findOrFail($this->clientId);
                
                // Only update password if provided
                if (!empty($this->password)) {
                    $data['password'] = Hash::make($this->password);
                }
                
                $client->update($data);
                $message = 'Client updated successfully.';
            } else {
                $data['password'] = Hash::make($this->password);
                $client = User::create($data);
                $message = 'Client created successfully.';
            }

            $this->dispatch('clientSaved', message: $message);
            $this->dispatch('$refresh')->to('admin.management.clients.index');
            $this->closeModal();

        } catch (\Exception $e) {
            $this->addError('form', 'An error occurred while saving the client. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.admin.management.clients.client-form');
    }
}