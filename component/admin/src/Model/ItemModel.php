<?php
namespace xdecaro\Component\Inventory\Administrator\Model;
defined('_JEXEC') or die;
use Joomla\CMS\Factory; use Joomla\CMS\MVC\Model\AdminModel; use Joomla\CMS\Table\Table;
final class ItemModel extends AdminModel { protected $text_prefix='COM_XDECAROINVENTORY'; public function getTable($name='Item',$prefix='Administrator',$options=[]):Table{return parent::getTable($name,$prefix,$options);} public function getForm($data=[],$loadData=true){return $this->loadForm('com_xdecaroinventory.item','item',['control'=>'jform','load_data'=>$loadData]);} protected function loadFormData(){$app=Factory::getApplication();$data=$app->getUserState('com_xdecaroinventory.edit.item.data',[]);return $data?:$this->getItem();} public function save($data):bool{$id=(int)($data['id']??0);if($id>0){$table=$this->getTable();if(!$table->load($id))return false;$data['quantity']=$table->quantity;}else{$quantity=(float)($data['quantity']??0);if($quantity<0)$data['quantity']=0;}return parent::save($data);} }
