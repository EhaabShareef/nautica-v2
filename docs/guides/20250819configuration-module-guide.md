# Configuration Module Guide

## Overview

The Configuration Module provides a centralized system for managing application-level settings and property configurations. Only administrators have access to these configuration features.

## Database Structure

### Core Tables

#### Properties Configuration
- **properties**: Main property records
  - `id` - Primary key
  - `name` - Property name
  - `code` - Unique property code
  - `address` - Property address (optional)

- **resources**: Property sub-components
  - `id` - Primary key
  - `property_id` - Foreign key to properties
  - `name` - Resource name
  - `capacity` - Resource capacity (optional)
  - `attributes` - JSON field for flexible configuration

#### Application Settings
- **settings**: Global application settings
  - `setting_key` - Primary key (string)
  - `value` - JSON field for any data type
  
- **app_types**: System lookup values
  - `id` - UUID primary key
  - `group` - Setting category
  - `code` - Unique code within group
  - `label` - Display name
  - `extra` - JSON field for additional data
  - `is_active` - Status flag

## Access Control

### Admin-Only Access
- All configuration features restricted to users with `admin` role
- Middleware: `EnsureUserIsAdmin`
- Routes protected under `/admin/` prefix

## Configuration Workflow

### 1. Property Management
```
Admin Dashboard → Properties → 
├── Create/Edit Property
├── Manage Resources
└── Configure Attributes
```

### 2. Settings Management
```
Admin Dashboard → Settings → 
├── Application Settings (global)
├── App Types (lookup values)
└── System Configuration
```

### 3. Data Flow
1. **Property Configuration**
   - Admin creates property with basic info
   - Adds resources (sub-components) to property
   - Configures resource attributes via JSON

2. **Settings Management**
   - Admin manages global settings via key-value pairs
   - Uses app_types for dropdown/lookup options
   - Settings stored as JSON for flexibility

## Implementation Notes

### Models
- `Setting`: Uses string primary key, JSON casting for values
- `AppType`: Uses UUID, supports grouping and extra attributes
- `Property`: Standard model with cascade delete to resources
- `Resource`: Belongs to property, uses JSON for flexible attributes

### Key Features
- **Flexible JSON Storage**: Both settings and resources use JSON fields
- **Hierarchical Structure**: Properties contain resources
- **Lookup System**: App types provide categorized options
- **Admin Security**: All access restricted to admin role

## Usage Examples

### Setting Configuration
```php
// Application-wide setting
Setting::create([
    'setting_key' => 'max_bookings_per_user',
    'value' => 5
]);

// Complex setting
Setting::create([
    'setting_key' => 'notification_config',
    'value' => [
        'email' => true,
        'sms' => false,
        'channels' => ['booking', 'payment']
    ]
]);
```

### Property Configuration
```php
// Property with resources
$property = Property::create([
    'name' => 'Marina Bay Resort',
    'code' => 'MBR',
    'address' => '123 Bay Street'
]);

$property->resources()->create([
    'name' => 'Dock A',
    'capacity' => 50,
    'attributes' => [
        'type' => 'premium',
        'amenities' => ['power', 'water', 'wifi'],
        'pricing_tier' => 1
    ]
]);
```