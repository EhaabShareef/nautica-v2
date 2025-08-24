<div class="p-6 fade-in new-reservation-container" style="background: var(--background); min-height: 100vh;">
    <!-- Header -->
    <div class="mb-8" style="display: flex; justify-content: space-between; align-items: start;">
        <div>
            <h1 style="font-size: 2rem; font-weight: bold; color: var(--foreground); margin-bottom: 0.5rem;">
                New Reservation
            </h1>
            <p style="color: var(--muted-foreground); font-size: 1rem;">
                Create a new booking reservation - Step {{ $step }} of 6
            </p>
        </div>
    </div>

    <!-- Progress Indicator -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            @for ($i = 1; $i <= 6; $i++)
                <div class="flex items-center {{ $i < 6 ? 'flex-1' : '' }}">
                    <div class="step-indicator {{ $step >= $i ? 'active' : '' }}" data-step="{{ $i }}">
                        <span>{{ $i }}</span>
                    </div>
                    @if ($i < 6)
                        <div class="step-connector {{ $step > $i ? 'completed' : '' }}"></div>
                    @endif
                </div>
            @endfor
        </div>
        <div class="step-labels grid grid-cols-6 gap-2 text-center">
            <span class="step-label {{ $step >= 1 ? 'active' : '' }}">Booking Type</span>
            <span class="step-label {{ $step >= 2 ? 'active' : '' }}">Client & Vessel</span>
            <span class="step-label {{ $step >= 3 ? 'active' : '' }}">Location</span>
            <span class="step-label {{ $step >= 4 ? 'active' : '' }}">Date & Time</span>
            <span class="step-label {{ $step >= 5 ? 'active' : '' }}">Select Slot</span>
            <span class="step-label {{ $step >= 6 ? 'active' : '' }}">Services</span>
        </div>
    </div>

    @if (session('success'))
        <div class="alert-success slide-up mb-6">
            <x-heroicon name="check-circle" class="w-5 h-5" />
            {{ session('success') }}
        </div>
    @endif

    <!-- Step Content -->
    <div class="card step-content-card slide-up" style="animation-delay: 0.1s;">
        @if ($step === 1)
            <!-- Step 1: Booking Type -->
            <div class="step-content">
                <h2 class="step-title">
                    <x-heroicon name="calendar-days" class="w-6 h-6" />
                    Select Booking Type
                </h2>
                <p class="step-description">Choose the type of reservation you want to create</p>
                
                <div class="booking-type-grid">
                    @foreach(['hourly' => 'Hourly', 'daily' => 'Daily', 'monthly' => 'Monthly', 'yearly' => 'Yearly'] as $type => $label)
                        <button type="button" 
                                wire:click="selectBookingType('{{ $type }}')"
                                class="booking-type-card {{ $bookingType === $type ? 'selected' : '' }}"
                                data-type="{{ $type }}">
                            <div class="booking-type-icon">
                                @if($type === 'hourly')
                                    <x-heroicon name="clock" class="w-8 h-8" />
                                @elseif($type === 'daily')
                                    <x-heroicon name="sun" class="w-8 h-8" />
                                @elseif($type === 'monthly')
                                    <x-heroicon name="calendar" class="w-8 h-8" />
                                @else
                                    <x-heroicon name="calendar-days" class="w-8 h-8" />
                                @endif
                            </div>
                            <h3>{{ $label }}</h3>
                            <p>{{ $type === 'hourly' ? 'Short-term hourly booking' : ($type === 'daily' ? 'Daily reservation' : ($type === 'monthly' ? 'Monthly booking' : 'Long-term yearly booking')) }}</p>
                        </button>
                    @endforeach
                </div>

                <!-- Optional manual navigation -->
                @if($bookingType)
                    <div class="step-actions">
                        <div></div> <!-- Spacer -->
                        <button type="button" class="btn-primary" wire:click="goToStep(2)" wire:loading.attr="disabled" wire:target="goToStep">
                            Continue with {{ ucfirst($bookingType) }}
                            <x-heroicon name="arrow-right" class="w-4 h-4" />
                        </button>
                    </div>
                @endif
            </div>
        @endif

        @if ($step === 2)
            <!-- Step 2: Client & Vessel -->
            <div class="step-content">
                <h2 class="step-title">
                    <x-heroicon name="user-group" class="w-6 h-6" />
                    Select Client & Vessel
                </h2>
                <p class="step-description">Choose the client and their vessel for this reservation</p>
                
                <div class="form-section">
                    <div class="input-group">
                        <label class="form-label">Client</label>
                        <input type="text" 
                               wire:model.live.debounce.300ms="clientSearch" 
                               placeholder="Search client by name or email..."
                               class="form-input">
                        
                        <!-- Debug info -->
                        @if($clientSearch && strlen($clientSearch) >= 2)
                            <div class="text-xs text-gray-500 mt-1">
                                Searching for: "{{ $clientSearch }}" - Found: {{ count($clientResults ?? []) }} results
                            </div>
                        @endif

                        @if (!empty($clientResults))
                            <div class="search-results">
                                @foreach ($clientResults as $c)
                                    <button type="button"
                                            class="search-result-item"
                                            wire:click="selectClient('{{ $c->id }}')"
                                            wire:key="client-{{ $c->id }}">
                                        <div class="search-result-info">
                                            <span class="search-result-name">{{ $c->name }}</span>
                                            <span class="search-result-email">{{ $c->email }}</span>
                                        </div>
                                        <x-heroicon name="chevron-right" class="w-4 h-4" />
                                    </button>
                                @endforeach
                            </div>
                        @endif
                        
                        @if ($this->selectedClient)
                            <div class="selected-item">
                                <x-heroicon name="check-circle" class="w-5 h-5 text-green-500" />
                                Selected: {{ $this->selectedClient->name }}
                            </div>
                        @endif
                    </div>

                    @if ($this->selectedClient)
                        <div class="input-group">
                            <label class="form-label">Vessel</label>
                            <input type="text" 
                                   wire:model.live.debounce.300ms="vesselSearch" 
                                   placeholder="Search vessel by name..."
                                   class="form-input">
                            
                            @if ($vesselResults && $vesselResults->count())
                                <div class="search-results">
                                    @foreach ($vesselResults as $v)
                                        <button type="button"
                                                class="search-result-item"
                                                wire:click="selectVessel('{{ $v->id }}')"
                                                wire:key="vessel-{{ $v->id }}">
                                            <div class="search-result-info">
                                                <span class="search-result-name">{{ $v->display_name }}</span>
                                            </div>
                                            <x-heroicon name="chevron-right" class="w-4 h-4" />
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                            
                            @if ($this->selectedVessel)
                                <div class="selected-item">
                                    <x-heroicon name="check-circle" class="w-5 h-5 text-green-500" />
                                    Selected: {{ $this->selectedVessel->display_name }}
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
                
                <div class="step-actions">
                    <button type="button" class="btn-secondary" wire:click="goToStep(1)">
                        <x-heroicon name="arrow-left" class="w-4 h-4" />
                        Back
                    </button>
                    <button type="button"
                            class="btn-primary {{ !$this->selectedVessel ? 'disabled' : '' }}"
                            wire:click="goToStep(3)"
                            wire:loading.attr="disabled"
                            wire:target="goToStep"
                            @disabled(!$this->selectedVessel)>
                        Next
                        <x-heroicon name="arrow-right" class="w-4 h-4" />
                    </button>
                </div>
            </div>
        @endif

        @if ($step === 3)
            <!-- Step 3: Location Selection -->
            <div class="step-content">
                <h2 class="step-title">
                    <x-heroicon name="map-pin" class="w-6 h-6" />
                    Select Location (Optional)
                </h2>
                <p class="step-description">Choose preferred property, block, and zone</p>
                
                <div class="form-section">
                    <div class="input-group">
                        <label class="form-label">Property</label>
                        <select wire:model.live="selectedProperty" class="form-select">
                            <option value="">Any Property</option>
                            @foreach ($properties as $prop)
                                <option value="{{ $prop->id }}">{{ $prop->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    @if ($blocks && $blocks->count())
                        <div class="input-group">
                            <label class="form-label">Block</label>
                            <select wire:model="selectedBlock" class="form-select">
                                <option value="">Any Block</option>
                                @foreach ($blocks as $block)
                                    <option value="{{ $block->id }}">{{ $block->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    @if ($zones && $zones->count())
                        <div class="input-group">
                            <label class="form-label">Zone</label>
                            <select wire:model="selectedZone" class="form-select">
                                <option value="">Any Zone</option>
                                @foreach ($zones as $zone)
                                    <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>
                
                <div class="step-actions">
                    <button type="button" class="btn-secondary" wire:click="goToStep(2)">
                        <x-heroicon name="arrow-left" class="w-4 h-4" />
                        Back
                    </button>
                    <button type="button" class="btn-primary" wire:click="goToStep(4)" wire:loading.attr="disabled" wire:target="goToStep">
                        Next
                        <x-heroicon name="arrow-right" class="w-4 h-4" />
                    </button>
                </div>
            </div>
        @endif

        @if ($step === 4)
            <!-- Step 4: Date & Time -->
            <div class="step-content">
                <h2 class="step-title">
                    <x-heroicon name="calendar" class="w-6 h-6" />
                    Set Date & Time
                </h2>
                <p class="step-description">Choose when you want to make the reservation</p>
                
                <div class="form-section">
                    @if ($bookingType === 'hourly')
                        <div class="datetime-grid hourly">
                            <div class="input-group">
                                <label class="form-label">Date</label>
                                <input type="date" wire:model="startDate" class="form-input">
                            </div>
                            <div class="input-group">
                                <label class="form-label">Start Time</label>
                                <input type="time" wire:model="startTime" class="form-input">
                            </div>
                            <div class="input-group">
                                <label class="form-label">End Time</label>
                                <input type="time" wire:model="endTime" class="form-input">
                            </div>
                        </div>
                    @elseif ($bookingType === 'daily')
                        <div class="datetime-grid daily">
                            <div class="input-group">
                                <label class="form-label">Start Date</label>
                                <input type="date" wire:model="startDate" class="form-input">
                            </div>
                            <div class="input-group">
                                <label class="form-label">End Date</label>
                                <input type="date" wire:model="endDate" class="form-input">
                            </div>
                        </div>
                    @else
                        <div class="datetime-grid extended">
                            <div class="input-group">
                                <label class="form-label">Start Date</label>
                                <input type="date" wire:model="startDate" class="form-input">
                            </div>
                            <div class="input-group">
                                <label class="form-label">Duration ({{ $bookingType === 'monthly' ? 'months' : 'years' }})</label>
                                <input type="number" min="1" wire:model="duration" class="form-input">
                            </div>
                        </div>
                    @endif
                </div>
                
                <div class="step-actions">
                    <button type="button" class="btn-secondary" wire:click="goToStep(3)">
                        <x-heroicon name="arrow-left" class="w-4 h-4" />
                        Back
                    </button>
                    <button type="button" class="btn-primary" wire:click="calculateAvailability" wire:loading.attr="disabled" wire:target="calculateAvailability">
                        Check Availability
                        <x-heroicon name="magnifying-glass" class="w-4 h-4" />
                    </button>
                </div>
            </div>
        @endif

        @if ($step === 5)
            <!-- Step 5: Select Slot -->
            <div class="step-content">
                <h2 class="step-title">
                    <x-heroicon name="squares-plus" class="w-6 h-6" />
                    Select Available Slot
                </h2>
                <p class="step-description">Choose from available slots for your selected time</p>
                
                @if ($availableSlots && $availableSlots->isEmpty())
                    <div class="empty-state">
                        <x-heroicon name="exclamation-triangle" class="w-12 h-12 text-amber-500 mx-auto mb-4" />
                        <h3>No Available Slots</h3>
                        <p>There are no available slots for the selected timeframe. Please try different dates or times.</p>
                        <button type="button" class="btn-secondary" wire:click="goToStep(4)">
                            Change Date/Time
                        </button>
                    </div>
                @else
                    <div class="slots-grid">
                        @foreach ($availableSlots as $slot)
                            <button type="button"
                                    class="slot-card {{ $selectedSlot == $slot->id ? 'selected' : '' }}"
                                    wire:key="slot-{{ $slot->id }}"
                                    wire:click="selectSlot({{ $slot->id }})"
                                    wire:loading.attr="disabled"
                                    wire:target="selectSlot">
                                <div class="slot-info">
                                    <h4>{{ $slot->code }}</h4>
                                    <div class="slot-location">
                                        <span>{{ $slot->zone->block->property->name }}</span>
                                        <span>{{ $slot->zone->block->name }} / {{ $slot->zone->name }}</span>
                                    </div>
                                </div>
                                <div class="slot-status available">
                                    <x-heroicon name="check-circle" class="w-5 h-5" />
                                    Available
                                </div>
                            </button>
                        @endforeach
                    </div>
                @endif
                
                <div class="step-actions">
                    <button type="button" class="btn-secondary" wire:click="goToStep(4)">
                        <x-heroicon name="arrow-left" class="w-4 h-4" />
                        Back
                    </button>
                </div>
            </div>
        @endif

        @if ($step === 6)
            <!-- Step 6: Additional Services -->
            <div class="step-content">
                <h2 class="step-title">
                    <x-heroicon name="cog-6-tooth" class="w-6 h-6" />
                    Additional Services
                </h2>
                <p class="step-description">Select any additional services (optional)</p>
                
                <div class="services-section">
                    @foreach ($this->servicesList as $code => $label)
                        <label class="service-item" wire:key="service-{{ $code }}">
                            <input type="checkbox" wire:model="selectedServices" value="{{ $code }}" class="service-checkbox">
                            <div class="service-info">
                                <h4>{{ $label }}</h4>
                                <p>Additional service for your booking</p>
                            </div>
                            <div class="service-check">
                                <x-heroicon name="check" class="w-4 h-4" />
                            </div>
                        </label>
                    @endforeach
                </div>
                
                <div class="step-actions">
                    <button type="button" class="btn-secondary" wire:click="goToStep(5)">
                        <x-heroicon name="arrow-left" class="w-4 h-4" />
                        Back
                    </button>
                    <button type="button" class="btn-success" wire:click="createBooking" wire:loading.attr="disabled" wire:target="createBooking">
                        <x-heroicon name="check-circle" class="w-5 h-5" />
                        Create Booking
                    </button>
                </div>
            </div>
        @endif
    </div>

    <style>
/* Step Indicator Styles */
.new-reservation-container .step-indicator {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 50%;
    background: var(--muted);
    color: var(--muted-foreground);
    font-weight: 600;
    transition: all 0.3s ease;
    position: relative;
}

.new-reservation-container .step-indicator.active {
    background: var(--primary);
    color: white;
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
}

/* Dark theme override for step indicator text */
.dark .new-reservation-container .step-indicator.active {
    color: var(--foreground);
}

.new-reservation-container .step-connector {
    height: 2px;
    background: var(--muted);
    transition: all 0.3s ease;
}

.new-reservation-container .step-connector.completed {
    background: var(--primary);
}

.new-reservation-container .step-label {
    font-size: 0.75rem;
    color: var(--muted-foreground);
    transition: all 0.3s ease;
}

.new-reservation-container .step-label.active {
    color: var(--primary);
    font-weight: 600;
}

/* Step Content Styles */
.new-reservation-container .step-content-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 2rem;
    min-height: 500px;
}

.new-reservation-container .step-content {
    display: flex;
    flex-direction: column;
    height: 100%;
}

.new-reservation-container .step-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--foreground);
    margin-bottom: 0.5rem;
}

.new-reservation-container .step-description {
    color: var(--muted-foreground);
    margin-bottom: 2rem;
}

/* Booking Type Cards */
.new-reservation-container .booking-type-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.new-reservation-container .booking-type-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 1.5rem;
    border: 2px solid var(--border);
    border-radius: 12px;
    background: var(--card);
    transition: all 0.3s ease;
    cursor: pointer;
    text-align: center;
}

