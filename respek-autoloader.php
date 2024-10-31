<?php
/**
 * Class Autoloader
 *
 *
 */


/*
 * PHP 5.1.2 < does not have spl_autoload_register 
    Fallback for Pre 5.1.2 PHP version
*/

    if ( !class_exists( 'respek_nature\Components\Respek_TemplateRenderer' ) )
        require_once( plugin_dir_path( __FILE__ )."/components/Respek-TemplateRenderer.php");

    if ( !class_exists( 'respek_nature\Components\Respek_HelperComponent' ) )
        require_once( plugin_dir_path( __FILE__ )."/components/Respek-HelperComponent.php");

    if ( !class_exists( 'respek_nature\Components\Respek_ApiClient' ) )
        require_once( plugin_dir_path( __FILE__ )."/components/Respek-ApiClient.php");
    
        
    if ( !class_exists( 'respek_nature\Inc\Base\BaseController' ) )
        require_once( plugin_dir_path( __FILE__ )."/inc/Base/BaseController.php");
    
    if ( !class_exists( 'respek_nature\Inc\Base\Enqueue' ) )
        require_once( plugin_dir_path( __FILE__ )."/inc/Base/Enqueue.php");
        
    if ( !class_exists( 'respek_nature\Inc\Pages\RespekSettings' ) )
        require_once( plugin_dir_path( __FILE__ )."/inc/Pages/RespekSettings.php");
    
    if ( !class_exists( 'respek_nature\Inc\Api\RespekSettingsApi' ) )
        require_once( plugin_dir_path( __FILE__ )."/inc/Api/RespekSettingsApi.php");
    

    if ( !class_exists( 'respek_nature\Inc\Api\Callbacks\ManagerCallbacks' ) )
        require_once( plugin_dir_path( __FILE__ )."/inc/Api/Callbacks/ManagerCallbacks.php");
    
    if ( !class_exists( 'respek_nature\Inc\Api\Callbacks\AdminCallbacks' ) )
        require_once( plugin_dir_path( __FILE__ )."/inc/Api/Callbacks/AdminCallbacks.php");
        