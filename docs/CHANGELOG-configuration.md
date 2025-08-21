# Configuration Module Changelog

## Added
- Introduced event-driven CRUD for Blocks with dedicated form and delete Livewire components.
- Updated blocks index view with search, active filter, responsive tables, and action buttons.
- Mounted new Block form/delete components in configuration index container.
- Added "Configuration" shortcut button on the admin dashboard header for admins.
- Added event-driven CRUD for Zones with dedicated form and delete Livewire components.
- Zones index view now supports search (name/code/location), active filter, per-page selection, responsive tables, and event-driven actions.
- Mounted Zone form/delete components in configuration index container.
- Zone deletion prevented when associated Slots exist.

## Notes
- Further entities (Zones, Slots, Settings, App Types) still require migration to the new pattern.
