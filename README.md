# Inventory by xdecaro

Joomla component for physical inventory and stock-domain data in the xdecaro ecosystem.

## Technical identity

- Component: `com_xdecaroinventory`
- Package: `pkg_xdecaroinventory`
- PHP namespace: `xdecaro\Component\Inventory`
- Database tables: `#__xdecaroinventory_*`
- Current prerelease line: `0.3.3`
- Platform: **Joomla 6 only**
- Runtime baseline: Joomla 6.1.3
- PHP minimum: 8.3

The vendor namespace is intentionally lowercase: `xdecaro`.

## Inventory 0.3.3

Inventory 0.3.3 is a Joomla 6 runtime compatibility patch based on real Joomla 6.1.3 testing. It fixes the Beni and Movimenti list models so they obtain the application through Joomla's supported application accessor instead of calling an undefined `ListModel::getApplication()` method.

The Dashboard now reads the installed Inventory version dynamically from Joomla extension metadata instead of hardcoding a release number. Core diagnostics also fall back to the installed Core library/package manifest version when the Core runtime class is not autoloadable; Core UI assets are still enabled only when the required Core API classes are actually available.

The 0.3.2 non-destructive self-healing migration remains in place and uses `CREATE TABLE IF NOT EXISTS` for `#__xdecaroinventory_items` and `#__xdecaroinventory_movements`.

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
