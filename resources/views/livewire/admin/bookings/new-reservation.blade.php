<div class="max-w-4xl mx-auto p-6 space-y-6">
    <h1 class="text-2xl font-semibold" style="color: var(--foreground);">New Reservation</h1>

    @if (session('success'))
        <div class="p-4 rounded" style="background: var(--green-100); color: var(--green-800);">
            {{ session('success') }}
        </div>
    @endif

    @if ($step === 1)
        <div class="space-y-4">
            <h2 class="text-lg font-medium" style="color: var(--foreground);">Select Booking Type</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach(['hourly' => 'Hourly', 'daily' => 'Daily', 'monthly' => 'Monthly', 'yearly' => 'Yearly'] as $type => $label)
                    <button type="button" wire:click="$set('bookingType','{{ $type }}'); $set('step',2)"
                        class="px-4 py-3 rounded border {{ $bookingType === $type ? 'bg-blue-600 text-white' : '' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>
    @endif

    @if ($step === 2)
        <div class="space-y-6">
            <div>
                <label class="block text-sm font-medium mb-1" style="color: var(--foreground);">Client</label>
                <input type="text" wire:model.debounce.300ms="clientSearch" placeholder="Search client..."
                    class="form-input w-full">
                @if (!empty($clientResults))
                    <ul class="border rounded mt-1 bg-white dark:bg-gray-800 max-h-40 overflow-y-auto">
                        @foreach ($clientResults as $c)
                            <li>
                                <button type="button" class="w-full text-left px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-700"
                                    wire:click="selectClient('{{ $c->id }}')">{{ $c->name }} ({{ $c->email }})</button>
                            </li>
                        @endforeach
                    </ul>
                @endif
                @if ($selectedClient)
                    <p class="mt-2 text-sm text-muted-foreground">Selected: {{ $selectedClient->name }}</p>
                @endif
            </div>

            @if ($selectedClient)
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--foreground);">Vessel</label>
                    <input type="text" wire:model.debounce.300ms="vesselSearch" placeholder="Search vessel..."
                        class="form-input w-full">
                    @if ($vesselResults && $vesselResults->count())
                        <ul class="border rounded mt-1 bg-white dark:bg-gray-800 max-h-40 overflow-y-auto">
                            @foreach ($vesselResults as $v)
                                <li>
                                    <button type="button" class="w-full text-left px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-700"
                                        wire:click="selectVessel('{{ $v->id }}')">{{ $v->display_name }}</button>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    @if ($selectedVessel)
                        <p class="mt-2 text-sm text-muted-foreground">Selected: {{ $selectedVessel->display_name }}</p>
                    @endif
                </div>
            @endif

            <div class="flex justify-between">
                <button type="button" class="btn px-4 py-2" wire:click="$set('step',1)">Back</button>
                <button type="button" class="btn px-4 py-2 bg-blue-600 text-white" wire:click="$set('step',3)"
                    @disabled="!$selectedVessel">Next</button>
            </div>
        </div>
    @endif

    @if ($step === 3)
        <div class="space-y-6">
            <div>
                <label class="block text-sm font-medium mb-1" style="color: var(--foreground);">Property (optional)</label>
                <select wire:model="selectedProperty" class="form-select w-full">
                    <option value="">Any</option>
                    @foreach ($properties as $prop)
                        <option value="{{ $prop->id }}">{{ $prop->name }}</option>
                    @endforeach
                </select>
            </div>
            @if ($blocks)
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--foreground);">Block (optional)</label>
                    <select wire:model="selectedBlock" class="form-select w-full">
                        <option value="">Any</option>
                        @foreach ($blocks as $block)
                            <option value="{{ $block->id }}">{{ $block->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            @if ($zones)
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--foreground);">Zone (optional)</label>
                    <select wire:model="selectedZone" class="form-select w-full">
                        <option value="">Any</option>
                        @foreach ($zones as $zone)
                            <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="flex justify-between">
                <button type="button" class="btn px-4 py-2" wire:click="$set('step',2)">Back</button>
                <button type="button" class="btn px-4 py-2 bg-blue-600 text-white" wire:click="$set('step',4)">Next</button>
            </div>
        </div>
    @endif

    @if ($step === 4)
        <div class="space-y-6">
            @if ($bookingType === 'hourly')
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm mb-1" style="color: var(--foreground);">Date</label>
                        <input type="date" wire:model="startDate" class="form-input w-full">
                    </div>
                    <div>
                        <label class="block text-sm mb-1" style="color: var(--foreground);">Start Time</label>
                        <input type="time" wire:model="startTime" class="form-input w-full">
                    </div>
                    <div>
                        <label class="block text-sm mb-1" style="color: var(--foreground);">End Time</label>
                        <input type="time" wire:model="endTime" class="form-input w-full">
                    </div>
                </div>
            @elseif ($bookingType === 'daily')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm mb-1" style="color: var(--foreground);">Start Date</label>
                        <input type="date" wire:model="startDate" class="form-input w-full">
                    </div>
                    <div>
                        <label class="block text-sm mb-1" style="color: var(--foreground);">End Date</label>
                        <input type="date" wire:model="endDate" class="form-input w-full">
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm mb-1" style="color: var(--foreground);">Start Date</label>
                        <input type="date" wire:model="startDate" class="form-input w-full">
                    </div>
                    <div>
                        <label class="block text-sm mb-1" style="color: var(--foreground);">Duration ({{ $bookingType === 'monthly' ? 'months' : 'years' }})</label>
                        <input type="number" min="1" wire:model="duration" class="form-input w-full">
                    </div>
                </div>
            @endif
            <div class="flex justify-between">
                <button type="button" class="btn px-4 py-2" wire:click="$set('step',3)">Back</button>
                <button type="button" class="btn px-4 py-2 bg-blue-600 text-white" wire:click="calculateAvailability">Check
                    Availability</button>
            </div>
        </div>
    @endif

    @if ($step === 5)
        <div class="space-y-4">
            <h2 class="text-lg font-medium" style="color: var(--foreground);">Select Slot</h2>
            @if ($availableSlots->isEmpty())
                <p class="text-muted-foreground">No available slots for selected timeframe.</p>
            @else
                <ul class="space-y-2">
                    @foreach ($availableSlots as $slot)
                        <li>
                            <button type="button"
                                class="w-full text-left px-4 py-2 border rounded {{ $selectedSlot === $slot->id ? 'bg-blue-600 text-white' : '' }}"
                                wire:click="$set('selectedSlot','{{ $slot->id }}'); $set('step',6)">
                                {{ $slot->code }} - {{ $slot->zone->block->property->name }} /
                                {{ $slot->zone->block->name }} / {{ $slot->zone->name }}
                            </button>
                        </li>
                    @endforeach
                </ul>
            @endif
            <div class="flex justify-between mt-6">
                <button type="button" class="btn px-4 py-2" wire:click="$set('step',4)">Back</button>
            </div>
        </div>
    @endif

    @if ($step === 6)
        <div class="space-y-6">
            <div>
                <h2 class="text-lg font-medium mb-2" style="color: var(--foreground);">Additional Services (optional)</h2>
                <div class="space-y-2">
                    @foreach ($servicesList as $code => $label)
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" wire:model="selectedServices" value="{{ $code }}">
                            <span>{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            <div class="flex justify-between">
                <button type="button" class="btn px-4 py-2" wire:click="$set('step',5)">Back</button>
                <button type="button" class="btn px-4 py-2 bg-green-600 text-white" wire:click="createBooking">Create
                    Booking</button>
            </div>
        </div>
    @endif
</div>
