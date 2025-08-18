
# Configuration Model

> **Key Principle:** Replace enums with **configurable types** managed in Admin → Settings. All "type" and "status" values reference these tables.

## Why Configuration-Driven?

Traditional applications hardcode status values and types as enums. Nautica uses a flexible approach where business users can modify these values without code changes.

**Benefits:**
- Port-specific terminology (e.g., "berth" vs "slot")
- Custom booking types per marina
- Region-specific payment methods
- Evolving business processes

## Tables

### `app_types`

| column     | type    | notes                                                                                                                |
| ---------- | ------- | -------------------------------------------------------------------------------------------------------------------- |
| id         | uuid    |                                                                                                                      |
| group      | string  | logical group, e.g., `booking_status`, `booking_type`, `service_unit`, `payment_method`, `tax_rate`, `cancel_reason` |
| code       | string  | unique within group                                                                                                  |
| label      | string  | human readable                                                                                                       |
| extra      | json    | optional metadata (e.g., color, order, default=true)                                                                 |
| is\_active | boolean |                                                                                                                      |

### `settings`

| column | type        | notes                                                 |
| ------ | ----------- | ----------------------------------------------------- |
| key    | string (pk) | e.g., `booking.hold_minutes`, `invoice.number_prefix` |
| value  | json        | arbitrary value                                       |

### `services` (catalog)

| column         | type           | notes                                       |
| -------------- | -------------- | ------------------------------------------- |
| id             | uuid           |                                             |
| name           | string         | e.g., Crane, Lorry                          |
| code           | string         | unique                                      |
| unit\_type\_id | fk(app\_types) | group=`service_unit` (e.g., hour, job, ton) |
| base\_price    | decimal(12,2)  | optional                                    |
| tax\_rate\_id  | fk(app\_types) | group=`tax_rate`                            |
| is\_active     | boolean        |                                             |

> Seed initial `app_types` groups with minimal sensible defaults; admins can add/edit.