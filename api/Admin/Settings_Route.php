<?php
namespace VAPP\Api\Admin;

use WP_REST_Controller;

class Settings_Route extends WP_REST_Controller  {

    protected $namespace;
    protected $rest_base;

    public function __construct() {
        $this->namespace = 'api/v1';
        $this->rest_base = 'settings';
        $this->external_api = 'external';
        $this->refresh_api = 'external/refresh';
    }

    /**
     * Register Routes
     */
    public function register_routes() {
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base,
            [
                [
                    'methods'             => \WP_REST_Server::READABLE,
                    'permission_callback' => [ $this, 'get_items_permission_check' ],
                    'callback'            => [ $this, 'get_items' ],
                    'args'                => $this->get_collection_params()
                ],
                [
                    'methods'             => \WP_REST_Server::CREATABLE,
                    'permission_callback' => [ $this, 'create_items_permission_check' ],
                    'callback'            => [ $this, 'create_items' ],
                    'args'                => $this->get_endpoint_args_for_item_schema(true )
                ]
            ]
        );
        
        register_rest_route(
            $this->namespace,
            '/' . $this->external_api,
            [
                [
                    'methods'             => \WP_REST_Server::READABLE,
                    'callback'            => [ $this, 'get_api_response' ],
                    'permission_callback' => [ $this, 'get_items_permission_check' ],
                    'args'                => $this->get_collection_params()
                ]
            ]
        );
        
        register_rest_route(
            $this->namespace,
            '/' . $this->refresh_api,
            [
                [
                    'methods'             => \WP_REST_Server::READABLE,
                    'callback'            => [ $this, 'refresh_api_response' ],
                    'permission_callback' => [ $this, 'get_items_permission_check' ],
                    'args'                => $this->get_collection_params()
                ]
            ]
        );
    }

    /**
     * Get items response
     */
    public function get_items( $request ) {
        $_wpnonce = $request->get_header('X-WP-Nonce');
        $verify = wp_verify_nonce($_wpnonce, 'wp_rest');
        
        if($verify){
            $response = get_option( 'test_project_option', true );
            
            return rest_ensure_response( $response );
        }else{
            return array(
                "data" => false,
                "status" => 200,
                "statusText" => 'You are not authorized to perform this action. Please verify App nonce.'
            );
        }

    }
    

    /**
     * Get API response
     */
    public function get_api_response( $request ) {
        $_wpnonce = $request->get_header('X-WP-Nonce');
        $verify = wp_verify_nonce($_wpnonce, 'wp_rest');
        
        if($verify){
            $external_api = get_transient( 'external_api' );
            if( $external_api ){
                return $external_api;
            }else{
                $response = wp_remote_get( 'https://miusage.com/v1/challenge/2/static/' );
                if($response['response']['code'] === 200){
                    $exp_time = HOUR_IN_SECONDS;
                    $api_data = wp_remote_retrieve_body( $response );
                    set_transient( 'external_api', $api_data, $exp_time );
                    return $api_data;
                }
            }
        }else{
            return array(
                "data" => false,
                "status" => 200,
                "statusText" => 'You are not authorized to perform this action. Please verify App nonce.'
            );
        }
    }
    
    public function refresh_api_response( $request ) {
        $_wpnonce = $request->get_header('X-WP-Nonce');
        $verify = wp_verify_nonce($_wpnonce, 'wp_rest');
        
        if($verify){
            delete_transient( 'external_api' );
            
            $response = wp_remote_get( 'https://miusage.com/v1/challenge/2/static/' );
            if($response['response']['code'] === 200){
                $exp_time = HOUR_IN_SECONDS;
                $api_data = wp_remote_retrieve_body( $response );
                set_transient( 'external_api', $api_data, $exp_time );
                return $api_data;
            }
        }else{
            return array(
                "data" => false,
                "status" => 200,
                "statusText" => 'You are not authorized to perform this action. Please verify App nonce.'
            );
        }
    }

    /**
     * Get items permission check
     */
    public function get_items_permission_check( $request ) {
        if( current_user_can( 'administrator' ) ) {
            return true;
        }

        return true;
    }

    /**
     * Create item response
     */
    public function create_items( $request ) {
        $_wpnonce = $request->get_header('X-WP-Nonce');
        $verify = wp_verify_nonce($_wpnonce, 'wp_rest');

        if(!$verify){
            return array(
                "data" => false,
                "status" => 200,
                "statusText" => 'You are not authorized to perform this action. Please verify App nonce.'
            );
        }

        // Data validation
        $numrows = NULL;
        if(isset( $request['numrows'] )):
            $numrows = filter_var(
                $request['numrows'], 
                FILTER_VALIDATE_INT, 
                array(
                    'options' => array(
                        'min_range' => 1, 
                        'max_range' => 5
                    )
                )
            );

            if(!numrows):
                return array(
                    "data" => false,
                    "status" => 200,
                    "statusText" => 'Numrows should be between 1 and 5'
                );
            endif;
        else:
            return array(
                    "data" => false,
                    "statusText" => 'Please provide `numrows` setting name.'
                );
        endif;

        $humandate  = NULL;
        if(isset( $request['humandate'] )):
            $humandate  = is_bool($request['humandate']);
            if(!$humandate):
                return array(
                    "data" => false,
                    "statusText" => 'Human Date should be true or false.' 
                );
            else:
                $humandate  = $request['humandate'];
            endif;
        else:
            return array(
                    "data" => false, 
                    "statusText" => 'Please provide `humandate` setting name.' 
                );
        endif;
        
        $emails  = NULL;
        if(isset( $request['emails'] ) && is_email( $request['emails'])):
            $emails  = sanitize_email( $request['emails'] );
        else:
            return array(
                "data" => false, 
                "statusText" => 'Please provide `emails` setting name OR provide valid email address' 
            );
        endif;

        // Save option data into WordPress
        $test_project_option = array(
            'numrows' => $numrows,
            'humandate' => $humandate,
            'emails' => $emails
        );

        return update_option( 'test_project_option', $test_project_option );        
    }

    /**
     * Create item permission check
     */
    public function create_items_permission_check( $request ) {
        if( current_user_can( 'administrator' ) ) {
            return true;
        }

        return true;
    }

    /**
     * Retrives the query parameters for the items collection
     */
    public function get_collection_params() {
        return [];
    }

}