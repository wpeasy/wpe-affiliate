<?php

namespace WPEAffiliate\Modules\AffiliateAdverts\Controller;

use WPEAffiliate\Application\Controller\ApplicationController;
use WPEasyLibrary\Helpers\View\ViewHelper;
use WPEasyLibrary\Interfaces\IWordPressModule;

class ModuleController implements IWordPressModule {

    static $moduleConfig;

    static $nonceAction = 'wpe_affiliate_get';
    static $ajaxNonce;

    static $_init;

    /********************************
     * Interface Methods
     */
    static function init() {

        if ( self::$_init ) {
            return;
        }
        self::$_init = true;

        // TODO: Implement init() method.
        self::$moduleConfig = require_once dirname( __DIR__ ) . '/config.php';
        ApplicationController::registerModule( __CLASS__, self::$moduleConfig );
        SettingsController::init( self::$moduleConfig );
        self::_addShortcodes();

        add_action( 'admin_init', [ __CLASS__, 'adminInit' ] );

        add_action( 'wp_ajax_wpe_affiliate_get_adverts', [ __CLASS__, 'wpe_affiliate_get_adverts' ] );


    }

    static function adminInit() {
        self::$ajaxNonce = wp_create_nonce( self::$nonceAction );
    }

    static function getDashboardView() {
        return ViewHelper::getView( dirname( __DIR__ ) . '/View/dashboardView.phtml' );
    }

    static function activate() {
        // TODO: Implement activate() method.
    }

    static function upgrade() {
        // TODO: Implement upgrade() method.
    }

    static function getDescription() {
        // TODO: Implement getDescription() method.
    }

    static function deactivate() {
        // TODO: Implement deactivate() method.
    }

    /*************************
     * AJAX methods
     */

    static function wpe_affiliate_get_adverts() {
        if ( ! wp_verify_nonce( $_REQUEST['nonce'], self::$nonceAction ) ) {
            status_header( 401 );
            die( "Not Authorized" );
        }
        $id = SettingsController::$currentOptions['id'];
        if((int) $id <= 0){
            die( ViewHelper::getView( dirname( __DIR__ ) . '/View/noBannersView.phtml' ) );
        }else{
            $out = ViewHelper::getView(
              dirname(__DIR__) . '/View/bannersListView.phtml',
              [
                  'banners' => self::$moduleConfig['banners']
              ]
            );
            die($out);
        }
    }

    /***********************************
     *  Shortcodes
     */
    private static function _addShortcodes()
    {
        add_shortcode('wpe_affiliate', [__CLASS__, 'wpe_affiliate_shortcode']);
    }


    /**
     * @param $atts
     * @return bool|string
     */
    static function wpe_affiliate_shortcode($atts)
    {
        if(empty($atts['affiliate_id'])){
            echo 'No affiliate id provided';
            return false;
        }

        if(empty($atts['banner'])){
            echo 'No banner id provided';
            return false;
        }

        $affiliateID = $atts['affiliate_id'];

        $conf = self::$moduleConfig;
        $bannerConf = $conf['banners'];

        $bannerSrc = $bannerConf['srcRoot'] . $atts['banner'];
        $width = 0;
        $height = 0;
        foreach ($bannerConf['images'] as $banner){
            if( $banner['name'] === $atts['banner']){
                $width = $banner['width'];
                $height = $banner['height'];
                break;
            }
        }

        $out = <<<OUT
<a href="https://store.wpeasy.net/aff.php?aff={$affiliateID}">
<img src="{$bannerSrc}" width="{$width}" height="{$height}" border="0"
></a>
OUT;
        return $out;

    }



}
