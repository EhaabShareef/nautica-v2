<?php

namespace App\Livewire\Admin\Bookings;

use Livewire\Component;

class BookingsIndex extends Component
{
    public function render()
    {
        return view('livewire.admin.bookings.bookings-index')
            ->layout('components.layouts.app', ['title' => 'Bookings Management']);
    }
}