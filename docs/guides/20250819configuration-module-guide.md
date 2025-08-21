# Nautica Configuration Module Guide

## Overview

The Configuration Module provides a centralized system for managing marina properties and their hierarchical structure (Properties → Blocks → Zones → Slots). It also handles application-wide settings and lookup values. Only administrators have access to these configuration features.

## Database Structure

### Core Hierarchy Tables

#### Properties (Marina Locations)
- **properties**: Main marina property records
  - `id` (UUID) - Primary key
  - `name` - Property name (e.g., "Marina Bay Resort")
  - `code` - Unique property identifier (e.g., "MBR")
  - `address` - Property physical address (nullable)
  - `timezone` - Property timezone (nullable)
  - `currency` - Property currency code (nullable, 3-char)
  - `is_active` - Active status flag
  - `created_at`, `updated_at` - Timestamps

#### Blocks (Property Sections)
- **blocks**: Property sub-sections/areas
  - `id` (UUID) - Primary key
  - `property_id` (UUID) - Foreign key to properties
  - `name` - Block name (e.g., "Dock A")
  - `code` - Unique code within property
  - `location` - Block location description (nullable)
  - `is_active` - Active status flag
  - `created_at`, `updated_at` - Timestamps
  - **Constraint**: Unique combination of `property_id` + `code`

#### Zones (Block Subdivisions)
- **zones**: Block subdivisions
  - `id` (UUID) - Primary key
  - `block_id` (UUID) - Foreign key to blocks
  - `name` - Zone name (e.g., "Section 1")
  - `code` - Unique code within block
  - `description` - Zone description (nullable)
  - `is_active` - Active status flag
  - `created_at`, `updated_at` - Timestamps
  - **Constraint**: Unique combination of `block_id` + `code`

#### Slots (Individual Berths)
- **slots**: Individual boat berths/parking spaces
  - `id` (UUID) - Primary key
  - `zone_id` (UUID) - Foreign key to zones
  - `name` - Slot name/number (e.g., "A1-01")
  - `code` - Unique code within zone
  - `length` (decimal) - Slot length in meters (nullable)
  - `width` (decimal) - Slot width in meters (nullable)
  - `depth` (decimal) - Water depth in meters (nullable)
  - `amenities` (JSON) - Available amenities (nullable)
  - `base_rate` (decimal) - Base pricing rate (nullable)
  - `is_active` - Active status flag
  - `created_at`, `updated_at` - Timestamps
  - **Constraint**: Unique combination of `zone_id` + `code`

### Supporting Tables

#### Application Settings
- **settings**: Global application configuration
  - `setting_key` (string) - Primary key
  - `value` (JSON) - Setting value (flexible data type)
  - `created_at`, `updated_at` - Timestamps

#### Lookup Values  
- **app_types**: System lookup/dropdown values
  - `id` (UUID) - Primary key
  - `group` - Category/group identifier
  - `code` - Unique code within group
  - `label` - Display name/label
  - `extra` (JSON) - Additional metadata (nullable)
  - `is_active` - Active status flag
  - `created_at`, `updated_at` - Timestamps
  - **Constraint**: Unique combination of `group` + `code`

### Business Logic Tables

#### Vessels (Boat Information)
- **vessels**: Customer boat/yacht records
  - `id` (UUID) - Primary key
  - `user_id` (UUID) - Foreign key to users
  - `name` - Vessel name
  - `type` - Vessel type
  - `length`, `width`, `draft` - Vessel dimensions
  - `registration` - Registration number
  - `is_active` - Active status
  - `created_at`, `updated_at` - Timestamps

#### Bookings (Slot Reservations)
- **bookings**: Slot reservation records
  - `id` (UUID) - Primary key
  - `user_id`, `slot_id`, `vessel_id` - Foreign keys
  - `start_date`, `end_date` - Booking period
  - `status` - Booking status
  - `total_amount` - Total booking cost
  - `special_requests` (JSON) - Additional requirements
  - `created_at`, `updated_at` - Timestamps

#### Financial Records
- **contracts**: Long-term agreements
- **invoices**: Billing records
- **invoice_lines**: Invoice line items
- **payments**: Payment transactions

## Access Control

### Admin-Only Access
- All configuration features restricted to users with `admin` role
- Middleware: `auth`, `admin` (custom middleware)
- Routes protected under `/admin/configuration` prefix
- Component-level authorization using Laravel policies

### Route Structure
```php
// Admin routes group
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
    Route::get('/configuration', ConfigurationIndex::class)->name('configuration');
});
```

## Configuration Workflow

### 1. Property Management Hierarchy
```
Admin Dashboard → Configuration → 
├── Properties (Marina Locations)
│   ├── Create/Edit Property Details
│   ├── Manage Property Settings
│   └── View Property Statistics
├── Blocks (Property Sections)  
│   ├── Assign to Properties
│   ├── Configure Block Details
│   └── Manage Block Zones
├── Zones (Block Subdivisions)
│   ├── Assign to Blocks
│   ├── Configure Zone Details
│   └── Manage Zone Slots
└── Slots (Individual Berths)
    ├── Assign to Zones
    ├── Configure Slot Specifications
    └── Set Pricing and Amenities
```

### 2. Settings Management
```
Admin Dashboard → Configuration → Settings → 
├── Application Settings (global key-value pairs)
├── App Types (dropdown/lookup values)
└── System Configuration (operational parameters)
```

### 3. Data Flow Example
1. **Property Setup**
   - Admin creates property "Marina Bay Resort" (code: MBR)
   - Sets timezone to "America/New_York", currency to "USD"

