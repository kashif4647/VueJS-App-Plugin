<?php
// namespace VAPP\Includes;

class Admin {

    /**
     * Construct Function
     */
    public function __construct() {
        add_action( 'admin_menu', [ $this, 'admin_menu' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'register_scripts_styles' ] );
    }

    public function register_scripts_styles() {
        $this->load_scripts();
        $this->load_styles();
    }

    /**
     * Load Scripts
     *
     * @return void
     */
    public function load_scripts() {
        wp_register_script( 'vapp-manifest', VAPP_PLUGIN_URL . 'assets/js/manifest.js', [], rand(), true );
        wp_register_script( 'vapp-vendor', VAPP_PLUGIN_URL . 'assets/js/vendor.js', [ 'vapp-manifest' ], rand(), true );
        wp_register_script( 'vapp-admin', VAPP_PLUGIN_URL . 'assets/js/admin.js', [ 'vapp-vendor' ], rand(), true );

        wp_enqueue_script( 'vapp-manifest' );
        wp_enqueue_script( 'vapp-vendor' );
        wp_enqueue_script( 'vapp-admin' );
        wp_enqueue_script( 'wpse30583_script' );

        wp_localize_script( 'vapp-admin', 'vappAdminLocalizer', [
            'adminUrl'  => admin_url( '/' ),
            'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
            'apiUrl'    => home_url( '/wp-json' ),
            'VUE_NONCE' => wp_create_nonce( 'wp_rest' ),
        ] );
    }

    public function load_styles() {
        wp_register_style( 'vapp-admin', VAPP_PLUGIN_URL . 'assets/css/admin.css' );

        wp_enqueue_style( 'vapp-admin' );
    }

    /**
     * Register Menu Page
     * @since 1.0.0
     */
    public function admin_menu() {
        global $submenu;

        $capability = 'manage_options';
        $slug       = 'vue-app';

        $hook = add_menu_page(
            __( 'VueJS app', 'vuejsapp' ),
            __( 'VueJS app', 'vuejsapp' ),
            $capability,
            $slug,
            [ $this, 'menu_page_template' ],
            'dashicons-buddicons-replies'
        );

        if( current_user_can( $capability )  ) {
            $submenu[ $slug ][] = [ __( 'Table', 'vuejsapp' ), $capability, 'admin.php?page=' . $slug . '#/' ];
            $submenu[ $slug ][] = [ __( 'Graph', 'vuejsapp' ), $capability, 'admin.php?page=' . $slug . '#/graph' ];
            $submenu[ $slug ][] = [ __( 'Settings', 'vuejsapp' ), $capability, 'admin.php?page=' . $slug . '#/settings' ];
        }
    }

    /**
     * Init Hooks for Admin Pages
     * @since 1.0.0
     */
    public function init_hooks() {
        add_action( 'admin_enqueu_scripts', [ $this, 'enqueue_scripts' ] );
    }

    /**
     * Load Necessary Scripts & Styles
     * @since 1.0.0
     */
    public function enqueue_scripts() {
        wp_enqueue_style( 'vapp-admin' );
        wp_enqueue_script( 'vapp-admin' );
    }

    /**
     * Render Admin Page
     * @since 1.0.0
     */
    public function menu_page_template() {
        echo '<div class="wrap"><div id="vapp-admin-app"></div></div>';
    }

}