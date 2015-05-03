<?php

namespace HMSC;

/**
 * The Base Social Network class for getting social counts
 */
class Base_Social_Network {
    var $base_url;

    public function __construct() {
        // Set the base_url
    }

    /**
     * Builds the base count URL
     * @param  string $url URL
     * @return string      Decorated URL
     */
    public function build_count_url( $url ) {
        return $this->base_url . esc_url($url);
    }

    /**
     * Get the count for a particular URL
     * @param  string $url URL
     * @return int         Count
     */
    public function get_count_for_url( $url ) {
        $count = 0;

        $count_url = $this->build_count_url($url);

        // Get the response
        $response = wp_remote_get( $count_url, $args );

        if ( is_array($response) ) {
            $count = (int) $this->get_count_from_response( $response );
        }

        return $count;
    }

    /**
     * Process a response and return a count
     * @param  array $response Response from a network
     * @return int             Count
     */
    public function get_count_from_response( $response ) {
        // By default, return response
        return $response;
    }
}