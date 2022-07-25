<?php
// namespace VAPP\Includes;

class Frontend {

    public function __construct() {
        add_shortcode( 'vapp-app', [ $this, 'render_frontend' ] );
    }

    /**
     * Render Frontend
     * @since 1.0.0
     */
    public function render_frontend( $atts, $content = '' ) {
        wp_enqueue_style( 'vapp-frontend' );
        wp_enqueue_script( 'vapp-frontend' );

        $content .= '<div id="vapp-frontend-app"></div>';

        return $content;
    }

}