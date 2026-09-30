<?php
namespace xdecaro\Component\Inventory\Administrator\View\Item;
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;

final class HtmlView extends BaseHtmlView
{
    public $form;
    public $item;

    public function display($tpl = null): void
    {
        $identity = Factory::getApplication()->getIdentity();

        if (!$identity->authorise('core.manage', 'com_xdecaroinventory')
            || !$identity->authorise('inventory.items.manage', 'com_xdecaroinventory')) {
            throw new \RuntimeException(Text::_('JERROR_ALERTNOAUTHOR'), 403);
        }

        $this->form = $this->get('Form');
        $this->item = $this->get('Item');

        if (count($errors = $this->get('Errors'))) {
            throw new \RuntimeException(implode("\n", $errors));
        }

        ToolbarHelper::title(
            Text::_($this->item->id ? 'COM_XDECAROINVENTORY_EDIT_ITEM' : 'COM_XDECAROINVENTORY_NEW_ITEM'),
            'archive'
        );
        ToolbarHelper::apply('item.apply');
        ToolbarHelper::save('item.save');
        ToolbarHelper::cancel('item.cancel');

        $wa = $this->document->getWebAssetManager();
        $wa->useStyle('com_xdecaroinventory.admin');
        $wa->useScript('form.validate');

        parent::display($tpl);
    }
}
