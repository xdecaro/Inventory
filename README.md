# Inventory by xdecaro

Joomla component for physical inventory and stock-domain data in the xdecaro ecosystem.

## Technical identity

- Component: `com_xdecaroinventory`
- Package: `pkg_xdecaroinventory`
- PHP namespace: `xdecaro\Component\Inventory`
- Database tables: `#__xdecaroinventory_*`
- Current prerelease line: `0.3.5`
- Platform: **Joomla 6 only**
- Runtime baseline: Joomla 6.1.4
- PHP minimum: 8.3

The vendor namespace is intentionally lowercase: `xdecaro`.

## Inventory 0.3.5

Inventory 0.3.5 is a runtime-stabilization patch driven by real Joomla 6 testing. It fixes the FormController component option mismatch that generated `option=com_inventory` and caused 404 errors when opening new/edit forms. All Inventory controllers now explicitly pin `com_xdecaroinventory`, and movement creation has an explicit ACL-aware `allowAdd()` path.

The Web Asset Manager declaration is also corrected so Joomla resolves `media/com_xdecaroinventory/css/admin.css` through the component style URI `com_xdecaroinventory/admin.css`. Dashboard, list and form styling therefore use the same asset contract.

Additional hardening in this patch includes:

- consistent server-side `core.manage` checks on administration views;
- action buttons and item edit links hidden when the matching Inventory ACL is missing;
- `form.validate` enabled on item and movement forms;
- UUID kept hidden and immutable after creation;
- server-side whitelist validation for item type and state;
- quantity input constrained to the `DECIMAL(14,3)` database range;
- distinct prepared-statement placeholders for multi-column searches;
- safe handling of unexpected movement-save errors with Joomla logging and a translated generic message;
- localized dates, user names and explicit empty-list states;
- broader Core by xdecaro version diagnostics covering library, component and current/legacy package identifiers.

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
