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



}