<?php

namespace PdPluginManager;

use PdPluginManager\Actions;
use PdPluginManager\Settings;
use PdPluginManager\Filters;
use PdPluginManager\Updater;

class PluginManager
{
    public function __construct()
    {
        Updater::init();
        new Actions();
        new Settings();
        new Filters();
        new Notifier();
    }

    public static function activate()
    {
        \flush_rewrite_rules();
    }

    public static function deactivate()
    {
        \flush_rewrite_rules();
    }

}