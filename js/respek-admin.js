( function ( $ ) {
	'use strict';

	var addScript = function (src) {
		var s = document.createElement('script');
		s.src = src;
		s.async = true;
		document.head.appendChild(s);
	}
	//<!-- Google tag (gtag.js) -->
	addScript('https://www.googletagmanager.com/gtag/js?id=G-DDC9EEMX9F');


	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());

	gtag('config', 'G-DDC9EEMX9F');
	
	$(document).ready(function () { 
		checkMerchantAuthStatus(null)
		$('#respek_show_popup').prop('checked') ? $('.popup-setting').show() : $('.popup-setting').hide();
		const timeinterval = setInterval(checkMerchantAuthStatus, 6000);

		$('#button_auth').click(function (e) {
			e.preventDefault();
			if ( $( '#button_auth' ).hasClass( 'active' ) ) {
				//Do deactivation
				$.ajax({
					method: 'POST',
					url: ajaxurl,
					data: {
						action: 'merchant_deactivation'
					},
					beforeSend: function () {
					    if($('#button_auth').hasClass('active'))
							$('#button_auth').text(WP_AUTH.canceling);
						else $('#button_auth').text(WP_AUTH.authorizing);
					        $('#button_auth').append('<span class="spinner is-active"></span>');
					},
					success: function (response) {
						if ($('#button_auth').hasClass('active')) {
							$('#button_auth').removeClass('active');
							location.reload();
						}
					},
					complete: function () {
						$(".spinner").remove();
					}
				});
			}
			else checkMerchantAuthStatus('click')
		});
		$("#preview_popup_manager").click(function (e) {
			e.preventDefault();
			$('#overlay').css('display', 'block');
			$('.popup-content').addClass('dp-flex');
			var title = $('#respek_popup_title').val();
			var msg = $('#respek_popup_message').val();
			$('#popup_title').text(title);
			$('#msg_text p').text(msg);
			$('.popup-content').show();
		});
		// close popup preview 
		$('.close-btn').click(function (e) {
			e.preventDefault();
			$('.popup-content').removeClass('dp-flex');
			$('#overlay').css('display', 'none');
			$('.popup-content').hide();
		});
		// POPUP SETTINGS
		$('.popup-setting').on('change', function () {
			var obj = $(this)
			$.ajax({
				method: 'POST',
				url: ajaxurl,
				data: {
					action: 'update_popup_settings',
					popup_status: $('#respek_show_popup').prop('checked') ? 1 : 0,
					popup_time: $('#respek_timestamp_popup').val(),
					popup_page: $('#respek_page_popup').val(),
					title: $('#respek_popup_title').val(),
					message: $('#respek_popup_message').val(),
				},
				beforeSend: function () {
					obj.parent().append('<span class="spinner is-active"></span>');
				},
				success: function (response) {
					savedPopup();
				},
				complete: function () {
					$(".spinner").remove();
				}
			});
		});
		$('#respek_timestamp_popup').on('change', function () {
			$.ajax({
				method: 'POST',
				url: ajaxurl,
				data: {
					action: 'update_popup_settings',
					popup_status: $('#respek_show_popup').prop('checked')
						? 1
						: 0,
					popup_time: $('#respek_timestamp_popup').val(),
					popup_page: $('#respek_page_popup').val(),
					title: $('#respek_popup_title').val(),
					message: $('#respek_popup_message').val(),
				},
				beforeSend: function() {
					$('#respek_timestamp_popup').parent().append('<span class="spinner is-active"></span>');
				},
				success: function(response) {
					savedPopup();
				},
				complete: function() {
					 $(".spinner").remove();
				},
			});
		});
		$('#respek_collections').on('change', function () {
			if ($('#respek_collections').is(':checked')) {
				$('#respek_popup_title').val(WP_AUTH.default_title);
				$('#respek_popup_message').val(WP_AUTH.default_txt);
			}
			$.ajax({
				method: 'POST',
				url: ajaxurl,
				data: {
					action: 'update_collection_settings',
					collections: $('#respek_collections').prop('checked') ? 1 : 0,
					matching_collections: $('#respek_matching_collections').prop('checked') ? 1 : 0,
					on_us_collections: $('#respek_on_us_collections').prop('checked') ? 1 : 0,
					title: $('#respek_popup_title').val(),
					message: $('#respek_popup_message').val(),
				},
				beforeSend: function () {
					$('#respek_collections').parent().append('<span class="spinner is-active"></span>');
				},
				success: function (response) {
					savedPopup();
				},
				complete: function () {
					$(".spinner").remove();
				}
			});
		});
		$('#respek_matching_collections').on('change', function () {
			if ($('#respek_on_us_collections').is(':checked'))
				$('#respek_on_us_collections').prop('checked', false);
			if ($('#respek_matching_collections').is(':checked')) {
				$('#respek_popup_title').val(WP_AUTH.matching_title);
				$('#respek_popup_message').val(WP_AUTH.matching_txt);
			} else {
				$('#respek_popup_title').val(WP_AUTH.default_title);
				$('#respek_popup_message').val(WP_AUTH.default_txt);
			}
				
			$.ajax({
				method: 'POST',
				url: ajaxurl,
				data: {
					action: 'update_collection_settings',
					collections: $('#respek_collections').prop('checked') ? 1 : 0,
					matching_collections: $('#respek_matching_collections').prop('checked') ? 1 : 0,
					on_us_collections: $('#respek_on_us_collections').prop('checked') ? 1 : 0,
					title: $('#respek_popup_title').val(),
					message: $('#respek_popup_message').val(),
				},
				beforeSend: function () {
					$('#respek_matching_collections').parent().append('<span class="spinner is-active"></span>');
				},
				success: function (response) {
					savedPopup();
				},
				complete: function () {
				   $(".spinner").remove();
				}
			});
		});
		$('#respek_on_us_collections').on('change', function () {
			if ($('#respek_matching_collections').is(':checked'))
				$('#respek_matching_collections').prop('checked', false);
			if ($('#respek_on_us_collections').is(':checked')) {
				$('#respek_popup_title').val(WP_AUTH.matching_title);
				$('#respek_popup_message').val(WP_AUTH.on_us_txt);
			}else {
				$('#respek_popup_title').val(WP_AUTH.default_title);
				$('#respek_popup_message').val(WP_AUTH.default_txt);
			}
			$.ajax({
				method: 'POST',
				url: ajaxurl,
				data: {
					action: 'update_collection_settings',
					collections: $('#respek_collections').prop('checked') ? 1 : 0,
					matching_collections: $('#respek_matching_collections').prop('checked') ? 1 : 0,
					on_us_collections: $('#respek_on_us_collections').prop('checked') ? 1 : 0,
					title: $('#respek_popup_title').val(),
					message: $('#respek_popup_message').val(),
				},
				beforeSend: function () {
					$('#respek_on_us_collections').parent().append('<span class="spinner is-active"></span>');
				},
				success: function (response) {
					savedPopup();
				},
				complete: function () {
					$(".spinner").remove();
				}
			});
		});
		$('#respek_show_popup').on('change', function () {
			if ($('#respek_matching_collections').is(':checked'))
				$('#respek_matching_collections').prop('checked', false);
			
			$('#respek_show_popup').prop('checked') ? $('.popup-setting').show() : $('.popup-setting').hide();
			
			$.ajax({
				method: 'POST',
				url: ajaxurl,
				data: {
					action: 'update_popup_settings_fields',
					value: $('#respek_show_popup').prop('checked') ? 1 : 0,
				},
				beforeSend: function () {
					$('#respek_show_popup').parent().append('<span class="spinner is-active"></span>');
				},
				success: function (response) {
					savedPopup();
				},
				complete: function () {
					$(".spinner").remove();
				}
			});
		});
		// POPUP SETTINGS END

		
		function checkMerchantAuthStatus(e) {   // check auth status
			
			$.ajax({
				method: 'POST',
				url: ajaxurl,
				data: {
					action: 'order_check_merchant_auth_status',
				},
				beforeSend: function () {
				},
				success: function (response) {
					if (response.active === true) {
						clearInterval(timeinterval);
						$('#button_auth').text(WP_AUTH.unauthorize);
						updateMerchantStatus(response.auth_token);
						 $('#button_auth').removeClass('authorising');
					} else {
						if (e == 'click') {
							$('#button_auth').attr('href', response.init_url);
							$('#button_auth').text(WP_AUTH.authorizing);
							$('#button_auth').addClass('authorising');
							 if(!$('.merchant_url .spinner').hasClass('is-active'))$('#button_auth').append('<span class="spinner is-active"></span>');
							$('.auth_card .field-subtitle').empty().append(WP_AUTH.auth_link_desc + ' <a target="_blank" class="auth-link" href="'+response.init_url+'">'+WP_AUTH.auth_link+'</a>');
							window.open(response.init_url)
						}
						$.ajax({
							method: 'POST',
							url: ajaxurl,
							data: {
								action: 'reset_collection_settings',
								is_active: 0,
								collections: 0,
								matching_collections: 0,
								on_us_collections: 0,
								auth_token: 0,
							},
							beforeSend: function () {  
							},
							success: function (response) {
								if ($('#button_auth').hasClass('active')) {
									$('#button_auth').removeClass('active');
									location.reload();
								}
							},
							complete: function () {
								if(!$('#button_auth').hasClass('authorising')) $(".spinner").remove(); 
							}
						});
					}
				},
			});
			
		}
		function updateMerchantStatus(token) {
			$.ajax({
				method: 'POST',
				url: ajaxurl,
				data: {
					action: 'update_merchant_auth_status',
					auth_token: token,
				},
				beforeSend: function () {
				},
				success: function(response) {
					if (response) {
						if (!$('#button_auth').hasClass('active')) location.reload();
					}
				},
				complete: function() {
					 $(".spinner").remove();
				},
			});
		}
	});
	function savedPopup() {
		$('.success-popup').show();
		setTimeout(function () {
			$('.success-popup').hide();
		}, 3000);
	}
})(jQuery);