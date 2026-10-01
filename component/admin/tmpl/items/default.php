<?php
defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
?>
<form action="<?php echo Route::_('index.php?option=com_xdecaroinventory&view=items'); ?>" method="post" name="adminForm" id="adminForm">
    <?php echo LayoutHelper::render('joomla.searchtools.default', ['view' => $this]); ?>
    <div class="xdecaroinventory-table-wrap">
        <table class="table itemList" id="itemList">
            <thead>
                <tr>
                    <th><?php echo Text::_('COM_XDECAROINVENTORY_FIELD_SKU'); ?></th>
                    <th><?php echo HTMLHelper::_('searchtools.sort', 'COM_XDECAROINVENTORY_FIELD_NAME', 'name', $listDirn, $listOrder); ?></th>
                    <th class="d-none d-md-table-cell"><?php echo Text::_('COM_XDECAROINVENTORY_FIELD_TYPE'); ?></th>
                    <th><?php echo Text::_('COM_XDECAROINVENTORY_FIELD_QUANTITY'); ?></th>
                    <th class="d-none d-lg-table-cell"><?php echo Text::_('COM_XDECAROINVENTORY_FIELD_UNIT'); ?></th>
                    <th><?php echo Text::_('JSTATUS'); ?></th>
                    <th class="d-none d-lg-table-cell"><?php echo Text::_('COM_XDECAROINVENTORY_FIELD_MODIFIED'); ?></th>
                </tr>
            </thead>
            <tbody>
            <?php if (!$this->items) : ?>
                <tr><td colspan="7" class="text-center text-muted py-4"><?php echo Text::_('COM_XDECAROINVENTORY_NO_ITEMS'); ?></td></tr>
            <?php else : ?>
                <?php foreach ($this->items as $item) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars((string) ($item->sku ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php if ($this->canManageItems) : ?><a href="<?php echo Route::_('index.php?option=com_xdecaroinventory&task=item.edit&id=' . (int) $item->id); ?>"><?php echo htmlspecialchars((string) $item->name, ENT_QUOTES, 'UTF-8'); ?></a><?php else : ?><?php echo htmlspecialchars((string) $item->name, ENT_QUOTES, 'UTF-8'); ?><?php endif; ?></td>
                        <td class="d-none d-md-table-cell"><?php echo Text::_('COM_XDECAROINVENTORY_TYPE_' . strtoupper((string) $item->item_type)); ?></td>
                        <td><?php echo htmlspecialchars((string) $item->quantity, ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="d-none d-lg-table-cell"><?php echo htmlspecialchars((string) $item->unit, ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo (int) $item->state === 1 ? Text::_('JPUBLISHED') : Text::_('JUNPUBLISHED'); ?></td>
                        <td class="d-none d-lg-table-cell"><?php $date = $item->modified ?: $item->created; echo $date ? HTMLHelper::_('date', $date, Text::_('DATE_FORMAT_LC4')) : ''; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if ($this->pagination->total > 0) : ?><?php echo $this->pagination->getListFooter(); ?><?php endif; ?>
    <input type="hidden" name="task" value="">
    <input type="hidden" name="boxchecked" value="0">
    <?php echo HTMLHelper::_('form.token'); ?>
</form>
