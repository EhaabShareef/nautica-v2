<?php

namespace App\Livewire\Admin\Management\Clients\Components;

use App\Models\User;
use Livewire\Component;

class StatsCards extends Component
{
    public function getStatsProperty()
    {
        return [
            'total_clients' => User::clients()->count(),
            'active_clients' => User::clients()->active()->where('is_blacklisted', false)->count(),
            'inactive_clients' => User::clients()->where('is_active', false)->where('is_blacklisted', false)->count(),
            'blacklisted_clients' => User::clients()->where('is_blacklisted', true)->count(),
        ];
    }

    public function render()
    {
        return view('livewire.admin.management.clients.components.stats-cards', [
            'stats' => $this->stats,
        ]);
    }
}