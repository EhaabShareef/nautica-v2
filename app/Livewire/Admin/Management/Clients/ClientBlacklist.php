<?php

namespace App\Livewire\Admin\Management\Clients;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;

class ClientBlacklist extends Component
{
    public $showModal = false;
    public $client = null;
    public $isBlacklisting = true;
    public $blacklistReason = '';

    protected $rules = [
        'blacklistReason' => 'nullable|string|max:500',
    ];

    #[On('blacklist-client')]
    public function showBlacklistModal($clientId)
    {
        $this->client = User::findOrFail($clientId);
        $this->isBlacklisting = !$this->client->is_blacklisted;
        $this->blacklistReason = '';
        $this->showModal = true;
        $this->resetErrorBag();
    }

    public function confirmBlacklist()
    {
        if (!$this->client || $this->client->is_blacklisted) {
            $this->addError('general', 'Client is already blacklisted or not found.');
            return;
        }

        try {
            // Set blacklist status explicitly (not mass assignable)
            $this->client->is_blacklisted = true;
            $this->client->save();

            // You could also log the reason if needed
            // activity()->causedBy(auth()->user())->performedOn($this->client)
            //     ->withProperties(['reason' => $this->blacklistReason])
            //     ->log('client_blacklisted');

            $this->closeModal();
            $this->dispatch('client-blacklisted', [
                'message' => 'Client has been blacklisted successfully.'
            ]);

        } catch (\Exception $e) {
            $this->addError('general', 'Failed to blacklist client. Please try again.');
        }
    }

    public function confirmUnblacklist()
    {
        if (!$this->client || !$this->client->is_blacklisted) {
            $this->addError('general', 'Client is not blacklisted or not found.');
            return;
        }

        try {
            // Remove blacklist status explicitly (not mass assignable)
            $this->client->is_blacklisted = false;
            $this->client->save();

            $this->closeModal();
            $this->dispatch('client-unblacklisted', [
                'message' => 'Client has been removed from blacklist successfully.'
            ]);

        } catch (\Exception $e) {
            $this->addError('general', 'Failed to remove client from blacklist. Please try again.');
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->client = null;
        $this->blacklistReason = '';
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.admin.management.clients.client-blacklist');
    }
}