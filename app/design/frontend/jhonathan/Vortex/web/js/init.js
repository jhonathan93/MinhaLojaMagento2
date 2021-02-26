/**
 * @author Jhonathan da silva
 * @link https://github.com/jhonathan93
 * @link https://www.linkedin.com/in/jhonathan-silva-367541171/
 * @package Jhonathan_Core
 */

define([
    'jquery',
    'mask',
    'debounce'
], ($, mask, debounce)=> {
    'use strict';

    $.widget('jhonathan.start', {
        options: {
            elements : '',
            mask: '',
            validate: ''
        },

        _create: function () {
            $.map(this.options.mask, (value, index) => {
                if (value !== 'false') {
                    this.setMask(this.options.elements[index], value);
                }
            });

            $.map(this.options.validate, (value, index) => {
                if (value !== 'false') {
                    debounce.listenerDebounce(this.options.elements[index]);
                }
            });
        },

        setMask: (element, mask) => {
            $(element).mask(mask);
        }
    });

    return $.jhonathan.start;
});
