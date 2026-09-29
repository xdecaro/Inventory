<?php
namespace xdecaro\Component\Inventory\Administrator\View\Dashboard;
defined('_JEXEC') or die;

use Joomla\CMS\Extension\ExtensionHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
use xdecaro\Component\Inventory\Administrator\Service\CoreIntegrationService;

final class HtmlView extends BaseHtmlView
{
    public bool $coreUiActive = false;
    public string $coreVersion = '';
    public string $componentVersion = '';
    public array $summary = [];

    public function display($tpl = null): void
    {
        $app = Factory::getApplication();

        if (!$app->getIdentity()->authorise('core.manage', 'com_xdecaroinventory')) {
            throw new \RuntimeException(Text::_('JERROR_ALERTNOAUTHOR'), 403);
        }

        $this->summary = $this->getModel()->getSummary();
        $this->componentVersion = $this->getInstalledVersion();

        ToolbarHelper::title(Text::_('COM_XDECAROINVENTORY'), 'archive');

        $wa = $app->getDocument()->getWebAssetManager();
        $wa->getRegistry()->addExtensionRegistryFile('com_xdecaroinventory');

        try {
            $core = Factory::getContainer()->get(CoreIntegrationService::class);
            $this->coreVersion = $core->getVersion();
            $this->coreUiActive = $core->enableUi($wa);
        } catch (\Throwable) {
            $this->coreUiActive = false;
        }

        $wa->useStyle('com_xdecaroinventory.admin');
        parent::display($tpl);
    }

    private function getInstalledVersion(): string
    {
        try {
            $record = ExtensionHelper::getExtensionRecord('com_xdecaroinventory', 'component');
            if (!$record) {
                return '';
            }

            $manifest = json_decode((string) ($record->manifest_cache ?? ''), true);
            return is_array($manifest) ? trim((string) ($manifest['version'] ?? '')) : '';
        } catch (\Throwable) {
            return '';
        }
    }
}
