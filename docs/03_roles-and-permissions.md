# docs/roles-and-permissions.md

## Roles (v0.1)

* **admin**: full access to configuration, management, finance, reports.
* **client**: self‑service registration, vessel management, booking/service requests, invoice & payment views.

## Permissions (granular)

> Map these to Spatie permissions. Roles are collections of these.

### Configuration

* `config.manage` – create/update/delete properties, blocks, zones, slots.
* `config.services.manage` – manage service catalog (crane, lorry, etc.).
* `config.settings.manage` – manage types (status types, booking types, reasons, tax rates, etc.).

### Management

* `clients.manage` – create/update client organizations & users.
* `vessels.manage` – create/update vessels.
* `bookings.view`
* `bookings.create`
* `bookings.update`
* `bookings.approve` – approve/assign/confirm bookings.
* `services.request` – create service requests
* `services.fulfill` – mark service delivered, add charges

### Finance

* `invoices.issue`
* `invoices.view`
* `payments.record`
* `payments.view`

### Reports & Schedule

* `schedule.view`
* `reports.view`

### Default role bundles

* **admin**: all above
* **client**: `bookings.view`, `bookings.create`, `services.request`, `invoices.view`, `payments.view` + manage own `vessels` & profile.

---