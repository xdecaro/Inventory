<?php
declare(strict_types=1);

$root = dirname(__DIR__);
function expectTrue(bool $condition, string $message): void {
    if (!$condition) {
        fwrite(STDERR, "FAIL: {$message}\n");
        exit(1);
    }
}

expectTrue(trim((string) file_get_contents($root . '/VERSION')) === '0.3.2', 'VERSION must be 0.3.2');
$component = (string) file_get_contents($root . '/component/xdecaroinventory.xml');
$package = (string) file_get_contents($root . '/package/pkg_xdecaroinventory.xml');
$updates = (string) file_get_contents($root . '/updates/pkg_xdecaroinventory.xml');
$readme = (string) file_get_contents($root . '/README.md');
$repairPath = $root . '/component/admin/sql/updates/mysql/0.3.2.sql';

expectTrue(str_contains($component, '<version>0.3.2</version>'), 'component manifest version');
expectTrue(str_contains($package, '<version>0.3.2</version>'), 'package manifest version');
expectTrue(str_contains($updates, '<version>0.3.2</version>'), 'update server version');
expectTrue(str_contains($component, '<targetplatform name="joomla" version="6.*"/>'), 'component must target Joomla 6 only');
expectTrue(str_contains($package, '<targetplatform name="joomla" version="6.*"/>'), 'package must target Joomla 6 only');
expectTrue(str_contains($updates, '<targetplatform name="joomla" version="6\\.[0-9]+"/>'), 'update server must target Joomla 6 only');
expectTrue(str_contains($updates, '<php_minimum>8.3.0</php_minimum>'), 'Joomla 6 requires PHP 8.3 minimum');
expectTrue(!str_contains($component, '(5|6)'), 'component still declares Joomla 5 compatibility');
expectTrue(!str_contains($package, '(5|6)'), 'package still declares Joomla 5 compatibility');
expectTrue(!str_contains($updates, '(5|6)'), 'update server still declares Joomla 5 compatibility');
expectTrue(str_contains($readme, 'Joomla 6 only'), 'README must declare Joomla 6 only');
expectTrue(str_contains($component, 'view="items"'), 'items submenu missing');
expectTrue(str_contains($component, 'view="movements"'), 'movements submenu missing');
expectTrue(str_contains($component, 'xdecaro\\Component\\Inventory'), 'lowercase xdecaro namespace missing');
expectTrue(is_file($repairPath), '0.3.2 self-healing SQL update missing');

$repair = is_file($repairPath) ? (string) file_get_contents($repairPath) : '';
foreach (['#__xdecaroinventory_items', '#__xdecaroinventory_movements'] as $table) {
    expectTrue(str_contains($repair, 'CREATE TABLE IF NOT EXISTS `' . $table . '`'), 'repair update must create missing table ' . $table);
}
expectTrue(str_contains($repair, 'FOREIGN KEY (`item_id`) REFERENCES `#__xdecaroinventory_items`(`id`)'), 'movement foreign key missing from repair update');

$sqlFiles = glob($root . '/component/admin/sql/**/*.sql') ?: [];
$sqlFiles = array_merge($sqlFiles, glob($root . '/component/admin/sql/updates/mysql/*.sql') ?: []);
foreach ($sqlFiles as $file) {
    $sql = strtoupper((string) file_get_contents($file));
    expectTrue(!preg_match('/\b(DROP|TRUNCATE)\s+TABLE\b/', $sql), 'destructive SQL in ' . $file);
}

echo "PASS inventory-structure-smoke\n";
