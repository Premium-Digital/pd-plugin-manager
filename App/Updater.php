<?php

namespace PdPluginManager;

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

class Updater
{
    public static function init()
    {
        if (!defined('PD_PLUGIN_MANAGER_REPO_URL')) {
            error_log('[PD Plugin Manager] Constant PD_PLUGIN_MANAGER_REPO_URL is not defined.');
            return;
        }

        if (!class_exists(PucFactory::class)) {
            error_log('[PD Plugin Manager] Plugin Update Checker is not available.');
            return;
        }

        $pluginFile = PD_PLUGIN_MANAGER_PLUGIN_DIR_PATH . '/pd-plugin-manager.php';

        $updateChecker = PucFactory::buildUpdateChecker(
            PD_PLUGIN_MANAGER_REPO_URL,
            $pluginFile,
            'pd-plugin-manager'
        );

        $updateChecker->getVcsApi()->enableReleaseAssets();
    }
}