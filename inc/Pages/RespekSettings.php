<?php


 namespace respek_nature\Inc\Pages;

use respek_nature\Inc\Api\RespekSettingsApi;
use respek_nature\Inc\Base\BaseController;
use respek_nature\Inc\Api\Callbacks\AdminCallbacks;
use respek_nature\Inc\Api\Callbacks\ManagerCallbacks;

 /**
*  lets do some registering!!!!
*/
if ( !class_exists( 'respek_nature\Inc\Pages\RespekSettings' ) ) :
class RespekSettings{
    
    public $settings;

	public $callbacks;
	public $callbacks_mngr;

	public $pages = array();
	public $subpages = array();
	
    public function register(){
        
        $this->settings = new RespekSettingsApi();
		$this->callbacks = new AdminCallbacks();
		$this->callbacks_mngr = new ManagerCallbacks();

		$this->setPages();

		$this->setSettings();
		$this->setSections();
        $this->setFields();
		$this->settings->addPages( $this->pages )->withSubPages(  __( 'Dashboard', 'respek-nature' ) )->register();
    }

	public function setPages() {
		$this->pages = array(
			array(
				'page_title' => 'ReSpek Plugin', 
				'menu_title' => 'ReSpek Nature', 
				'capability' => 'manage_options', 
				'menu_slug' => 'respek-nature', 
				'callback' => array( $this->callbacks, 'adminDashboard' ), 
				'icon_url' => RESPEK_PREFIX_IMAGES_URL.'/respek_logo_dark.png', 
				'position' => 99
			)
		);
	}

    public function setSettings(){
        $args = array(
            array(
				'option_group' => 'respek_plugin_settings_group',
				'option_name' => 'button_auth',
				'callback' => array( $this->callbacks_mngr, 'checkboxSanitize' )
			)
			// array(
			// 	'option_group' => 'respek_plugin_settings',
			// 	'option_name' => 'settings_manager',
			// 	'callback' => array( $this->callbacks_mngr, 'checkboxSanitize' )
			// )
		);
        $this->settings->setSettings( $args );
    }

    public function setSections(){
		$args = array(
			array(
				'id' => 'respek_settings_index',
				'callback' => array( $this->callbacks_mngr, 'adminSectionManager' ),
				'page' => 'respek-nature'
			)
		);

		$this->settings->setSections( $args );
    }
    
    public function setFields(){
         $args = array(
             array(
				'id' => 'button_auth',
				'title' => __( 'ReSpek Nature Billing', 'respek-nature' ),
				'callback' => array( $this->callbacks_mngr, 'buttonAuthField' ),
				'page' => 'respek-nature',
				'section' => 'respek_settings_index',
				'args' => array(
					'label_for' => 'button_auth',
					'class' => 'ui-toggle',
					'title' => __( 'ReSpek Nature Billing', 'respek-nature' ),
					'subtitle' => __("Click 'Authorize' to complete the installation and authorize billing to allow us to collect contributions made by your customers.", 'respek-nature').
					'<p style="    color: #8c8f94;">'.__("At the end of the month your credit card will be billed for the spekboom your customers have purchased.", 'respek-nature').'</p>',
					'button_text' => __('Authorize','respek-nature' )
				),
			)
        );
        $this->settings->setFields( $args );
    }
}
endif;