<?php
return [
    'moduleName' => 'WPEasy Affiliate',
    'moduleDescription' => "Easy method fro WPEasy Affiliates to get banners",
    'path' => __DIR__,
    'url' => plugin_dir_url(__FILE__),
    'settings' =>
        [
            'optionName' => 'wpe_affiliate',
            'capability' => 'manage_options'
        ],

	'banners' => [
		'srcRoot' => 'https://store.wpeasy.net/banners/',
		'images' => [
			[
				'name' => 'banner-150x150.png',
				'width' => 150,
				'height' => 150
			],
			[
				'name' => 'banner-alt-150x150.png',
				'width' => 150,
				'height' => 150
			],
			[
				'name' => 'banner-160x600.png',
				'width' => 160,
				'height' => 600
			],
			[
				'name' => 'banner-alt-160x600.png',
				'width' => 160,
				'height' => 600
			],
			[
				'name' => 'banner-300x250.png',
				'width' => 300,
				'height' => 250
			],
			[
				'name' => 'banner-alt-300x250.png',
				'width' => 300,
				'height' => 250
			],
			[
				'name' => 'banner-728x90.png',
				'width' => 728,
				'height' => 90
			],
			[
				'name' => 'banner-alt-728x90.png',
				'width' => 728,
				'height' => 90
			]
		],

	]
];
