<?php

namespace HMSC;

class Pinterest extends Base_Social_Network {
    public function __construct() {
        $this->base_url = 'http://api.pinterest.com/v1/urls/count.json?url=';

        parent::__construct();
    }

    public function get_count_from_response( $response ) {
        $count = 0;

        if ( $response['response']['message'] === 'OK' ) {
            $data = preg_replace('/^receiveCount\((.*)\)$/', "\\1", $response['body']);
            $body = json_decode($data);

            $count = $body->count;
        }

        return $count;
    }
}
