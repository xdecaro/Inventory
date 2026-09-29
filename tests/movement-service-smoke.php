<?php
declare(strict_types=1);
$root=dirname(__DIR__);
function m(bool $ok,string $msg):void{if(!$ok){fwrite(STDERR,"FAIL: $msg\n");exit(1);}}
$p=$root.'/component/admin/src/Service/MovementService.php';m(is_file($p),'MovementService missing');$s=@file_get_contents($p)?:'';foreach(['transactionStart','forUpdate','transactionCommit','transactionRollback','quantity_delta','#__xdecaroinventory_movements','#__xdecaroinventory_items'] as$x)m(str_contains($s,$x),"missing $x");m(str_contains($s,'$newQuantity < 0'),'negative stock guard missing');
$c=@file_get_contents($root.'/component/admin/src/Controller/MovementController.php')?:'';m(str_contains($c,'MovementService'),'controller must use MovementService');m(!str_contains($c,'items.quantity'),'controller updates stock directly');
echo "PASS movement-service-smoke\n";
