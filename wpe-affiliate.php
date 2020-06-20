<?php
/**
 * Plugin Name: WPEasy Affiliate
 * Plugin URI:
 * Description: UI for Affiliates to generate HTML links and Shortcodes for WPEasy Adverts
 * Version: 1.0.10
 * Author: Alan Blair
 * Author URI:
 * Text Domain: wpeasy
 */

namespace WPEasyAffiliatePlugin;
use WPEasyLibrary\WordPress\UpdateFromGithubController;
use WPEasyLibrary\WordPress\WPEasyApplication;

require_once __DIR__ . '/vendor/autoload.php';

if ( is_admin() ) {
    new UpdateFromGithubController( __FILE__, 'wpeasy', "wpe-affiliate" );

}
$config = require __DIR__ . '/application.config.php';
WPEasyApplication::init($config);
WPEasyApplication::registerLoadedPlugin($config);
