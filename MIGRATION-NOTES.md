# Migration Notes - Database Consolidation

## Overview

Phase 1 of the Admin Configuration Audit involved consolidating fragmented migration files into logical, maintainable groups while enhancing the database schema with proper constraints and indexes.

## New Migration Structure

```
database/migrations/
├── 0001_01_01_000000_create_users_table.php          # Laravel base
├── 0001_01_01_000001_create_cache_table.php          # Laravel base  
├── 0001_01_01_000002_create_jobs_table.php           # Laravel base
├── 2025_08_20_101500_drop_and_recreate_conflicting_tables.php  # Transition helper
├── 2025_08_20_101516_create_permission_tables.php    # Spatie permissions
├── 2025_08_20_102000_create_settings_and_types.php   # Settings & App Types
├── 2025_08_20_103000_create_configuration_tables.php # Properties→Blocks→Zones→Slots
├── 2025_08_20_104000_create_business_tables.php      # Vessels, Bookings, Invoices, etc.
└── deprecated/                                       # Original migrations (git-ignored)
```

## Key Schema Enhancements

### 1. Scoped Uniqueness Constraints
- `blocks.code` unique per `property_id`
- `zones.code` unique per `block_id` 
- `slots.code` unique per `zone_id`
- `app_types.code` unique per `group`

### 2. Enhanced Indexing
```sql
-- Configuration tables
properties: [is_active], [code]
blocks: [property_id, is_active]
zones: [block_id, is_active] 
slots: [zone_id, is_active], [location]

-- Settings & App Types
settings: [group, is_active], [is_protected]
app_types: [group], [group, is_active, sort_order]

-- Business tables
bookings: [user_id, status], [slot_id, start_date, end_date]
contracts: [user_id, status], [slot_id, status]
invoices: [user_id, status], [status, due_date]
```

### 3. Foreign Key Policies
- **Configuration hierarchy**: `restrictOnDelete` to prevent accidental data loss
- **User-owned data**: `cascadeOnDelete` for cleanup
- **Business relationships**: `restrictOnDelete` for slots to protect bookings/contracts

### 4. Enhanced Settings Schema
```sql
settings:
  - key (primary)
  - group (nullable, indexed)
  - value (json)
  - label (nullable)
  - description (text, nullable)
  - is_protected (boolean, default: false, indexed)
  - is_active (boolean, default: true)
```

### 5. Enhanced App Types Schema
```sql
app_types:
  - id (uuid, primary)
  - group (indexed)
  - code (unique with group)
  - label
  - description (nullable)
  - sort_order (default: 0)
  - extra (json, nullable)
  - is_active (default: true)
  - is_protected (default: false)
```

## Running Migrations

### Fresh Installation
```bash
php artisan migrate:fresh --seed
```

### Existing Installation
If you have existing data and want to migrate to the consolidated schema:

1. **Backup your database first**
2. The consolidation includes a helper migration that drops existing tables
3. Run migrations: `php artisan migrate`
4. Re-seed data: `php artisan db:seed`

## Updated Seeders

### Settings Seeder
- Now includes group, label, description, and protection flags
- Sample protected settings for invoice prefixes and app name

### App Types Seeder  
- Includes sort_order for proper ordering
- Support for description and protection flags
- Covers all business domain types

### Demo Data Seeder
- Updated to work with new schema requirements
- Includes proper location data for slots
- Maintains referential integrity

## Validation Impact

The consolidation affects model validation rules:

### Property Model
```php
'code' => 'required|string|max:50|unique:properties,code'
```

### Block Model  
```php
'code' => 'required|string|max:50|unique:blocks,code,NULL,id,property_id,' . $this->property_id
```

### Zone Model
```php
'code' => 'required|string|max:50|unique:zones,code,NULL,id,block_id,' . $this->block_id  
```

### Slot Model
```php
'code' => 'required|string|max:50|unique:slots,code,NULL,id,zone_id,' . $this->zone_id
'location' => 'required|string|max:255'
```

## Breaking Changes

1. **Slots.location is now required** (was nullable)
2. **Settings table structure changed** - includes new metadata columns
3. **App Types table enhanced** - includes description, sort_order, is_protected
4. **Foreign key constraints tightened** - some deletes now restricted

## Performance Improvements

- **Reduced N+1 potential**: Better indexing on common filter fields
- **Faster searches**: Indexes on active flags, groups, and location
- **Optimized joins**: Foreign key indexes improve join performance

---
*Generated during Phase 1: Database & File Consolidation - August 2025*