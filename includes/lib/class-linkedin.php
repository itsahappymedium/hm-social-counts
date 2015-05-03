<?php

namespace HMSC;

class LinkedIn extends Base_Social_Network {
    public function __construct() {
        $this->base_url = 'http://www.linkedin.com/countserv/count/share?url=';

        parent::__construct();
    }

    public function build_count_url( $url ) {
        return $this->base_url . $url . '&format=json';
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
