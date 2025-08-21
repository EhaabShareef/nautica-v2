# Configuration Module Changelog

## Added
- Introduced event-driven CRUD for Blocks with dedicated form and delete Livewire components.
- Updated blocks index view with search, active filter, responsive tables, and action buttons.
- Mounted new Block form/delete components in configuration index container.
- Added "Configuration" shortcut button on the admin dashboard header for admins.
- Added event-driven CRUD for Slots with dedicated form and delete Livewire components.
- Slots index includes search (code/location), active filter, pagination, and cascading scope filters (Property → Block → Zone).
- Added deletion guard preventing removal of slots with linked bookings or contracts.

## Notes
- Further entities (Zones, Slots, Settings, App Types) still require migration to the new pattern.
