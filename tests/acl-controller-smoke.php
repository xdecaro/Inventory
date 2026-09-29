<?php
declare(strict_types=1);
$root=dirname(__DIR__);
function a(bool $ok,string $m):void{if(!$ok){fwrite(STDERR,"FAIL: $m\n");exit(1);}}
$access=@file_get_contents($root.'/component/admin/access.xml')?:'';
a(str_contains($access,'inventory.items.manage'),'items ACL missing');
a(str_contains($access,'inventory.movements.create'),'movements ACL missing');
foreach(['ItemController.php','MovementController.php'] as $f){$p=$root.'/component/admin/src/Controller/'.$f;a(is_file($p),"$f missing");$s=@file_get_contents($p)?:'';a(str_contains($s,'checkToken'),"$f CSRF missing");a(str_contains($s,'authorise'),"$f ACL missing");}
echo "PASS acl-controller-smoke\n";
