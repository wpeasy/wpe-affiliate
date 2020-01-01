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



}