# Repository Guidelines — Inventory by xdecaro

Inventory is a separate Joomla product in the xdecaro ecosystem.

## Domain boundary

Inventory owns physical item/catalog records, stock quantities and inventory movements. Resources owns allocatable resource definitions; Bookings owns reservations; Finance owns accounting. Inventory may reference those entities through public contracts only.

Core by xdecaro provides only shared infrastructure: Web Asset Manager assets, design tokens, compatibility helpers, diagnostics and public cross-product reference contracts. Do not move Inventory-specific business rules into Core.

Dependency direction is `Inventory -> Core`, never `Core -> Inventory`. Never read or write another product's private tables.

## Joomla/security

Use modern Joomla APIs, server-side ACL, CSRF for state-changing operations, validated input, escaped output, bound database queries and `#__` tables. Inventory movement integrity is business-critical: do not silently rewrite or delete movement history during normal updates.

## Releases

Version 0.1.0 establishes the first technical baseline. Keep stable IDs `com_xdecaroinventory` and `pkg_xdecaroinventory`. Normal updates must preserve data and configuration.
