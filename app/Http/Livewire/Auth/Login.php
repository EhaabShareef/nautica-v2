<?php

namespace App\Http\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
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

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            $user = Auth::user();
            if ($user->hasRole('admin')) {
                return redirect()->intended('/admin/dashboard');
            }

            return redirect()->intended('/client/dashboard');
        }

        $this->addError('email', 'Invalid credentials.');
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('layouts.app');
    }
}
