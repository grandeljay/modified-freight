/**
 * Freight
 *
 * @author  Jay Trees <freight@grandels.email>
 * @link    https://github.com/grandeljay/modified-freight
 * @package GrandelJayFreight
 */

'use strict';

document.addEventListener('DOMContentLoaded', function() {
    const MODULE_NAME = 'MODULE_SHIPPING_GRANDELJAYFREIGHT';

    /**
     * Countries
     */
    let input_countries = Array.from(
        document.querySelectorAll('[name^="configuration[' + MODULE_NAME + '_COUNTRY_"]')
    );

    input_countries.every(function(input_country, index) {
        let popup = new Popup(input_country);

        return true;
    });

    /**
     * Surcharges
     */
    let input_surcharges = document.querySelector('[name^="configuration[' + MODULE_NAME + '_SURCHARGES_SURCHARGES"]');
    let popup            = new Popup(input_surcharges);

    /**
     * For some reason the form is sometimes not submitting. This dirty hack
     * will help until I find out why.
     */
    let form       = document.querySelector('form[name="modules"]');
    let formSubmit = form.querySelector('[type="submit"]');

    formSubmit.addEventListener('click', function() {
        setTimeout(() => {
            form.submit();
        }, 0);
    });
});
