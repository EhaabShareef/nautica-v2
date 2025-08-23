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
        // Calculate total revenue from active contracts and completed bookings
        $contractRevenue = DB::table('contracts')
            ->where('status', 'active')
            ->sum('monthly_rate');

        $bookingRevenue = DB::table('bookings')
            ->whereIn('status', ['confirmed', 'completed'])
            ->sum('total_amount');

        $totalRevenue = $contractRevenue + $bookingRevenue;
        return round($totalRevenue / 1000, 1); // Convert to thousands
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
        return view('livewire.admin.dashboard')->layout('layouts.admin');
    }
}
