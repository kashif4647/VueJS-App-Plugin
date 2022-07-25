<?php
/**
 * Plugin Name: VueJS App
 * Description: VueJS App
 * Version: 1.0.0
 * Text-Domain: vuejsapp
 */

if( ! defined( 'ABSPATH' ) ) exit(); // No direct access allowed

/**
 * Require Autoloader
 */
require_once 'vendor/autoload.php';

use VAPP\Api\Api;
// use VAPP\Includes\Admin;
include_once( plugin_dir_path(__FILE__) . 'api/Api.php');
include_once( plugin_dir_path(__FILE__) . 'includes/Admin.php');
include_once( plugin_dir_path(__FILE__) . 'includes/Frontend.php');
// use VAPP\Includes\Frontend;

final class VUE_APP {

    /**
     * Define Plugin Version
     */
    const VERSION = '1.0.0';

    /**
     * Construct Function
     */
    public function __construct() {
        $this->plugin_constants();
        register_activation_hook( __FILE__, [ $this, 'activate' ] );
        register_deactivation_hook( __FILE__, [ $this, 'deactivate' ] );
        add_action( 'plugins_loaded', [ $this, 'init_plugin' ] );
    }

    /**
     * Plugin Constants
     * @since 1.0.0
     */
    public function plugin_constants() {
        define( 'VAPP_VERSION', self::VERSION );
        define( 'VAPP_PLUGIN_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
        define( 'VAPP_PLUGIN_URL', trailingslashit( plugins_url( '', __FILE__ ) ) );
    }

    /**
     * Singletone Instance
     * @since 1.0.0
     */
    public static function init() {
        static $instance = false;

        if( !$instance ) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * On Plugin Activation
     * @since 1.0.0
     */
    public function activate() {
        $is_installed = get_option( 'vapp_is_installed' );

        if( ! $is_installed ) {
            update_option( 'vapp_is_installed', time() );
        }

        $response = get_option( 'test_project_option', true );
        if(empty($response)){
            $test_project_option = array(
                'numrows' => 5,
                'humandate' => 1,
                'emails' => get_option('admin_email')
            );
    
            update_option( 'test_project_option', $test_project_option );
        }

        update_option( 'vapp_is_installed', VAPP_VERSION );
    }

    /**
     * On Plugin De-actiavtion
     * @since 1.0.0
     */
    public function deactivate() {
        // On plugin deactivation
    }

    /**
     * Init Plugin
     * @since 1.0.0
     */
    public function init_plugin() {
        // init
        new Admin();
        new Frontend();
        new Api();
    }

}

/**
 * Initialize Main Plugin
 * @since 1.0.0
 */
function VUE_APP() {
    return VUE_APP::init();
}

// Run the Plugin
VUE_APP();