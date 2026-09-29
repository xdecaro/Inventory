<?php
declare(strict_types=1);

$root = dirname(__DIR__);
function expectTrue(bool $condition, string $message): void {
    if (!$condition) {
        fwrite(STDERR, "FAIL: {$message}\n");
        exit(1);
    }
}

expectTrue(trim((string) file_get_contents($root . '/VERSION')) === '0.3.0', 'VERSION must be 0.3.0');
$component = (string) file_get_contents($root . '/component/xdecaroinventory.xml');
$package = (string) file_get_contents($root . '/package/pkg_xdecaroinventory.xml');
expectTrue(str_contains($component, '<version>0.3.0</version>'), 'component manifest version');
expectTrue(str_contains($package, '<version>0.3.0</version>'), 'package manifest version');
expectTrue(str_contains($component, 'view="items"'), 'items submenu missing');
expectTrue(str_contains($component, 'view="movements"'), 'movements submenu missing');
expectTrue(str_contains($component, 'xdecaro\\Component\\Inventory'), 'lowercase xdecaro namespace missing');
expectTrue(is_file($root . '/component/admin/sql/updates/mysql/0.3.0.sql'), '0.3.0 SQL update missing');

$sqlFiles = glob($root . '/component/admin/sql/**/*.sql') ?: [];
$sqlFiles = array_merge($sqlFiles, glob($root . '/component/admin/sql/updates/mysql/*.sql') ?: []);
foreach ($sqlFiles as $file) {
    $sql = strtoupper((string) file_get_contents($file));
    expectTrue(!preg_match('/\b(DROP|TRUNCATE)\s+TABLE\b/', $sql), 'destructive SQL in ' . $file);
}

echo "PASS inventory-structure-smoke\n";
