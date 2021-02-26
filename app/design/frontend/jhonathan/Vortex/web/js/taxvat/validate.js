/**
 * @author Jhonathan da silva
 * @link https://github.com/jhonathan93
 * @link https://www.linkedin.com/in/jhonathan-silva-367541171/
 * @package Jhonathan_Core
 */

define([
    'jquery',
    'mage/url',
    'error'
], ($, url, error) => {
    'use strict';

    return {
        validateTaxvat: (e) => {
            let value = e.target.value.replace(/[\/]/g, '@');
            $.getJSON(url.build(`rest/V1/Authentication/${value}`), function(response) {
                response = JSON.parse(response);
                if (response.result) {
                    error.removeError(e);
                } else {
                    error.createError(e, response.message);
                }
            });
        }
    }
});
