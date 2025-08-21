# Configuration Page Implementation Guide

## Senior Developer Guide: Extending Configuration Module UI Pattern

This guide outlines the standardized UI pattern we've established for the Properties management system and how to apply it consistently across all other configuration modules (Blocks, Zones, Slots, Settings, App Types).

## 🎯 Architecture Overview

### Established Pattern Structure
```
app/Livewire/Admin/Configuration/
├── Index.php                 # Main container with tabs and stats
├── Properties.php            # Display + Event Dispatching
├── Blocks.php                # Display + Event Dispatching
├── Zones.php                 # Display + Event Dispatching
├── Slots.php                 # Display + Event Dispatching
├── Settings.php              # Display + Event Dispatching
├── AppTypes.php              # Display + Event Dispatching
└── Forms/
    ├── PropertyForm.php      # Create/Edit Modal
    ├── PropertyDelete.php    # Delete Confirmation
    ├── BlockForm.php         # Create/Edit Modal
    ├── BlockDelete.php       # Delete Confirmation
    └── ... (repeat for each entity)
```

## 📋 Implementation Checklist

### Phase 1: Component Restructuring

#### 1.1 Main Display Components (`<Entity>.php`)
For each entity (Blocks, Zones, Slots, Settings, AppTypes):

**✅ Properties Pattern (Reference)**
```php
class Properties extends Component
{
    use WithPagination;

    public $search = '';
    public $showInactive = false;

    protected $listeners = [
        'property:saved' => '$refresh',
        'property:deleted' => '$refresh'
    ];

    // Event dispatching methods (not modal handling)
    public function create() {
        $this->dispatch('property:create');
    }

    public function edit($id) {
        $this->dispatch('property:edit', $id);
    }

    public function delete($id) {
        $this->dispatch('property:delete', $id);
    }
}
```

**🔄 Required Changes:**
- [ ] Remove modal-related properties (`$showModal`, `$editingEntity`, form fields)
- [ ] Replace modal methods with event dispatching
- [ ] Add search functionality with debounce
- [ ] Add active/inactive filter toggle
- [ ] Implement proper pagination reset
- [ ] Add efficient relationship loading

#### 1.2 Form Components (`Forms/<Entity>Form.php`)
**✅ Pattern Structure:**
```php
class EntityForm extends Component
{
    use AuthorizesRequests;

    public bool $showModal = false;
    public ?Entity $editingEntity = null;
    
    // Form fields as public properties
    public string $name = '';
    public string $code = '';
    // ... other fields

    protected $listeners = [
        'entity:create' => 'create',
        'entity:edit' => 'edit'
    ];

    protected function rules(): array {
        return [
            'name' => 'required|string|max:255',
            // ... validation rules
        ];
    }

    public function create() {
        $this->authorize('create', Entity::class);
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($entityId) {
        $this->editingEntity = Entity::find($entityId);
        if (!$this->editingEntity) return;
        
        $this->authorize('update', $this->editingEntity);
        $this->loadEntity();
        $this->showModal = true;
    }

    public function save() {
        $this->validate();
        
        try {
            DB::transaction(function () {
                $data = [
                    'name' => $this->name,
                    'code' => $this->code,
                    // ... other fields
                ];

                if ($this->editingEntity) {
                    $this->editingEntity->update($data);
                    session()->flash('message', 'Entity updated successfully!');
                } else {
                    Entity::create($data);
                    session()->flash('message', 'Entity created successfully!');
                }

                $this->dispatch('entity:saved');
            });

            // Close modal only after successful transaction
            $this->closeModal();

        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Entity save failed', [
                'entity_id' => $this->editingEntity?->id,
                'data' => $data ?? [],
                'error' => $e->getMessage()
            ]);

            // Flash error message to user
            session()->flash('error', 'Failed to save entity. Please check your input and try again.');

            // Don't close modal on failure so user can retry
        }
    }

    public function closeModal() {
        $this->showModal = false;
        $this->resetForm(); // or clear entity
        $this->dispatchBrowserEvent('entity-form:closed');
    }
}
```

