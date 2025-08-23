{{-- Vessel Form Modal --}}
<div>
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
             x-data
             x-init="$nextTick(() => { document.body.style.overflow='hidden' })"
             x-on:keydown.escape.window="$wire.closeModal()"
             x-on:vessel-form:closed.window="$nextTick(() => { document.body.style.overflow=''; })"
             wire:ignore.self role="dialog" aria-modal="true"
             aria-labelledby="vessel-form-title"
             aria-describedby="vessel-form-desc">

            {{-- Full-screen backdrop --}}
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" wire:click="closeModal"></div>

            {{-- Modal panel --}}
            <div class="relative w-full max-w-4xl max-h-[90vh] transform transition-all duration-200"
                 style="background: var(--card); border: 1px solid var(--border); border-radius: var(--radius); box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); animation: modal-appear 0.2s ease-out;">
                    
                {{-- Modal Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b" style="border-color: var(--border);">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-lg">
                            <x-heroicon name="{{ $isEditing ? 'pencil' : 'rocket-launch' }}" class="w-5 h-5 text-white" />
                        </div>
                        <div>
                            <h3 id="vessel-form-title" class="text-lg font-semibold" style="color: var(--foreground);">
                                {{ $isEditing ? 'Edit Vessel' : 'Register New Vessel' }}
                            </h3>
                            <p id="vessel-form-desc" class="text-sm" style="color: var(--muted-foreground);">
                                {{ $isEditing ? 'Update vessel information' : 'Register a new vessel in the system' }}
                            </p>
                        </div>
                    </div>
                    <button type="button" wire:click="closeModal" class="p-2 rounded-lg transition-colors hover:bg-gray-100 dark:hover:bg-gray-800">
                        <x-heroicon name="x-mark" class="w-5 h-5" style="color: var(--muted-foreground);" />
                    </button>
                </div>

                {{-- Modal Form --}}
                <form wire:submit.prevent="save">
                    <div class="px-6 py-6 space-y-6 max-h-[60vh] overflow-y-auto">
                        {{-- Error Display --}}
                        @if($errors->has('form'))
                            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                                <div class="flex items-center gap-2">
                                    <x-heroicon name="exclamation-triangle" class="w-5 h-5 text-red-500" />
                                    <p class="text-sm text-red-700 dark:text-red-400">{{ $errors->first('form') }}</p>
                                </div>
                            </div>
                        @endif

                        {{-- Basic Information --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Vessel Name --}}
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Vessel Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="name"
                                       wire:model="name"
                                       class="form-input w-full {{ $errors->has('name') ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : '' }}"
                                       placeholder="Enter vessel name">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Registration Number --}}
                            <div>
                                <label for="registration_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Registration Number <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="registration_number"
                                       wire:model="registration_number"
                                       class="form-input w-full {{ $errors->has('registration_number') ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : '' }}"
                                       placeholder="Enter registration number">
                                @error('registration_number')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Owner Selection --}}
                        <div>
                            <label for="owner_search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Owner <span class="text-red-500">*</span>
                            </label>
                            <div class="relative" x-data="{ open: @entangle('showOwnerDropdown') }">
                                <input type="text" 
                                       id="owner_search"
                                       wire:model.live.debounce.300ms="ownerSearch"
                                       @focus="open = true"
                                       class="form-input w-full {{ $errors->has('owner_client_id') ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : '' }}"
                                       placeholder="Search for owner...">
                                
                                @if($showOwnerDropdown && count($eligibleOwners) > 0)
                                    <div class="absolute z-10 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                        @foreach($eligibleOwners as $client)
                                            <button type="button" 
                                                    wire:click="selectOwner({{ $client['id'] }})"
                                                    class="w-full px-4 py-2 text-left hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-2">
                                                <div class="w-6 h-6 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center text-white text-xs">
                                                    {{ substr($client['display_name'], 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium">{{ $client['display_name'] }}</div>
                                                    <div class="text-xs text-muted-foreground">{{ $client['email'] }}</div>
                                                </div>
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            @error('owner_client_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Renter Selection --}}
                        <div>
                            <label for="renter_search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Renter (Optional)
                            </label>
                            <div class="relative" x-data="{ open: @entangle('showRenterDropdown') }">
                                <div class="flex gap-2">
                                    <input type="text" 
                                           id="renter_search"
                                           wire:model.live.debounce.300ms="renterSearch"
                                           @focus="open = true"
                                           class="form-input flex-1 {{ $errors->has('renter_client_id') ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : '' }}"
                                           placeholder="Search for renter...">
                                    @if($renter_client_id)
                                        <button type="button" 
                                                wire:click="clearRenter"
                                                class="px-3 py-2 text-sm bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">
                                            Clear
                                        </button>
                                    @endif
                                </div>
                                
                                @if($showRenterDropdown && count($eligibleRenters) > 0)
                                    <div class="absolute z-10 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                        @foreach($eligibleRenters as $client)
                                            <button type="button" 
                                                    wire:click="selectRenter({{ $client['id'] }})"
                                                    class="w-full px-4 py-2 text-left hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-2">
                                                <div class="w-6 h-6 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white text-xs">
                                                    {{ substr($client['display_name'], 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium">{{ $client['display_name'] }}</div>
                                                    <div class="text-xs text-muted-foreground">{{ $client['email'] }}</div>
                                                </div>
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            @error('renter_client_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Type and Dimensions --}}
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            {{-- Type --}}
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Type
                                </label>
                                <select wire:model="type" id="type" class="form-input w-full">
                                    <option value="">Select type...</option>
                                    @foreach($vesselTypes as $vesselType)
                                        <option value="{{ $vesselType['code'] }}">{{ $vesselType['name'] }}</option>
                                    @endforeach
                                </select>
                                @error('type')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Length --}}
                            <div>
                                <label for="length" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Length (m)
                                </label>
                                <input type="number" 
                                       id="length"
                                       wire:model="length"
                                       step="0.01"
                                       class="form-input w-full {{ $errors->has('length') ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : '' }}"
                                       placeholder="0.00">
                                @error('length')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Width --}}
                            <div>
                                <label for="width" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Width (m)
                                </label>
                                <input type="number" 
                                       id="width"
                                       wire:model="width"
                                       step="0.01"
                                       class="form-input w-full {{ $errors->has('width') ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : '' }}"
                                       placeholder="0.00">
                                @error('width')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Draft --}}
                            <div>
                                <label for="draft" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Draft (m)
                                </label>
                                <input type="number" 
                                       id="draft"
                                       wire:model="draft"
                                       step="0.01"
                                       class="form-input w-full {{ $errors->has('draft') ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : '' }}"
                                       placeholder="0.00">
                                @error('draft')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Specifications --}}
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Additional Specifications
                                </label>
                                <button type="button" 
                                        wire:click="addSpecification"
                                        class="text-sm text-blue-600 hover:text-blue-700 flex items-center gap-1">
                                    <x-heroicon name="plus" class="w-4 h-4" />
                                    Add Specification
                                </button>
                            </div>
                            
                            @if(is_array($specifications) && count($specifications) > 0)
                                <div class="space-y-2">
                                    @foreach($specifications as $index => $spec)
                                        <div class="flex gap-2 items-start">
                                            <input type="text" 
                                                   wire:model="specifications.{{ $index }}.key"
                                                   placeholder="Key (e.g., Engine)"
                                                   class="form-input flex-1">
                                            <input type="text" 
                                                   wire:model="specifications.{{ $index }}.value"
                                                   placeholder="Value (e.g., 200HP)"
                                                   class="form-input flex-1">
                                            <button type="button" 
                                                    wire:click="removeSpecification({{ $index }})"
                                                    class="p-2 text-red-600 hover:text-red-700">
                                                <x-heroicon name="trash" class="w-4 h-4" />
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        {{-- Status --}}
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="is_active"
                                   wire:model="is_active"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_active" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                Active vessel
                            </label>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="px-6 py-4 border-t flex justify-end gap-3" style="border-color: var(--border);">
                        <button type="button" wire:click="closeModal" class="btn-secondary">
                            Cancel
                        </button>
                        <button type="submit" 
                                wire:loading.attr="disabled"
                                wire:target="save"
                                class="btn">
                            <span wire:loading.remove wire:target="save">
                                {{ $isEditing ? 'Update Vessel' : 'Register Vessel' }}
                            </span>
                            <span wire:loading wire:target="save" class="flex items-center gap-2">
                                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{ $isEditing ? 'Updating...' : 'Registering...' }}
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>