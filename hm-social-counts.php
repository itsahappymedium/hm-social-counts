<?php
/*
Plugin Name: HM Social Counts
Version: 1.0
Description: Gather share counts from various social networks
Author: Happy Medium
Author URI: https://itsahappymedium.com
Plugin URI: https://itsahappymedium.com
Text Domain: hm-social-counts
Domain Path: /languages
*/

namespace HMSC;

class Social_Counts {
    /**
     * Length in seconds of expiration of transient
     * @var int
     */
    var $expiration;

    public function __construct() {
        // One hour by default
        $this->expiration = 1 * HOUR_IN_SECONDS;
    }

    /**
     * Initialize the class once (below)
     * @return void
     */
    public function initialize() {
        $this->load_files();
    }

    /**
     * Load the required files for the plugin
     * @return void
     */
    private function load_files() {
        require_once 'includes/lib/class-base-social-network.php';
        require_once 'includes/lib/class-facebook.php';
        require_once 'includes/lib/class-twitter.php';
        require_once 'includes/lib/class-pinterest.php';
        require_once 'includes/lib/class-linkedin.php';
        require_once 'includes/class-frontend.php';
        require_once 'includes/class-shortcodes.php';
        require_once 'includes/functions.php';
    }

    /**
     * Get total counts for a url
     * @param  string $url URL
     * @return array       Total and per-network counts
     */
    public function get_total_counts( $url ) {
        // Set the variables we'll be using
        $total_count = 0;
        $counts = array();

        // Sanitize the URL passed in by the user
        $url = esc_url( $url );

        // Set a key to either save or retrieve this count from the transient cache
        $key = 'hmsc-' . md5($url);

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
            set_transient( $key, $counts, $this->expiration );
        }

        return $counts;
    }
}

/**
 * Contain the HMSC class in a function call so it can be referenced later if needed
 * @return object Instantiated Social Counts class
 */
function hmsc() {
    global $hmsc;

    if ( ! isset($hmsc) ) {
        $hmsc = new Social_Counts();

        $hmsc->initialize();
    }

    return $hmsc;
}

// Initialize
hmsc();
