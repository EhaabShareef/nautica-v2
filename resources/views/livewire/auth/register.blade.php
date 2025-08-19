<div class="container fade-in">
    <div class="card slide-up">
        <h1 class="card-title">Register</h1>
        <form wire:submit.prevent="register" class="form">
            <div class="form-group">
                <label for="name" class="form-label">Name</label>
                <input id="name" type="text" wire:model="name" class="form-input" required>
                @error('name') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input id="email" type="email" wire:model="email" class="form-input" required>
                @error('email') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input id="password" type="password" wire:model="password" class="form-input" required>
                @error('password') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <button type="submit" class="form-button">Register</button>
            </div>
        </form>
    </div>
</div>
