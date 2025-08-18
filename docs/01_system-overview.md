# System Overview

## What is Nautica?

Nautica is a **Shipping Vessel Parking Slot Rental Service** that manages marina and port operations. Think of it as an advanced booking system for vessel parking spaces (berths/slots).

## Core Concepts

### Physical Structure
- **Property** → **Block** → **Zone** → **Slot** (hierarchical organization)
- Each slot has physical constraints (max length, beam, draft)
- Shore power availability per slot

### Key Entities
- **Organizations**: Vessel owners, operators, agents
- **Users**: Admin staff and client users linked to organizations
- **Vessels**: Ships with physical dimensions and ownership
- **Bookings**: Reservations of slots for specific time periods
- **Contracts**: Pricing plans that govern booking costs

### Business Flow
1. **Client registers** organization and vessels
2. **Client requests booking** for specific dates/vessel
3. **Admin approves** and assigns specific slot
4. **System creates contract** with pricing
5. **Billing occurs** based on contract terms
6. **Check-in/check-out** tracked for overstay calculations

## Architecture Principles

### API-First Design
- RESTful API provides all functionality
- Frontend (Livewire/Alpine) consumes API
- Mobile-friendly responsive design

### Configuration-Driven
- **No hard-coded enums** - all types configurable via admin
- Status types, booking types, payment methods all stored in `app_types`
- Settings stored in flexible key-value `settings` table

### Multi-Tenant Ready
- Properties can represent different marinas/ports
- Organizations isolate client data
- Timezone and currency per property

## User Roles

### Admin Users
- Full system access
- Manage properties, slots, bookings
- Configure types and settings
- Handle billing and payments
- View reports and schedules

### Client Users
- Self-service registration
- Manage own vessels
- Request bookings and services
- View invoices and payments
- Limited to own organization data

## Technical Stack

- **Backend**: Laravel (PHP)
- **Frontend**: Livewire + Alpine.js
- **Database**: PostgreSQL (supports JSON columns)
- **Authentication**: Laravel built-in + Spatie permissions
- **API**: RESTful with JSON responses

## Next Steps

After understanding this overview, proceed to:
1. `02_configuration-model.md` - Learn about the flexible type system
2. `03_roles-and-permissions.md` - Understand access control
3. Data model files to understand entities and relationships