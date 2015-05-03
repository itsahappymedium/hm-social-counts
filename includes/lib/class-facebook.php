<?php

namespace HMSC;

class Facebook extends Base_Social_Network {
    public function __construct() {
        $this->base_url = 'https://graph.facebook.com/fql?q=SELECT%20like_count,%20total_count,%20share_count,%20click_count,%20comment_count%20FROM%20link_stat%20WHERE%20url%20=%20%22';

        parent::__construct();
    }

    public function build_count_url( $url ) {
        return $this->base_url . $url . '%22';
    }

    public function get_count_from_response( $response ) {
        $count = 0;

        if ( $response['response']['message'] === 'OK' ) {
            $body = json_decode($response['body']);

            $count = $body->data[0]->total_count;
        }

        return $count;
    }
}
