<?php
declare(strict_types=1);
$root=dirname(__DIR__);
function u(bool $ok,string $msg):void{if(!$ok){fwrite(STDERR,"FAIL: $msg\n");exit(1);}}
$css=@file_get_contents($root.'/component/media/css/admin.css')?:'';
u(str_contains($css,'@media'),'responsive CSS missing');
u(str_contains($css,'var('),'theme variables missing');
foreach(['items/default.php','movements/default.php','dashboard/default.php'] as$f){$s=@file_get_contents($root.'/component/admin/tmpl/'.$f)?:'';u(str_contains($s,'Text::_('),"language usage missing in $f");}
foreach(['Item','Movement'] as$view){$s=@file_get_contents($root.'/component/admin/src/View/'.$view.'/HtmlView.php')?:'';u(str_contains($s,"useStyle('com_xdecaroinventory.admin')"),"admin asset missing in $view form view");}
$movementTable=@file_get_contents($root.'/component/admin/src/Table/MovementTable.php')?:'';
u(!str_contains($movementTable,"'Inventory movement history is append-only.'"),'hardcoded append-only error');
u(str_contains($movementTable,'Text::_('),'translated append-only error missing');
foreach(['it-IT','en-GB'] as$l){$s=@file_get_contents($root.'/component/admin/language/'.$l.'/com_xdecaroinventory.ini')?:'';foreach(['COM_XDECAROINVENTORY_ITEMS','COM_XDECAROINVENTORY_MOVEMENTS','COM_XDECAROINVENTORY_NEW_ITEM','COM_XDECAROINVENTORY_NEW_MOVEMENT','COM_XDECAROINVENTORY_ERROR_MOVEMENT_APPEND_ONLY'] as$k)u(str_contains($s,$k),"$k missing in $l");}
echo "PASS ui-language-smoke\n";
