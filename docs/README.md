# Nautica v2 Documentation

**Nautica** — Shipping Vessel Parking Slot Rental Service  
**Mode:** API‑first. Frontend (Livewire/Alpine) consumes REST.  
**Architecture:** Minimal, configurable (no hard enums), admin + client roles.

## Documentation Overview

This documentation is structured for AI agents and developers to understand and implement the Nautica system. Read the files in this order:

### Phase 1: Foundation & Configuration
* `01_system-overview.md` - High-level system architecture and concepts
* `02_configuration-model.md` - Configurable types system (replaces enums)
* `03_roles-and-permissions.md` - User roles and access control

### Phase 2: Core Data Models  
* `04_property-hierarchy.md` - Physical structure (Property → Block → Zone → Slot)
* `05_entities-core.md` - Main entities (Organizations, Users, Vessels, Bookings)
* `06_entities-finance.md` - Financial entities (Contracts, Invoices, Payments)

### Phase 3: Business Logic & Operations
* `07_workflow-bookings.md` - Booking lifecycle and state management  
* `08_billing-and-invoicing.md` - Financial processes
* `09_schedule-and-reports.md` - Admin operational views

### Technical References
* `adr/0003-no-enums-config-driven.md` - Architecture decision record


---

