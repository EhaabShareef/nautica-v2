# Nautica v2 Project

## Overview
Laravel-based application with Livewire components for configuration management.

## Key Features
- **Unified Settings & App Type Configuration Module**: Complete admin interface for managing application settings and type configurations with forms, validation, and caching
- **Comprehensive Configuration Management**: Hierarchical Property→Block→Zone→Slot system with scoped uniqueness and optimized queries
- User authentication and authorization with Spatie Laravel Permission
- Admin dashboard and configuration panels with consistent UI/UX patterns

## Architecture

### Models
- `Setting`: Application configuration settings with JSON casting, group-based organization, and targeted caching via SettingsService
- `AppType`: Configurable application types with group+code composite uniqueness and cache management
- `Property`, `Block`, `Zone`, `Slot`: Hierarchical configuration system with scoped uniqueness constraints
- Standard Laravel authentication models with Spatie Permission integration

### Services
- `SettingsService`: Centralized settings management with targeted cache invalidation (individual keys and groups)

### Database Schema (Consolidated - Phase 1)
- **Consolidated Migrations**: Reduced from 16+ fragmented files to 3 logical groups:
  - `2025_08_20_102000_create_settings_and_types.php`: Settings + AppTypes with metadata fields
  - `2025_08_20_103000_create_configuration_tables.php`: Property→Block→Zone→Slot hierarchy
  - `2025_08_20_104000_create_permissions_and_roles.php`: Spatie permission tables
- **Scoped Uniqueness**: Implemented hierarchical constraints (Block.code unique per Property, etc.)
- **Enhanced Indexing**: Performance optimization for common query patterns

### Livewire Components (Admin Configuration - Phases 2 & 3)

#### Standardized Component Patterns (Phase 2)
- **Three-Component Architecture**: Display, Form, Delete components for each entity
- **Model-Based Validation**: All models implement `getValidationRules()` with scoped uniqueness
- **Livewire v3 Events**: Migrated from `dispatchBrowserEvent` to `dispatch` API
- **Query Optimization**: Eager loading, selective field loading, debounced search (300ms)
- **Consistent Pagination**: `perPage` options [5, 10, 25, 50, 100] with reset on filter changes

#### Enhanced Components (Phase 3)
- `SettingForm`: Create/edit settings with proper validation and cache clearing
- `SettingDelete`: Secure deletion with TOCTOU protection via fresh model fetching
- `AppTypeForm`: Manage app types with composite uniqueness validation (group+code)
- `RoleEditor`: Role and permission management with Spatie cache clearing (verified)

### Key Implementation Details
- **Caching Strategy**: Targeted cache invalidation for settings (individual keys and groups), AppType group caching
- **Security**: TOCTOU protection via model re-fetching before authorization
- **Data Integrity**: Composite uniqueness constraints, JSON handling with consistent array returns
- **UI/UX Consistency**: Standardized action bars, table styling, form patterns across all modules
- **Performance**: Optimized queries with eager loading and selective field loading

### Phase Completion Status
- **Phase 1 (Database & File Consolidation)**: ✅ Complete
  - Consolidated 16+ migrations into 3 logical groups
  - Enhanced schema with scoped uniqueness and proper indexing
  - Updated seeders and moved deprecated files to git-ignored folders
  - Successfully tested with `migrate:fresh --seed`

- **Phase 2 (Component Pattern Standardization)**: ✅ Complete  
  - Added `getValidationRules()` methods to all models
  - Standardized form validation using `rules()` method
  - Migrated all `dispatchBrowserEvent` to `dispatch` (8 instances fixed)
  - Applied consistent pagination and search patterns
  - Optimized queries across Properties, Blocks, Zones, Slots components

- **Phase 3 (Settings & Roles Enhancement)**: ✅ Complete
  - Enhanced Settings/AppTypes cache integration and validation
  - Standardized Settings/AppTypes components with Phase 2 patterns
  - Applied UI/UX consistency to Settings list view
  - Verified Role Editor has proper Spatie permission cache clearing
  - Added AppType model validation and cache management methods

- **Phase 4 (UI/UX Consistency Pass)**: ✅ Complete
  - Standardized action bars across all configuration modules with consistent spacing and styling
  - Enhanced Settings and AppTypes list views with modern action bars and mobile card layouts
  - Ensured consistent table styling and pagination placement across all modules
  - Fixed dark/light theme parity issues by replacing hardcoded colors with CSS variables
  - Standardized button heights (h-10) and transitions across all components
  - Improved per-page selector functionality and mobile responsiveness

### Branch Structure
- `master`: Main development branch (all phases merged ✅)
- `feat/config-audit-phase1-database`: Phase 1 database consolidation (merged)
- `feat/config-audit-phase2-components`: Phase 2 component standardization (merged)
- `feat/config-audit-phase3-settings-roles`: Phase 3 settings enhancement (merged)
- `feat/config-audit-phase4-ui-consistency`: Phase 4 UI/UX consistency (merged)

## Development
- Platform: Windows (win32)
- Framework: Laravel with Livewire v3
- Frontend: Blade templates with Alpine.js
- Caching: Laravel Cache with targeted invalidation patterns
- Permissions: Spatie Laravel Permission package
- Testing: PHPUnit with feature tests for configuration modules

## Recent Audit Implementation (Phases 1-4) - COMPLETE ✅
Based on `docs/audit/audit_01(configurations).md`, systematically implemented:
1. **Database schema consolidation and optimization** - Consolidated 16+ migrations into 3 logical groups
2. **Component pattern standardization** - Unified validation, events, and query patterns across all modules
3. **Settings and roles enhancement** - Added cache management and model validation with Phase 2 patterns
4. **UI/UX consistency improvements** - Standardized action bars, mobile layouts, and theme support

**Result**: A comprehensive, production-ready admin configuration system with consistent patterns, optimal performance, and professional UI/UX across all modules.