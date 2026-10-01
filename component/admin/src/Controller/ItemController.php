<?php
namespace xdecaro\Component\Inventory\Administrator\Controller;
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Session\Session;

final class ItemController extends FormController
{
    protected $option = 'com_xdecaroinventory';
    protected $view_item = 'item';
    protected $view_list = 'items';

    public function getModel($name = 'Item', $prefix = 'Administrator', $config = ['ignore_request' => true]): BaseDatabaseModel
    {
        return parent::getModel($name, $prefix, $config);
    }

    protected function allowAdd($data = []): bool
    {
        return $this->canManageItems();
    }

    protected function allowEdit($data = [], $key = 'id'): bool
    {
        return $this->canManageItems();
    }

    public function save($key = null, $urlVar = null): bool
    {
        if (!Session::checkToken()) {
            throw new \RuntimeException(Text::_('JINVALID_TOKEN'), 403);
        }

        if (!$this->canManageItems()) {
            throw new \RuntimeException(Text::_('JERROR_ALERTNOAUTHOR'), 403);
        }

        return parent::save($key, $urlVar);
    }

    public function cancel($key = null): bool
    {
        if (!Session::checkToken()) {
            throw new \RuntimeException(Text::_('JINVALID_TOKEN'), 403);
        }

        return parent::cancel($key);
    }

    private function canManageItems(): bool
    {
        $identity = Factory::getApplication()->getIdentity();

        return $identity->authorise('core.manage', 'com_xdecaroinventory')
            && $identity->authorise('inventory.items.manage', 'com_xdecaroinventory');
    }
}
