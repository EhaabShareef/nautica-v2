<div class="min-h-screen flex items-center justify-center p-4 fade-in">
    <div class="w-full max-w-md">
        <div class="card slide-up">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 style="font-size: 1.875rem; font-weight: bold; color: var(--foreground); margin-bottom: 0.5rem;">
                    Get Started
                </h1>
                <p style="color: var(--muted-foreground); font-size: 0.875rem;">
                    Create your Nautica account
                </p>
            </div>

            <form wire:submit.prevent="register" class="space-y-6">
                <div>
                    <label for="name" style="display: block; font-weight: 500; color: var(--foreground); margin-bottom: 0.5rem; font-size: 0.875rem;">
                        <x-heroicon name="user" class="w-4 h-4 inline mr-2" />
                        Full Name
                    </label>
                    <input id="name" name="name" type="text" wire:model="name" class="form-input" required autocomplete="name" autofocus
                           @error('name') aria-invalid="true" aria-describedby="name-error" @enderror
                           placeholder="Enter your full name">
                    @error('name') 
                        <p id="name-error" role="alert" style="color: var(--destructive); font-size: 0.75rem; margin-top: 0.25rem;">
                            {{ $message }}
                        </p> 
                    @enderror
                </div>

                <div>
                    <label for="email" style="display: block; font-weight: 500; color: var(--foreground); margin-bottom: 0.5rem; font-size: 0.875rem;">
                        <x-heroicon name="envelope" class="w-4 h-4 inline mr-2" />
                        Email Address
                    </label>
                    <input id="email" name="email" type="email" wire:model="email" class="form-input" required autocomplete="email" autocapitalize="none" spellcheck="false"
                           @error('email') aria-invalid="true" aria-describedby="email-error" @enderror
                           placeholder="Enter your email">
                    @error('email')
                        <p id="email-error" role="alert" style="color: var(--destructive); font-size: 0.75rem; margin-top: 0.25rem;">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="password" style="display: block; font-weight: 500; color: var(--foreground); margin-bottom: 0.5rem; font-size: 0.875rem;">
                        <x-heroicon name="lock-closed" class="w-4 h-4 inline mr-2" />
                        Password
                    <input id="password" name="password" type="password" wire:model.defer="password" class="form-input" required autocomplete="new-password" minlength="8"
                           @error('password') aria-invalid="true" aria-describedby="password-error" @enderror
                           placeholder="Create a secure password">
                    @error('password')
                        <p id="password-error" role="alert" style="color: var(--destructive); font-size: 0.75rem; margin-top: 0.25rem;">
                            {{ $message }}
                        </p>
                    @enderror
                        </p> 
                    @enderror
                </div>

                <button type="submit" class="btn w-full">
                    <x-heroicon name="user-plus" class="w-4 h-4" />
                    Create Account
                </button>
            </form>

            <!-- Footer -->
            <div class="mt-6 text-center">
                <p style="color: var(--muted-foreground); font-size: 0.875rem;">
                    Already have an account? 
                    <a href="/login" style="color: var(--primary); font-weight: 500; text-decoration: none;">
                        Sign in here
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
