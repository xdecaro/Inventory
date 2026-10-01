<?php
namespace xdecaro\Component\Inventory\Administrator\View\Movement;
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;

final class HtmlView extends BaseHtmlView
{
    public $form;

    public function display($tpl = null): void
    {
        $identity = Factory::getApplication()->getIdentity();

        if (!$identity->authorise('core.manage', 'com_xdecaroinventory')
            || !$identity->authorise('inventory.movements.create', 'com_xdecaroinventory')) {
            throw new \RuntimeException(Text::_('JERROR_ALERTNOAUTHOR'), 403);
        }

        $this->form = $this->get('Form');

        ToolbarHelper::title(Text::_('COM_XDECAROINVENTORY_NEW_MOVEMENT'), 'loop');
        ToolbarHelper::save('movement.save');
        ToolbarHelper::cancel('movement.cancel');

        $wa = $this->document->getWebAssetManager();
        $wa->useStyle('com_xdecaroinventory.admin');
        $wa->useScript('form.validate');

        parent::display($tpl);
    }
}
