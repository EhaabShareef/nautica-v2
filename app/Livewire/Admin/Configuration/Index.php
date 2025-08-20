<?php

namespace App\Livewire\Admin\Configuration;

use App\Models\Property;
use App\Models\Block;
use App\Models\Zone;
use App\Models\Slot;
use App\Models\Activity;
use Livewire\Component;

class Index extends Component
{
    public $activeTab = 'properties';
    public $stats = [];

    public function mount()
    {
        $this->loadStats();
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    private function loadStats()
    {
        $this->stats = [
            'total_properties' => Property::count(),
            'active_properties' => Property::where('is_active', true)->count(),
            'total_blocks' => Block::count(),
            'total_zones' => Zone::count(),
            'active_slots' => Slot::where('is_active', true)->count(),
            'last_updated' => Activity::where('description', 'like', '%configuration%')
                                    ->orWhere('description', 'like', '%property%')
                                    ->orWhere('description', 'like', '%block%')
                                    ->orWhere('description', 'like', '%zone%')
                                    ->orWhere('description', 'like', '%slot%')
                                    ->latest()
                                    ->first()?->created_at?->diffForHumans() ?? 'Never',
        ];
    }

    public function render()
    {
        return view('livewire.admin.configuration.index')->layout('layouts.app');
    }
}