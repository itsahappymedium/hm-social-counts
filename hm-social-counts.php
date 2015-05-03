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
    public function __construct() {
        $this->load_files();
    }

    private function load_files() {
        require_once 'includes/lib/class-base-social-network.php';
        require_once 'includes/lib/class-facebook.php';
        require_once 'includes/lib/class-twitter.php';
        require_once 'includes/lib/class-pinterest.php';
        require_once 'includes/lib/class-linkedin.php';
        require_once 'includes/class-frontend.php';
        require_once 'includes/functions.php';
    }
}

new Social_Counts();