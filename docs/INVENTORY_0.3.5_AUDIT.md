# Inventory 0.3.5 runtime audit

Real Joomla 6.1.4 verification exposed cross-cutting runtime issues after 0.3.4. This stabilization pass covers MVC component option routing, ACL consistency, Web Asset Manager URI resolution, forms, server-side validation, prepared-statement bindings, Core diagnostics and administration UI details. The patch must remain non-destructive and the PR stays Draft until the Joomla runtime smoke test passes.