.new-reservation-container .booking-type-card:hover {
    border-color: var(--primary);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.new-reservation-container .booking-type-card.selected {
    border-color: var(--primary);
    background: rgba(99, 102, 241, 0.1);
}

.new-reservation-container .booking-type-icon {
    margin-bottom: 1rem;
    color: var(--primary);
}

.new-reservation-container .booking-type-card h3 {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--foreground);
    margin-bottom: 0.5rem;
    margin: 0 0 0.5rem 0;
}

.new-reservation-container .booking-type-card p {
    color: var(--muted-foreground);
    font-size: 0.875rem;
    margin: 0;
}

/* Form Styles */
.new-reservation-container .form-section {
    flex: 1;
    margin-bottom: 2rem;
}

.new-reservation-container .input-group {
    margin-bottom: 1.5rem;
}

.new-reservation-container .form-label {
    display: block;
    font-weight: 600;
    color: var(--foreground);
    margin-bottom: 0.5rem;
}

.new-reservation-container .form-input, .new-reservation-container .form-select {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid var(--border);
    border-radius: 8px;
    background: var(--background);
    color: var(--foreground);
    transition: all 0.2s ease;
}

.new-reservation-container .form-input:focus, .new-reservation-container .form-select:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

/* Search Results */
.new-reservation-container .search-results {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 8px;
    max-height: 200px;
    overflow-y: auto;
    margin-top: 0.5rem;
}

