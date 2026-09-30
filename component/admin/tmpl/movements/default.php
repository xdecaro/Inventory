<?php
defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
?>
<form action="<?php echo Route::_('index.php?option=com_xdecaroinventory&view=movements'); ?>" method="post" name="adminForm" id="adminForm">
    <?php echo LayoutHelper::render('joomla.searchtools.default', ['view' => $this]); ?>
    <div class="xdecaroinventory-table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th><?php echo Text::_('COM_XDECAROINVENTORY_FIELD_ITEM'); ?></th>
                    <th><?php echo Text::_('COM_XDECAROINVENTORY_FIELD_DELTA'); ?></th>
                    <th><?php echo Text::_('COM_XDECAROINVENTORY_FIELD_REASON'); ?></th>
                    <th class="d-none d-md-table-cell"><?php echo Text::_('COM_XDECAROINVENTORY_FIELD_NOTE'); ?></th>
                    <th><?php echo Text::_('JDATE'); ?></th>
                    <th class="d-none d-lg-table-cell"><?php echo Text::_('COM_XDECAROINVENTORY_FIELD_USER'); ?></th>
                </tr>
            </thead>
            <tbody>
            <?php if (!$this->items) : ?>
                <tr><td colspan="6" class="text-center text-muted py-4"><?php echo Text::_('COM_XDECAROINVENTORY_NO_MOVEMENTS'); ?></td></tr>
            <?php else : ?>
                <?php foreach ($this->items as $item) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars((string) $item->item_name, ENT_QUOTES, 'UTF-8'); ?><?php if ($item->item_sku) : ?><small class="d-block text-muted"><?php echo htmlspecialchars((string) $item->item_sku, ENT_QUOTES, 'UTF-8'); ?></small><?php endif; ?></td>
                        <td><span class="badge <?php echo (float) $item->quantity_delta >= 0 ? 'bg-success' : 'bg-danger'; ?>"><?php echo htmlspecialchars((string) $item->quantity_delta, ENT_QUOTES, 'UTF-8'); ?></span></td>
                        <td><?php echo htmlspecialchars((string) $item->reason, ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="d-none d-md-table-cell"><?php echo htmlspecialchars((string) ($item->note ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo HTMLHelper::_('date', $item->created, Text::_('DATE_FORMAT_LC4')); ?></td>
                        <td class="d-none d-lg-table-cell"><?php echo htmlspecialchars((string) ($item->user_name ?: ('#' . (int) $item->created_by)), ENT_QUOTES, 'UTF-8'); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if ($this->pagination->total > 0) : ?><?php echo $this->pagination->getListFooter(); ?><?php endif; ?>
    <input type="hidden" name="task" value="">
    <?php echo HTMLHelper::_('form.token'); ?>
</form>
