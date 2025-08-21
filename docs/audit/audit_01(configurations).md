# Agent Work Order — Apply Fixes & Standardize Admin Configuration Modules

> **Goal:** Make a comprehensive, code-level pass over all Admin Configuration modules and **apply** improvements (not just report). Open a single PR with grouped commits. **Skip tests**.

## 0) Branch & Preflight

1. Create a feature branch: `feat/config-audit-fixes`.
2. Read `docs/guides/*` to align with the project’s event-driven Livewire pattern and UI conventions.
3. Ensure `composer install` works (use the prior fix playbook if needed).

---

## 1) Architecture Standardization (apply refactors)

**Target:** Every configuration entity in scope (Properties, Blocks, Zones, Slots, Settings, AppTypes, Roles→Permissions).

* **Split responsibilities (enforce pattern):**

  * Display component = list/search/filter/paginate **only**; dispatch events.
  * Form component = owns modal state, validation, save/update; listens to `entity:create|edit`.
  * Delete component = owns confirmation, authorization, delete; listens to `entity:delete`.
* **Event names (make consistent):**

  * `entity:create`, `entity:edit`, `entity:delete`, `entity:saved`, `entity:deleted`.
* **Mount modals once:** In each index/container, ensure form/delete components are mounted once at the bottom. Remove duplicate modal instances.
* **Routes/RBAC:** All config routes under `/admin/configuration` with admin middleware. Verify each Livewire route/component is protected.

**Deliverable:** Replace/adjust components and views across all entities to match the above—commit as “refactor(pattern): standardize component split + events”.

---

## 2) Data & Validation (DB-level + form rules)

* **Scoped uniqueness (apply at DB + validation):**

  * Codes unique **per parent scope** (e.g., Block.code per Property, Zone.code per Block, Slot.code per Zone).
* **FKs & deletes:** Ensure FK constraints exist and match product rules (restrict or cascade). If UI blocks deletes with children, keep DB as `restrict`.
* **Casts:** JSON fields (e.g., Setting.value, AppType.extra) are properly cast.
* **Nullability/defaults:** Align forms with schema; avoid saving empty strings where NULL intended.
* **Indexes:** Add/confirm indexes for common filters (active flags, group, code, foreign keys).

**Deliverable:** New/updated migrations + model casts/rules—commit as “feat(db): enforce scoped uniqueness + fks + indexes”.

---

## 3) Query & Performance (eliminate N+1)

* Add `with(...)`/`withCount(...)` to listing queries (e.g., `zone.block.property`, counts only where shown).
* Debounced search; reset pagination on search/filter/perPage changes.
* Paginate everything that can grow.

**Deliverable:** Query updates in all display components—commit as “perf(query): eager load + paginate + debounce”.

---

## 4) Unified Settings Page (Settings + AppTypes)

* **Single page** with two tabs:

  * **Application Settings** (key/value JSON; CRUD with modal; optional protected keys).
  * **Configurable Types (AppTypes)** (group, code, label, sort, active/protected; `(group, code)` unique).
* **Caching (if present or simple to add):**

  * Cache Settings and AppTypes (per group); **bust cache on create/update/delete**.

**Deliverable:** Implement/align components + basic cache busting—commit as “feat(settings): unify app settings + types with cache bust”.

---

## 5) Roles → Permissions Manager (dynamic)

* Keep **roles read-only**, edit **permission assignments** only.
* Load all permissions dynamically (grouped by guard/group), support per-role assignment/unassignment with bulk in group.
* **Apply** writes in a batch; **clear Spatie permission cache** after changes.

**Deliverable:** Working per-role editor (matrix optional)—commit as “feat(roles): dynamic permission manager with batch apply + cache clear”.

---

## 6) UI/UX Consistency Pass (apply visual/behavior fixes)

* **Action bars:** Title, helper text, search (debounced), active-only toggle, per-page, Add button—identical across modules.
* **Tables & mobile cards:** Same density, `align-middle`, consistent column order; responsive mobile card variant.
* **Forms:** Reuse shared input/modal/button components; consistent grid, labels, help text, validation error positions.
* **Buttons:** Consistent heights and icon usage; disable during `wire:loading`.
* **Empty states:** Informative, with CTA to add.
* **Dark/Light parity:** Fix any off-contrast, borders, focus rings.
* **A11y:** Labels for inputs, `aria-*` on modals, focus management (focus first field when modal opens).

**Deliverable:** Blade updates across modules—commit as “style(ui): unify action bars, tables, forms, and empty states + a11y”.

---

## 7) Transitions & Loading States (micro-interactions)

* **Modals:** opacity + translate transitions (150–300ms), ESC/backdrop close.
* **Buttons & rows:** show spinners/`wire:loading` where operations take >200ms.
* **No layout shift:** use transform/opacity, avoid height reflows.

**Deliverable:** Transition utilities added; loading indicators wired—commit as “feat(ux): consistent transitions + loading states”.

---

## 8) Error Handling & Flash Messages

* Standardize server error display (validation + general) within modals.
* Use consistent success/error flash areas or toasts (same partial/component across modules).
* Guarded deletes (e.g., prevent delete when children exist) show clear reasons.

**Deliverable:** Shared flash/toast usage—commit as “fix(ux): unified flash/errors and guarded delete messages”.

---

## 9) Code Hygiene & Conventions

* Remove dead code, commented blocks, inconsistent naming.
* Replace inline styles with shared CSS (app.css/util classes).
* Ensure component/namespace names follow `Admin/Configuration/...`.
* Run linters/formatters and auto-fix (no tests).

**Deliverable:** Bulk cleanup—commit as “chore(code): lint/format, remove dead code, fix naming”.

---

## 10) Documentation & Changelog

* Add/Update:

  * `docs/CHANGELOG-configuration.md` summarizing all applied changes entity-by-entity.
  * `docs/MIGRATION-NOTES.md` for DB changes, uniqueness rules, and delete policies.
  * (Optional) short `docs/CACHE-NOTES.md` explaining Settings/AppTypes cache and invalidation hooks.

**Deliverable:** Docs updates—commit as “docs: changelog + migration notes + cache notes”.

---

## 11) PR Assembly (no tests)

* Title: **“Admin Configuration — Standardization & Fixes (Architecture, DB, UX, Roles Manager, Settings Unification)”**.
* Include a **high-level summary**:

  * What was standardized and why.
  * DB changes (scoped uniqueness/FKs/indexes).
  * UX unification and transitions.
  * Cache behavior for Settings/AppTypes.
  * Roles→Permissions dynamic manager details.
  * Any breaking changes (e.g., new migrations) and how to run them.
* Checklist in PR body:

  * [ ] Migrations run cleanly.
  * [ ] Pages render with no N+1 (spot-checked).
  * [ ] CRUD flows use event-driven modal pattern.
  * [ ] Search/filter/pagination behave consistently.
  * [ ] Roles permissions update and clear cache.
  * [ ] Dark/Light + responsive verified visually.
  * [ ] Docs updated.

---

## 12) Done Definition (for this PR)

* All modules in the Admin Configuration area **conform to the same pattern** and the code reflects the fixes (not just reported).
* DB constraints and indexes present where required.
* Settings/AppTypes unified page works; Roles manager assigns/unassigns dynamically.
* UI/UX/Transitions standardized project-wide for configuration screens.
* Docs/changelog explain what changed and how to operate going forward.

---

**Important:** Execute each section above by **modifying the codebase** and committing changes. Do not produce a “findings-only” report; the PR must contain the applied refactors and fixes. Skip automated tests entirely./u