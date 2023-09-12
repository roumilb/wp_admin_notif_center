<?php

namespace WANC\Services;

use WANC\Core\WancSettings;
use WANC\Debug;
use WANC\Repositories\NoticeRepository;

class UpdateService
{
    public function update()
    {
        $wancSettings = new WancSettings();
        $versionDatabase = $wancSettings->getOption('wanc_version', '0.0.0');
        $pluginData = get_plugin_data(WP_PLUGIN_DIR.DIRECTORY_SEPARATOR.'wp-admin-notification-center'.DIRECTORY_SEPARATOR.'index.php');
        $currentVersion = $pluginData['Version'];

        if (version_compare($versionDatabase, $currentVersion, '>=')) {
            return;
        }

        if (version_compare($versionDatabase, '2.3.2', '<')) {
            $noticeRepository = new NoticeRepository();
            $noticeRepository->createDatabaseTable();
        }

        $wancSettings->updateOption('wanc_version', $currentVersion);
    }
}