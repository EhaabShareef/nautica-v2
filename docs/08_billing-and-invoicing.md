# Billing and Invoicing

> This document describes the financial processes in Nautica. For entity schemas, see `06_entities-finance.md`.

## Contract plans

* **Hourly/Daily/Monthly/Yearly** via `contracts.plan_type_id` (from `app_types`).
* Each contract stores **base rate** and **terms json**. Invoices derive from contracts + actual usage.

## Invoicing

* Manual or **scheduled** per plan:

  * Hourly/Daily: on checkout or end of day.
  * Monthly: on cycle day (config: `billing.cycle_day`).
  * Yearly: on anniversary.
* **Proforma** (draft) → **issued** → **paid**. Notifications on pending/overdue and on payment received.

## Payments

* Accept manual (cash/bank) and gateway; store receipts (file URL in `payments.receipt_meta`).
* Reconcile to invoices; allow **partial payments**.

## Charges

* Booking base fee (by type & duration).
* Overstay surcharge.
* Services (from `service_requests`).
* Taxes (from `app_types: tax_rate`).