#### 1.3 Delete Components (`Forms/<Entity>Delete.php`)
**✅ Pattern Structure:**
```php
use Illuminate\Support\Facades\DB;

class EntityDelete extends Component
{
    use AuthorizesRequests;

    public bool $showModal = false;
    public ?Entity $entity = null;

    protected $listeners = [
        'entity:delete' => 'confirmDelete'
    ];

    public function confirmDelete(string $entityId) {
        $this->entity = Entity::with('relationships')->find($entityId);
        if (!$this->entity) return;
        
        $this->authorize('delete', $this->entity);
        $this->showModal = true;
    }

    public function delete() {
        if (!$this->entity) return;
        
        try {
            DB::transaction(function () {
                $entityName = $this->entity->name;
                $this->entity->delete();
                
                // Flash success message and dispatch events within transaction
                session()->flash('message', "Entity '{$entityName}' deleted successfully!");
                $this->dispatch('entity:deleted');
            });
            
            // Close modal only after successful transaction
            $this->closeModal();
            
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Entity deletion failed', [
                'entity_id' => $this->entity->id,
                'entity_name' => $this->entity->name,
                'error' => $e->getMessage()
            ]);
            
            // Flash error message to user
            session()->flash('error', 'Failed to delete entity. Please try again or contact support if the issue persists.');
            
            // Don't close modal or dispatch success events on failure
        }
    }
}
```

### Phase 2: View Templates

#### 2.1 Main Display Views Pattern
**📁 File**: `resources/views/livewire/admin/configuration/<entity>.blade.php`

**🔄 Required Elements:**
- [ ] **Header with Actions Bar**
  ```blade
  {{-- Search and Filters --}}
  <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
      {{-- Search Input --}}
      <div class="relative flex-1 max-w-md">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <x-heroicon name="magnifying-glass" class="w-4 h-4" style="color: var(--muted-foreground);" />
          </div>
          <input 
              type="text" 
              wire:model.live.debounce.300ms="search"
              placeholder="Search by name or code..."
              class="form-input pl-10 h-10 text-sm"
          >
      </div>

      {{-- Toggle Filter --}}
      <button wire:click="toggleInactiveFilter" class="...">
          {{-- Active/Inactive toggle --}}
      </button>

      {{-- Add Entity Button --}}
      <button wire:click="create" class="btn h-10...">
          {{-- Create button --}}
      </button>
  </div>
  ```

- [ ] **Responsive Table Structure**
  - Desktop: Full table with all columns
  - Mobile: Card-based layout
  - Proper column alignment (`align-middle`)
  - Consistent button styling (`min-w-[4.5rem]`)

- [ ] **Event-Based Actions**
  ```blade
  <button wire:click="edit('{{ $entity->id }}')" class="btn-secondary...">
      Edit
  </button>
  <button wire:click="delete('{{ $entity->id }}')" class="btn-destructive...">
      Delete  
  </button>
  ```

#### 2.2 Form Modal Views
**📁 Files**: 
- `resources/views/livewire/admin/configuration/forms/<entity>-form.blade.php`
- `resources/views/livewire/admin/configuration/forms/<entity>-delete.blade.php`

**🔄 Required Structure:**
```blade
<div>
@if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
         x-data
         x-init="$nextTick(() => { document.body.style.overflow='hidden' })"
         x-on:keydown.escape.window="$wire.closeModal()"
         x-on:entity-form:closed.window="$nextTick(() => { document.body.style.overflow=''; })"
         wire:ignore.self>
        
        {{-- Full-screen backdrop --}}
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"
             wire:click="closeModal"
             style="backdrop-filter: blur(8px);"></div>

        {{-- Modal content --}}
        {{-- Form or delete confirmation content --}}
    </div>
@endif
</div>
```

**Modal Scroll Cleanup**: The `x-on:entity-form:closed.window` listener restores body scrolling when the modal closes. Each form type should have its own event name (e.g., `property-form:closed`, `block-form:closed`).

#### 2.3 Index View Integration
**📁 File**: `resources/views/livewire/admin/configuration/index.blade.php`

**🔄 Required Updates:**
```blade
{{-- Add form components at bottom --}}
@livewire('admin.configuration.forms.block-form')
@livewire('admin.configuration.forms.block-delete')
@livewire('admin.configuration.forms.zone-form')
@livewire('admin.configuration.forms.zone-delete')
{{-- Repeat for all entities --}}
```

### Phase 3: Database Optimizations

#### 3.1 Efficient Relationship Loading
```php
// In render() methods, use eager loading
$query = Entity::with(['relationships'])
    ->withCount(['relatedItems']);
```

