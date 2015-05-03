<?php

namespace HMSC;

class Twitter extends Base_Social_Network {
    public function __construct() {
        $this->base_url = 'http://cdn.api.twitter.com/1/urls/count.json?url=';

        parent::__construct();
    }

    public function get_count_from_response( $response ) {
        $count = 0;

        if ( $response['response']['message'] === 'OK' ) {
            $body = json_decode($response['body']);

            $count = $body->count;
        }

        return $count;
    }
}
