<?php
namespace xdecaro\Component\Inventory\Administrator\View\Movement;
defined('_JEXEC') or die;
use Joomla\CMS\Factory; use Joomla\CMS\Language\Text; use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView; use Joomla\CMS\Toolbar\ToolbarHelper;
final class HtmlView extends BaseHtmlView { public $form;public function display($tpl=null):void{$this->form=$this->get('Form');ToolbarHelper::title(Text::_('COM_XDECAROINVENTORY_NEW_MOVEMENT'),'loop');ToolbarHelper::save('movement.save');ToolbarHelper::cancel('movement.cancel');Factory::getApplication()->getDocument()->getWebAssetManager()->getRegistry()->addExtensionRegistryFile('com_xdecaroinventory')->useStyle('com_xdecaroinventory.admin');parent::display($tpl);} }
