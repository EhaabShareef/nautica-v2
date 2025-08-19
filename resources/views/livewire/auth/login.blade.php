<div class="container fade-in">
    <div class="card slide-up">
        <h1 class="card-title">Login</h1>
        <form wire:submit.prevent="login" class="form">
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input id="email" type="email" wire:model="email" class="form-input" required>
                @error('email') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            @if ($useOtp)
                <div class="form-group">
                    <label for="otp" class="form-label">OTP Code</label>
                    <input id="otp" type="text" wire:model="otp" class="form-input">
                    @error('otp') <span class="form-error">{{ $message }}</span> @enderror
                </div>
            @else
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" wire:model="password" class="form-input">
                    @error('password') <span class="form-error">{{ $message }}</span> @enderror
                </div>
            @endif

            <div class="form-group">
                <button type="button" class="btn-secondary" wire:click="toggleOtp">
                    {{ $useOtp ? 'Use Password' : 'Use OTP' }}
                </button>
            </div>

            <div class="form-group">
                <button type="submit" class="form-button">Login</button>
            </div>
        </form>
    </div>
</div>