#### 3.2 Search Implementation
```php
// Secure search pattern with escaped wildcards
if ($this->search) {
    $escapedSearch = addcslashes($this->search, '%_\\');
    $query->where(function ($q) use ($escapedSearch) {
        $q->where('name', 'like', '%' . $escapedSearch . '%')
          ->orWhere('code', 'like', '%' . $escapedSearch . '%');
    });
}
```

**Security Note**: Always escape user input to prevent SQL wildcard injection attacks. The `addcslashes()` function escapes `%`, `_`, and `\` characters that could be abused in LIKE queries.

#### 3.3 Filter Implementation
```php
// Active/Inactive filtering
if ($this->showInactive) {
    $query->where('is_active', false);
} else {
    $query->where('is_active', true);
}
```

### Phase 4: Styling Standards

#### 4.1 Button Consistency
```css
/* All action buttons should have consistent height */
.h-10 { height: 2.5rem; } /* 40px */

/* Edit/Delete buttons should have minimum width */
.min-w-[4.5rem] { min-width: 4.5rem; } /* 72px */

/* Destructive button hover states */
.btn-destructive:hover {
    background-color: color-mix(in oklch, var(--destructive) 10%, transparent);
}
```

#### 4.2 Table Styling
```blade
{{-- All table cells should use align-middle --}}
<td class="px-4 py-3 align-middle">
    {{-- Content --}}
</td>

{{-- Badge styling for counts --}}
<span class="badge">{{ $count }}</span>

{{-- Status badges --}}
<span class="badge {{ $entity->is_active ? 'success' : 'secondary' }}">
    {{ $entity->is_active ? 'Active' : 'Inactive' }}
</span>
```

#### 4.3 Form Styling
```blade
{{-- Input styling --}}
<input type="text" wire:model="field" class="form-input">

{{-- Form layout --}}
<div class="space-y-4">
    {{-- Form fields with consistent spacing --}}
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    {{-- Two-column responsive layout --}}
</div>
```

## 🚀 Implementation Priority

### Phase 1: Core Structure (Week 1)
1. **Blocks Module**: Complete form separation and event-based architecture
2. **Zones Module**: Implement search and filtering
3. **Slots Module**: Add relationship-aware queries

### Phase 2: Advanced Features (Week 2)
1. **Settings Module**: Key-value pair management with JSON support
2. **App Types Module**: Grouped lookup value management
3. **Performance optimization**: Query optimization and caching

### Phase 3: Polish & Testing (Week 3)
1. **UI consistency**: Ensure all modules match Properties styling
2. **Responsive testing**: Mobile/desktop compatibility
3. **Authorization testing**: Role-based access control
4. **Performance testing**: Large dataset handling

## 🔧 Technical Considerations

### Authorization Pattern
```php
// In all form components
public function create() {
    $this->authorize('create', EntityModel::class);
    // ...
}

public function edit($entityId) {
    $entity = EntityModel::find($entityId);
    $this->authorize('update', $entity);
    // ...
}
```

### Event Naming Convention
```php
// Events should follow: 'entity:action' pattern
$this->dispatch('property:create');
$this->dispatch('property:edit', $propertyId);
$this->dispatch('property:delete', $propertyId);
$this->dispatch('property:saved');
$this->dispatch('property:deleted');
```

### Validation Standards
```php
// Use form requests or component validation
protected function rules(): array {
    return [
        'name' => 'required|string|max:255',
        'code' => [
            'required',
            'string', 
            'max:50',
            Rule::unique('table', 'code')->ignore($this->editingEntity?->id)
        ],
        'is_active' => 'boolean',
    ];
}
```

## 📚 Key Benefits of This Pattern

1. **🔄 Separation of Concerns**: Display logic separate from form logic
2. **🎯 Reusability**: Form components can be reused across different contexts
3. **⚡ Performance**: Optimized queries and lazy loading
4. **📱 Responsive**: Consistent mobile/desktop experience
5. **🔒 Security**: Proper authorization at every level
6. **🧪 Testability**: Clear component boundaries for testing
7. **🎨 Consistency**: Uniform UI/UX across all modules

## 🎉 Success Metrics

- [ ] All CRUD operations work seamlessly with modal forms
- [ ] Search and filtering work across all modules
- [ ] Mobile responsiveness maintained
- [ ] Authorization properly enforced
- [ ] Performance remains optimal with large datasets
- [ ] UI consistency matches Properties module exactly

---

**Next Steps**: Start with Blocks module implementation following this exact pattern, then proceed through Zones, Slots, Settings, and App Types in that order.