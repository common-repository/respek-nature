// Import SCSS entry file so that webpack picks up changes
// eslint-disable-next-line @woocommerce/dependency-group
import './index.scss';

function checkMerchantAuthStatus() {
    const data = {
        action: 'order_check_merchant_auth_status',
        whatever: 1234,
    };

    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
    jQuery.post(ajaxurl, data, function (response) {
        response = JSON.parse(response);
        if (response.active === true) {
            console.log('done');
            clearInterval(timeinterval);
            jQuery('#merchant_config').html('The auth token is ' + response.auth_token);
            jQuery('#merchant_url').html('');

        } else {
            console.log('not yet');
            const init_form = `<form method="get" action="${response.init_url}"
            target="_blank"  rel="opener">
            <input type="submit" value="Complete Registration" /> 
            </form>
            `
            jQuery('#merchant_url').html(init_form);
        } `§`
    });
}
// const timeinterval = setInterval(checkMerchantAuthStatus, 6000);
