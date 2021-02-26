/**
 * @author Jhonathan da silva
 * @link https://github.com/jhonathan93
 * @link https://www.linkedin.com/in/jhonathan-silva-367541171/
 * @package Jhonathan_Core
 */

define([
    'jquery',
    'mage/url',
    'error',
    'loading'
], ($, url, error, loading) => {
    'use strict';

    return {
        searchAddress: (e) => {
            loading.createLoading(e, 'Carregando...');
            let elements = e.originalEvent.path[3];

            $.getJSON(url.build(`rest/V1/searchaddress/${e.target.value}`), function(response) {
                response = JSON.parse(response);

                if (parseInt(response.sucess)) {
                    error.removeError(e);
                    loading.removeLoading(e);

                    let street_1 = $(elements).find('input#street_1')[0];
                    let street_4 = $(elements).find('input#street_4')[0];
                    let city = $(elements).find('input#city')[0];
                    let region = $(elements).find('select#region_id')[0];

                    if (street_1) {
                        $(street_1).val(response['rua']);
                    }

                    if (street_4) {
                        $(street_4).val(response['bairro']);
                    }

                    if (city) {
                        $(city).val(response['cidade']);
                    }

                    if (region) {
                        $(region).val(response['estado']);
                    }
                } else {
                    loading.removeLoading(e);
                    error.createError(e, 'O CEP informado não existe.');
                }
            });
        }
    };
});
