<?php
namespace xdecaro\Component\Inventory\Administrator\Controller;
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Log\Log;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;
use xdecaro\Component\Inventory\Administrator\Service\MovementService;

final class MovementController extends FormController
{
    protected $option = 'com_xdecaroinventory';
    protected $view_item = 'movement';
    protected $view_list = 'movements';

    public function getModel($name = 'Movement', $prefix = 'Administrator', $config = ['ignore_request' => true]): BaseDatabaseModel
    {
        return parent::getModel($name, $prefix, $config);
    }

    protected function allowAdd($data = []): bool
    {
        return $this->canCreateMovement();
    }

    protected function allowEdit($data = [], $key = 'id'): bool
    {
        return false;
    }

    public function save($key = null, $urlVar = null): bool
    {
        if (!Session::checkToken()) {
            throw new \RuntimeException(Text::_('JINVALID_TOKEN'), 403);
        }

        $app = Factory::getApplication();
        $identity = $app->getIdentity();

        if (!$this->canCreateMovement()) {
            throw new \RuntimeException(Text::_('JERROR_ALERTNOAUTHOR'), 403);
        }

        $data = $this->input->get('jform', [], 'array');
        $itemId = (int) ($data['item_id'] ?? 0);
        $delta = trim((string) ($data['quantity_delta'] ?? ''));
        $reason = trim((string) ($data['reason'] ?? ''));
        $note = trim((string) ($data['note'] ?? ''));

        try {
            Factory::getContainer()->get(MovementService::class)->record(
                $itemId,
                $delta,
                $reason,
                $note !== '' ? $note : null,
                (int) $identity->id
            );
            $app->enqueueMessage(Text::_('COM_XDECAROINVENTORY_MOVEMENT_SAVED'), 'message');
            $this->setRedirect(Route::_('index.php?option=com_xdecaroinventory&view=movements', false));

            return true;
        } catch (\InvalidArgumentException|\DomainException $e) {
            $app->enqueueMessage($e->getMessage(), 'error');
            $this->setRedirect(Route::_('index.php?option=com_xdecaroinventory&view=movement&layout=edit', false));

            return false;
        } catch (\Throwable $e) {
            Log::add($e->getMessage(), Log::ERROR, 'com_xdecaroinventory');
            $app->enqueueMessage(Text::_('COM_XDECAROINVENTORY_ERROR_MOVEMENT_SAVE_FAILED'), 'error');
            $this->setRedirect(Route::_('index.php?option=com_xdecaroinventory&view=movement&layout=edit', false));

            return false;
        }
    }

    public function cancel($key = null): bool
    {
        if (!Session::checkToken()) {
            throw new \RuntimeException(Text::_('JINVALID_TOKEN'), 403);
        }

        $this->setRedirect(Route::_('index.php?option=com_xdecaroinventory&view=movements', false));

        return true;
    }

    private function canCreateMovement(): bool
    {
        $identity = Factory::getApplication()->getIdentity();

        return $identity->authorise('core.manage', 'com_xdecaroinventory')
            && $identity->authorise('inventory.movements.create', 'com_xdecaroinventory');
    }
}