.new-reservation-container .search-result-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    padding: 0.75rem;
    text-align: left;
    border: none;
    background: transparent;
    transition: all 0.2s ease;
    border-bottom: 1px solid var(--border);
}

.new-reservation-container .search-result-item:last-child {
    border-bottom: none;
}

.new-reservation-container .search-result-item:hover {
    background: var(--muted);
}

.new-reservation-container .search-result-info {
    display: flex;
    flex-direction: column;
}

.new-reservation-container .search-result-name {
    font-weight: 600;
    color: var(--foreground);
}

.new-reservation-container .search-result-email {
    font-size: 0.875rem;
    color: var(--muted-foreground);
}

.new-reservation-container .selected-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 0.5rem;
    padding: 0.5rem;
    background: rgba(34, 197, 94, 0.1);
    border-radius: 8px;
    color: var(--foreground);
}

/* Date/Time Grids */
.new-reservation-container .datetime-grid {
    display: grid;
    gap: 1rem;
}

.new-reservation-container .datetime-grid.hourly {
    grid-template-columns: 1fr 1fr 1fr;
}

.new-reservation-container .datetime-grid.daily, .new-reservation-container .datetime-grid.extended {
    grid-template-columns: 1fr 1fr;
}

/* Slots Grid */
.new-reservation-container .slots-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.new-reservation-container .slot-card {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    border: 2px solid var(--border);
    border-radius: 12px;
    background: var(--card);
    transition: all 0.3s ease;
    cursor: pointer;
}

