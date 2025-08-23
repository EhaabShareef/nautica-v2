<div class="min-h-screen flex items-center justify-center p-4 fade-in">
    <div class="w-full max-w-md">
        <div class="card slide-up">
            <!-- Header -->
            <div class="mb-8 text-center">
                <h1 class="heading-xl">Welcome Back</h1>
                <p class="body-sm-muted">Sign in to your Nautica account</p>
            </div>

            <form wire:submit.prevent="login" class="space-y-6">
                <div>
                    <label for="email" class="form-label">
                        <x-heroicon name="envelope" class="mr-2 inline h-4 w-4" />
                        Email Address
                    </label>
                    <input id="email" type="email" wire:model="email" class="form-input" required 
                           placeholder="Enter your email">
                    @error('email') 
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>

                @if ($useOtp)
                    <div>
                        <label for="otp" class="form-label">
                            <x-heroicon name="key" class="mr-2 inline h-4 w-4" />
                            OTP Code
                        </label>
                        <input id="otp" type="text" wire:model="otp" class="form-input"
                               placeholder="Enter OTP code">
                        @error('otp')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>
                @else
                    <div>
                        <label for="password" class="form-label">
                            <x-heroicon name="lock-closed" class="mr-2 inline h-4 w-4" />
                            Password
                        </label>
                        <input id="password" type="password" wire:model="password" class="form-input"
                               placeholder="Enter your password">
                        @error('password')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>
                @endif

                <div class="flex items-center justify-between">
                    <button type="button" class="btn-secondary px-4 py-2 text-xs" wire:click="toggleOtp">
                        <x-heroicon name="{{ $useOtp ? 'eye-slash' : 'key' }}" class="h-4 w-4" />
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
                <p class="body-sm-muted">
                    Don't have an account?
                    <a href="/register" class="link-primary">
                        Sign up here
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
