<?php

namespace App\Livewire;

use Livewire\Component;

class LandingPage extends Component
{
    public bool $darkMode = false;

    public function mount()
    {
        // Check for user's theme preference from localStorage or system preference
        $this->darkMode = false; // Default to light mode, will be overridden by JavaScript
    }

    public function toggleDarkMode()
    {
        $this->darkMode = !$this->darkMode;
        $this->dispatch('theme-changed', theme: $this->darkMode ? 'dark' : 'light');
    }

    public function render()
    {
        return view('livewire.landing-page')->layout('layouts.app');
    }
}
