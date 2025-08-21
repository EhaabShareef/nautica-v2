# Nautica v2 Project

## Overview
Laravel-based application with Livewire components for configuration management.

## Key Features
- **Unified Settings & App Type Configuration Module**: Complete admin interface for managing application settings and type configurations with forms, validation, and caching
- User authentication and authorization
- Admin dashboard and configuration panels

## Architecture

### Models
- `Setting`: Application configuration settings with JSON casting, group-based organization, and caching
- `AppType`: Configurable application types with group+code composite uniqueness
- Standard Laravel authentication models

### Services
- `SettingsService`: Centralized settings management with targeted cache invalidation

### Livewire Components (Admin Configuration)
- `SettingForm`: Create/edit settings with proper validation and cache clearing
- `SettingDelete`: Secure deletion with race condition protection via fresh model fetching
- `AppTypeForm`: Manage app types with composite uniqueness validation (group+code)
- `RoleEditor`: Role and permission management interface with clean, pagination-free implementation

### Key Implementation Details
- **Caching Strategy**: Targeted cache invalidation for settings (individual keys and groups)
- **Security**: TOCTOU protection via model re-fetching before authorization
- **Data Integrity**: Composite uniqueness constraints, JSON handling with consistent array returns
- **UI/UX**: Non-editable primary keys during edits, proper modal management

### Recent Improvements
- Migrated from Livewire v2 to v3 dispatch API
- Enhanced cache management avoiding unsafe `Cache::flush()`
- Fixed double-encoding issues in JSON handling
- Improved form validation with composite constraints
- Added race condition protection for delete operations
- Cleaned up role management component by removing unused pagination code

## Development
- Platform: Windows (win32)
- Framework: Laravel with Livewire
- Frontend: Blade templates with Alpine.js
- Caching: Laravel Cache with targeted invalidation patterns