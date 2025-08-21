# Settings Implementation Guide

## Senior Developer Guide: Unified Settings & App Types Management

This guide outlines the implementation plan for a unified Settings page that manages both **Application Settings** and **Configurable Types (AppTypes)** following the established configuration module patterns.

## 🎯 Architecture Overview

### Current State Analysis
- **Setting Model**: Uses string primary key (`key`), stores JSON `value`, basic structure
- **AppType Model**: Uses UUID primary key, has `group/code` unique constraint, includes `extra` JSON field
- **Missing Features**: No grouping for settings, no `is_protected` field, no advanced filtering capabilities

### Target Architecture
```
app/Livewire/Admin/Configuration/Settings/
├── Index.php                    # Main container with tabs
├── SettingsList.php             # Application Settings display
├── AppTypesList.php             # Configurable Types display
└── Forms/
    ├── SettingForm.php          # Setting Create/Edit Modal
    ├── SettingDelete.php        # Setting Delete Confirmation
    ├── AppTypeForm.php          # AppType Create/Edit Modal
    └── AppTypeDelete.php        # AppType Delete Confirmation
```

## 📋 Implementation Plan

### Phase 1: Database Schema Enhancements (Week 1)

#### 1.1 Settings Table Enhancement
**Migration**: `add_settings_metadata_columns.php`
```php
Schema::table('settings', function (Blueprint $table) {
    $table->string('group')->nullable()->after('key');
    $table->string('label')->nullable()->after('value');
    $table->text('description')->nullable()->after('label');
    $table->boolean('is_protected')->default(false)->after('description');
    $table->boolean('is_active')->default(true)->after('is_protected');
    
    // Add indexes for performance
    $table->index(['group', 'is_active']);
    $table->index('is_protected');
});
```

#### 1.2 AppType Schema Validation
**Migration**: `enhance_app_types_table.php`
```php
Schema::table('app_types', function (Blueprint $table) {
    $table->integer('sort_order')->default(0)->after('label');
    $table->boolean('is_protected')->default(false)->after('is_active');
    $table->text('description')->nullable()->after('label');
    
    // Enhanced indexes
    $table->index(['group', 'is_active', 'sort_order']);
});
```

### Phase 2: Model Enhancements (Week 1)

#### 2.1 Setting Model Updates
```php
class Setting extends Model
{
    protected $fillable = [
        'key', 'group', 'value', 'label', 'description', 'is_protected', 'is_active'
    ];
    
    protected $casts = [
        'is_protected' => 'boolean',
        'is_active' => 'boolean',
    ];
    
    // Scope for admin-accessible settings
    public function scopeAccessible($query) {
        return $query->where('is_active', true);
    }
    
    // Scope by group
    public function scopeByGroup($query, $group) {
        return $query->where('group', $group);
    }
    
    // Cache helper methods
    public static function get($key, $default = null) {
        return Cache::remember("setting:{$key}", 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->where('is_active', true)->first();
            return $setting ? $setting->value : $default;
        });
    }
}
```

#### 2.2 AppType Model Updates
```php
class AppType extends Model
{
    protected $fillable = [
        'group', 'code', 'label', 'description', 'sort_order', 
        'extra', 'is_protected', 'is_active'
    ];
    
    protected $casts = [
        'extra' => 'array',
        'is_protected' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];
    
    // Scope for active types
    public function scopeActive($query) {
        return $query->where('is_active', true);
    }
    
    // Scope by group with ordering
    public function scopeByGroup($query, $group) {
        return $query->where('group', $group)
                    ->orderBy('sort_order')
                    ->orderBy('label');
    }
    
    // Cache helper methods
    public static function getByGroup($group) {
        return Cache::remember("types:{$group}", 3600, function () use ($group) {
            return static::active()->byGroup($group)->get();
        });
    }
}
```

### Phase 3: Livewire Components (Week 2)

