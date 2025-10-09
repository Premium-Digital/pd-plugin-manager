<?php

/**
 * Plugin Name: PD Plugin Manager
 * Description: Plugin manager for all PD's plugins.
 * Version: 1.0.1
 * Author: kkarasiewicz
 */

namespace PdPluginManager;
use PdPluginManager\PluginManager;

if (!defined('WPINC')) {
    die;
}

if (!defined('PD_PLUGIN_MANAGER_PLUGIN_DIR_PATH')) {
    define('PD_PLUGIN_MANAGER_PLUGIN_DIR_PATH', plugin_dir_path(__FILE__));
}

if (!defined('PD_PLUGIN_MANAGER_PLUGIN_DIR_URL')) {
    define('PD_PLUGIN_MANAGER_PLUGIN_DIR_URL', plugin_dir_url(__FILE__));
}

if (!defined('PD_PLUGIN_MANAGER_REPO_URL')) {
    define('PD_PLUGIN_MANAGER_REPO_URL', 'https://github.com/Premium-Digital/pd-plugin-manager');
}

require PD_PLUGIN_MANAGER_PLUGIN_DIR_PATH . '/vendor/autoload.php';

class PdPluginManager
{
    public function __construct()
    {
      load_plugin_textdomain('pd-plugin-manager', false, dirname(plugin_basename(__FILE__)) . '/languages');
      new PluginManager();
    }
}

new PdPluginManager();

register_activation_hook(__FILE__, ['PdPluginManager\PluginManager', 'activate']);
register_deactivation_hook(__FILE__, ['PdPluginManager\PluginManager', 'deactivate']);