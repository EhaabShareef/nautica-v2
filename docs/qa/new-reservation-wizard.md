# New Reservation Wizard QA

## Fixed Issues
- Step transitions validated server-side via `goToStep` and logged.
- Booking creation wrapped in a transaction with final availability check.
- Added unique Livewire keys for dynamic dropdown and slot lists to prevent hydration errors.

## Test Cases
- Blocked progression to Location step without a selected vessel.
- Re-attempting to book an occupied slot triggers validation error.
- Slot selection moves to Services step and preserves choice when navigating back.
