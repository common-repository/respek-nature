(function ($) {

    $(document).ready(function () {
        document.querySelector('#respek_offset').onclick = function (e) {
            let check = document.createElement('input');
            check.type = "checkbox";
            check.class = "input-checkbox ";
            check.name = "respek_offset";
            check.id = "respek_offset_hidden";
            check.checked = true;
            check.value = e.target.checked ? "1" : "0";
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

        };
    
    });
})(jQuery);