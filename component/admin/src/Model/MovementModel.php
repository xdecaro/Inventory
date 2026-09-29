<?php
namespace xdecaro\Component\Inventory\Administrator\Model;
defined('_JEXEC') or die; use Joomla\CMS\MVC\Model\AdminModel;
final class MovementModel extends AdminModel { protected $text_prefix='COM_XDECAROINVENTORY'; public function getForm($data=[],$loadData=true){return $this->loadForm('com_xdecaroinventory.movement','movement',['control'=>'jform','load_data'=>$loadData]);} protected function loadFormData(){return [];} }
