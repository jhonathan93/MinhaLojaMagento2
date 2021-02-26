define([
    'jquery',
    'mask'
], ($) => {
    'use strict';

    $.widget('mgu.listener', {
        options: {
            element : '',
            taxvat: ''
        },

        _create: function () {
            $(this.options.element).on('change', (e) => {
                if (e.target.value === '0') {
                    this.changeTaxvat(this.options.taxvat, this.changeLabel, this.mask, 'CPF', '000.000.000-00', 'Nome', 'Sobrenome');
                } else {
                    this.changeTaxvat(this.options.taxvat, this.changeLabel, this.mask, 'CNPJ', '00.000.000/0000-00', 'Razão Social', 'Nome fantasia');
                }
            });
        },

        changeTaxvat: (element, fncLabel, fncMask, textLabel, mask, firstname, lastname) => {
            $(element).val('');
            fncLabel(element, textLabel, firstname, lastname);
            fncMask(element, mask);
        },

        mask: (element, mask) => {
            $(element).mask(mask);
        },

        changeLabel: (element, label, firstname, lastname) => {
            $('input#firstname').parent().parent().find('label.label').text(firstname);
            $('input#lastname').parent().parent().find('label.label').text(lastname);
            $(element).parent().parent().find('label.label').text(label);
        },
    });

    return $.mgu.listener;
});
