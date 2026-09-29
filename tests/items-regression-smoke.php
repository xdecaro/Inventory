<?php
declare(strict_types=1);
$root=dirname(__DIR__);
function i(bool $ok,string $m):void{if(!$ok){fwrite(STDERR,"FAIL: $m\n");exit(1);}}
$files=['component/admin/src/Model/ItemsModel.php','component/admin/src/Model/ItemModel.php','component/admin/src/Table/ItemTable.php','component/admin/tmpl/items/default.php','component/admin/tmpl/item/edit.php','component/admin/forms/item.xml','component/admin/forms/filter_items.xml'];foreach($files as$f)i(is_file($root.'/'.$f),"missing $f");
$table=@file_get_contents($root.'/component/admin/src/Table/ItemTable.php')?:'';i(str_contains($table,'random_bytes'),'UUID generation missing');i(str_contains($table,'sku'),'SKU validation missing');
$model=@file_get_contents($root.'/component/admin/src/Model/ItemModel.php')?:'';i(str_contains($model,'quantity'),'quantity edit protection missing');
$list=@file_get_contents($root.'/component/admin/src/Model/ItemsModel.php')?:'';i(str_contains($list,'filter.search'),'search missing');i(str_contains($list,'bind('),'query bind missing');
$form=@file_get_contents($root.'/component/admin/forms/item.xml')?:'';i(str_contains($form,'name="uuid"')&&str_contains($form,'readonly="true"'),'uuid readonly missing');
$tpl=@file_get_contents($root.'/component/admin/tmpl/items/default.php')?:'';i(str_contains($tpl,'htmlspecialchars'),'escaping missing');
echo "PASS items-regression-smoke\n";
