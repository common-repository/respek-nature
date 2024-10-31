<?php
namespace respek_nature\Components;

if ( !class_exists( 'respek_nature\Components\Respek_HelperComponent' ) ) :

    class Respek_HelperComponent
    {
        public function __construct()
        {

        }     

        static public function RenderImage($uri, $class = null, $class_global = null, $id = null, $extra_class = null)
        {
            $img_html = '<img alt="Offset Carbon. Restore nature, Plant a spekboom" title="Offset Carbon. Restore nature, Plant a spekboom" src="' .esc_url(plugins_url($uri, __FILE__)) . '" ';
            $img_html = str_ireplace( '/Components', '', $img_html );
            if (isset($class))
                $img_html .= 'class="' . $class .' '. $class_global . ' ' . $extra_class . '"';
            if (isset($id))
                $img_html .= 'id="' . $id . '" ';

            return $img_html . ' />';
        }
        static public function getImageUri($uri){
            $img_uri = esc_url(plugins_url($uri, __FILE__));
            $img_uri = str_ireplace( '/Components', '', $img_uri );
            return $img_uri;
        }

        public function RenderCheckbox($surcharge, $cart)
        {
            global $woocommerce;

            $templateRenderer = new Respek_TemplateRenderer(plugin_dir_path(__FILE__).'../templates/');

            $template = get_option('respek_button_template', 'respek_button_template_default');
            // if (get_option('respek_cfp') == 'on')
            //     $template = 'respek_button_template_default_cfp';

            // Render checkbox / button according to admin settings
            echo $templateRenderer->render($template,
            array('cart' => $cart,
                    'respek_session_opted' =>  $woocommerce->session->respek_offset,
                    'currency_symbol' =>get_woocommerce_currency_symbol(),
                    'surcharge' => $surcharge,
                    'respek_gif_feature' => get_option('respek_gif_feature', 'on'),
                    // fake it till you make it; 0 => 1
                    'compensation_count' => get_option('respek_compensation_count', 1),
                    // impact from kg => tonne, 1 decimal point, rounding up
                    // ceil 4.2 => 5, so /10, ceil, /100, round with 1 decimal
                    'impact_total' => round(ceil(get_option('respek_impact', 100)/10) / 100, 1)
                )
            );

        }
        
        public function RenderPopup($surcharge){
            $templateRenderer = new Respek_TemplateRenderer(plugin_dir_path(__FILE__).'../templates/');
            $template = get_option('respek_popup_template', 'respek_popup_template_default');
            
            echo $templateRenderer->render($template, 
            array( 
                
                'currency_symbol' =>get_woocommerce_currency_symbol(),
                'surcharge' => $surcharge)
            );
        }

        static function _get_current_wp_user() {
            self::require_pluggable_essentials();
            self::wp_cookie_constants();

            return wp_get_current_user();
        }

        private static function require_pluggable_essentials() {
            if ( ! function_exists( 'wp_get_current_user' ) ) {
                require_once ABSPATH . 'wp-includes/pluggable.php';
            }
        }

        private static function wp_cookie_constants() {
            if ( defined( 'LOGGED_IN_COOKIE' ) &&
                 ( defined( 'AUTH_COOKIE' ) || defined( 'SECURE_AUTH_COOKIE' ) )
            ) {
                return;
            }

            /**
             * Used to guarantee unique hash cookies
             *
             * @since 1.5.0
             */
            if ( ! defined( 'COOKIEHASH' ) ) {
                $siteurl = get_site_option( 'siteurl' );
                if ( $siteurl ) {
                    define( 'COOKIEHASH', md5( $siteurl ) );
                } else {
                    define( 'COOKIEHASH', '' );
                }
            }

            if ( ! defined( 'LOGGED_IN_COOKIE' ) ) {
                define( 'LOGGED_IN_COOKIE', 'wordpress_logged_in_' . COOKIEHASH );
            }

            /**
             * @since 2.5.0
             */
            if ( ! defined( 'AUTH_COOKIE' ) ) {
                define( 'AUTH_COOKIE', 'wordpress_' . COOKIEHASH );
            }

            /**
             * @since 2.6.0
             */
            if ( ! defined( 'SECURE_AUTH_COOKIE' ) ) {
                define( 'SECURE_AUTH_COOKIE', 'wordpress_sec_' . COOKIEHASH );
            }
        }

    }

endif;