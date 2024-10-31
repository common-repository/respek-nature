(function ($) {
    "use strict";
    $(document).ready(function () {

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
        gtag('config', 'G-DDC9EEMX9F', { 'merchant': plugin.merchant });
        
        var respek_viewed = $('#popup_status').val() != 1 || getCookie('respek_viewed') || false;
        
        if ($('#collections_state').val() == 1) {
            $('#respek_offset').prop('checked') ? '' : $('#respek_offset').trigger('click');
            if ($('#respek_offset').prop('checked')) {
                $('#contribute-button').css('display', 'none');
            }  
            updateCart();
            var chck = document.querySelector('#respek_offset');
            chck.setAttribute("disabled", "");
        }
         // check is cookie is set before displaying popup
         if (!respek_viewed  && !$('#respek_offset').prop('checked')) {
            showPopup();
    }
        // close popup preview 
        $('#no-thanks-button').click(function (e) {
            e.preventDefault(); 
            var popup_timestamp = $('#popup_timestamp').val() * .00069444444; // number of minutes to show after
            setCookie( 'respek_viewed', true, popup_timestamp );
            $('.pico-content').removeClass('block');
            $('#overlay').css('display', 'none');
            $('.pico-content').hide();
            gtag('event', 'cancel_popup');
        });
        $('#contribute-button').click(function (e) {
            e.preventDefault();
            var popup_timestamp = $('#popup_timestamp').val() * .00069444444; // number of minutes to show after
            $('.woocommerce-cart-form').find('input.qty').first().trigger("change");
            $('#respek_offset').trigger("click");

            setTimeout(function () {
                $('body').trigger('update_checkout');
                gtag('event', 'ok_popup', { value: plugin.surcharge, currency: plugin.currency});
                // prevent update cart firing on cart+checkout pages
                if (!$('form.checkout').length) {
                    // This fixes fee adding for shops with a disabled update cart button
                    $("[name='update_cart']").removeAttr("disabled").trigger("click");
                    $("[name='update_cart']").trigger("click");
                }
            }, 200);

            $('.pico-content').removeClass('block');
			$('#overlay').css('display', 'none');
			$('.pico-content').hide();
        });
        function showPopup() { 
            $('#respek_offset').prop('checked') ? '' : setTimeout(function() {
                $('#overlay').css('display', 'block');
                $('.pico-content').addClass('block');
                $('.pico-content').toggle();
                gtag('event', 'show_popup', { value: plugin.surcharge, currency: plugin.currency});
            }, 500);            
        }
        function getCookie(cname) {
            var name = cname + "=";
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
            }
            return "";
        }

        function setCookie(cname, cvalue, exdays) {
            var d = new Date();
            d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
            var expires = "expires=" + d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }
        function updateCart() {
            let check = document.createElement('input');
            check.type = "checkbox";
            check.class = "input-checkbox ";
            check.name = "respek_offset";
            check.id = "respek_offset_hidden";
            check.checked = true;
            check.value = "1";
            check.style = "display:none";
            document.querySelector('.woocommerce-cart-form')?.append(check);
            document.querySelector('form.woocommerce-checkout')?.append(check);
        
            jQuery('.woocommerce-cart-form').find('input.qty').first().trigger("change");

            setTimeout(function () {
                jQuery('body').trigger('update_checkout');

                // prevent update cart firing on cart+checkout pages
                if (!jQuery('form.checkout').length) {
                    // This fixes fee adding for shops with a disabled update cart button
                    jQuery("[name='update_cart']").removeAttr("disabled").trigger("click");
                    jQuery("[name='update_cart']").trigger("click");
                }
            }, 200);
        }
        
    });
    // show pop up preview





})(jQuery);