#### 3.1 Main Container Component
**File**: `app/Livewire/Admin/Configuration/Settings/Index.php`
```php
class Index extends Component
{
    public string $activeTab = 'settings';
    
    protected $listeners = [
        'setting:saved' => '$refresh',
        'setting:deleted' => '$refresh',
        'apptype:saved' => '$refresh',
        'apptype:deleted' => '$refresh',
    ];
    
    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }
    
    public function render()
    {
        return view('livewire.admin.configuration.settings.index');
    }
}
```

#### 3.2 Settings List Component
**File**: `app/Livewire/Admin/Configuration/Settings/SettingsList.php`
```php
class SettingsList extends Component
{
    use WithPagination;
    
    public string $search = '';
    public string $groupFilter = '';
    public bool $showProtected = false;
    
    protected $listeners = [
        'setting:saved' => '$refresh',
        'setting:deleted' => '$refresh'
    ];
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function create()
    {
        $this->dispatch('setting:create');
    }
    
    public function edit($settingKey)
    {
        $this->dispatch('setting:edit', $settingKey);
    }
    
    public function delete($settingKey)
    {
        $this->dispatch('setting:delete', $settingKey);
    }
    
    public function render()
    {
        $query = Setting::query();
        
        // Search functionality with escaped wildcards
        if ($this->search) {
            $escapedSearch = addcslashes($this->search, '%_\\');
            $query->where(function ($q) use ($escapedSearch) {
                $q->where('key', 'like', '%' . $escapedSearch . '%')
                  ->orWhere('label', 'like', '%' . $escapedSearch . '%')
                  ->orWhere('description', 'like', '%' . $escapedSearch . '%');
            });
        }
        
        // Group filter
        if ($this->groupFilter) {
            $query->where('group', $this->groupFilter);
        }
        
        // Protected filter
        if (!$this->showProtected) {
            $query->where('is_protected', false);
        }
        
        $settings = $query->where('is_active', true)
                         ->orderBy('group')
                         ->orderBy('key')
                         ->paginate(15);
                         
        $groups = Setting::select('group')
                        ->whereNotNull('group')
                        ->distinct()
                        ->pluck('group');
        
        return view('livewire.admin.configuration.settings.settings-list', [
            'settings' => $settings,
            'groups' => $groups
        ]);
    }
}
```

#### 3.3 AppTypes List Component
**File**: `app/Livewire/Admin/Configuration/Settings/AppTypesList.php`
```php
class AppTypesList extends Component
{
    use WithPagination;
    
    public string $search = '';
    public string $groupFilter = '';
    public bool $showInactive = false;
    public bool $showProtected = false;
    
    protected $listeners = [
        'apptype:saved' => '$refresh',
        'apptype:deleted' => '$refresh'
    ];
    
    public function mount()
    {
        // Set default group if available
        $firstGroup = AppType::select('group')->distinct()->first();
        $this->groupFilter = $firstGroup?->group ?? '';
    }
    
    public function create()
    {
        $this->dispatch('apptype:create');
    }
    
    public function edit($appTypeId)
    {
        $this->dispatch('apptype:edit', $appTypeId);
    }
    
    public function delete($appTypeId)
    {
        $this->dispatch('apptype:delete', $appTypeId);
    }
    
    public function render()
    {
        $query = AppType::query();
        
        // Group filter (required for AppTypes)
        if ($this->groupFilter) {
            $query->where('group', $this->groupFilter);
        }
        
        // Search functionality
        if ($this->search) {
            $escapedSearch = addcslashes($this->search, '%_\\');
            $query->where(function ($q) use ($escapedSearch) {
                $q->where('code', 'like', '%' . $escapedSearch . '%')
                  ->orWhere('label', 'like', '%' . $escapedSearch . '%');
            });
        }
        
        // Active/Inactive filter
        if (!$this->showInactive) {
            $query->where('is_active', true);
        }
        
        // Protected filter
        if (!$this->showProtected) {
            $query->where('is_protected', false);
        }
        
        $appTypes = $query->orderBy('sort_order')
                         ->orderBy('label')
                         ->paginate(15);
                         
        $groups = AppType::select('group')
                        ->distinct()
                        ->orderBy('group')
                        ->pluck('group');
        
        return view('livewire.admin.configuration.settings.app-types-list', [
            'appTypes' => $appTypes,
            'groups' => $groups
        ]);
    }
}
```

