/**
 *
 */
(function($) {
    // Proxy: forward calls cellsRenderer
    // @var function
    window.jqxGridCellsRenderer = function() {
        return $.jqxGridCellsRenderer && $.jqxGridCellsRenderer.apply($, arguments);
    };
    //.end

    // Proxy: forward calls editor actions
    // @var function
    window.mkJqxGridEditor = function(type, columnName) {
        var callback = null;
        switch (type) {
            case 0: // create
                callback = function() {
                    if ($.jqxGridCreateEditor) {
                        var args = $.myUtils.js.args2Arr(arguments);
                        args.unshift(columnName);
                        $.jqxGridCreateEditor.apply(this, args);
                    }
                };
                break;
            case 1: // init
                callback = function() {
                    if ($.jqxGridInitEditor) {
                        var args = $.myUtils.js.args2Arr(arguments);
                        args.unshift(columnName);
                        $.jqxGridInitEditor.apply(this, args);
                    }
                };
                break;
            case 2: // get value
                callback = function() {
                    if ($.jqxGridGetEditorValue) {
                        var args = $.myUtils.js.args2Arr(arguments);
                        args.unshift(columnName);
                        $.jqxGridGetEditorValue.apply(this, args);
                    }
                };
                break;
        }
        return callback;
    };
    //.end

    // Grid aggregates
    // @var function
    window.mkJqxGridAggregates = function(/* columnName */) {
        var aggr = undefined;
        /* switch (columnName) { case 'salary_subtotal': */
            aggr = [{
                '' : function(aggVal, curVal/*, column, record*/) {
                    return curVal ? aggVal + (1 * curVal) : aggVal;
                }
            }];
        /* break; } */
        return aggr;
    };
    //.end
})(jQuery);
