<?php
declare(strict_types=1);
$root=dirname(__DIR__);
function d(bool $ok,string $msg):void{if(!$ok){fwrite(STDERR,"FAIL: $msg\n");exit(1);}}
$p=$root.'/component/admin/src/Model/DashboardModel.php';d(is_file($p),'DashboardModel missing');$s=@file_get_contents($p)?:'';foreach(['total_items','active_items','total_quantity','recent_movements','#__xdecaroinventory_items','#__xdecaroinventory_movements'] as$x)d(str_contains($s,$x),"missing $x");
$view=@file_get_contents($root.'/component/admin/src/View/Dashboard/HtmlView.php')?:'';d(str_contains($view,'componentVersion'),'dynamic component version missing from dashboard view');d(str_contains($view,'ExtensionHelper::getExtensionRecord'),'dashboard must read installed extension metadata');
$tpl=@file_get_contents($root.'/component/admin/tmpl/dashboard/default.php')?:'';d(str_contains($tpl,'COM_XDECAROINVENTORY_NEW_ITEM'),'new item action missing');d(str_contains($tpl,'COM_XDECAROINVENTORY_NEW_MOVEMENT'),'new movement action missing');d(str_contains($tpl,'$this->componentVersion'),'dashboard must render dynamic installed version');d(!preg_match('/<dd>0\.3\.\d+<\/dd>/', $tpl),'dashboard version must not be hardcoded');
echo "PASS dashboard-smoke\n";
