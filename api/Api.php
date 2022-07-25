<?php
namespace VAPP\Api;

use WP_REST_Controller;
use VAPP\Api\Admin\Settings_Route;
include_once( plugin_dir_path(__FILE__) . 'Admin/Settings_Route.php');

/**
 * Rest API Handler
 */
class Api extends WP_REST_Controller {

    /**
     * Construct Function
     */
    public function __construct() {
        add_action( 'rest_api_init', [ $this, 'register_routes' ] );
    }

    /**
     * Register API routes
     */
    public function register_routes() {
        ( new Settings_Route() )->register_routes();
    }

}