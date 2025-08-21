# Deprecated Files - Phase 1 Database Consolidation

This document tracks files that were moved to deprecated folders during the Phase 1 database consolidation effort.

## Migration Files Consolidated

The following migration files were consolidated into new, logically grouped migrations and moved to `database/migrations/deprecated/`:

### Settings & App Types (→ `2025_08_20_102000_create_settings_and_types.php`)
- `2025_08_20_101152_create_app_types_table.php`
- `2025_08_20_101153_create_settings_table.php` 
- `2025_08_21_090000_add_settings_metadata_columns.php`
- `2025_08_21_090001_enhance_app_types_table.php`

### Configuration Tables (→ `2025_08_20_103000_create_configuration_tables.php`)
- `2025_08_20_101154_create_properties_table.php`
- `2025_08_20_101155_create_blocks_table.php`
- `2025_08_20_101157_create_zones_table.php`
- `2025_08_20_101204_create_slots_table.php`

### Slot Modifications (→ consolidated into configuration tables)
- `2025_08_20_200001_add_location_to_slots_table.php`
- `2025_08_21_063818_add_foreign_key_to_slots_zone_id_column.php`
- `2025_08_21_065936_update_slot_foreign_keys_to_restrict_delete.php`
- `2025_08_21_070952_backfill_and_make_slots_location_not_null.php`

### Business Tables (→ `2025_08_20_104000_create_business_tables.php`)
- `2025_08_20_101205_create_vessels_table.php`
- `2025_08_20_101207_create_bookings_table.php`
- `2025_08_20_101208_create_booking_logs_table.php`
- `2025_08_20_101209_create_contracts_table.php`
- `2025_08_20_101215_create_invoices_table.php`
- `2025_08_20_101217_create_invoice_lines_table.php`
- `2025_08_20_101218_create_payments_table.php`
- `2025_08_20_101219_create_activities_table.php`

## Improvements Made in Consolidation

1. **Scoped Uniqueness**: Added proper scoped uniqueness constraints (Block.code per Property, Zone.code per Block, Slot.code per Zone)
2. **Enhanced Indexing**: Added indexes for common filters (active flags, groups, foreign keys)
3. **Foreign Key Consistency**: Set restrictOnDelete for configuration hierarchy to prevent accidental data loss
4. **Schema Completeness**: Slots table includes location field from the start (non-nullable)
5. **Settings Enhancement**: Includes all metadata fields (group, label, description, is_protected, is_active)
6. **App Types Enhancement**: Includes description, sort_order, and is_protected fields

## Testing Status

✅ **Migration Status**: All consolidated migrations run successfully  
✅ **Seeder Status**: All seeders updated and working with new schema  
✅ **Fresh Install**: `php artisan migrate:fresh --seed` completes successfully

## Next Steps

The consolidated migrations provide a clean foundation for:
- Phase 2: Component Pattern Standardization
- Phase 3: Settings & Roles Enhancement  
- Phase 4: UI/UX Standardization
- Phase 5: Polish & Documentation

---
*Generated during Phase 1: Database & File Consolidation - August 2025*