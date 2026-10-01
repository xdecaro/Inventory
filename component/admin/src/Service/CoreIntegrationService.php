<?php
namespace xdecaro\Component\Inventory\Administrator\Service;
defined('_JEXEC') or die;

use Joomla\CMS\Extension\ExtensionHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\WebAsset\WebAssetManager;

final class CoreIntegrationService
{
    public const COMPONENT = 'com_xdecaroinventory';
    public const MINIMUM_CORE = '1.1.0';

    public function getVersion(): string
    {
        if (class_exists(\xdecaro\Core\Version::class)) {
            return trim((string) \xdecaro\Core\Version::VERSION);
        }

        $candidates = [
            ['xdecaro/core', 'library'],
            ['com_xdecarocore', 'component'],
            ['pkg_xdecarocore', 'package'],
            ['pkg_core', 'package'],
        ];

        foreach ($candidates as [$element, $type]) {
            try {
                $record = ExtensionHelper::getExtensionRecord($element, $type);
                if (!$record) {
                    continue;
                }

                $manifest = json_decode((string) ($record->manifest_cache ?? ''), true);
                $version = is_array($manifest) ? trim((string) ($manifest['version'] ?? '')) : '';
                if ($version !== '') {
                    return $version;
                }
            } catch (\Throwable) {
            }
        }

        return '';
    }

    public function isReferenceApiAvailable(): bool
    {
        return class_exists(\xdecaro\Core\Integration\EntityReference::class)
            && class_exists(\xdecaro\Core\Integration\RelationReference::class);
    }

    public function enableUi(WebAssetManager $webAssets): bool
    {
        $version = $this->getVersion();
        if ($version === '' || version_compare($version, self::MINIMUM_CORE, '<') || !class_exists(\xdecaro\Core\Asset\AssetService::class)) {
            return false;
        }

        try {
            return (new \xdecaro\Core\Asset\AssetService())->useComponents($webAssets);
        } catch (\Throwable) {
            return false;
        }
    }

    public function createEntityReference(int|string $id): object
    {
        if (!$this->isReferenceApiAvailable()) {
            throw new \RuntimeException(Text::_('COM_XDECAROINVENTORY_ERROR_CORE_REFERENCE_UNAVAILABLE'));
        }

        return new \xdecaro\Core\Integration\EntityReference(self::COMPONENT, 'item', $id);
    }
}
