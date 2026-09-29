<?php
namespace xdecaro\Component\Inventory\Administrator\Model;
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\Database\ParameterType;

final class MovementsModel extends ListModel
{
    public function __construct($config = [], $factory = null)
    {
        $config['filter_fields'] = $config['filter_fields'] ?? ['id','created','quantity_delta','reason','item_name'];
        parent::__construct($config, $factory);
    }

    protected function populateState($ordering = 'm.created', $direction = 'desc'): void
    {
        $app = Factory::getApplication();
        $this->setState('filter.search', $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search', '', 'string'));
        $this->setState('filter.item_id', $app->getUserStateFromRequest($this->context . '.filter.item_id', 'filter_item_id', '', 'int'));
        parent::populateState($ordering, $direction);
    }

    protected function getListQuery()
    {
        $db = $this->getDatabase();
        $q = $db->createQuery()
            ->select([
                'm.id','m.item_id','m.quantity_delta','m.reason','m.note','m.created','m.created_by',
                $db->quoteName('i.name', 'item_name'),
                $db->quoteName('i.sku', 'item_sku'),
            ])
            ->from($db->quoteName('#__xdecaroinventory_movements', 'm'))
            ->join('INNER', $db->quoteName('#__xdecaroinventory_items', 'i') . ' ON ' . $db->quoteName('i.id') . ' = ' . $db->quoteName('m.item_id'));

        $search = trim((string) $this->getState('filter.search'));
        if ($search !== '') {
            $search = '%' . str_replace(' ', '%', $search) . '%';
            $q->where('(' . $db->quoteName('m.reason') . ' LIKE :search OR ' . $db->quoteName('i.name') . ' LIKE :search OR ' . $db->quoteName('i.sku') . ' LIKE :search)')
                ->bind(':search', $search, ParameterType::STRING);
        }

        $item = (int) $this->getState('filter.item_id');
        if ($item > 0) {
            $q->where($db->quoteName('m.item_id') . ' = :item_id')->bind(':item_id', $item, ParameterType::INTEGER);
        }

        $allowed = ['m.created','m.quantity_delta','m.reason','i.name'];
        $order = (string) $this->getState('list.ordering', 'm.created');
        if (!in_array($order, $allowed, true)) {
            $order = 'm.created';
        }

        $dir = strtoupper((string) $this->getState('list.direction', 'DESC')) === 'ASC' ? 'ASC' : 'DESC';

        return $q->order($db->quoteName($order) . ' ' . $dir);
    }
}