2. **Block Creation**
   - Admin creates blocks within property: "Dock A", "Dock B"
   - Each block gets unique code within property: "DOCK-A", "DOCK-B"

3. **Zone Organization** 
   - Admin creates zones within blocks: "Section 1", "Section 2"
   - Zones get codes like "SEC-1", "SEC-2" within each block

4. **Slot Definition**
   - Admin creates individual slots within zones: "A1-01", "A1-02"
   - Sets slot specifications: 40ft length, power/water amenities

## Implementation Architecture

### Component Structure
```
app/Livewire/Admin/Configuration/
├── Index.php                    # Main tabbed interface with stats
├── Properties.php               # Property list and search
├── Blocks.php                   # Block management 
├── Zones.php                    # Zone management
├── Slots.php                    # Slot management
├── Settings.php                 # Application settings
├── AppTypes.php                 # Lookup value management
└── Forms/
    ├── PropertyForm.php         # Property create/edit modal
    ├── PropertyDelete.php       # Property deletion confirmation
    └── ... (similar for each entity)
```

### Key Laravel Features Used
- **UUID Primary Keys**: All entities use UUID for better distributed system support
- **JSON Casting**: Settings values and slot amenities use JSON storage
- **Model Relationships**: Proper Eloquent relationships with cascade delete
- **Route Model Binding**: Automatic model resolution in routes
- **Authorization**: Policy-based access control for all operations
- **Livewire Components**: Real-time UI without page refreshes

### Models and Relationships
```php
// Property model
class Property extends Model {
    protected $fillable = ['name', 'code', 'address', 'timezone', 'currency', 'is_active'];
    
    public function blocks() {
        return $this->hasMany(Block::class);
    }
}

// Cascading relationships
Property → hasMany(Block) → hasMany(Zone) → hasMany(Slot)
```

## UI/UX Features

### Search and Filtering
- **Debounced search**: 300ms delay on name/code search
- **Active/Inactive toggle**: Filter by entity status
- **Responsive design**: Desktop table + mobile cards
- **Real-time updates**: Live search without page refresh

### Modal Management
- **Full-screen modals**: Professional overlay with backdrop blur
- **Event-driven**: Decoupled form components using Livewire events
- **Keyboard navigation**: ESC key closes modals
- **Mobile-friendly**: Touch-optimized for all devices

### Statistics Dashboard
- **System overview**: Key metrics at configuration index level
- **Real-time counts**: Properties, active slots, configuration items
- **Last updated tracking**: Shows recent configuration changes
- **Visual indicators**: Color-coded status indicators

## Usage Examples

### Creating a Complete Marina Setup
```php
// 1. Create Property
$property = Property::create([
    'name' => 'Marina Bay Resort',
    'code' => 'MBR',
    'address' => '123 Marina Drive, Bay City',
    'timezone' => 'America/New_York',
    'currency' => 'USD',
    'is_active' => true
]);

// 2. Create Blocks
$dockA = $property->blocks()->create([
    'name' => 'Dock A - Premium',
    'code' => 'DOCK-A',
    'location' => 'North side of marina',
    'is_active' => true
]);

// 3. Create Zones
$section1 = $dockA->zones()->create([
    'name' => 'Section 1',
    'code' => 'SEC-1',
    'description' => 'Large vessel section',
    'is_active' => true
]);

// 4. Create Slots
$section1->slots()->create([
    'name' => 'Berth A1-01',
    'code' => 'A1-01',
    'length' => 45.0,
    'width' => 15.0,
    'depth' => 12.0,
    'amenities' => ['power_50amp', 'water', 'wifi', 'cable_tv'],
    'base_rate' => 125.00,
    'is_active' => true
]);
```

### Application Settings Management
```php
// Global application settings
Setting::create([
    'setting_key' => 'booking_advance_days',
    'value' => 365
]);

// Complex configuration
Setting::create([
    'setting_key' => 'notification_preferences',
    'value' => [
        'email_enabled' => true,
        'sms_enabled' => false,
        'channels' => ['booking_confirmed', 'payment_received', 'booking_reminder'],
        'admin_notifications' => ['new_booking', 'payment_failed']
    ]
]);

// Lookup values for dropdowns
AppType::create([
    'group' => 'vessel_types',
    'code' => 'sailboat',
    'label' => 'Sailboat',
    'extra' => ['icon' => 'sailboat', 'category' => 'recreational'],
    'is_active' => true
]);
```

## Performance Considerations

### Database Optimization
- **Eager Loading**: Relationships loaded efficiently with `with()`
- **Query Counts**: Using `withCount()` for statistics
- **Indexed Searches**: Database indexes on frequently searched columns
- **Pagination**: Efficient pagination for large datasets

### Caching Strategy
- **Model Caching**: Settings and app types cached for performance
- **Query Result Caching**: Expensive relationship queries cached
- **Session Storage**: Form state persistence across requests

## Security Features

### Authorization
- **Policy-based**: Laravel policies for all CRUD operations
- **Role checking**: Admin role required for all configuration access
- **Resource ownership**: Users can only access appropriate resources
- **CSRF Protection**: All forms protected against cross-site attacks

### Data Validation
- **Component validation**: Livewire component-level validation
- **Unique constraints**: Database-level unique constraints
- **Input sanitization**: Automatic XSS protection
- **Type safety**: Strong typing in all components

---

This configuration system provides a robust foundation for marina management with hierarchical organization, flexible settings, and professional user interface suitable for scaling to multiple properties and thousands of slots.