<?php

namespace HMSC;

class Shortcodes {
    public function __construct() {
        add_shortcode( 'hmsc_count', array( $this, 'hmsc_count' ) );
    }

    /**
     * Print out the HMSC count shortcode
     * @param  array $atts  Attributes passed in
     * @return string       Count of current page
     */
    public function hmsc_count( $atts ) {
        global $post;

        // Set the Post ID for the default URL
        $post_id = $post->ID;

        $a = shortcode_atts( array(
            'url' => get_permalink( $post_id ),
        ), $atts );

        return number_format( get_hmsc_count( $a['url'] ) );
    }
}

new Shortcodes();