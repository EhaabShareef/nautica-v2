# Core Entities

> This document describes the main data entities in Nautica. For financial entities, see `06_entities-finance.md`.

## Parties & Users

### `organizations` (clients/owners/renters)

| column           | type           | notes                                         |
| ---------------- | -------------- | --------------------------------------------- |
| id               | uuid           |                                               |
| name             | string         |                                               |
| type\_id         | fk(app\_types) | group=`org_type` (`owner`,`operator`,`agent`) |
| billing\_email   | string         |                                               |
| billing\_phone   | string         |                                               |
| billing\_address | string         |                                               |
| is\_active       | boolean        |                                               |

### `users`

\| id (uuid), organization\_id (nullable for admins), name, email, password, phone, is\_active (bool)

### `vessels`

| column           | type            | notes          |
| ---------------- | --------------- | -------------- |
| id               | uuid            |                |
| organization\_id | fk              | owner/operator |
| name             | string          |                |
| imo              | string nullable |                |
| mmsi             | string nullable |                |
| loa\_m           | decimal(6,2)    | length overall |
| beam\_m          | decimal(5,2)    |                |
| draft\_m         | decimal(4,2)    |                |
| notes            | text            |                |

## Booking Core

### `bookings`

| column            | type              | notes                                                                                              |
| ----------------- | ----------------- | -------------------------------------------------------------------------------------------------- |
| id                | uuid              |                                                                                                    |
| property\_id      | fk                | for multi‑site ops                                                                                 |
| slot\_id          | fk nullable       | can be null until assignment                                                                       |
| vessel\_id        | fk                |                                                                                                    |
| organization\_id  | fk                | renter/owner                                                                                       |
| booking\_type\_id | fk(app\_types)    | group=`booking_type` (`hourly`,`daily`,`monthly`,`yearly`)                                         |
| status\_id        | fk(app\_types)    | group=`booking_status` (`requested`,`approved`,`confirmed`,`cancelled`,`checked_in`,`checked_out`) |
| eta               | datetime          |                                                                                                    |
| etd               | datetime          |                                                                                                    |
| hold\_expires\_at | datetime nullable | if reserved pending approval/payment                                                               |
| notes             | text              |                                                                                                    |
| price\_snapshot   | json              | computed at confirm                                                                                |

### `booking_events`

Audit trail of state changes.
\| id | booking\_id | actor\_id | from\_status\_id | to\_status\_id | payload(json) | created\_at |

## Services

### `service_requests`

| column           | type              | notes                                                                    |
| ---------------- | ----------------- | ------------------------------------------------------------------------ |
| id               | uuid              |                                                                          |
| booking\_id      | fk nullable       | optional — can be standalone                                             |
| organization\_id | fk                | required if standalone                                                   |
| vessel\_id       | fk                | required if standalone                                                   |
| service\_id      | fk                | catalog item                                                             |
| quantity         | decimal(10,2)     | per unit\_type                                                           |
| unit\_price      | decimal(12,2)     | snapshot                                                                 |
| tax\_rate\_id    | fk(app\_types)    |                                                                          |
| status\_id       | fk(app\_types)    | group=`service_status` (`requested`,`scheduled`,`completed`,`cancelled`) |
| scheduled\_at    | datetime nullable |                                                                          |
| completed\_at    | datetime nullable |                                                                          |
| notes            | text              |                                                                          |

## Cross-References

For financial entities (contracts, invoices, payments), see `06_entities-finance.md`.

---
