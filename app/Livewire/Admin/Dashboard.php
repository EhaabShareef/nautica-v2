<?php

namespace App\Livewire\Admin;

use App\Models\Activity;
use App\Models\Booking;
use App\Models\Property;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    public array $stats = [];
    public $recentActivities;

    public function mount()
    {
        $this->loadStats();
        $this->loadRecentActivities();
    }

    private function loadStats()
    {
        $this->stats = [
            'properties' => Property::count(),
            'active_bookings' => Booking::whereIn('status', ['approved', 'confirmed', 'checked_in'])->count(),
            'users' => User::count(),
            'revenue' => $this->calculateRevenue(),
        ];
    }

    private function calculateRevenue(): float
    {
        // Calculate total revenue from completed bookings/contracts
        $revenue = DB::table('contracts')
            ->join('bookings', 'contracts.booking_id', '=', 'bookings.id')
            ->where('contracts.status', 'active')
            ->sum('contracts.total');

        return round($revenue / 1000, 1); // Convert to thousands
    }

    private function loadRecentActivities()
    {
        $this->recentActivities = Activity::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.dashboard')->layout('layouts.app');
    }
}
