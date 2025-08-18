# docs/adr/0003-no-enums-config-driven.md

**Context**: Business terms (statuses, types, payment methods, tax rates) change over time per port.

**Decision**: Replace code enums with **data‑driven types** using `app_types` grouped by purpose. All references via foreign keys; UI manages entries.

**Consequences**:

* Pros: flexible, admin‑editable, multi‑tenant friendly.
* Cons: must validate referential integrity; migrations simpler but seed defaults needed.

**Implementation notes**:

* Add indexes on `(group, code)` unique.
* Provide seeders for default groups & values.
* Cache lookups by group.