<?php 

use respek_nature\Inc\Api\Callbacks\ManagerCallbacks;
use respek_nature\Inc\Base\BaseController;

?>
<div class="wrap">
<div id="welcome-panel" class="welcome-panel respek-welcome-panel">
		<div class="welcome-panel-content">
			<div class="welcome-panel-header">
                <span class="v33_10">
                    <?php esc_html_e('Offset Carbon.', 'respek-nature') ?><br/>
                    <?php esc_html_e('Restore Nature.', 'respek-nature') ?><br/>
                    <?php esc_html_e('Plant a Spekboom.', 'respek-nature') ?>
                </span>
				<p>
					<a href="https://www.respeknature.org" target="_blank">
					<?php esc_html_e('Learn more', 'respek-nature');	?>.</a>
				</p>
			</div>
            
        </div>
</div>
<center><h2><?php _e('ReSpek Nature Settings', 'respek-nature');	?></h2></center>

    <?php settings_errors(); ?>
    <?php
            $this->callbacks_mngr = new ManagerCallbacks();


            // add_settings_field( 
            //     'popup_settings_title',
            //     __('Manage Popup settings.', 'respek-nature'),
            //     array( $this->callbacks_mngr, 'adminPopupSectionManager' ),
            //     'respek-nature',
            //     'respek_settings_index',
            //     array( 
            //         'label_for' => 'popup_settings_title',
            //         'class' => 'ui-toggle',
            //         'title' => __('Manage Popup settings.', 'respek-nature'),
            //         'subtitle' => __('"On Us" - ReSpek will charge you the merchant and not recover from the customer', 'respek-nature')
            //     ) 
            // );

           

            if(get_option('respek_is_active')){
                add_settings_field( 
                    'respek_collections',
                    __('ReSpek Nature is collecting donations', 'respek-nature'),
                    array( $this->callbacks_mngr, 'checkboxField' ),
                    'respek-nature',
                    'respek_settings_index',
                    array( 
                        'label_for' => 'respek_collections',
                        'class' => 'ui-toggle',
                        'title' => esc_html__('ReSpek Nature is collecting donations', 'respek-nature'),
                        'subtitle' => ''
                    ) 
                );
                add_settings_field( 
                    'respek_matching_collections',
                    __('Matching: Contribute together with your customer', 'respek-nature'),
                    array( $this->callbacks_mngr, 'checkboxField' ),
                    'respek-nature',
                    'respek_settings_index',
                    array( 
                        'label_for' => 'respek_matching_collections',
                        'class' => 'ui-toggle',
                        'title' => __('Matching: Contribute together with your customer', 'respek-nature'),
                        'subtitle' => __('"Matching" - ReSpek will charge you the merchant and your who opts in.', 'respek-nature')
                    ) 
                );
                add_settings_field( 
                    'respek_on_us_collections',
                    __('On Us: Contribute on behalf of your customers', 'respek-nature'),
                    array( $this->callbacks_mngr, 'checkboxField' ),
                    'respek-nature',
                    'respek_settings_index',
                    array( 
                        'label_for' => 'respek_on_us_collections',
                        'class' => 'ui-toggle',
                        'title' => __('On Us: Contribute on behalf of your customers', 'respek-nature'),
                        'subtitle' => __('"On Us" - ReSpek will charge you the merchant and not recover from the customer', 'respek-nature')
                    ) 
                );
                add_settings_field( 
                    'popup_settings_title',
                    __('Manage Popup settings.', 'respek-nature'),
                    array( $this->callbacks_mngr, 'adminPopupSectionManager' ),
                    'respek-nature',
                    'respek_settings_index',
                    array( 
                        'label_for' => 'popup_settings_title',
                        'class' => 'ui-toggle',
                        'title' => __('Manage Popup settings.', 'respek-nature'),
                        'subtitle' => __('"On Us" - ReSpek will charge you the merchant and not recover from the customer', 'respek-nature')
                    ) 
                );
                add_settings_field( 
                    'respek_show_popup',
                    __('Show popup', 'respek-nature'),
                    array( $this->callbacks_mngr, 'checkboxField' ),
                    'respek-nature',
                    'respek_settings_index',
                    array( 
                        'label_for' => 'respek_show_popup',
                        'class' => 'ui-toggle clear',
                        'title' => __('Show popup', 'respek-nature'),
                        'subtitle' => __('')
                    ) 
                );
            }
            add_settings_field( 
                    'respek_timestamp_popup',
                    __('Time delay before showing popup (seconds)', 'respek-nature'),
                    array( $this->callbacks_mngr, 'numberField' ),
                    'respek-nature',
                    'respek_settings_index',
                    array( 
                        'label_for' => 'respek_timestamp_popup',
                        'class' => 'ui-toggle popup-setting',
                        'title' => __('Minutes after opt-out, to show popup again', 'respek-nature'),
                        'subtitle' => __('1 day = 1440 minutes', 'respek-nature'),
                    ) 
                );
                add_settings_field( 
                    'respek_page_popup',
                    __('Page to show popup', 'respek-nature'),
                    array( $this->callbacks_mngr, 'selectField' ),
                    'respek-nature',
                    'respek_settings_index',
                    array( 
                        'label_for' => 'respek_page_popup',
                        'class' => 'ui-toggle popup-setting',
                        'title' => __('Page to show popup', 'respek-nature'),
                        'subtitle' => '',
                    ) 
                );
                add_settings_field( 
                    'respek_popup_title',
                    __('Contributions', 'respek-nature'),
                    array( $this->callbacks_mngr, 'textField' ),
                    'respek-nature',
                    'respek_settings_index',
                    array( 
                        'label_for' => 'respek_popup_title',
                        'class' => 'ui-toggle popup-setting',
                        'title' => __('Contributions', 'respek-nature'),
                        'subtitle' => ''
                    ) 
                );
                add_settings_field( 
                    'respek_popup_message',
                    __('Customer Message', 'respek-nature'),
                    array( $this->callbacks_mngr, 'textareaField' ),
                    'respek-nature',
                    'respek_settings_index',
                    array( 
                        'label_for' => 'respek_popup_message',
                        'class' => 'ui-toggle popup-setting',
                        'title' => __('Customer Message', 'respek-nature'),
                        'subtitle' => ''
                    ) 
                );
                add_settings_field( 
                    'preview_popup_manager',
                    __('Preview Popup', 'respek-nature'),
                    array( $this->callbacks_mngr, 'buttonField' ),
                    'respek-nature',
                    'respek_settings_index',
                    array( 
                        'label_for' => 'preview_popup_manager',
                        'class' => 'ui-toggle popup-setting',
                        'title' => __('Preview Popup', 'respek-nature'),
                        'subtitle' => '',
                        'button_text' => __('Preview Popup', 'respek-nature')
                    ) 
                );
    ?>
    <div class="success-popup"><?php esc_html_e('Settings Saved!', 'respek-nature')?></div>
    <div id="overlay"></div> 
    <center>
    <form method="post" class="auth-form">
        <?php 
            settings_fields( 'respek_plugin_settings_group' );
            do_settings_sections( 'respek-nature' );
        ?>
    </form>
    </center>
    <div class="popup-content card">
        <div class="close-btn">
            ×
        </div>
        <input type="hidden" id="popup_status" value="<?php echo esc_attr(get_option('respek_show_popup'));?>">
        <input type="hidden" id="popup_timestamp" value="<?php echo esc_attr(get_option('respek_timestamp_popup'));?>">
        <div class="content-wraper">
            <div class="card-title">
                <h1><?php esc_html_e('Offset Carbon.', 'respek-nature') ?><br/><?php esc_html_e('Restore Nature.', 'respek-nature') ?><br/><?php esc_html_e('Plant a Spekboom.', 'respek-nature') ?></h1>
            </div>
            <div class="card-content">
                <div id="msg_text"><p><?php echo wp_kses(get_option('respek_popup_message'),'post');?></p></div>
            </div>
            <div class="card-footer">
                <a class="footer-btn contribute-btn" href="#"><?php esc_html_e('Contribute', 'respek-nature') ?>
                <?php echo get_woocommerce_currency_symbol();?> xx
                </a>
                <a class="footer-btn close-btn" href="#"><?php esc_html_e('No thanks', 'respek-nature') ?></a>
            </div>
        </div>
        <div class="popup-img" style="background:url('<?php  echo esc_url($this->plugin_url) ?>images/popup_spek_progressive.jpg');background-position: center;background-repeat: no-repeat; position: absolute;right: 0px;top: 0px;"><div class="respek-logo"><img src="<?php  echo $this->plugin_url ?>images/respek_logo_dark.png" alt=""></div></div>
    </div>
</div>