.new-reservation-container .slot-card:hover {
    border-color: var(--primary);
    transform: translateY(-2px);
}

.new-reservation-container .slot-card.selected {
    border-color: var(--primary);
    background: rgba(99, 102, 241, 0.1);
}

.new-reservation-container .slot-info h4 {
    font-weight: 600;
    color: var(--foreground);
    margin-bottom: 0.25rem;
    margin: 0 0 0.25rem 0;
}

.new-reservation-container .slot-location {
    display: flex;
    flex-direction: column;
    font-size: 0.875rem;
    color: var(--muted-foreground);
}

.new-reservation-container .slot-status {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    font-weight: 600;
}

.new-reservation-container .slot-status.available {
    color: #22c55e;
}

/* Services */
.new-reservation-container .services-section {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 2rem;
}

.new-reservation-container .service-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border: 1px solid var(--border);
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.new-reservation-container .service-item:hover {
    background: var(--muted);
}

.new-reservation-container .service-checkbox {
    display: none;
}

.new-reservation-container .service-info {
    flex: 1;
}

.new-reservation-container .service-info h4 {
    font-weight: 600;
    color: var(--foreground);
    margin: 0;
}

.new-reservation-container .service-info p {
    font-size: 0.875rem;
    color: var(--muted-foreground);
    margin: 0;
}

