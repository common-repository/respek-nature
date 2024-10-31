<?php


 namespace respek_nature\Inc\Base;

use respek_nature\Inc\Base\BaseController;

  /**
*  sort out plugin path headache!!!!
*/
class Enqueue extends BaseController{
    public function register(){
      if (isset($_GET['page']) && ($_GET['page'] == 'respek-nature')) { 
        // if we are on the plugin page, enable the script
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
      }
        
    }
    public function enqueue() {
      // enqueue all our scripts and variables
      
      wp_enqueue_style( 'respek_style', $this->plugin_url . 'css/admin.css' );
      wp_enqueue_script( 'respek_script', $this->plugin_url . 'js/respek-admin.js', array( 'jquery' ) );
      wp_localize_script('respek_script', 'WP_AUTH', array(
        'authorizing' => __( 'Authorizing', 'respek-nature' ),
        'canceling' => __( 'Canceling', 'respek-nature' ),
        'unauthorize' => __( 'Cancel Billing', 'respek-nature' ),
        'auth_link_desc' => __( 'Please complete the billing authorization at the following', 'respek-nature' ),
        'auth_link' => __( 'link', 'respek-nature' ),
        'default_title' => __( 'Contribute', 'respek-nature' ),
        'default_txt' => __( 'Purchase and plant a spekboom in the Karoo to offset your CO2.', 'respek-nature' ),
        'matching_title' => __( 'Contribution', 'respek-nature' ),
        'on_us_title' => __( 'Our Gift to Nature', 'respek-nature' ),
        'matching_txt' => __('Purchase and plant a spekboom in the Karoo to offset your CO2. We will match you, spekboom for spekboom', 'respek-nature'),
        'on_us_txt' => __('We will be offsetting the carbon of your purchase by planting a spekboom succulent in the Karoo, South Africa'),
      ));
	}
}
