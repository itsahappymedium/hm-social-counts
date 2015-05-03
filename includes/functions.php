<?php

// HMSC Functions

/**
 * Get the count for a url
 * @param  string $url URL (optional)
 * @return int         Count
 */
function get_hmsc_count( $url='' ) {
    $count = 0;

    if ( empty($url) ) {
        $url = get_permalink();
    }

    $counts = HMSC\Frontend::get_total_counts( $url );

    if ( isset($counts['total']) ) {
        $count = $counts['total'];
    }

    return $count;
}

/**
 * Print out the count for a url
 * @param  string $url URL (optional)
 * @return void
 */
function the_hmsc_count( $url='' ) {
    if ( empty($url) ) {
        $url = get_permalink();
    }

    $count = get_hmsc_count( $url );

    echo $count;
}