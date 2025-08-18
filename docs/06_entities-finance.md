# Financial Entities

> This document describes the financial data entities in Nautica. For core entities, see `05_entities-core.md`.

## Contracts & Pricing

### `contracts`

| column         | type           | notes                              |
| -------------- | -------------- | ---------------------------------- |
| id             | uuid           |                                    |
| booking\_id    | fk             | one active plan per booking        |
| plan\_type\_id | fk(app\_types) | same group as booking\_type        |
| start\_date    | date           |                                    |
| end\_date      | date nullable  | for open ended monthly             |
| rate           | decimal(12,2)  | base rate per plan                 |
| terms          | json           | e.g., included hours, overstay fee |

**Notes:**
- Contracts anchor billing logic  
- Invoices are scheduled based on contract type (hour/day/month/year)  
- Admins can override `rate` at contract level without affecting global defaults

## Invoicing & Payments

### `invoices`

| column           | type           | notes                                                   |
| ---------------- | -------------- | ------------------------------------------------------- |
| id               | uuid           |                                                         |
| number           | string unique  | `INV-YYYY-#####`                                        |
| organization\_id | fk             | bill‑to                                                 |
| booking\_id      | fk nullable    |                                                         |
| currency         | string         |                                                         |
| status\_id       | fk(app\_types) | group=`invoice_status` (`draft`,`issued`,`paid`,`void`) |
| issued\_at       | datetime       |                                                         |
| due\_at          | datetime       |                                                         |
| subtotal         | decimal(12,2)  |                                                         |
| tax              | decimal(12,2)  |                                                         |
| total            | decimal(12,2)  |                                                         |

### `invoice_lines`

| column      | type           | notes |
| ----------- | -------------- | ----- |
| id          | uuid           |       |
| invoice\_id | fk             |       |
| item\_type  | string         | `booking`, `service`, `overstay` |
| ref\_id     | uuid nullable  | reference to booking/service |
| description | string         | human-readable line item |
| quantity    | decimal(10,2)  |       |
| unit\_price | decimal(12,2)  |       |
| tax\_rate\_id | fk(app\_types) | group=`tax_rate` |
| line\_total | decimal(12,2)  |       |

### `payments`

| column        | type           | notes                                                              |
| ------------- | -------------- | ------------------------------------------------------------------ |
| id            | uuid           |                                                                    |
| invoice\_id   | fk             |                                                                    |
| method\_id    | fk(app\_types) | group=`payment_method` (`cash`,`bank`,`gateway`)                   |
| amount        | decimal(12,2)  |                                                                    |
| currency      | string         |                                                                    |
| received\_at  | datetime       |                                                                    |
| reference     | string         | txn ref/receipt no                                                 |
| status\_id    | fk(app\_types) | group=`payment_status` (`pending`,`succeeded`,`failed`,`refunded`) |
| receipt\_meta | json           | uploaded receipt info                                              |

## Business Rules

### Contract Plans
* **Hourly/Daily/Monthly/Yearly** via `contracts.plan_type_id` (from `app_types`)
* Each contract stores **base rate** and **terms json**
* Invoices derive from contracts + actual usage

### Invoicing Schedule
* Manual or **scheduled** per plan:
  * Hourly/Daily: on checkout or end of day
  * Monthly: on cycle day (config: `billing.cycle_day`)
  * Yearly: on anniversary
* **Proforma** (draft) → **issued** → **paid**
* Notifications on pending/overdue and on payment received

### Payment Processing
* Accept manual (cash/bank) and gateway payments
* Store receipts (file URL in `payments.receipt_meta`)
* Reconcile to invoices; allow **partial payments**

### Charge Types
* Booking base fee (by type & duration)
* Overstay surcharge (calculated at check-out)
* Services (from `service_requests`)
* Taxes (from `app_types: tax_rate`)