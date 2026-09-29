# Inventory by xdecaro

Joomla component for physical inventory and stock-domain data in the xdecaro ecosystem.

## Technical identity

- Component: `com_xdecaroinventory`
- Package: `pkg_xdecaroinventory`
- PHP namespace: `xdecaro\Component\Inventory`
- Database tables: `#__xdecaroinventory_*`
- Current prerelease line: `0.3.2`
- Platform: **Joomla 6 only**
- Runtime baseline: Joomla 6.1.3
- PHP minimum: 8.3

The vendor namespace is intentionally lowercase: `xdecaro`.

## Inventory 0.3.2

Inventory 0.3.2 fixes an upgrade-path defect found during real Joomla 6.1.3 testing: an installation that already had Inventory registered but was missing the base Inventory tables could remain broken because the previous 0.3.0/0.3.1 update SQL files did not recreate them.

The 0.3.2 migration is self-healing and non-destructive. It uses `CREATE TABLE IF NOT EXISTS` for `#__xdecaroinventory_items` and `#__xdecaroinventory_movements`, preserving any existing tables and data while creating whichever base tables are missing.

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

Inventory owns catalogued physical items, stock quantities and inventory movements. Resources owns allocatable resources, Bookings owns reservations and Finance owns accounting. Integration with other xdecaro products must use public contracts; Inventory must not read or write another product's private tables.
