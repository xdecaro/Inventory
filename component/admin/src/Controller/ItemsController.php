<?php
namespace xdecaro\Component\Inventory\Administrator\Controller;
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Controller\AdminController; use Joomla\CMS\MVC\Model\BaseDatabaseModel;
final class ItemsController extends AdminController { public function getModel($name='Item',$prefix='Administrator',$config=['ignore_request'=>true]): BaseDatabaseModel { return parent::getModel($name,$prefix,$config); } }
