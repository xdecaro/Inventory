<?php
declare(strict_types=1);
$root=dirname(__DIR__);
function a(bool $ok,string $m):void{if(!$ok){fwrite(STDERR,"FAIL: $m\n");exit(1);}}
$access=@file_get_contents($root.'/component/admin/access.xml')?:'';
a(str_contains($access,'inventory.items.manage'),'items ACL missing');
a(str_contains($access,'inventory.movements.create'),'movements ACL missing');
foreach(['DisplayController.php','ItemsController.php','MovementsController.php','ItemController.php','MovementController.php'] as $f){
    $p=$root.'/component/admin/src/Controller/'.$f;
    a(is_file($p),"$f missing");
    $s=@file_get_contents($p)?:'';
    a(str_contains($s,'com_xdecaroinventory'),"$f component option missing");
    a(str_contains($s,'$option'),"$f must pin Form/Admin controller option");
}
foreach(['ItemController.php','MovementController.php'] as $f){
    $s=@file_get_contents($root.'/component/admin/src/Controller/'.$f)?:'';
    a(str_contains($s,'checkToken'),"$f CSRF missing");
    a(str_contains($s,'authorise'),"$f ACL missing");
}
$movement=@file_get_contents($root.'/component/admin/src/Controller/MovementController.php')?:'';
a(str_contains($movement,'function allowAdd'),'MovementController custom allowAdd missing');
a(str_contains($movement,"inventory.movements.create"),'MovementController movement ACL missing');
a(str_contains($movement,"Log::add"),'unexpected movement errors must be logged safely');
a(str_contains($movement,'COM_XDECAROINVENTORY_ERROR_MOVEMENT_SAVE_FAILED'),'generic technical movement error missing');
echo "PASS acl-controller-smoke\n";
