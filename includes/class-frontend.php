<?php

namespace HMSC;

/**
 * Frontend functions for our plugin
 */
class Frontend {
    public function __construct() {
        // AJAX event listeners
        add_action( 'wp_ajax_hmsc_get_count', array( $this, 'ajax_get_total_counts' ) );
        add_action( 'wp_ajax_nopriv_hmsc_get_count', array( $this, 'ajax_get_total_counts' ) );
    }

    /**
     * Get the total counts of a URL over ajax
     * Must pass a valid $_POST['postId'] parameter
     *
     * @return void
     */
    public function ajax_get_total_counts() {
        $data = array();
        $count = 0;

        if ( isset($_POST['postId']) ) {
            // Grab the post ID
            $post_id = (int) $_POST['postId'];

            // Get URL from that post ID
            $url = get_permalink( $post_id );

            if ( ! $url ) {
                $data['error'] = 'Post ID (' . $post_id . ') could not be found.';

                wp_send_json_error( $data );
            }

            $counts = hmsc()->get_total_counts( $url );

            // Set count shortcut from the total value
            $count = $counts['total'];

            // Build the response data object
            $data['count'] = $count;

            // Send back the per-network response for possible processing
            $data['networks'] = $counts['networks'];

            // Send a customized message to be inserted into the DOM
            $data['count_message'] = number_format($count) . ' ' . _n( 'Share', 'Shares', $count );

            wp_send_json_success( $data );
        } else {
            $data['error'] = 'You must pass a valid URL';

            wp_send_json_error( $data );
        }
    }
}

new Frontend();