.new-reservation-container .service-check {
    width: 1.5rem;
    height: 1.5rem;
    border: 2px solid var(--border);
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.new-reservation-container .service-item:has(.service-checkbox:checked) .service-check {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
}

/* Dark theme override for service check text */
.dark .new-reservation-container .service-item:has(.service-checkbox:checked) .service-check {
    color: var(--foreground);
}

/* Buttons */
.new-reservation-container .step-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: auto;
    gap: 1rem;
}

.new-reservation-container .btn-primary, .new-reservation-container .btn-secondary, .new-reservation-container .btn-success {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    border-radius: 8px;
    transition: all 0.2s ease;
    border: none;
    cursor: pointer;
}

.new-reservation-container .btn-primary {
    background: var(--primary);
    color: white;
}

/* Dark theme override for primary button text */
.dark .new-reservation-container .btn-primary {
    color: var(--foreground);
}

.new-reservation-container .btn-primary:hover:not(.disabled) {
    background: color-mix(in oklch, var(--primary) 90%, black);
    transform: translateY(-1px);
}

.new-reservation-container .btn-primary.disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.new-reservation-container .btn-secondary {
    background: var(--secondary);
    color: var(--secondary-foreground);
}

.new-reservation-container .btn-secondary:hover {
    background: var(--muted);
}

