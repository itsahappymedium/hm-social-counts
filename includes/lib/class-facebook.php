<?php

namespace HMSC;

use Facebook\Facebook as FB;
use Facebook\FacebookRequest;
use Facebook\FacebookApp;

class Facebook extends Base_Social_Network {
    public function __construct() {
        parent::__construct();
    }

    public function _get_count_for_url( $url ) {
        $app_id = '631909236894818';
        $app_secret = 'd64ea53dad2de6b8014f322e84a3ad74';
        $token = "$app_id|$app_secret";

        $fb = new FB([
          'app_id' => $app_id,
          'app_secret' => $app_secret,
          'default_graph_version' => 'v2.5',
        ]);

        $fb->setDefaultAccessToken("$app_id|$app_secret");

        try {
            $response = $fb->get('/?id=' . $url);

            $node = $response->getGraphNode();

            return (int) $node['share']['share_count'];
        } catch ( \Exception $e ) {
            return $e;
        }
    }
}
