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
            'active_clients' => User::clients()->active()->count(),
            'inactive_clients' => User::clients()->where('is_active', false)->count(),
            'clients_with_vessels' => User::clients()->has('vessels')->count(),
        ];
    }

    public function render()
    {
        return view('livewire.admin.management.clients.components.stats-cards', [
            'stats' => $this->stats,
        ]);
    }
}