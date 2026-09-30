<?php
declare(strict_types=1);
$s=file_get_contents(__DIR__.'/../component/admin/src/Service/CoreIntegrationService.php');if($s===false)exit(1);
foreach(['xdecaro\\Core\\Integration\\EntityReference','xdecaro\\Core\\Integration\\RelationReference','xdecaro\\Core\\Asset\\AssetService','com_xdecaroinventory'] as $n){if(!str_contains($s,$n)){fwrite(STDERR,"Missing Core marker: $n\n");exit(1);}}
foreach(['ExtensionHelper::getExtensionRecord','manifest_cache','xdecaro/core','com_xdecarocore','pkg_xdecarocore','pkg_core'] as $n){if(!str_contains($s,$n)){fwrite(STDERR,"Missing Core diagnostic fallback: $n\n");exit(1);}}
if(!str_contains($s,'COM_XDECAROINVENTORY_ERROR_CORE_REFERENCE_UNAVAILABLE')){fwrite(STDERR,"Translated Core reference error missing\n");exit(1);}
echo "Core integration smoke: OK\n";