### Phase 4: Form Components (Week 2)

#### 4.1 Setting Form Component
**File**: `app/Livewire/Admin/Configuration/Settings/Forms/SettingForm.php`
```php
class SettingForm extends Component
{
    use AuthorizesRequests;
    
    public bool $showModal = false;
    public ?Setting $editingSetting = null;
    
    public string $key = '';
    public string $group = '';
    public string $label = '';
    public string $description = '';
    public string $valueInput = '';
    public bool $is_protected = false;
    public bool $is_active = true;
    
    protected $listeners = [
        'setting:create' => 'create',
        'setting:edit' => 'edit'
    ];
    
    protected function rules(): array
    {
        $keyRule = 'required|string|max:255|unique:settings,key';
        if ($this->editingSetting) {
            $keyRule = 'required|string|max:255|unique:settings,key,' . $this->editingSetting->key . ',key';
        }
        
        return [
            'key' => $keyRule,
            'group' => 'nullable|string|max:100',
            'label' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'valueInput' => 'required',
            'is_protected' => 'boolean',
            'is_active' => 'boolean',
        ];
    }
    
    public function create()
    {
        $this->authorize('create', Setting::class);
        $this->resetForm();
        $this->showModal = true;
    }
    
    public function edit($settingKey)
    {
        $setting = Setting::where('key', $settingKey)->first();
        if (!$setting) return;
        
        $this->authorize('update', $setting);
        $this->loadSetting($setting);
        $this->showModal = true;
    }
    
    public function save()
    {
        // Authorization per action
        if ($this->editingSetting) {
            $this->authorize('update', $this->editingSetting);
        } else {
            $this->authorize('create', Setting::class);
        }
        
        $this->validate();
        
        // Validate JSON if needed
        $value = $this->parseValue($this->valueInput);
        
        try {
            DB::transaction(function () use ($value) {
                $data = [
                    'key' => $this->key,
                    'group' => $this->group ?: null,
                    'value' => $value,
                    'label' => $this->label ?: null,
                    'description' => $this->description ?: null,
                    'is_protected' => $this->is_protected,
                    'is_active' => $this->is_active,
                ];
                
                if ($this->editingSetting) {
                    $this->editingSetting->update($data);
                    session()->flash('message', 'Setting updated successfully!');
                } else {
                    Setting::create($data);
                    session()->flash('message', 'Setting created successfully!');
                }
                
                $this->dispatch('setting:saved');
                // Clear cache
                Cache::forget("setting:{$this->key}");
            });
            
            $this->closeModal();
            
        } catch (\Exception $e) {
            \Log::error('Setting save failed', [
                'key' => $this->key,
                'error' => $e->getMessage()
            ]);
            
            session()->flash('error', 'Failed to save setting. Please check your input and try again.');
        }
    }
    
    private function parseValue($input)
    {
        // Try to decode as JSON first
        $decoded = json_decode($input, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }
        
        // Return as string if not valid JSON
        return $input;
    }
    
    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->dispatchBrowserEvent('setting-form:closed');
    }
    
    // ... resetForm() and loadSetting() methods
}
```

### Phase 5: Views & UI (Week 3)

