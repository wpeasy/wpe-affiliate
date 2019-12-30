<?php


namespace WPEAffiliate\Modules\AffiliateAdverts\Controller;


class SettingsController {
	private static $_init;

	static $settingsConfig;

	static $currentOptions;

	static $nonceAction = 'wpe_affiliate';

	static function init( $conf )
	{
		if (self::$_init) return;
		self::$_init = true;

		$settingsConfig  = $conf['settings'];
		self::$settingsConfig = $settingsConfig;
		self::$currentOptions = get_option($settingsConfig ['optionName']);

		add_action( self::$nonceAction . '_save', [__CLASS__, 'saveSettings']);
	}

	static function saveSettings()
	{
		if ( !wp_verify_nonce( $_REQUEST['nonce'], self::$nonceAction)) {
            status_header( 401 );
			exit("Warning: No access");
		}

		$optionName = self::$settingsConfig['optionName'];
		$str = parse_str(urldecode($_POST['data']), $arr);
		update_option($optionName, $arr[$optionName]);
		die();
	}
}