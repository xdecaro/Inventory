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
        $app = Factory::getApplication();

        if (!$app->getIdentity()->authorise('core.manage', 'com_xdecaroinventory')) {
            throw new \RuntimeException(Text::_('JERROR_ALERTNOAUTHOR'), 403);
        }

        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->state = $this->get('State');
        $this->filterForm = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');

        if (count($errors = $this->get('Errors'))) {
            throw new \RuntimeException(implode("\n", $errors));
        }

        ToolbarHelper::title(Text::_('COM_XDECAROINVENTORY_MOVEMENTS'), 'loop');
        if ($app->getIdentity()->authorise('inventory.movements.create', 'com_xdecaroinventory')) {
            ToolbarHelper::addNew('movement.add', 'COM_XDECAROINVENTORY_NEW_MOVEMENT');
        }

        $this->document->getWebAssetManager()->useStyle('com_xdecaroinventory.admin');

        parent::display($tpl);
    }
}
