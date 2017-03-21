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
     * Get the count for any URL
     * @param  string $url URL
     * @return int         Count
     */
    public function _get_count_for_url( $url ) {
        $count_url = $this->build_count_url($url);

        // Get the response
        $response = wp_remote_get( $count_url );

        if ( is_array($response) ) {
            return (int) $this->get_count_from_response( $response );
        }
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
     * Tests: http, https, www (if non-www)
     * @param  string $url URL
     * @return int         Count
     */
    public function get_count_for_url( $url ) {
        $count = 0;

        // Let's debug easier ok?
        if ( strstr($url, '.hmdev') ) {
            $url = str_replace('http://happymedium.hmdev', 'https://itsahappymedium.com', $url);
        }

        if ( strstr($url, 'itsahappyclient') ) {
            $url = str_replace('http://happymedium.itsahappyclient.com', 'https://itsahappymedium.com', $url);
        }

        // Start with original URL
        $count += $this->_get_count_for_url( $url );

        // Get alternate protocol
        $count += $this->get_count_for_url_alt_protocol( $url );

        // If it's non-WWW, get the WWW protocol
        if ( ! strstr($url, 'www.itsahappymedium.com') && ! strstr($url, '.hmdev') ) {
            $count += $this->get_count_for_url_www( $url );
        }

        return $count;
    }

    /**
     * Now, get the count for the HTTP(S) version of the link
     * This is because we've switched back and forth between protocols
     * in the past
     *
     * @param  string $original_url The original URL
     * @return int                  The count for the URL at an alternate protocal
     */
    public function get_count_for_url_alt_protocol( $original_url ) {

        if ( strstr($original_url, 'https') ) {
            $alt_url = preg_replace("/^https:/i", "http:", $original_url);
        } else {
            $alt_url = preg_replace("/^http:/i", "https:", $original_url);
        }

        return $this->_get_count_for_url( $alt_url );
    }

    /**
     * Get the count for a WWW url if it's needed
     * @param  string $original_url Original URL
     * @return int                  Count
     */
    public function get_count_for_url_www( $original_url ) {
        if ( strstr($original_url, 'www.itsahappymedium.com') ) {
            return 0;
        }

        // Strip HTTPS
        $www_url = preg_replace("/^https:/i", "http:", $original_url);

        // Add in WWW
        $www_url = preg_replace("/itsahappymedium.com/i", "www.itsahappymedium.com", $www_url);

        return $this->_get_count_for_url( $www_url );
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
