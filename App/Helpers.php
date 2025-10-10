<?php

namespace PdPluginManager;

if (!defined('ABSPATH')) exit;

class Helpers
{
    public static function getPluginsList(){
        if (!function_exists('get_plugins')) {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $myPlugins = [
            [
                'slug' => 'pd-activity-log',
                'name' => 'PD Activity Log',
                'description' => 'Plugin to track and log various activities and changes within the WordPress site.',
                'repo' => 'https://github.com/Premium-Digital/pd-activity-log/releases/latest/download/pd-activity-log.zip'
            ],
            [
                'slug' => 'pd-extra-widgets',
                'name' => 'PD Extra Widgets',
                'description' => 'A collection of additional widgets to enhance your WordPress site functionality.',
                'repo' => 'https://github.com/Premium-Digital/pd-extra-widgets/releases/latest/download/pd-extra-widgets.zip'
            ],
            [
                'slug' => 'pd-seo-optimizer',
                'name' => 'PD Seo Optimizer',
                'description' => 'SEO optimization plugin to improve website visibility and search engine rankings.',
                'repo' => 'https://github.com/Premium-Digital/pd-seo-optimizer/releases/latest/download/pd-seo-optimizer.zip'
            ],
            [
                'slug' => 'pd-shortcodes',
                'name' => 'PD Shortcodes',
                'description' => 'A plugin that provides a variety of shortcodes to enhance content creation in WordPress.',
                'repo' => 'https://github.com/Premium-Digital/pd-shortcodes/releases/latest/download/pd-shortcodes.zip'
            ],
        ];

        $allPlugins = get_plugins();
        $pluginsList = [];

        foreach ($myPlugins as $plugin) {
            $pluginFile = $plugin['slug'].'/'.$plugin['slug'].'.php';
            $installed = isset($allPlugins[$pluginFile]) || file_exists(WP_PLUGIN_DIR.'/'.$pluginFile);
            $active = $installed ? is_plugin_active($pluginFile) : false;

            if ($installed) {
                $plugin_data = get_plugin_data(WP_PLUGIN_DIR.'/'.$pluginFile);
                $pluginsList[] = array_merge($plugin, [
                    'installed' => true,
                    'active' => $active,
                    'name' => $plugin_data['Name'],
                    'description' => preg_replace('/<cite>.*?<\/cite>/', '', $plugin_data['Description']),
                    'version' => $plugin_data['Version'],
                    'plugin_file' => $pluginFile,
                ]);
            } else {
                $pluginsList[] = array_merge($plugin, [
                    'installed' => false,
                    'active' => false,
                ]);
            }
        }
        return $pluginsList;
    }

    public static function getPluginPathFromSlug( $slug ) {
    	$plugins = get_plugins();

    	if ( strstr( $slug, '/' ) ) {
    		// The slug is already a plugin path.
    		return $slug;
    	}

    	foreach ( $plugins as $pluginPath => $data ) {
    		$pathParts = explode( '/', $pluginPath );
    		if ( $pathParts[0] === $slug ) {
    			return WP_PLUGIN_DIR.'/'.$pluginPath;
    		}
    	}

    	return false;
    }
}