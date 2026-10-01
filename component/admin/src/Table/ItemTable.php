<?php
namespace xdecaro\Component\Inventory\Administrator\Table;
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseInterface;
use Joomla\Database\ParameterType;

final class ItemTable extends Table
{
    private DatabaseInterface $database;

    public function __construct(DatabaseInterface $db)
    {
        $this->database = $db;
        parent::__construct('#__xdecaroinventory_items', 'id', $db);
    }

    public function check(): bool
    {
        $this->name = trim((string) $this->name);
        $this->sku = trim((string) $this->sku);
        $this->item_type = trim((string) $this->item_type) ?: 'item';
        $this->unit = trim((string) $this->unit) ?: 'pcs';

        if ($this->name === '') {
            $this->setError(Text::_('COM_XDECAROINVENTORY_ERROR_NAME_REQUIRED'));
            return false;
        }

        if (!in_array($this->item_type, ['item', 'consumable'], true)) {
            $this->setError(Text::_('COM_XDECAROINVENTORY_ERROR_INVALID_TYPE'));
            return false;
        }

        $stateValue = (string) $this->state;
        if (!in_array($stateValue, ['0', '1'], true)) {
            $this->setError(Text::_('COM_XDECAROINVENTORY_ERROR_INVALID_STATE'));
            return false;
        }
        $this->state = (int) $stateValue;

        $normalizedQuantity = self::normalizeQuantity((string) $this->quantity);
        if ($normalizedQuantity === null) {
            $this->setError(Text::_('COM_XDECAROINVENTORY_ERROR_INVALID_QUANTITY'));
            return false;
        }
        if (str_starts_with($normalizedQuantity, '-')) {
            $this->setError(Text::_('COM_XDECAROINVENTORY_ERROR_NEGATIVE_INITIAL_QUANTITY'));
            return false;
        }
        $this->quantity = $normalizedQuantity;

        if ($this->sku !== '') {
            $q = $this->database->createQuery()
                ->select($this->database->quoteName('id'))
                ->from($this->database->quoteName('#__xdecaroinventory_items'))
                ->where($this->database->quoteName('sku') . ' = :sku')
                ->bind(':sku', $this->sku, ParameterType::STRING);

            if ((int) $this->id > 0) {
                $id = (int) $this->id;
                $q->where($this->database->quoteName('id') . ' <> :id')->bind(':id', $id, ParameterType::INTEGER);
            }

            $this->database->setQuery($q, 0, 1);
            if ($this->database->loadResult()) {
                $this->setError(Text::_('COM_XDECAROINVENTORY_ERROR_SKU_DUPLICATE'));
                return false;
            }
        } else {
            $this->sku = null;
        }

        return parent::check();
    }

    public function store($updateNulls = true): bool
    {
        $now = Factory::getDate()->toSql();
        $userId = (int) Factory::getApplication()->getIdentity()->id;

        if ((int) $this->id === 0) {
            $this->uuid = self::uuidV4();
            $this->created = $this->created ?: $now;
            $this->created_by = $this->created_by ?: $userId;
        } else {
            $this->modified = $now;
            $this->modified_by = $userId;
        }

        return parent::store($updateNulls);
    }

    private static function normalizeQuantity(string $value): ?string
    {
        $value = trim(str_replace(',', '.', $value));
        if (!preg_match('/^[+-]?\d{1,11}(?:\.\d{1,3})?$/', $value)) {
            return null;
        }

        $negative = str_starts_with($value, '-');
        $unsigned = ltrim($value, '+-');
        [$whole, $fraction] = array_pad(explode('.', $unsigned, 2), 2, '');
        $fraction = str_pad($fraction, 3, '0');

        return ($negative ? '-' : '') . $whole . '.' . $fraction;
    }

    private static function uuidV4(): string
    {
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
        $hex = bin2hex($data);

        return substr($hex, 0, 8) . '-' . substr($hex, 8, 4) . '-' . substr($hex, 12, 4) . '-' . substr($hex, 16, 4) . '-' . substr($hex, 20);
    }
}
