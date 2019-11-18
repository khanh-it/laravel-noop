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
            var $myGrid = mJWids.$grid;
            // @var {Function}
            // Helper: remove paging params
            function urlAll(url) {
                return url
                    .replace(/&?pagenum=\d+/g, '')
                    .replace(/&?recordstartindex=\d+/g, '')
                    .replace(/&?recordendindex=\d+/g, '')
                    .replace(/&?pagesize=\d+/g, '&pagesize=' + (Math.pow(2, 31) - 1))
                ;
            }
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
            // rpt: del + excel
            var actRptDel = null, actRptDelAll = null;
            if (('rpt-del' === evt.action) || (actRptDelAll = ('rpt-del-all' === evt.action))) {
                actRptDel = evt.action;
            }
            var actRptExcel = null, actRptExcelAll = null;
            if (('rpt-excel' === evt.action) || (actRptExcelAll = ('rpt-excel-all' === evt.action))) {
                actRptExcel = evt.action;
            }
            if (actRptDel || actRptExcel) {
                var rows = $myGrid.jqxGrid('getrows');
                var pid = [];
                for (var i = 0; i < rows.length; i++) {
                    var row = rows[i];
                    if (row) { pid.push(row.rpt_id); }
                }
                if (pid.length > 0) {
                    pid = ('&pid=' + pid.join());
                    if (actRptDel) {
                        var result = window.confirm("LƯU Ý: THAO TÁC XÓA DỮ LIỆU KHÔNG THỂ PHỤC HỒI. Vui lòng xác nhận xóa dữ liệu thống kê.");
                        if (result) {
                            $myGrid.one('jqxGrid.source.beforesend', function(evt, jqXHR, settings) {
                                var url = urlAll(settings.url + (!actRptDelAll ? pid : '') + ('&act=' + actRptDel));
                                jqXHR.abort(); // prevent ajax call
                                location.assign(url);
                            });
                            $myGrid.triggerHandler('jqxGrid.updatebounddata');
                        }
                    }
                    // +++
                    if (actRptExcel) {
                        $myGrid.one('jqxGrid.source.beforesend', function(evt, jqXHR, settings) {
                            var url = urlAll(settings.url + (!actRptExcelAll ? pid : '') + ('&act=' + actRptExcel));
                            jqXHR.abort(); // prevent ajax call
                            location.assign(url);
                            setTimeout(function() {
                                $myGrid.triggerHandler('jqxGrid.updatebounddata');
                            });
                        });
                        $myGrid.triggerHandler('jqxGrid.updatebounddata');
                    }
                } else {
                    alert("Chưa có dữ liệu thao tác.");
                }
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
                "class" : "btn btn-sm btn-warning",
                "icon" : "glyphicon glyphicon-remove-circle",
                "text" : "Đóng",
                "title" : "Đóng",
                "default": true,
            });
            // xoa du lieu theo trang hien tai
            tbItems.push({
                "toolbar": "rpt-del",
                "class" : "btn btn-sm btn-danger",
                "icon" : "glyphicon glyphicon-trash",
                "text" : "Xóa dữ liệu",
                "title" : "Xóa dữ liệu theo trang",
                "default": true,
            });
            // xoa tat ca du lieu
            tbItems.push({
                "toolbar": "rpt-del-all",
                "class" : "btn btn-sm btn-danger",
                "icon" : "glyphicon glyphicon-trash",
                "text" : "Xóa tất cả",
                "title" : "Xóa tất cả dữ liệu",
                "default": true,
            });
            // export excel du lieu theo trang hien tai
            tbItems.push({
                "toolbar": "rpt-excel",
                "class" : "btn btn-sm btn-success",
                "icon" : "glyphicon glyphicon-download-alt",
                "text" : "Xuất excel",
                "title" : "Xuất excel dữ liệu theo trang",
                "default": true,
            });
            // export excel tat ca du lieu
            tbItems.push({
                "toolbar": "rpt-excel-all",
                "class" : "btn btn-sm btn-success",
                "icon" : "glyphicon glyphicon-download-alt",
                "text" : "Xuất excel tất cả",
                "title" : "Xuất excel tất cả dữ liệu",
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
