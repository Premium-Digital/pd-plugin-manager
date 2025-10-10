<?php

namespace PdPluginManager;

class Actions
{
    public function __construct()
    {
        add_action( 'wp_enqueue_scripts', array( $this, 'registerStylesAndScripts' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'registerAdminStylesAndScripts' ));
        add_action( 'wp_ajax_pd_plugin_action', array( $this, 'handlePluginAction' ) );
        add_action( 'wp_ajax_nopriv_pd_plugin_action', array( $this, 'handlePluginAction' ) );
    }

    public function registerStylesAndScripts()
    {
        //styles
        wp_enqueue_style( 'pd-plugin-manager-styles', PD_PLUGIN_MANAGER_PLUGIN_DIR_URL . 'dist/front.css' );

        //scripts
        wp_enqueue_script( 'pd-plugin-manager-scripts', PD_PLUGIN_MANAGER_PLUGIN_DIR_URL . 'dist/front.js', array(), null, true );
    }

    public function registerAdminStylesAndScripts()
    {
        //styles
        wp_enqueue_style( 'pd-plugin-manager-admin-styles', PD_PLUGIN_MANAGER_PLUGIN_DIR_URL . 'dist/admin.css' );

        //scripts
        wp_enqueue_script('jquery');
        wp_enqueue_media();
        wp_enqueue_script( 'pd-plugin-manager-admin-scripts', PD_PLUGIN_MANAGER_PLUGIN_DIR_URL . 'dist/admin.js', array('jquery'), null, true );

        wp_localize_script('pd-plugin-manager-admin-scripts', 'PDPluginManager', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('pd_plugin_manager_nonce')
        ]);
    }
    
    public function handlePluginAction()
    {
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Brak uprawnień');
        }

        check_ajax_referer('pd_plugin_manager_nonce', 'nonce');

        $action = sanitize_text_field($_POST['plugin_action'] ?? '');
        $pluginRepoUrl = esc_url_raw($_POST['plugin_repo'] ?? '');
        $pluginFile = sanitize_text_field($_POST['plugin_file'] ?? '');

        if (!$action) {
            wp_send_json_error('Niepoprawne dane');
        }

        include_once ABSPATH . 'wp-admin/includes/plugin.php';
        include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

        $skin = new \WP_Ajax_Upgrader_Skin();
        $upgrader = new \Plugin_Upgrader($skin);
        $wpFileSystem = new \WP_Filesystem_Base();
 
        switch ($action) {
            case 'install':
                $zip_url = esc_url_raw($pluginRepoUrl ?? '');
                if ($zip_url) {
                    $result = $upgrader->install($zip_url);
                }
                if (is_wp_error($result)) {
                        wp_send_json_error([
                            'message' => $result->get_error_message()
                        ]);
                }
                $status = "installed";
                break;

            case 'activate':
                if (file_exists(WP_PLUGIN_DIR . '/' . $pluginFile)) {
                    activate_plugin($pluginFile);
                    $status = "activated";
                }
                break;

            case 'deactivate':
                if (is_plugin_active($pluginFile)) {
                    deactivate_plugins($pluginFile);
                    $status = "deactived";
                }
                break;

            case 'uninstall':
                $plugin_dir_path = WP_PLUGIN_DIR . '/' . dirname($pluginFile);
                if (file_exists(WP_PLUGIN_DIR . '/' . $pluginFile)) {
                    delete_plugins([$pluginFile]);
                    $status = "uninstalled";
                }
                if (is_dir($plugin_dir_path)) {
                    $wpFileSystem->rmdir($plugin_dir_path, true);
                }
                break;

            default:
                wp_send_json_error('Nieznana akcja');
        }

        wp_send_json_success(['status'=> $status, 'action' => $action]);
    }
}