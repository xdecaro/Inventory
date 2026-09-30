<?php
declare(strict_types=1);
$root=dirname(__DIR__);
function u(bool $ok,string $msg):void{if(!$ok){fwrite(STDERR,"FAIL: $msg\n");exit(1);}}
$css=@file_get_contents($root.'/component/media/css/admin.css')?:'';
u(str_contains($css,'@media'),'responsive CSS missing');
u(str_contains($css,'var('),'theme variables missing');
$assets=@file_get_contents($root.'/component/media/joomla.asset.json')?:'';
u(str_contains($assets,'com_xdecaroinventory/admin.css'),'component style URI must omit the automatic css directory');
u(!str_contains($assets,'com_xdecaroinventory/css/admin.css'),'duplicated css path remains in asset URI');
foreach(['items/default.php','movements/default.php','dashboard/default.php'] as$f){$s=@file_get_contents($root.'/component/admin/tmpl/'.$f)?:'';u(str_contains($s,'Text::_('),"language usage missing in $f");}
foreach(['Items','Item','Movements','Movement'] as$view){
    $s=@file_get_contents($root.'/component/admin/src/View/'.$view.'/HtmlView.php')?:'';
    u(str_contains($s,"useStyle('com_xdecaroinventory.admin')"),"admin asset missing in $view view");
    u(!str_contains($s,"getRegistry()->addExtensionRegistryFile('com_xdecaroinventory')->useStyle"),"$view incorrectly calls useStyle() on WebAssetRegistry");
    u(str_contains($s,"getWebAssetManager()"),"$view WebAssetManager missing");
    u(str_contains($s,"core.manage"),"$view server-side core.manage gate missing");
}
foreach(['Item','Movement'] as$view){$s=@file_get_contents($root.'/component/admin/src/View/'.$view.'/HtmlView.php')?:'';u(str_contains($s,"useScript('form.validate')"),"form.validate missing in $view");}
$dashboardView=@file_get_contents($root.'/component/admin/src/View/Dashboard/HtmlView.php')?:'';
u(str_contains($dashboardView,'canManageItems')&&str_contains($dashboardView,'canCreateMovement'),'Dashboard action ACL state missing');
$dashboardTpl=@file_get_contents($root.'/component/admin/tmpl/dashboard/default.php')?:'';
u(str_contains($dashboardTpl,"HTMLHelper::_('date'"),'Dashboard recent movement date localization missing');
$movementTable=@file_get_contents($root.'/component/admin/src/Table/MovementTable.php')?:'';
u(!str_contains($movementTable,"'Inventory movement history is append-only.'"),'hardcoded append-only error');
u(str_contains($movementTable,'Text::_('),'translated append-only error missing');
foreach(['it-IT','en-GB'] as$l){$s=@file_get_contents($root.'/component/admin/language/'.$l.'/com_xdecaroinventory.ini')?:'';foreach(['COM_XDECAROINVENTORY_ITEMS','COM_XDECAROINVENTORY_MOVEMENTS','COM_XDECAROINVENTORY_NEW_ITEM','COM_XDECAROINVENTORY_NEW_MOVEMENT','COM_XDECAROINVENTORY_ERROR_MOVEMENT_APPEND_ONLY','COM_XDECAROINVENTORY_ERROR_MOVEMENT_SAVE_FAILED','COM_XDECAROINVENTORY_NO_ITEMS'] as$k)u(str_contains($s,$k),"$k missing in $l");}
echo "PASS ui-language-smoke\n";
