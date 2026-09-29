# Inventory by xdecaro

Joomla component for physical inventory and stock-domain data in the xdecaro ecosystem.

## Technical identity

- Component: `com_xdecaroinventory`
- Package: `pkg_xdecaroinventory`
- PHP namespace: `xdecaro\Component\Inventory`
- Database tables: `#__xdecaroinventory_*`
- Current prerelease line: `0.3.1`
- Platform: **Joomla 6 only**
- Runtime baseline: Joomla 6.1.3
- PHP minimum: 8.3

The vendor namespace is intentionally lowercase: `xdecaro`.

## Inventory 0.3.1

Inventory 0.3.1 keeps the functional Inventory 0.3 baseline and makes the supported platform explicit: Joomla 6 only. Joomla 5 is no longer declared or tested as a supported target.

The functional baseline includes:

- live Dashboard with item and movement KPIs;
- item management with UUID, SKU, type, quantity, unit, state and access;
- search, filters, ordering and pagination;
- append-only inventory movement history;
- atomic transactional stock updates with rollback;
- prevention of negative stock;
- dedicated ACL and CSRF checks;
- responsive light/dark compatible administration UI;
- Italian and English language strings.

The 0.2.0 data model is preserved: `#__xdecaroinventory_items.quantity` remains the current balance and `#__xdecaroinventory_movements` remains the movement ledger. The 0.3.1 update introduces no destructive schema change.

Inventory owns catalogued physical items, stock quantities and inventory movements. Resources owns allocatable resources, Bookings owns reservations and Finance owns accounting. Integration with other xdecaro products must use public contracts; Inventory must not read or write another product's private tables.
