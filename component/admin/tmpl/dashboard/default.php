<?php
defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

$summary = $this->summary;
$scope = $this->coreUiActive ? 'xdecaro-scope ' : '';
?>
<div class="<?php echo $scope; ?>xdecaroinventory-dashboard">
    <?php if ($this->canManageItems || $this->canCreateMovement) : ?>
    <div class="xdecaroinventory-actions">
        <?php if ($this->canManageItems) : ?><a class="btn btn-primary" href="<?php echo Route::_('index.php?option=com_xdecaroinventory&task=item.add'); ?>"><?php echo Text::_('COM_XDECAROINVENTORY_NEW_ITEM'); ?></a><?php endif; ?>
        <?php if ($this->canCreateMovement) : ?><a class="btn btn-outline-primary" href="<?php echo Route::_('index.php?option=com_xdecaroinventory&task=movement.add'); ?>"><?php echo Text::_('COM_XDECAROINVENTORY_NEW_MOVEMENT'); ?></a><?php endif; ?>
    </div>
    <?php endif; ?>
    <div class="xdecaroinventory-kpis">
        <article class="xdecaroinventory-kpi"><span><?php echo Text::_('COM_XDECAROINVENTORY_TOTAL_ITEMS'); ?></span><strong><?php echo (int) $summary['total_items']; ?></strong></article>
        <article class="xdecaroinventory-kpi"><span><?php echo Text::_('COM_XDECAROINVENTORY_ACTIVE_ITEMS'); ?></span><strong><?php echo (int) $summary['active_items']; ?></strong></article>
        <article class="xdecaroinventory-kpi"><span><?php echo Text::_('COM_XDECAROINVENTORY_TOTAL_QUANTITY'); ?></span><strong><?php echo htmlspecialchars((string) $summary['total_quantity'], ENT_QUOTES, 'UTF-8'); ?></strong></article>
    </div>
    <section class="card xdecaroinventory-panel">
        <div class="card-body">
            <div class="xdecaroinventory-panel-head">
                <h2><?php echo Text::_('COM_XDECAROINVENTORY_RECENT_MOVEMENTS'); ?></h2>
                <a href="<?php echo Route::_('index.php?option=com_xdecaroinventory&view=movements'); ?>"><?php echo Text::_('COM_XDECAROINVENTORY_VIEW_ALL'); ?></a>
            </div>
            <?php if (!$summary['recent_movements']) : ?>
                <p class="text-muted"><?php echo Text::_('COM_XDECAROINVENTORY_NO_MOVEMENTS'); ?></p>
            <?php else : ?>
                <div class="xdecaroinventory-recent">
                    <?php foreach ($summary['recent_movements'] as $movement) : ?>
                        <div class="xdecaroinventory-recent-row">
                            <div><strong><?php echo htmlspecialchars((string) $movement->item_name, ENT_QUOTES, 'UTF-8'); ?></strong><small><?php echo htmlspecialchars((string) $movement->reason, ENT_QUOTES, 'UTF-8'); ?></small></div>
                            <span class="badge <?php echo (float) $movement->quantity_delta >= 0 ? 'bg-success' : 'bg-danger'; ?>"><?php echo htmlspecialchars((string) $movement->quantity_delta, ENT_QUOTES, 'UTF-8'); ?></span>
                            <time><?php echo HTMLHelper::_('date', $movement->created, Text::_('DATE_FORMAT_LC4')); ?></time>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <dl class="xdecaroinventory-diagnostics">
        <dt><?php echo Text::_('COM_XDECAROINVENTORY_VERSION'); ?></dt>
        <dd><?php echo $this->componentVersion !== '' ? htmlspecialchars($this->componentVersion, ENT_QUOTES, 'UTF-8') : Text::_('COM_XDECAROINVENTORY_CORE_MISSING'); ?></dd>
        <dt><?php echo Text::_('COM_XDECAROINVENTORY_CORE_VERSION'); ?></dt>
        <dd><?php echo $this->coreVersion !== '' ? htmlspecialchars($this->coreVersion, ENT_QUOTES, 'UTF-8') : Text::_('COM_XDECAROINVENTORY_CORE_MISSING'); ?></dd>
    </dl>
</div>
