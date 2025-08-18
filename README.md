# Nautica v2

**Shipping Vessel Parking Slot Rental Service**

[![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)

---

## 🚢 About Nautica

Nautica is a modern **API-first vessel parking slot rental service** designed for marinas, ports, and shipping facilities. It provides comprehensive management of vessel bookings, slot assignments, billing, and operational workflows.

### Key Features

- **🎯 API-First Architecture** - RESTful API with Livewire/Alpine frontend
- **⚙️ Configuration-Driven** - No hard enums, fully configurable types and settings
- **🏢 Multi-Tenant Ready** - Support multiple properties/marinas
- **👥 Role-Based Access** - Admin and client user roles with granular permissions
- **📊 Complete Workflow** - From booking request to check-out and billing
- **💰 Advanced Billing** - Flexible contracts, invoicing, and payment processing
- **📅 Operational Tools** - Schedule views, reports, and management dashboards

---

## 🏗️ Architecture

### Physical Hierarchy
```
Property → Block → Zone → Slot
```

### Core Workflow
```
Client Request → Admin Approval → Slot Assignment → Contract → Billing → Check-in/out
```

### Tech Stack
- **Backend**: Laravel 11 (PHP 8.2+)
- **Frontend**: Livewire + Alpine.js
- **Database**: PostgreSQL (JSON support)
- **Authentication**: Laravel Sanctum + Spatie Permissions
- **API**: RESTful JSON responses

---

## 📚 Documentation

Comprehensive documentation is available in the [`docs/`](docs/) directory:

1. **[System Overview](docs/01_system-overview.md)** - Architecture and concepts
2. **[Configuration Model](docs/02_configuration-model.md)** - Flexible type system
3. **[Roles & Permissions](docs/03_roles-and-permissions.md)** - Access control
4. **[Property Hierarchy](docs/04_property-hierarchy.md)** - Physical structure
5. **[Core Entities](docs/05_entities-core.md)** - Data models
6. **[Financial Entities](docs/06_entities-finance.md)** - Billing & payments
7. **[Booking Workflow](docs/07_workflow-bookings.md)** - Business processes
8. **[Billing & Invoicing](docs/08_billing-and-invoicing.md)** - Financial workflows
9. **[Schedule & Reports](docs/09_schedule-and-reports.md)** - Operational views

---

## 🚀 Quick Start

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & npm
- PostgreSQL

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/EhaabShareef/nautica-v2.git
   cd nautica-v2
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**
   ```bash
   # Configure database in .env file
   php artisan migrate
   php artisan db:seed
   ```

5. **Build assets**
   ```bash
   npm run build
   ```

6. **Start development server**
   ```bash
   php artisan serve
   ```

---

## 🎯 Core Concepts

### Organizations & Users
- **Organizations**: Vessel owners, operators, agents
- **Users**: Admin staff and client users
- **Multi-tenant**: Data isolation by organization

### Physical Structure
- **Properties**: Individual marinas/ports
- **Blocks**: Sections within properties (quays/piers)
- **Zones**: Sub-areas within blocks
- **Slots**: Individual parking spaces with constraints

### Booking Lifecycle
1. **Request** - Client submits booking request
2. **Approval** - Admin reviews and assigns slot
3. **Confirmation** - Contract created with pricing
4. **Check-in/out** - Operational tracking
5. **Billing** - Automated invoicing and payments

---

## 🛠️ Development

### Database Schema
The application uses a flexible, configuration-driven approach:
- **No hard enums** - All types stored in `app_types` table
- **JSON fields** - Flexible metadata and settings
- **UUID primary keys** - Better for distributed systems

### API Design
RESTful API following Laravel conventions:
- Resource controllers
- API versioning ready
- JSON responses
- Authentication via Sanctum

### Testing
```bash
# Run tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature
```

---

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 🆘 Support

- **Documentation**: [`docs/`](docs/) directory
- **Issues**: [GitHub Issues](https://github.com/EhaabShareef/nautica-v2/issues)
- **Discussions**: [GitHub Discussions](https://github.com/EhaabShareef/nautica-v2/discussions)

---

<p align="center">
  <strong>Built with ❤️ using Laravel</strong>
</p>