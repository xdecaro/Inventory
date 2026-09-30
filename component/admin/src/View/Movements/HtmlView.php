<?php
namespace xdecaro\Component\Inventory\Administrator\View\Movements;
defined('_JEXEC') or die;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;

final class HtmlView extends BaseHtmlView
{
    public $items;
    public $pagination;
    public $state;
    public $filterForm;
    public $activeFilters;

    public function display($tpl = null): void
    {
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->state = $this->get('State');
        $this->filterForm = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');

        $app = Factory::getApplication();

        ToolbarHelper::title(Text::_('COM_XDECAROINVENTORY_MOVEMENTS'), 'loop');
        if ($app->getIdentity()->authorise('inventory.movements.create', 'com_xdecaroinventory')) {
            ToolbarHelper::addNew('movement.add', 'COM_XDECAROINVENTORY_NEW_MOVEMENT');
        }

        $wa = $app->getDocument()->getWebAssetManager();
        $wa->getRegistry()->addExtensionRegistryFile('com_xdecaroinventory');
        $wa->useStyle('com_xdecaroinventory.admin');

        parent::display($tpl);
    }
}
