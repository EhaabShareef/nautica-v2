Here’s a crisp, no‑code instruction set your AI agent can follow to implement the **Vessel Management** module. It assumes the existing codebase (Laravel + Livewire, Spatie permissions, current DB) is the source of truth—have the agent read models, migrations, seeders, policies, and UI conventions before implementing.

---

# Vessel Management — Agent Instructions (No Code)


1. Review **roles/permissions** (Spatie) to align capabilities for `admin`, `agent`, and `client`.
2. Note any existing **lookup/catalog** tables (e.g., `app_types`) relevant for vessel types/flags.

---

## 1) Domain & Data Model

1. Create a **Vessel** entity with at least:

   * Identity: `id`, human‑readable `name`.
   * Ownership & tenancy: `owner_client_id` (required), `renter_client_id` (nullable).
   * Vessel descriptors (refer to existing conventions): registration no., type/class (via `app_types` if available), size metrics, flags, status.
   * Operational fields: `is_active`, `created_by`, `updated_by`, timestamps, soft deletes (if used elsewhere).
2. Relationships:

   * `Vessel belongsTo Client (owner)` — required.
   * `Vessel belongsTo Client (renter)` — optional.
   * Leave space to extend to multi‑owner later if needed (document the extension path; don’t implement now).
3. Constraints & Indexes:

   * Enforce **unique** registration number (or the identifier used in your domain).
   * Foreign keys to clients with `cascade` or `restrict` matching existing DB conventions.
   * Index common filters: owner, renter, status, type, registration.

---

## 2) Business Rules (Hard Requirements)

1. **Owner required, renter optional** on create. Agents must be able to register vessels **without a renter**.
2. **Client roles** are global: a single client can be an owner for some vessels and a renter for others.
3. **User eligibility**:

   * Owner and (if provided) renter must be **active** and **not blacklisted** at the time of assignment.
   * If a client is **inactive/blacklisted**, they cannot:

     * Be selected as owner/renter during vessel registration.
     * Register new vessels (for self‑service).
4. **Deactivation effects (future context hooks)**:

   * If a client is inactivated while attached to vessels:

     * They (the client) can no longer **make bookings**. Implement a central “can book?” check (policy/service) that denies when user is inactive/blacklisted. (Do not block vessel existence.)
   * A client **cannot be inactivated** if they have **pending payments** (future context). Add a clear TODO hook:

     * Call a Payments service/stub (`hasPendingPayments(client_id)`) and **prevent inactivation** if true. Surface a meaningful message.
5. Optional but recommended:

   * Prevent setting renter = owner for the **same vessel** (documented rule; toggle via config if your domain wants to allow it).

---

## 3) Permissions & Policies

1. Define/verify permission abilities (use your project’s naming scheme):

   * `vessels.view`, `vessels.create`, `vessels.update`, `vessels.delete`, `vessels.assignRenter`.
2. Role mapping:

   * **Admin**: full CRUD + override.
   * **Agent**: create/edit vessels; can register without renter; cannot create new client accounts (must select existing clients).
   * **Client**: manage **their own** vessels (owner perspective); optionally allow self‑registration if that exists in your product, subject to active/blacklist checks.
3. Policy checks must include:

   * Ownership (only owner sees/edits their vessel unless admin/agent).
   * Active/blacklist gating where relevant.

---

## 4) Validation & Service Layer

1. Centralize checks in a `VesselService` (or equivalent) used by UI/API:

   * Validate owner/renter existence and eligibility (active, not blacklisted).
   * Enforce uniqueness on registration identifier.
   * Business rule for renter ≠ owner (if adopted).
2. Add a `BookingEligibility` (or similar) service with a **single entry point** to decide if a user can book:

   * Deny if user inactive/blacklisted (future context: also deny if arrears/pending payments).
   * This keeps booking logic consistent across modules later.
3. Surface precise, user‑friendly error messages for disallowed actions (e.g., “Selected renter is inactive/blacklisted”).

---

## 5) Livewire UI (Follow existing patterns)

1. Components (names indicative—match your repo naming):

   * `manage-vessels` (index/list)
   * `vessel-form` (create/edit modal or page)
   * `vessel-view` (details page)
2. Index/List (`manage-vessels`):

   * Filters: owner, renter, status (active/inactive), type, search by name/registration.
   * Role‑aware actions (edit/delete/assign renter) and row‑level visibility per policy.
3. Create/Edit (`vessel-form`):

   * Step 1: **Select Owner** (search existing clients only; disallow inactive/blacklisted).
   * Step 2: Vessel details (type via `app_types` if available; registration; dimensions; flags).
   * Step 3 (optional): **Select Renter** (search existing clients; nullable; disallow inactive/blacklisted).
   * Persist via `VesselService` to keep logic centralized.
4. Details (`vessel-view`):

   * Show vessel metadata, owner, renter, status, created/updated audit.
   * Provide role‑aware actions: assign/change renter, deactivate vessel, etc.
5. Reuse the project’s **`ui/*` components** (inputs, select/search, modal, button). Maintain dark/light styling and interactions consistent with your app.

---

## 6) UX Safeguards

1. When picking owner/renter, **disable** or **hide** inactive/blacklisted clients; show tooltip/reason if hidden.
2. If attempting to assign an ineligible client, block with clear guidance.
3. On delete/deactivate vessel, show confirmation and summarize consequences (e.g., bookings impact handled by booking module later).

---

## 7) Hooks & Events

1. Emit domain events:

   * `VesselCreated`, `VesselUpdated`, `VesselRenterAssigned`, `VesselOwnerChanged`.
2. Log audit data (creator/updater IDs). Follow your project’s logging/audit trail format.
3. Add TODO integration points:

   * Payments: `hasPendingPayments(client_id)` in user deactivation flow.
   * Bookings: `canUserBook(user_id, vessel_id)` enforcement.

---

## 8) Seeders & Lookups

1. If you use `app_types`, add seed data for **vessel types/classes** (minimal set; keep consistent with existing groups/codes).
2. Provide a few demo vessels linked to existing demo clients for QA.

---

## 9) Testing (Happy & Edge Paths)

1. Unit tests for `VesselService`:

   * Create with valid owner; create with inactive/blacklisted owner ⇒ fail.
   * Assign renter valid/ineligible; renter same as owner (if disallowed) ⇒ fail.
2. Policy tests:

   * Client sees/edits only their vessels.
   * Agent/Admin permissions enforced.
3. Feature tests (Livewire):

   * Create vessel without renter (agent) ⇒ success.
   * Filter lists by owner/renter/status/type; pagination works.
4. Deactivation scenarios:

   * Inactivate client with vessels ⇒ booking guard denies (simulate call).
   * Attempt inactivation with pending payments ⇒ blocked (stubbed).

---

## 10) Documentation & Admin Notes

1. Update module docs: purpose, roles, rules, and screenshots of flows.
2. Document the **future‑context hooks** so the Payments and Bookings teams know where to integrate.
3. Add a short runbook for admins/agents:

   * How to register a vessel (with/without renter).
   * Why certain clients can’t be chosen (inactive/blacklisted).

---

### Acceptance Checklist

* [ ] Vessel table + relations created; indexes and constraints applied.
* [ ] Owner required; renter optional; validations enforced.
* [ ] Active/blacklist gating implemented in both form logic and service checks.
* [ ] Livewire screens: list, form, view; permissions respected.
* [ ] Booking eligibility hook in place (deny inactive/blacklisted users).
* [ ] Deactivation flow blocks when pending payments (stubbed) and documents behavior.
* [ ] Tests green; seed data available; docs updated.


