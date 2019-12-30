<?php
$pluginUrl = plugin_dir_url(__FILE__);

return [
	'isDebug' => defined('WP_DEBUG' && WP_DEBUG === true),
    'pluginSlugPrefix' => basename(__DIR__),
	'pluginName' => 'WPEasy Affiliate',
	'pluginDescription' => 'UI for Affiliates to generate HTML links and Shortcodes for WPEasy Adverts',
	'moduleDir' => __DIR__ . '/src/WPEAffiliate/Modules/',
	'pluginURL' => $pluginUrl,
    'assetsURL' => $pluginUrl . 'assets/',
    'pluginController' => '\WPEAffiliate\Application\Controller\ApplicationController',
    'modules' => [
        '\WPEAffiliate\Modules\AffiliateAdverts\Controller\ModuleController'
    ],
    'adminMenu' =>
        [
            'slug' => 'wpe_affiliates',
            'pageTitle' => 'WP Easy Affiliates',
            'menuTitle' => 'WP Easy Affiliates',
            'capability' => 'manage_options'
        ]
];
