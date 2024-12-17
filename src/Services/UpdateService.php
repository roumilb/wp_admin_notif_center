<?php

namespace WANC\Services;

use WANC\Core\WancSettings;
use WANC\Repositories\NoticeRepository;
use WANC\Database;

class UpdateService
{
    public function update()
    {
        if(!function_exists('get_plugin_data')){
            require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        }
        $wancSettings = new WancSettings();
        $versionDatabase = $wancSettings->getOption('wanc_version', '0.0.0');
        $pluginData = \get_plugin_data(WP_PLUGIN_DIR.DIRECTORY_SEPARATOR.'wp-admin-notification-center'.DIRECTORY_SEPARATOR.'index.php');
        $currentVersion = $pluginData['Version'];

        if (version_compare($versionDatabase, $currentVersion, '>=')) {
            return;
        }

        if (version_compare($versionDatabase, '2.3.3', '<')) {
            $noticeRepository = new NoticeRepository();
            $noticeRepository->createDatabaseTable();
        }

        $wancSettings->updateOption('wanc_version', $currentVersion);
    }
}