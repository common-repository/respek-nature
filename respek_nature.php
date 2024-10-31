<?php
/**
 * Plugin Name: ReSpek Nature
 * Description: A WooCommerce plugin to integrate ReSpek Nature
 * Version: 1.0.45
 * Plugin URI: https://www.respeknature.org
 * GitHub Plugin URI: respek-nature/respek-nature
 *
 * Text Domain: respek-nature
 * Domain Path: /languages
 * Author: ReSpek Nature
 * Author URI: https://www.respeknature.org
 * Woo: 12345:342928dfsfhsf8429842374wdf4234sfd
 * License: GPLv3
 * @package respek-nature
 */

namespace respek_nature;

if ( ! class_exists( 'ReSpek_Extension' ) ) :
	
    /**
     * ReSpek Extension core class
     */
    class ReSpek_Extension {
 
        /**
         * The single instance of the class.
         */
        protected static $_instance = null;
		private $helperComponent;
		private $apiInit;
		private $dbInit;
		private $apiClient;
		private $enqueue;
		

		const VERSION = '1.0.45';

			/**
		 * Set the minimum required versions for the plugin.
		 */
		const PLUGIN_REQUIREMENTS = array(
			'php_version' => '7.3',
			'wp_version'  => '5.6',
			'wc_version'  => '5.3',
		);

		
        /**
         * Constructor.
         */
        final public function __construct()
		{
			add_action( 'plugins_loaded', array( $this, 'init_plugin' ) );
		}

		public function init_plugin() {

			if ( ! $this->check_plugin_requirements() ) {
				return;
			}

			/**
			 * Check if WooCommerce is active
			 **/
			if (!function_exists('is_plugin_active')){
				include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			}
			if ( is_plugin_active( 'woocommerce/woocommerce.php' ))
			{
				require_once(plugin_dir_path(__FILE__) . '/respek-autoloader.php');

				define( 'RESPEK_PREFIX_IMAGES_URL', plugins_url( '/images', __FILE__ ) );



				$this->helperComponent = new \respek_nature\Components\Respek_HelperComponent();

				$this->apiClient = new \respek_nature\Components\Respek_ApiClient();

				$this->apiInit = new \respek_nature\Inc\Pages\RespekSettings();

				$this->enqueue = new \respek_nature\Inc\Base\Enqueue();

				$respek_checkout_placement = get_option('respek_checkout_placement', 'after_checkout_billing_form');
				$respek_popup_placement = get_option('respek_popup_placement', 'all');
				$currency = 1;
				
				$currency = $this->respek_calculateOffset();

				$message = sprintf(__('Add %s to your purchase and plant a spekboom in the Karoo to offset your CO2. We will match you, spekboom for spekboom.', 'respek-nature'), get_woocommerce_currency_symbol().$currency);
				
				// 
				add_option('respek_auth_token', 0);
				add_option('respek_is_active', 0);
				add_option('respek_collections', 0);
				add_option('respek_matching_collections', 0);
				add_option('respek_on_us_collections', 0);
				add_option('respek_show_popup', 0);
				add_option('respek_timestamp_popup', 3);
				add_option('respek_popup_placement', "all");
				add_option('respek_popup_title', 'Contribute');
				add_option('respek_popup_message', $message);
				$this->enqueue->register();
				$this->apiInit->register();
				//add_action( 'admin_menu', array($this, 'respek_admin_menu'));
				add_action( 'init', array($this, 'wpdocs_load_textdomain') );
				add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( $this, 'plugin_action_links' ) );
				add_action( 'wp_ajax_update_popup_settings', array($this, 'update_popup_settings') );
				add_action( 'wp_ajax_update_popup_settings_fields', array($this, 'update_popup_settings_fields') );
				add_action( 'wp_ajax_merchant_deactivation', array($this, 'merchant_deactivation') );
				add_action( 'wp_ajax_reset_collection_settings', array($this, 'reset_collection_settings') );
				add_action( 'wp_ajax_update_collection_settings', array($this, 'update_collection_settings') );
				
				

				if(get_option('respek_is_active') && get_option('respek_collections')){
					add_action('woocommerce_cart_collaterals', array($this, 'respek_offset_checkbox'));

					switch ($respek_checkout_placement) {
						case "before_checkout_form":
							add_action('woocommerce_before_checkout_form', array($this, 'respek_offset_checkbox'));
							break;
						case "checkout_before_customer_details":
							add_action('woocommerce_checkout_before_customer_details', array($this, 'respek_offset_checkbox'));
							break;
						case "after_checkout_billing_form":
							add_action('woocommerce_after_checkout_billing_form', array($this, 'respek_offset_checkbox'));
							break;
						case "after_order_notes":
							add_action('woocommerce_after_order_notes', array($this, 'respek_offset_checkbox'));
							break;
						case "review_order_before_submit":
							add_action('woocommerce_review_order_before_submit', array($this, 'respek_offset_checkbox'));
							break;
						case "checkout_order_review":
							add_action('woocommerce_checkout_order_review', array($this, 'respek_offset_checkbox'));
							break;
                    // The case below is temporarily removed due to a visual bug: The button hovering over the Place Order button
                    // on the checkout page of webshops
                    // ---------------------------------
                    // case "review_order_after_submit":
                    //     add_action('woocommerce_review_order_after_submit', array($this, 'respek_offset_checkbox'));
                    //     break;
                    // case "none": // this case is needed to remove the placement when you switch back to "Default" - don't remove this case
                        // break;
					}
					switch($respek_popup_placement){
						case "before_checkout_form":
							add_action('woocommerce_before_checkout_form', array($this, 'respek_popup'));
							break;
						case "before_cart":
							add_action('woocommerce_before_cart', array($this, 'respek_popup'));
							break;
						default:
							add_action('woocommerce_before_cart', array($this, 'respek_popup'));
							add_action('woocommerce_before_checkout_form', array($this, 'respek_popup'));
							break;
						
					}

					add_action('woocommerce_cart_calculate_fees', array($this, 'respek_extension_offset_charge'));
				}

				add_action('woocommerce_order_status_changed',
				array($this, 'respek_process_order'), 99, 3);

				add_action('woocommerce_payment_complete',
				array($this, 'respek_process_order_wo_complete'), 99, 1);

				add_filter( 'heartbeat_received', array($this,'respek_receive_heartbeat'), 10, 2 );

				add_action( 'wp_ajax_order_check_merchant_auth_status', array($this,'order_check_merchant_auth_status'));

				add_action( 'wp_ajax_update_merchant_auth_status', array($this, 'update_merchant_auth_status') );

				// $this->merchant = $this->getMerchantToken();
				// echo "this is the merch token".$this->$merchant;

				add_action( 'admin_enqueue_scripts', array('\\respek_nature\ReSpek_Extension', 'add_extension_register_script' ) );

				/**
				 * Register Front End
				 */
				add_action('wp_enqueue_scripts', array('\\respek_nature\ReSpek_Extension', 'respek_stylesheet'));
				add_action('wp_enqueue_scripts', array('\\respek_nature\ReSpek_Extension', 'respek_script'));
				add_action('wp_enqueue_scripts', array('\\respek_nature\ReSpek_Extension', 'respek_font'));
				add_action('wp_enqueue_scripts', array('\\respek_nature\ReSpek_Extension', 'respek_javascript'));

				register_activation_hook( __FILE__, array( '\\respek_nature\ReSpek_Extension', 'respek_extension_activate' ) );
				register_deactivation_hook( __FILE__, array( '\\respek_nature\ReSpek_Extension', 'respek_extension_deactivate' ) );


			}
        }



 
        /**
         * Main Extension Instance.
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

			/**
		 * Checks all plugin requirements. If run in admin context also adds a notice.
		 *
		 * @return boolean
		 */
		public function check_plugin_requirements() {

			$errors = array();
			global $wp_version;

			if ( ! version_compare( PHP_VERSION, self::PLUGIN_REQUIREMENTS['php_version'], '>=' ) ) {
				/* Translators: The minimum PHP version */
				$errors[] = sprintf( esc_html__( 'ReSpek Nature for WooCommerce requires a minimum PHP version of %s.', 'respek-nature' ), self::PLUGIN_REQUIREMENTS['php_version'] );
			}

			if ( ! version_compare( $wp_version, self::PLUGIN_REQUIREMENTS['wp_version'], '>=' ) ) {
				/* Translators: The minimum WP version */
				$errors[] = sprintf( esc_html__( 'ReSpek Nature for WooCommerce requires a minimum WordPress version of %s.', 'respek-nature' ), self::PLUGIN_REQUIREMENTS['wp_version'] );
			}
			if ( ! defined( 'WC_VERSION' ) || ! version_compare( WC_VERSION, self::PLUGIN_REQUIREMENTS['wc_version'], '>=' ) ) {
				/* Translators: The minimum WC version */
				$errors[] = sprintf( esc_html__( 'ReSpek Nature for WooCommerce requires a minimum WooCommerce version of %s.', 'respek-nature' ), self::PLUGIN_REQUIREMENTS['wc_version'] );
			}

			if ( apply_filters( 'woocommerce_admin_disabled', false ) ) {
				$errors[] = esc_html__( 'ReSpek Nature for WooCommerce requires WooCommerce Admin to be enabled.', 'respek-nature' );
			}

			
			if ( empty( $errors ) ) {
				return true;
			}

			if ( is_admin() ) {
				add_action(
					'admin_notices',
					function() use ( $errors ) {
						?>
						<div class="notice notice-error">
							<?php
							foreach ( $errors as $error ) {
								echo '<p>' . esc_html( $error ) . '</p>';
							}
							?>
						</div>
						<?php
					}
				);
				return;
			}

			return false;
		}


		final static function respek_extension_activate() {
			// Your activation logic goes here.
						
		}

		

		final static function respek_extension_deactivate() {
			// Your deactivation logic goes here.
			delete_option('respek_auth_token');
			delete_option('respek_is_active');
			delete_option('respek_collections');
			delete_option('respek_matching_collections');
			delete_option('respek_on_us_collections');
			delete_option('respek_show_popup');
			delete_option('respek_timestamp_popup');
			delete_option('respek_popup_placement');
			delete_option('respek_popup_title');
			delete_option('respek_popup_message');
			flush_rewrite_rules();
		}


			/**
	 * Show action links on the plugin screen.
	 *
	 * @param mixed $links Plugin Action links.
	 *
	 * @return array
	 */
	public static function plugin_action_links( $links ) {
		$action_links = array(
			'settings' => '<a href="' . admin_url( 'admin.php?page=respek-nature' ) . '" aria-label="' . esc_attr__( 'View ReSpek Nature settings', 'respek-nature' ) . '">' . esc_html__( 'Settings', 'respek-nature' ) . '</a>',
		);

		return array_merge( $action_links, $links );
	}

		
		/**
		 * Register the JS.
		 */
		final static function add_extension_register_script() {

			// error_log('add_extension_register_script');

			// if ( ! class_exists( 'Automattic\WooCommerce\Admin\Loader' ) || ! \Automattic\WooCommerce\Admin\Loader::is_admin_or_embed_page() ) {
			// 	return;
			// }
			// error_log('add_extension_register_script after loader check');
			$script_path       = '/build/index.js';
			$script_asset_path = dirname( __FILE__ ) . '/build/index.asset.php';
			$script_asset      = file_exists( $script_asset_path )
				? require( $script_asset_path )
				: array( 'dependencies' => array(), 'version' => filemtime( $script_path ) );
			$script_url = plugins_url( $script_path, __FILE__ );

			wp_register_script(
				'respek-nature',
				$script_url,
				$script_asset['dependencies'],
				$script_asset['version'],
				true
			);

			wp_register_style(
				'respek-nature',
				plugins_url( '/build/index.css', __FILE__ ),
				// Add any dependencies styles may have, such as wp-components.
				array(),
				filemtime( dirname( __FILE__ ) . '/build/index.css' )
			);

			wp_enqueue_script( 'respek-nature' );
			wp_enqueue_style( 'respek-nature' );
		}

		final public static function respek_stylesheet()
		{
			wp_register_style('respek_stylesheet', plugins_url('css/respek.css', __FILE__).'?plugin_version='.self::VERSION);
			wp_enqueue_style('respek_stylesheet');
		}
		final public static function respek_script()
		{
			wp_register_script('respek_script', plugins_url('js/respek.js', __FILE__).'?plugin_version='.self::VERSION, array( 'jquery' ) );
			wp_localize_script('respek_script', 'plugin',
				array(
					'merchant' => esc_attr($_SERVER['SERVER_NAME']),
					'surcharge' => self::instance()->respek_calculateOffset(),
					'currency'=> get_option('woocommerce_currency')
					));
				
			wp_enqueue_script('respek_script');
		}
	
		final public static function respek_font()
		{
			wp_enqueue_style( 'respek-google-fonts', 'https://fonts.googleapis.com/css2?family=Montserrat:wght@300&display=swap', false );
		}
	
		final public static function respek_javascript()
		{
			wp_register_script('respek_extention_script', plugins_url('js/extension.js', __FILE__), null );
	
			//only loads middleware JS if on cart, checkout or a woocommerce page
			if ( is_cart() || is_checkout() || is_woocommerce() || is_product() ) {
				//wp_enqueue_script('respek_js_cdn');
				wp_enqueue_script('respek_extention_script');
				wp_localize_script('respek_js_wp', 'plugin',
            array('url' => plugins_url('images', __FILE__), 'api_url' => 'https://www.respeknature.org/'));
			
			}
			// wp_register_script('respek_js_wp', plugins_url('js/respek-extension.js', __FILE__).'?plugin_version='.self::VERSION);
			
			
			wp_enqueue_script('respek_js_wp', "", array('jquery'), null, true);
			wp_localize_script('respek_js_wp', 'ajax_object',
				array('ajax_url' => admin_url('admin-ajax.php')));
			wp_localize_script('respek_js_wp', 'plugin',
				array('url' => plugins_url('images', __FILE__)));
	
		}

		final public function wpdocs_load_textdomain() {
			load_plugin_textdomain( 'respek-nature', false, dirname( plugin_basename( __FILE__ ) ) . '/inc/languages' ); 
		}
	

		final public function respek_plugin_impact_page(){ ?><h1>ReSpek Nature Impact</h1>
			<form method="post" action="options.php">
			<?php settings_fields( 'extra-post-info-settings' ); ?>
			<?php do_settings_sections( 'extra-post-info-settings' ); ?>
			<table class="form-table"><tr valign="top"><th scope="row">Extra post info:</th>
			<td><input type="text" name="extra_post_info" value="<?php //echo get_option( 'extra_post_info' ); ?>"/></td></tr></table>
			<?php submit_button(); ?>
			</form>
			<?php 
		}

		final public function renderCheckbox()
		{
			global $woocommerce;
			$this->surcharge = $this->respek_calculateOffset();

			$this->helperComponent->RenderCheckbox( esc_html(number_format($this->surcharge , $this->decimal_places, wc_get_price_decimal_separator(), ' ') ) , esc_attr(urlencode(json_encode($this->respek_offsetDataToJson())) ));
		}
		
		final public function respek_popup(){
			$this->surcharge = $this->respek_calculateOffset();
			$this->helperComponent->RenderPopup(esc_html(number_format($this->surcharge , $this->decimal_places, wc_get_price_decimal_separator(), ' ') ));
		}

		final public function respek_offset_checkbox()
		{
			$this->renderCheckbox();
		}

		

		final public function respek_extension_offset_charge($cart){
			$this->surcharge = $this->respek_calculateOffset();

			global $woocommerce;
	
			if (isset($_POST['post_data'])) {
				parse_str($_POST['post_data'], $post_data);
				$post_data = array_map('sanitize_text_field', $post_data);
			} else {
				$post_data = array_map('sanitize_text_field', $_POST);
			}
	
			if (isset($post_data['respek_offset'])) {
				if ($post_data['respek_offset'] == 1) {
					$woocommerce->session->respek_offset = 1;
				}
				else if ($post_data['respek_offset'] == 0) {
					$woocommerce->session->respek_offset = 0;
				}
			}
	
			if ($woocommerce->session->respek_offset == 1){
				if(get_option('respek_matching_collections')){
					$woocommerce->cart->add_fee(__( 'ReSpek Nature Carbon Offset', 'respek-nature' ), $this->surcharge*2, false, 'respek');
					$woocommerce->cart->add_fee(__( 'ReSpek Nature Carbon Offset matched by merchant', 'respek-nature' ), -$this->surcharge, false, 'respek');
				}
				else if(get_option('respek_on_us_collections')){
					$woocommerce->cart->add_fee(__( 'ReSpek Nature Carbon Offset', 'respek-nature' ), $this->surcharge, false, 'respek');
					$woocommerce->cart->add_fee(__( 'ReSpek Nature Carbon Offset on merchant', 'respek-nature' ), -$this->surcharge, false, 'respek');
				}		
				else
					$woocommerce->cart->add_fee(__( 'ReSpek Nature Carbon Offset', 'respek-nature' ), $this->surcharge, false, 'respek');
			}
		}

		final private function respek_calculateOffset(){
			$this->decimal_places = 0;
			if(get_option('woocommerce_currency') == "ZAR"){
				return 15;
			} else {
			return 1;
			}
			// return $this->respek_offsetDataToJson;
		}
		
		final private function respek_offsetDataToJson()
		{
			global $woocommerce;
			$cart = array();

	
			$items = $woocommerce->cart->get_cart();
			foreach ($items as $item => $values)
			{
				$_product = $values['data'];
	
				$product_data = array();
				$product_data['name'] = $_product->get_name();
				$product_data['quantity'] = $values['quantity'];
				$product_data['brand'] = "";
				$product_data['description'] = $_product->get_description();
				$product_data['shortDescription'] = $_product->get_short_description();
				$product_data['sku'] = $_product->get_sku();
			   // $product_data['gtin'] = $_product->get;
				$product_data['price'] = $_product->get_price();
				$product_data['taxClass'] = $_product->get_tax_class();
				$product_data['weight'] = $_product->get_weight();
				$product_data['attributes'] = $_product->get_attributes();
				$product_data['defaultAttributes'] = $_product->get_default_attributes();
	
				$cart[] = $product_data;
			}
	
			return $cart;
		}

		final public function respek_receive_heartbeat( array $response, array $data ) {
			
			$this->merchant = $this->getMerchantToken();

			$response['respek_merchant'] = json_encode( $this->merchant);
			error_log("respek_receive_heartbeat >{$response['respek_merchant']}<");

			if($this->merchant->init_url) {
				$response['respek_merchant'] = $this->merchant->init_url;
				return $response;
			}
			else {
				return $response;
			}
				
		}

		final public function respek_process_order_wo_complete($order_id)
		{
			$this->respek_process_order($order_id, '', 'completed');
		}

		final public function respek_process_order($order_id, $old_status, $new_status='completed')
		{

			error_log("order {$order_id} status {$old_status} > {$new_status}");

			global $woocommerce;
			switch ($new_status) {
				
				case "completed":
				case "processing":
					$order = wc_get_order($order_id);
					$fees = $order->get_fees();
					$json_fees = json_encode($fees);
					error_log("order {$order} fees {$json_fees}");
	
					$respek_merchant_token = get_option('respek_auth_token', '');
					$orderTotal = $order->get_total();
					$compensationCost = 0;
					foreach ($fees as $fee) {
						error_log("order {$order_id} fees {$fee->get_name()}");
						if ($fee->get_name() == __( 'ReSpek Nature Carbon Offset', 'respek-nature' )) {
							$compensationCost = $fee->get_total();
							break;
						}
					}
					error_log(json_encode([$respek_merchant_token, $order_id, $orderTotal, $compensationCost, $order->get_billing_email(),$order->get_billing_first_name().' '.$order->get_billing_last_name() ]));


	
					foreach ($fees as $fee) {
						// Did customer opt in for ReSpek Offset?
						if ($fee->get_name() == __( 'ReSpek Nature Carbon Offset', 'respek-nature' )) {
							
							$this->respek_save_order($order_id,$compensationCost);
						}
					}
	
					
	
					break;
	
				case "refunded":
				
			}
		}

		final private function respek_save_order($order_id, $fees)
		{
			$order = wc_get_order($order_id);
		
			// $respek_merchant_token = get_option('respek_merchant_token', "respek_woo_merch");
			$orderTotal = $order->get_total();
			// $data = array($respek_merchant_token, $order_id, $compensationCost, $orderTotal);

			$data = array(
				'order' => $order_id,
				'product' => 'respek',
				'value' => $fees,
				'quantity' =>  get_option('respek_matching_collections') == 1 ? 2 : 1,
				'name' => $order->get_billing_first_name().' '.$order->get_billing_last_name(),
				'email' => $order->get_billing_email(),
				'domain' => sanitize_text_field($_SERVER['SERVER_NAME']) // domain name
			);

			$spek = $this->apiClient->getSpek($data);
			
	
		}
		function order_check_merchant_auth_status() {
			$this->merchant = $this->getMerchantToken();
			wp_send_json($this->merchant);
		}
		public function update_merchant_auth_status() {
			update_option('respek_auth_token', sanitize_text_field($_POST['auth_token']));
			update_option('respek_is_active', 1);
			update_option('respek_collections', 1);
			update_option('respek_show_popup', 1);
			return true;
			
		}
		public function getMerchantToken(){
			
			$merchantName = sanitize_text_field($_SERVER['SERVER_NAME']);
        	// $merchantEmail = get_option('admin_email');
			$current_user = $this->helperComponent->_get_current_wp_user();
			$merchantEmail = $current_user->user_email;
			$data = array(
				'domain'=>$merchantName, 
				'email'=>$merchantEmail,
				'name'=>get_option('blogname'),
				'platform'=>'woocommerce',
				'currency'=>get_option('woocommerce_currency')
			);

			error_log(json_encode($data));

			return $this->apiClient->getMerchant($data);

			// return json_encode();
		}
		
		public function update_popup_settings_fields(){
			wp_send_json(update_option('respek_show_popup', sanitize_text_field($_POST['value'])));
		}

		public function update_popup_settings(){

			update_option('respek_show_popup', sanitize_text_field($_POST['popup_status']));
			update_option('respek_timestamp_popup', sanitize_text_field($_POST['popup_time']));
			update_option('respek_popup_placement', sanitize_text_field($_POST['popup_page']));
			update_option('respek_popup_title', sanitize_text_field($_POST['title']));
			update_option('respek_popup_message',sanitize_text_field( $_POST['message']));

			$data = array(
				'config' => array(
					'active'=>get_option('respek_collections'),
					'matching'=>get_option('respek_matching_collections'),
					'on_us'=>get_option('respek_on_us_collections'),
					'currency'=>get_option('woocommerce_currency'),
					'variant'=> sanitize_text_field( $_POST['title']),
					'popup_message' => sanitize_text_field( $_POST['message']),
					'show_after' => sanitize_text_field( $_POST['popup_time']),
					'popup_page' => sanitize_text_field( $_POST['popup_page'])

				)
			);
			error_log(json_encode($data)); 
			wp_send_json($this->apiClient->setMerchantConfig($data));

		}
		public function merchant_deactivation(){
			global $woocommerce;
			$currency = $this->respek_calculateOffset();
			
			$message = "Add ".get_woocommerce_currency_symbol() ."".$currency." to your purchase and plant a spekboom in the Karoo to offset your CO2. We will match you, spekboom for spekboom";
			update_option('respek_auth_token', 0);
			update_option('respek_is_active', 0);
			update_option('respek_collections', 0);
			update_option('respek_matching_collections', 0);
			update_option('respek_on_us_collections', 0);
			update_option('respek_show_popup', 0);
			update_option('respek_timestamp_popup', 3);
			update_option('respek_popup_placement', "all");
			update_option('respek_popup_title', "Contribute");
			update_option('respek_popup_message', sanitize_text_field($message));

			$merchantName = sanitize_text_field($_SERVER['SERVER_NAME']);
        	$merchantEmail = sanitize_text_field(get_option('admin_email'));
			$data = array(
				'domain'=>$merchantName, 
				'email'=>$merchantEmail,
				'name'=>get_option('blogname'),
				'platform'=>'woocommerce'
			);

			error_log(json_encode($data));

			wp_send_json($this->apiClient->deactivateMerchant($data));
		}
		public function update_collection_settings(){

			update_option('respek_collections', sanitize_text_field($_POST['collections']));
			update_option('respek_matching_collections', sanitize_text_field($_POST['matching_collections']));
			update_option('respek_on_us_collections', sanitize_text_field($_POST['on_us_collections']));
			update_option('respek_popup_message', sanitize_text_field($_POST['message']));

			// update_option(sanitize_text_field($_POST['manager']), sanitize_text_field($_POST['value']));

			$data = array(
				'config' => array(
					'active'=> sanitize_text_field( $_POST['collections']),
					'matching'=> sanitize_text_field( $_POST['matching_collections']),
					'on_us'=> sanitize_text_field( $_POST['on_us_collections']),
					'currency'=>get_option('woocommerce_currency'),
					'variant'=> get_option('respek_popup_title'),
					'popup_message' => sanitize_text_field( $_POST['message']),
					'show_after' => get_option('respek_timestamp_popup'),
					'popup_page' => get_option('respek_popup_placement')
				)
			);
			error_log(json_encode($data)); 
			wp_send_json($this->apiClient->setMerchantConfig($data));
		}
		public function reset_collection_settings(){
			update_option('respek_auth_token', sanitize_text_field( $_POST['auth_token']));
			update_option('respek_is_active', sanitize_text_field( $_POST['is_active']));
			update_option('respek_collections',sanitize_text_field(  $_POST['collections']));
			update_option('respek_matching_collections', sanitize_text_field( $_POST['matching_collections']));
			update_option('respek_on_us_collections', sanitize_text_field( $_POST['on_us_collections']));
			return true;
		}       
		 /**
         * Cloning is forbidden.
         */
        public function __clone() {
            // Override this PHP function to prevent unwanted copies of your instance.
            //   Implement your own error or use `wc_doing_it_wrong()`
        }
	}
endif; //! class_exists( 'respek_nature\ReSpek_Extension' )

$reSpekPlugin = new \respek_nature\ReSpek_Extension();

?>
