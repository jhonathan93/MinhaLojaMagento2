result = {
    sucess : undefined,
    cep : undefined,
    rua: undefined,
    bairro: undefined,
    cidade: undefined,
    estado: undefined,
    pais: undefined
}

define([
    'underscore',
    'ko',
    'uiRegistry',
    'Magento_Ui/js/form/element/abstract',
    'jquery',
    'mask',
    'mage/url',
], function (_, ko, registry, Abstract, $, mask, url) {
    'use strict';

    let checkoutLoader = $('#checkout-loader').css('z-index', 1000);

    return Abstract.extend({
        defaults: {
            loading: ko.observable(false),
            imports: {
                update: '${ $.parentName }.country_id:value'
            }
        },

        initialize: function () {
            this._super();
            $('#'+this.uid).mask('00000-000');
            return this;
        },

        onUpdate: function () {
            $('#checkout').append(checkoutLoader);
            let validate = this.validate();

            if(validate.valid === true && this.value() && this.value().length === 9) {
                let element = this;
                let value = this.value().replace('-', '');

                $.getJSON(url.build(`rest/V1/searchaddress/${value}`), (result) => {
                    result = JSON.parse(result);

                    if (!result.sucess) {
                        console.log(result)
                    } else {
                        if(registry.get(`${element.parentName}.country_id`)) {
                            registry.get(`${element.parentName}.country_id`).value(result.pais);
                        }
                        if(registry.get(`${element.parentName}.street.0`)) {
                            registry.get(`${element.parentName}.street.0`).value(result.rua);
                        }
                        if(registry.get(`${element.parentName}.street.3`)) {
                            registry.get(`${element.parentName}.street.3`).value(result.bairro);
                        }
                        if(registry.get(`${element.parentName}.city`)) {
                            registry.get(`${element.parentName}.city`).value(result.cidade);
                        }
                        if(registry.get(`${element.parentName}.region_id`)) {
                            registry.get(`${element.parentName}.region_id`).value(result.estado);
                        }
                    }
                    $('#checkout-loader').remove();
                });
            } else {
                $('#checkout-loader').remove();
            }
        }
    });
});

// https://devdocs.magento.com/guides/v2.4/ui_comp_guide/concepts/ui_comp_uiregistry.html
