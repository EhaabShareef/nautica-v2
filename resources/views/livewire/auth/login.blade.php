<div class="min-h-screen flex items-center justify-center p-4 fade-in">
    <div class="w-full max-w-md">
        <div class="card slide-up">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 style="font-size: 1.875rem; font-weight: bold; color: var(--foreground); margin-bottom: 0.5rem;">
                    Welcome Back
                </h1>
                <p style="color: var(--muted-foreground); font-size: 0.875rem;">
                    Sign in to your Nautica account
                </p>
            </div>

            <form wire:submit.prevent="login" class="space-y-6">
                <div>
                    <label for="email" style="display: block; font-weight: 500; color: var(--foreground); margin-bottom: 0.5rem; font-size: 0.875rem;">
                        <x-heroicon name="envelope" class="w-4 h-4 inline mr-2" />
                        Email Address
                    </label>
                    <input id="email" type="email" wire:model="email" class="form-input" required 
                           placeholder="Enter your email">
                    @error('email') 
                        <p style="color: var(--destructive); font-size: 0.75rem; margin-top: 0.25rem;">
                            {{ $message }}
                        </p> 
                    @enderror
                </div>

                @if ($useOtp)
                    <div>
                        <label for="otp" style="display: block; font-weight: 500; color: var(--foreground); margin-bottom: 0.5rem; font-size: 0.875rem;">
                            <x-heroicon name="key" class="w-4 h-4 inline mr-2" />
                            OTP Code
                        </label>
                        <input id="otp" type="text" wire:model="otp" class="form-input" 
                               placeholder="Enter OTP code">
                        @error('otp') 
                            <p style="color: var(--destructive); font-size: 0.75rem; margin-top: 0.25rem;">
                                {{ $message }}
                            </p> 
                        @enderror
                    </div>
                @else
                    <div>
                        <label for="password" style="display: block; font-weight: 500; color: var(--foreground); margin-bottom: 0.5rem; font-size: 0.875rem;">
                            <x-heroicon name="lock-closed" class="w-4 h-4 inline mr-2" />
                            Password
                        </label>
                        <input id="password" type="password" wire:model="password" class="form-input" 
                               placeholder="Enter your password">
                        @error('password') 
                            <p style="color: var(--destructive); font-size: 0.75rem; margin-top: 0.25rem;">
                                {{ $message }}
                            </p> 
                        @enderror
                    </div>
                @endif

                <div class="flex justify-between items-center">
                    <button type="button" class="btn-secondary" wire:click="toggleOtp" style="font-size: 0.75rem; padding: 0.5rem 1rem;">
                        <x-heroicon name="{{ $useOtp ? 'eye-slash' : 'key' }}" class="w-4 h-4" />
                        {{ $useOtp ? 'Use Password' : 'Use OTP' }}
                    </button>
                </div>

                <button type="submit" class="btn w-full">
                    <x-heroicon name="arrow-right-on-rectangle" class="w-4 h-4" />
                    Sign In
                </button>
            </form>

            <!-- Footer -->
            <div class="mt-6 text-center">
                <p style="color: var(--muted-foreground); font-size: 0.875rem;">
                    Don't have an account? 
                    <a href="/register" style="color: var(--primary); font-weight: 500; text-decoration: none;">
                        Sign up here
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
