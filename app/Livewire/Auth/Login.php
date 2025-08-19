<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Component;
class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public string $otp = '';
    public bool $useOtp = false;

    public function toggleOtp(): void
    {
        $this->useOtp = ! $this->useOtp;
    }

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => $this->useOtp ? 'nullable' : 'required',
            'otp' => $this->useOtp ? 'required' : 'nullable',
        ]);

        if ($this->useOtp) {
            $this->addError('otp', 'OTP login not implemented.');
            return;
        }

        $throttleKey = Str::transliterate(Str::lower($this->email).'|'.request()->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $this->addError('email', 'Too many login attempts. Please try again later.');
            return;
        }

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            session()->regenerate();
            RateLimiter::clear($throttleKey);
            
            $user = Auth::user();
            
            // Default intended route based on role
            if ($user->hasRole('admin')) {
                $intended = '/admin/dashboard';
            } elseif ($user->hasRole('agent')) {
                $intended = '/agent/dashboard';
            } else {
                $intended = '/client/dashboard';
            }

            return redirect()->intended($intended);
        }

        $this->addError('email', 'Invalid credentials.');
        RateLimiter::hit($throttleKey, 60);
        $this->reset('password');
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('layouts.app');
    }
}
