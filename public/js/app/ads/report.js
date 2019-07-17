/**
 * ads::report
 */
(function($) {
    // On document ready
    $(function () {
        // Define vars
        var mJWids = $.mainJqxWidgets;
        var phpData = mJWids.$layout.data('php') || {};
        //
        var $jqxWindow = $('#jqxWindow');
        var $targetForm = $('#form1st');
        var parser = new UAParser();

        // prepare jqxWindow for CRUD actions
        mJWids.prepareWindowCRUD($jqxWindow);

        /**
         *
         */
        function handleActions(evt) {
            // @TODO
            $jqxWindow.attr('data-jqxaction', evt.action);
            //
            if ('show' === evt.action) {
                if (evt.row) {
                    // Modify data
                    var ua = parser.setUA(evt.row.rpt_ua).getResult();
                    $.extend(evt.row, {
                        'ua_platform': ua.os.name + ' ' + ua.os.version,
                        'ua_browser': ua.browser.name + ' ' + ua.browser.version
                    });
                    // Reset, refill form data
                    mJWids.helper.fillFormData($targetForm, evt.row);
                    // Show form
                    $jqxWindow.jqxWindow('title', $.myUtils.js._('Chi tiết'),);
                    $jqxWindow.jqxWindow('open');
                }
                evt.break = true;
            }
            // rpt
            if ('close' === evt.action) {
                window.close();
                evt.break = true;
            }
            //.end
            return evt.break;
        }
        /**
         *
         */
        mJWids.$layout.on('actionclick', function (jEvt, evt) {
            evt.break = handleActions(evt);
        });

        /**
         * Alter toolbar item(s).
         */
        mJWids.$toolbar.on('initToolbarItems', function(evt, tbItems) {
            mJWids.removeToolbarItems(['create', 'update', 'delete']);
            // toolbar: tro lai man hinh danh sach
            tbItems.push({
                "toolbar": "close",
                "class" : "btn btn-sm btn-danger",
                "icon" : "glyphicon glyphicon-remove-circle",
                "text" : "Đóng",
                "title" : "Đóng",
                "default": true,
            });
        });
        //.end
        /**
         * Remove grid context menu item(s).
         */
        mJWids.$grid.on('initMenuItems', function(evt, mnuItems) {
            mJWids.removeMenuItems(['create', 'update', 'delete']);
            $.each(mJWids.menuData.source, function(idx, srcItem) {
                if ('show' == srcItem.value) {
                    srcItem.default = true;
                }
            });
        });
        //.end
    });
})(jQuery);
