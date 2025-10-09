<?php

namespace PdPluginManager;

use PdPluginManager\Helpers;

class Settings
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'addAdminMenu']);
        //add_action('admin_init', [$this, 'registerSettings']);
    }

    public function addAdminMenu() {
        add_menu_page(
            'PD Plugin Manager',          
            'PD Plugin Manager',          
            'manage_options',             
            'pd-plugin-manager-settings', 
            [$this, 'renderSettingsPage'],
            'dashicons-admin-plugins',    
            80                           
        );
    }

    public function renderSettingsPage()
    {
        $plugins = Helpers::getPluginsList();
        $template = PD_PLUGIN_MANAGER_PLUGIN_DIR_PATH . '/templates/plugins-list.php';
        if (file_exists($template)) {
            include $template;
        }
    }
}