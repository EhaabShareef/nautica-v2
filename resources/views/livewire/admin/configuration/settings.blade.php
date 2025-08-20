<div>
    <!-- Header with Action Button -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <div>
            <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--foreground); margin: 0;">Application Settings</h3>
            <p style="color: var(--muted-foreground); font-size: 0.875rem; margin: 0;">Manage system-wide application settings and configurations</p>
        </div>
        <button wire:click="create" class="btn" style="font-size: 0.875rem;">
            <x-heroicon name="plus" class="w-4 h-4" />
            Add Setting
        </button>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div style="background-color: var(--success); color: var(--success-foreground); padding: 1rem; border-radius: var(--radius); margin-bottom: 1rem;">
            {{ session('message') }}
        </div>
    @endif

    <!-- Settings Table -->
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Setting Key</th>
                    <th>Value</th>
                    <th>Type</th>
                    <th>Updated</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($settings as $setting)
                    <tr>
                        <td>
                            <code style="background-color: var(--muted); color: var(--foreground); padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.875rem;">{{ $setting->key }}</code>
                        </td>
                        <td>
                            <div style="max-width: 300px; overflow: hidden;">
                                @if(is_bool($setting->value))
                                    <span class="badge {{ $setting->value ? 'success' : 'secondary' }}">
                                        {{ $setting->value ? 'True' : 'False' }}
                                    </span>
                                @elseif(is_array($setting->value) || is_object($setting->value))
                                    <details style="font-size: 0.875rem;">
                                        <summary style="cursor: pointer; color: var(--primary);">View JSON</summary>
                                        <pre style="background-color: var(--muted); padding: 0.5rem; border-radius: var(--radius); font-size: 0.75rem; margin-top: 0.5rem; overflow-x: auto;">{{ json_encode($setting->value, JSON_PRETTY_PRINT) }}</pre>
                                    </details>
                                @elseif(is_numeric($setting->value))
                                    <span style="font-weight: 500; color: var(--chart-4);">{{ $setting->value }}</span>
                                @else
                                    <span style="font-size: 0.875rem;">{{ Str::limit($setting->value, 50) }}</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if(is_bool($setting->value))
                                <span class="badge secondary">Boolean</span>
                            @elseif(is_array($setting->value) || is_object($setting->value))
                                <span class="badge primary">JSON</span>
                            @elseif(is_numeric($setting->value))
                                <span class="badge success">Number</span>
                            @else
                                <span class="badge">String</span>
                            @endif
                        </td>
                        <td>
                            <div style="font-size: 0.875rem;">{{ $setting->updated_at->format('M j, Y') }}</div>
                            <div style="font-size: 0.75rem; color: var(--muted-foreground);">{{ $setting->updated_at->diffForHumans() }}</div>
                        </td>
                        <td>
                            <div style="display: flex; gap: 0.5rem;">
                                <button wire:click="edit({{ $setting->key }})" class="btn-secondary" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                                    <x-heroicon name="pencil" class="w-3 h-3" />
                                    Edit
                                </button>
                                <button wire:click="delete({{ $setting->key }})" 
                                        wire:confirm="Are you sure you want to delete this setting?"
                                        class="btn-destructive" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                                    <x-heroicon name="trash" class="w-3 h-3" />
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 2rem;">
                            <x-heroicon name="cog-6-tooth" class="w-12 h-12 mx-auto mb-4" style="color: var(--muted-foreground);" />
                            <p style="color: var(--muted-foreground); margin: 0;">No settings found</p>
                            <p style="font-size: 0.875rem; color: var(--muted-foreground); margin-top: 0.5rem;">Create your first setting to configure the application</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="margin-top: 1rem;">
        {{ $settings->links() }}
    </div>

    <!-- Modal -->
    @if($showModal)
        <div class="modal-backdrop" wire:click="closeModal">
            <div class="modal" wire:click.stop>
                <div class="modal-header">
                    <h3>{{ $editingSetting ? 'Edit Setting' : 'Create Setting' }}</h3>
                    <button wire:click="closeModal" class="modal-close">
                        <x-heroicon name="x-mark" class="w-5 h-5" />
                    </button>
                </div>

                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="key">Setting Key</label>
                            <input type="text" id="key" wire:model="key" class="form-input" 
                                   placeholder="e.g., app.name, booking.hold_minutes" 
                                   {{ $editingSetting ? 'readonly' : '' }}>
                            @error('key') <span class="error">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label for="valueType">Value Type</label>
                            <select id="valueType" wire:model="valueType" class="form-input">
                                <option value="string">String</option>
                                <option value="number">Number</option>
                                <option value="boolean">Boolean</option>
                                <option value="json">JSON</option>
                            </select>
                            @error('valueType') <span class="error">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label for="value">
                                Value
                                @if($valueType === 'boolean')
                                    <span style="font-size: 0.75rem; color: var(--muted-foreground);">(1 for true, 0 for false)</span>
                                @elseif($valueType === 'json')
                                    <span style="font-size: 0.75rem; color: var(--muted-foreground);">(Valid JSON format required)</span>
                                @endif
                            </label>
                            
                            @if($valueType === 'boolean')
                                <select id="value" wire:model="value" class="form-input">
                                    <option value="1">True</option>
                                    <option value="0">False</option>
                                </select>
                            @elseif($valueType === 'json')
                                <textarea id="value" wire:model="value" class="form-input" rows="6" 
                                         placeholder='{"key": "value", "array": [1, 2, 3]}'></textarea>
                            @else
                                <input type="{{ $valueType === 'number' ? 'number' : 'text' }}" 
                                       id="value" wire:model="value" class="form-input" 
                                       placeholder="Enter {{ $valueType }} value">
                            @endif
                            
                            @error('value') <span class="error">{{ $message }}</span> @enderror
                        </div>

                        @if($valueType === 'json')
                            <div style="background-color: var(--muted); padding: 0.75rem; border-radius: var(--radius); font-size: 0.75rem;">
                                <strong>JSON Examples:</strong><br>
                                String: "Hello World"<br>
                                Array: ["item1", "item2", "item3"]<br>
                                Object: {"name": "John", "age": 30}
                            </div>
                        @endif
                    </div>

                    <div class="modal-footer">
                        <button type="button" wire:click="closeModal" class="btn-secondary">Cancel</button>
                        <button type="submit" class="btn">
                            {{ $editingSetting ? 'Update Setting' : 'Create Setting' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>