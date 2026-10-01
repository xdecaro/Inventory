<?php
declare(strict_types=1);
$root=dirname(__DIR__);
function m(bool $ok,string $msg):void{if(!$ok){fwrite(STDERR,"FAIL: $msg\n");exit(1);}}
$p=$root.'/component/admin/src/Service/MovementService.php';m(is_file($p),'MovementService missing');$s=@file_get_contents($p)?:'';
foreach(['transactionStart','affectedRows','transactionCommit','transactionRollback','quantity_delta','#__xdecaroinventory_movements','#__xdecaroinventory_items'] as$x)m(str_contains($s,$x),"missing $x");
m(str_contains($s,'affectedRows')&&str_contains($s,'>= 0'),'atomic negative-stock guard missing');
m(str_contains($s,'\\d{1,11}'),'movement DB-range guard missing');
$c=@file_get_contents($root.'/component/admin/src/Controller/MovementController.php')?:'';
m(str_contains($c,'MovementService'),'controller must use MovementService');
m(!str_contains($c,'items.quantity'),'controller updates stock directly');
m(str_contains($c,"protected \$option = 'com_xdecaroinventory'"),'movement controller option mismatch');
m(str_contains($c,'function allowAdd'),'movement add ACL override missing');
$list=@file_get_contents($root.'/component/admin/src/Model/MovementsModel.php')?:'';
m(!str_contains($list,'$this->getApplication()'),'MovementsModel must not call undefined ListModel::getApplication()');
m(str_contains($list,'Factory::getApplication()'),'MovementsModel must use Joomla application accessor');
foreach([':search_reason',':search_name',':search_sku'] as$x)m(str_contains($list,$x),"missing distinct movement search bind $x");
m(str_contains($list,'#__users')&&str_contains($list,'user_name'),'movement user-name join missing');
$form=@file_get_contents($root.'/component/admin/forms/movement.xml')?:'';
m(str_contains($form,'min="-99999999999.999"')&&str_contains($form,'max="99999999999.999"'),'movement quantity client DB-range guard missing');
echo "PASS movement-service-smoke\n";
