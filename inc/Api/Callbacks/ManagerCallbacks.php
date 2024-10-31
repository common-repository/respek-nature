<?php

namespace respek_nature\Inc\Api\Callbacks;


use respek_nature\Inc\Base\BaseController;

class ManagerCallbacks extends BaseController{

    public function checkboxSanitize( $input ){
		return ( isset($input) ? true : false );
	}

	public function adminSectionManager(){
		_e( 'Manage Features of this plugin.', 'respek-nature' );
	}
	public function adminPopupSectionManager(){
		echo'
		<hr style="margin-top:2rem;"/>
		<h2 style="margin-top:1rem;">'.__( 'Respek Popup Settings.', 'respek-nature' ).'</h2>
		';
	}

	public function checkboxField($args){
		$name = $args['label_for'];
		$classes = $args['class'];
		$title = $args['title'];
		$subtitle = $args['subtitle'];
		$checkbox = get_option( $name );
		echo '
		<div class="card">
			<div class="field-wrapper-text">
				<div class="card-title">'.esc_html($title).'</div>
				<div class="field-title"></div>
				<div class="field-subtitle">'.esc_html($subtitle).'</div>
			</div>
			<div class="field-wrapper">
				<label class="switch">
					<input type="checkbox" id="'.esc_attr($name).'" name="' . esc_attr($name) . '" value="1" class="'.esc_attr($name).' checkbox-' . esc_attr($classes) . '" ' . (esc_attr($checkbox) ? 'checked="checked"' : '') . '>
					<span class="slider round"></span>
				</label>
			</div>	

		</div>';
	}
	public function textareaField($args){
		
		$name = $args['label_for'];
		// $value = esc_attr(get_option($name));
		$classes = $args['class'];
		$title = $args['title'];
		$subtitle = $args['subtitle'];
		echo '
		<div class="card textarea-field">
			<div class="field-wrapper-text">
				<div class="card-title">'.esc_html($title).'</div>
				<div class="field-title"></div>
				<div class="field-subtitle">'.esc_html($subtitle).'</div>
			</div>
			<div class="field-wrapper">
				<textarea id="'.esc_attr($name).'" class="textarea-'.esc_attr($classes).'" name="'.esc_attr($name).'" rows="4" cols="40">'.esc_textarea(get_option($name)).'
				</textarea>
			</div>	

		</div>';

	}
	public function textField($args){
		$name = $args['label_for'];
		$value = esc_attr(get_option($name));
		$classes = $args['class'];
		$title = $args['title'];
		$subtitle = $args['subtitle'];

		echo '
			<div class="card text-field">
				<div class="field-text">
					<div class="card-title">'.esc_html($title).'</div>
				</div>
				<div class="field-wrapper">
					<input type="text" id="'.esc_attr($name).'" class="textfield-'.esc_attr($classes).'" name="'.esc_attr($name).'" value="'.esc_attr($value).'" placeholder="'.__( 'Contributions', 'respek-nature' ).'">
				</div>
			</div>
		';
	}
	public function numberField($args){
		$name = $args['label_for'];
		$classes = $args['class'];
		$title = $args['title'];
		$subtitle = $args['subtitle'];

		echo '
			<div class="card text-field">
				<div class="field-text">
					<div class="card-title">'.esc_html($title).'</div>
				</div>
				<div class="field-wrapper">
					<input type="number" id="'.esc_attr($name).'" class="numberfield" name="'.esc_attr($name).'" value="'.esc_attr(get_option('respek_timestamp_popup')).'" placeholder="'.__( 'minutes', 'respek-nature' ).'" min="1" max="10">
				</div>
			</div>
		';
	}
	public function selectField($args){
		$all_sel = '';
		$cart_sel = ''; 
		$checkout_sel = '';
		$name = $args['label_for'];
		$classes = $args['class'];
		$title = $args['title'];
		$subtitle = $args['subtitle'];
		$selectOption = get_option('respek_popup_placement');
		if($selectOption == "all") $all_sel = "selected";
		else if ($selectOption == "before_cart") $cart_sel = "selected";
		else $checkout_sel = "selected";

		echo '
			<div class="card text-field">
				<div class="field-text">
					<div class="card-title">'.esc_html($title).'</div>
				</div>
				<div class="field-wrapper">
					<select class="'.esc_attr($classes).'" name="'.esc_attr($name).'" id="'.esc_attr($name).'">
						<option value="all"'.esc_attr($all_sel).'>'.__( 'All', 'respek-nature' ).'</option>
						<option value="before_cart" '.esc_attr($cart_sel).'>'.__( 'Cart', 'respek-nature' ).'</option>
						<option value="before_checkout_form" '.esc_attr($checkout_sel).'>'.__( 'Checkout', 'respek-nature' ).'</option>
					</select>
				</div>
			</div>
		';
	}
	public function buttonAuthField($args){
		$value = $args['button_text'];
		$name = $args['label_for'];
		$title = $args['title'];
		$subtitle = $args['subtitle'];
	
		echo '
			<div class="card auth_card">
				<div class="field-wrapper-text">
					<div class="card-title">'.__($title, 'respek-nature').'</div>
					<div class="field-title"></div>
					<div class="field-subtitle">'.(get_option('respek_is_active') ? sprintf(__( 'ReSpek Nature is connected to %s', 'respek-nature' ), $_SERVER['SERVER_NAME']) : $subtitle).'</div>
				</div>
				<div class="field-wrapper merchant_url">
					<a href="#" target="_blank" id="'.esc_attr($name).'" class="'.esc_attr($name).'-btn btn-primary '.(get_option('respek_is_active') ? 'active' : '').'">'.(get_option('respek_is_active') ? __('Cancel Billing', 'respek-nature') : __('Authorize', 'respek-nature')).'</a>
					
				</div>	
			</div>			
		';
	}
	public function buttonField($args){
		$value = $args['button_text'];
		$name = $args['label_for'];
		$status =  '';
		
		echo '
			<input id="'.esc_attr($name).'" type="submit" id="'.esc_attr($name).'" name="'.esc_attr($name).'" class="'.esc_attr($name).'-btn btn-primary" value="'.esc_attr($value).'">
		';
	}
	public function infoField($args){
		$name = $args['label_for'];
		$title = $args['title'];
		$subtitle = $args['subtitle'];
		echo '
			<div class="card">
				<div class="field-text">
					<div class="card-title">'.esc_html($title).'</div>

					<div class="field-subtitle">'.esc_html($subtitle).'</div>
				</div>
			</div>
		';
	}
}