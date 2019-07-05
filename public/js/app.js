//
(function($){
/** https://laravel.com/docs/5.6/csrf  */
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
/** .end */

//
$.myUtils = $.myUtils = {
    //
    js: {
        /**
         * Translate
         * @param {string} text arguments
         * @return array
         */
        _: function(text, params) {
            return text;
        },
        /**
         * Function's arguments to array
         * @param {object|array} args arguments
         * @return array
         */
        args2Arr: function(args) {
            return Array.prototype.slice.call(args);
        }
    },
    //
    php: {
        /**
         * Default number format
         * @param number number
         * @param number decimals
         * @param string decPoint
         * @param string thousandsSep
         * @return number
         */
        numberFormat: function(number, decimals, decPoint, thousandsSep) {
            if ('number_format' in window) {
                number = number_format(number, decimals, decPoint, thousandsSep);
            } else {
                console.warn('number_format NOT in window');
            }
            return number;
        },
        /**
         * Tax number format
         * @param number number
         * @param string decPoint
         * @param string thousandsSep
         * @return number
         */
        numberFormatTax: function(number, decPoint, thousandsSep) {
            return this.numberFormat(number, 2, decPoint, thousandsSep);
        },
        /**
         * Worktime number format
         * @param number number
         * @param string decPoint
         * @param string thousandsSep
         * @return number
         */
        numberFormatWorktime: function(number, decPoint, thousandsSep) {
            return this.numberFormat(number, 3, decPoint, thousandsSep);
        }
    }
    //.end#php
};
//.
})(jQuery);
