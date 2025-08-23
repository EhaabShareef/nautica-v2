<?php

namespace App\Livewire\Admin\Management\Clients;

use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;

class ClientDelete extends Component
{
    public $client = null;
    public $showModal = false;
    public $confirmText = '';

    #[On('openClientDelete')]
    public function openModal($clientId)
    {
        // Fresh fetch to avoid TOCTOU issues
        $this->client = User::with('vessels')->find($clientId);
        
        if (!$this->client) {
            $this->dispatch('clientDeleted', message: 'Client not found.');
            return;
        }

        $this->showModal = true;
        $this->confirmText = '';
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->client = null;
        $this->confirmText = '';
    }

    public function delete()
    {
        if (!$this->client) {
            $this->addError('client', 'Client not found.');
            return;
        }

        // Verify confirmation text
        if (strtolower($this->confirmText) !== 'delete') {
            $this->addError('confirmText', 'Please type "delete" to confirm.');
            return;
        }

        try {
            // Check if client has vessels
            if ($this->client->vessels()->count() > 0) {
                $this->addError('client', 'Cannot delete client with existing vessels. Please remove vessels first.');
                return;
            }

            $clientName = $this->client->name;
            $this->client->delete();

            $this->dispatch('clientDeleted', message: "Client '{$clientName}' has been deleted successfully.");
            $this->dispatch('$refresh')->to('admin.management.clients.index');
            $this->closeModal();

        } catch (\Exception $e) {
            $this->addError('client', 'An error occurred while deleting the client. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.admin.management.clients.client-delete');
    }
}