.new-reservation-container .btn-success {
    background: #22c55e;
    color: white;
}

.new-reservation-container .btn-success:hover {
    background: #16a34a;
}

/* Empty State */
.new-reservation-container .empty-state {
    text-align: center;
    padding: 3rem;
}

.new-reservation-container .empty-state h3 {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--foreground);
    margin-bottom: 0.5rem;
    margin: 0 0 0.5rem 0;
}

.new-reservation-container .empty-state p {
    color: var(--muted-foreground);
    margin-bottom: 1.5rem;
    margin: 0 0 1.5rem 0;
}

/* Alert */
.new-reservation-container .alert-success {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    background: rgba(34, 197, 94, 0.1);
    color: #22c55e;
    border: 1px solid rgba(34, 197, 94, 0.2);
    border-radius: 8px;
}

/* Responsive */
@media (max-width: 768px) {
    .new-reservation-container .step-content-card {
        padding: 1.5rem;
    }
    
    .new-reservation-container .booking-type-grid {
        grid-template-columns: 1fr;
    }
    
    .new-reservation-container .datetime-grid.hourly {
        grid-template-columns: 1fr;
    }
    
    .new-reservation-container .slots-grid {
        grid-template-columns: 1fr;
    }
    
    .new-reservation-container .step-actions {
        flex-direction: column-reverse;
    }
    
    .new-reservation-container .btn-primary, .new-reservation-container .btn-secondary, .new-reservation-container .btn-success {
        width: 100%;
        justify-content: center;
    }
}
    </style>
</div>