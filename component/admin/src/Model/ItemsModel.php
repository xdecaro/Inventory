<?php
namespace xdecaro\Component\Inventory\Administrator\Model;
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\Database\ParameterType;

final class ItemsModel extends ListModel
{
    public function __construct($config = [], $factory = null)
    {
        $config['filter_fields'] = $config['filter_fields'] ?? ['id','sku','name','item_type','quantity','unit','state','access','modified','created'];
        parent::__construct($config, $factory);
    }

    protected function populateState($ordering = 'name', $direction = 'asc'): void
    {
        $app = Factory::getApplication();
        $this->setState('filter.search', $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search', '', 'string'));
        $this->setState('filter.item_type', $app->getUserStateFromRequest($this->context . '.filter.item_type', 'filter_item_type', '', 'cmd'));
        $this->setState('filter.state', $app->getUserStateFromRequest($this->context . '.filter.state', 'filter_state', '', 'string'));
        $this->setState('filter.access', $app->getUserStateFromRequest($this->context . '.filter.access', 'filter_access', '', 'string'));
        parent::populateState($ordering, $direction);
    }

    protected function getListQuery()
    {
        $db = $this->getDatabase();
        $q = $db->createQuery()
            ->select($db->quoteName(['id','uuid','sku','name','item_type','quantity','unit','state','access','created','modified']))
            ->from($db->quoteName('#__xdecaroinventory_items'));

        $search = trim((string) $this->getState('filter.search'));
        if ($search !== '') {
            $search = '%' . str_replace(' ', '%', $search) . '%';
            $q->where('(' . $db->quoteName('name') . ' LIKE :search OR ' . $db->quoteName('sku') . ' LIKE :search)')
                ->bind(':search', $search, ParameterType::STRING);
        }

        $type = (string) $this->getState('filter.item_type');
        if ($type !== '') {
            $q->where($db->quoteName('item_type') . ' = :item_type')->bind(':item_type', $type, ParameterType::STRING);
        }

        $state = $this->getState('filter.state');
        if ($state !== '' && $state !== null) {
            $state = (int) $state;
            $q->where($db->quoteName('state') . ' = :state')->bind(':state', $state, ParameterType::INTEGER);
        }

        $access = $this->getState('filter.access');
        if ($access !== '' && $access !== null) {
            $access = (int) $access;
            $q->where($db->quoteName('access') . ' = :access')->bind(':access', $access, ParameterType::INTEGER);
        }

        $allowed = ['id','sku','name','item_type','quantity','unit','state','access','modified','created'];
        $order = (string) $this->getState('list.ordering', 'name');
        if (!in_array($order, $allowed, true)) {
            $order = 'name';
        }

        $dir = strtoupper((string) $this->getState('list.direction', 'ASC')) === 'DESC' ? 'DESC' : 'ASC';

        return $q->order($db->quoteName($order) . ' ' . $dir);
    }
}