#### 5.1 Main Settings View
**File**: `resources/views/livewire/admin/configuration/settings/index.blade.php`
```blade
<div class="container mx-auto px-4 py-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold" style="color: var(--foreground);">Settings</h1>
            <p class="text-sm mt-1" style="color: var(--muted-foreground);">
                Manage application settings and configurable types
            </p>
        </div>
        
        {{-- Back to Dashboard --}}
        <a href="{{ route('admin.dashboard') }}" class="btn-secondary h-10 flex items-center gap-2">
            <x-heroicon name="arrow-left" class="w-4 h-4" />
            Back to Dashboard
        </a>
    </div>

    {{-- Tab Navigation --}}
    <div class="border-b mb-6" style="border-color: var(--border);">
        <nav class="-mb-px flex space-x-8">
            <button 
                wire:click="setActiveTab('settings')"
                class="py-2 px-1 border-b-2 font-medium text-sm transition-colors {{ $activeTab === 'settings' ? 'border-blue-500 text-blue-600' : 'border-transparent hover:border-gray-300' }}"
            >
                Application Settings
            </button>
            <button 
                wire:click="setActiveTab('types')"
                class="py-2 px-1 border-b-2 font-medium text-sm transition-colors {{ $activeTab === 'types' ? 'border-blue-500 text-blue-600' : 'border-transparent hover:border-gray-300' }}"
            >
                Configurable Types
            </button>
        </nav>
    </div>

    {{-- Tab Content --}}
    <div class="min-h-[400px]">
        @if($activeTab === 'settings')
            @livewire('admin.configuration.settings.settings-list')
        @else
            @livewire('admin.configuration.settings.app-types-list')
        @endif
    </div>

    {{-- Form Modals --}}
    @livewire('admin.configuration.settings.forms.setting-form')
    @livewire('admin.configuration.settings.forms.setting-delete')
    @livewire('admin.configuration.settings.forms.app-type-form')
    @livewire('admin.configuration.settings.forms.app-type-delete')
</div>
```

### Phase 6: Caching & Performance (Week 3)

#### 6.1 Cache Service
**File**: `app/Services/SettingsService.php`
```php
class SettingsService
{
    public static function get(string $key, $default = null)
    {
        return Cache::remember("setting:{$key}", 3600, function () use ($key, $default) {
            $setting = Setting::where('key', $key)->where('is_active', true)->first();
            return $setting ? $setting->value : $default;
        });
    }
    
    public static function getGroup(string $group): Collection
    {
        return Cache::remember("settings:group:{$group}", 3600, function () use ($group) {
            return Setting::where('group', $group)
                         ->where('is_active', true)
                         ->get()
                         ->pluck('value', 'key');
        });
    }
    
    public static function clearCache(?string $key = null)
    {
        if ($key) {
            Cache::forget("setting:{$key}");
        } else {
            Cache::flush(); // Or use more specific cache tag clearing
        }
    }
}
```

### Phase 7: Routes & Policies (Week 1)

#### 7.1 Route Definition
```php
// routes/web.php
Route::middleware(['auth', 'admin'])->prefix('admin/configuration')->group(function () {
    Route::get('/settings', [SettingsController::class, 'index'])->name('admin.configuration.settings');
});
```

#### 7.2 Policies
**File**: `app/Policies/SettingPolicy.php`
```php
class SettingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }
    
    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }
    
    public function update(User $user, Setting $setting): bool
    {
        return $user->hasRole('admin') && !$setting->is_protected;
    }
    
    public function delete(User $user, Setting $setting): bool
    {
        return $user->hasRole('admin') && !$setting->is_protected;
    }
}
```

## 🚀 Implementation Priority

### Week 1: Foundation
1. Database schema enhancements
2. Model updates with caching
3. Route and policy setup

### Week 2: Core Components
1. Livewire container and list components
2. Form components with validation
3. Event system implementation

### Week 3: UI & Polish
1. Blade templates with responsive design
2. Performance optimization
3. Testing and documentation

## 🔧 Key Features

### Security
- Admin-only access control
- Protected settings cannot be modified/deleted
- Per-action authorization checks
- Input validation and sanitization

### Performance
- Comprehensive caching strategy
- Efficient database queries with indexes
- Lazy loading and pagination

### UX
- Unified interface for both settings types
- Consistent modal patterns
- Search and filtering capabilities
- Mobile-responsive design

## 🎉 Success Criteria

 - [x] Single settings page with two functional tabs
 - [x] Full CRUD operations for both settings and app types
 - [x] Search and filtering work correctly
 - [x] Protected items cannot be deleted/modified
 - [x] Caching provides performance benefits
 - [x] Mobile responsiveness maintained
 - [x] Admin authorization enforced
 - [x] Event-driven architecture implemented
