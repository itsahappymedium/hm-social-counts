<?php

namespace HMSC;

/**
 * Frontend functions for our plugin
 */
class Frontend {
    /**
     * Length in seconds of expiration of transient
     * @var int
     */
    static $expiration;

    public function __construct() {
        // One hour by default
        $this->expiration = 1 * HOUR_IN_SECONDS;

        // AJAX event listeners
        add_action( 'wp_ajax_hmsc_get_count', array( $this, 'ajax_get_total_counts' ) );
        add_action( 'wp_ajax_nopriv_hmsc_get_count', array( $this, 'ajax_get_total_counts' ) );
    }

    /**
     * Get total counts for a url
     * @param  string $url URL
     * @return array       Total and per-network counts
     */
    public static function get_total_counts( $url ) {
        // Set the variables we'll be using
        $total_count = 0;
        $counts = array();

        // Sanitize the URL passed in by the user
        $url = esc_url( $url );

        // Set a key to either save or retrieve this count from the transient cache
        $key = 'hmcount-' . md5($url);

        // If the transient is false (not set), proceed to get the counts
        if ( false === ( $counts = get_transient( $key ) ) ) {
            // DRY: Put our social network classes in an array
            $social_networks = array(
                'Facebook',
                'Twitter',
                'Pinterest',
                'LinkedIn',
            );

            // Prep the array where we're going to be storing the per-network responses
            $counts['networks'] = array();

            // Loop through each social network, get the count of each, and
            // increment the total count
            foreach ($social_networks as $network) {
                // Since we're storing the class names in an array and calling
                // them from a global scope variable, we need to prepend the
                // name with our namespace (HMSC)
                $classname = 'HMSC\\' . $network;
                $social_network = new $classname();

                // Fire off the main function for getting a count
                $network_count = (int) $social_network->get_count_for_url($url);

                // Increment the total
                $total_count += $network_count;

                // Store this network's count in the network array
                $counts['networks'][$network] = $network_count;
            }

            // Stick the total count into the results array
            $counts['total'] = $total_count;

            // Set the transient so this doesn't have to get called a bunch
            set_transient( $key, $counts, self::$expiration );
        }

        return $counts;
    }

    /**
     * Get the total counts of a URL over ajax
     * Must pass a valid $_POST['url'] parameter
     *
     * @return void
     */
    public function ajax_get_total_counts() {
        $data = array();
        $count = 0;

        if ( isset($_POST['url']) ) {
            $url = esc_url( $_POST['url'] );
            $counts = $this->get_total_counts( $url );

            // Set count shortcut from the total value
            $count = $counts['total'];

            // Build the response data object
            $data['count'] = $count;

            // Send back the per-network response for possible processing
            $data['networks'] = $counts['networks'];

            // Send a customized message to be inserted into the DOM
            $data['count_message'] = $count . ' ' . _n( 'Share', 'Shares', $count );

            wp_send_json_success( $data );
        } else {
            $data['error'] = 'You must pass a valid URL';

            wp_send_json_error( $data );
        }
    }
}

new Frontend();