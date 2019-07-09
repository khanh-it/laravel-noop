/**
 * app/tag/_utils
 */
(function($) {
    // On document ready
    $(function () {
        // Define vars
        var mJWids = $.mainJqxWidgets;

        // Extend utils
        $.extend(mJWids, {
            /**
             * @param {Object} opts A data object
             * @param {Function} cb A callback function
             * @return void
             */
            myJqxSearchTag: function myJqxSearchTag(opts, cb) {
                return mJWids.myJqxSearch('../tag/index', opts, function(result) {
                    var data = [];
                    $.each(result.Rows || [], function(idx, obj) {
                        data.push(obj.tag_name + ' [' + obj.tag_id + ']');
                    });
                    // Fire callback
                    (cb || $.noop)(data, result);
                });
            },
            /**
             * @param {String} value Data
             * @param {Function} cb A callback function
             * @return void
             */
            myJqxSearchTagName: function myJqxSearchTagName(value, cb) {
                return this.myJqxSearchTag({
                    "filter": { "field": "tag_name", "value": value }
                }, cb);
            }
        });
    });
})(jQuery);
