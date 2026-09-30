<?php
declare(strict_types=1);
$root=dirname(__DIR__);
function i(bool $ok,string $m):void{if(!$ok){fwrite(STDERR,"FAIL: $m\n");exit(1);}}
$files=['component/admin/src/Model/ItemsModel.php','component/admin/src/Model/ItemModel.php','component/admin/src/Table/ItemTable.php','component/admin/tmpl/items/default.php','component/admin/tmpl/item/edit.php','component/admin/forms/item.xml','component/admin/forms/filter_items.xml'];foreach($files as$f)i(is_file($root.'/'.$f),"missing $f");
$table=@file_get_contents($root.'/component/admin/src/Table/ItemTable.php')?:'';
i(str_contains($table,'random_bytes'),'UUID generation missing');
i(str_contains($table,'sku'),'SKU validation missing');
i(str_contains($table,"['item', 'consumable']"),'item type whitelist missing');
i(str_contains($table,"['0', '1']"),'state whitelist missing');
i(str_contains($table,'\\d{1,11}'),'DECIMAL(14,3) range guard missing');
$model=@file_get_contents($root.'/component/admin/src/Model/ItemModel.php')?:'';
i(str_contains($model,"\$data['quantity'] = \$table->quantity"),'quantity edit protection missing');
i(str_contains($model,"\$data['uuid'] = \$table->uuid"),'UUID edit protection missing');
i(str_contains($model,"unset(\$data['uuid'])"),'new-record UUID input stripping missing');
$list=@file_get_contents($root.'/component/admin/src/Model/ItemsModel.php')?:'';
i(str_contains($list,'filter.search'),'search missing');
i(str_contains($list,':search_name')&&str_contains($list,':search_sku'),'distinct search binds missing');
i(!str_contains($list,'$this->getApplication()'),'ItemsModel must not call undefined ListModel::getApplication()');
i(str_contains($list,'Factory::getApplication()'),'ItemsModel must use Joomla application accessor');
$form=@file_get_contents($root.'/component/admin/forms/item.xml')?:'';
i(str_contains($form,'name="uuid"')&&str_contains($form,'type="hidden"'),'uuid must be hidden/immutable');
i(str_contains($form,'max="99999999999.999"'),'item quantity DB-range client guard missing');
$tpl=@file_get_contents($root.'/component/admin/tmpl/items/default.php')?:'';
i(str_contains($tpl,'htmlspecialchars'),'escaping missing');
i(str_contains($tpl,'canManageItems'),'item edit link ACL UX guard missing');
echo "PASS items-regression-smoke\n";
