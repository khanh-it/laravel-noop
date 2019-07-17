/**
 * system::utility::template
 */
(function($) {
    // On document ready
    $(function () {
        // Define vars
        var mJWids = $.mainJqxWidgets;
        var phpData = mJWids.$layout.data('php') || {};
        //
        var $jqxWindow = $('#jqxWindow');
        var $jqxWindowCode = $('#jqxWindowCode');
        var $targetForm = $('#form1st');
        var $form2nd = $('#form2nd');

        // prepare jqxWindow for CRUD actions
        mJWids.prepareWindowCRUD($jqxWindow);

        /**
         *
         */
        function handleActions(evt) {
            // @TODO
            $jqxWindow.attr('data-jqxaction', evt.action);
            //
            // Case: create
            if ('create' === evt.action) {
                // Reset, refill form data
                mJWids.helper.fillFormData($targetForm);
                // Show form
                $jqxWindow.jqxWindow('title', 'Thêm ads.');
                $jqxWindow.jqxWindow('open');
                evt.break = true;
            }
            // Case: update
            if ('update' === evt.action) {
                //
                if (evt.row) {
                    // Reset, refill form data
                    mJWids.helper.fillFormData($targetForm, evt.row);
                    // Show form
                    $jqxWindow.jqxWindow('title', 'Sửa ads.');
                    $jqxWindow.jqxWindow('open');
                }
                evt.break = true;
            }
            // Case: print
            if ('print' === evt.action) {
                if (evt.row) {
                    window.open(
                        (phpData.routes.export.replace('_id_', evt.row.rowid) + '?act=print'),
                        ('Printing...(' + evt.row.template_name + ')')
                    );
                }
                evt.break = true;
            }
            // Case: script
            if ('script' === evt.action && evt.row) {
                var html = $form2nd.data('html');
                if (!html) {
                    $form2nd.data('html', html = $.trim($form2nd.html()));
                }
                $form2nd.html(html
                    .replace(/__adsid__/g, new Date().getTime())
                    .replace(/__adshash__/g, evt.row.ads_hash)
                );
                // Show form
                $jqxWindowCode.jqxWindow('open');
            }
            // rpt
            if ('rpt' === evt.action && evt.row) {
                var cWin = $(window).data('_cWinRpt_Ads');
                var url = (phpData.routes.report.replace('_id_', evt.row.rowid));
                if (!cWin || (('closed' in cWin) && cWin.closed)) {
                    cWin = window.open(
                        url,
                        $.myUtils.js._('Thống kê'),
                        [
                            'width=1024',
                            'height=768',
                            'menubar=0',
                            'status=0',
                            'titlebar=0'
                        ].join(','),
                        true
                    );
                    $(window).data('_cWinRpt_Ads', cWin);
                } else {
                    cWin.location = url;
                }
                cWin.focus();
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
            // mJWids.removeToolbarItems(['show']);
            // toolbar: script
            tbItems.push({
                "toolbar": "script",
                "class" : "btn btn-sm btn-warning",
                "icon" : "glyphicon glyphicon-cog",
                "text" : "&lt;script /&gt;",
                "title" : "Embedded script"
            });
            // toolbar: rpt
            tbItems.push({
                "toolbar": "rpt",
                "class" : "btn btn-sm btn-primary",
                "icon" : "glyphicon glyphicon-stats",
                "text" : "Report",
                "title" : "Thống kê"
            });
        });
        //.end
        /**
         * Remove grid context menu item(s).
         */
        mJWids.$grid.on('initMenuItems', function(evt, mnuItems) {
            // mJWids.removeMenuItems(['create', 'show', 'delete']);
        });
        //.end

        /**
         *
         */
        $targetForm.on('responseOK', function(event, response, winactCB) {
            // Refresh grid
            mJWids.$grid.triggerHandler('jqxGrid.updatebounddata');
            // Close window?
            if (winactCB) {
                winactCB();
            }
        });
        //.end

        // Implement tags
        var $tags = $('#tags');
        $tags.on('open', function (event) {
            var $popup = $(event.args.popup);
            $popup.css('z-index', 1810);
        });
        $tags.jqxTextArea({
            placeHolder: $.myUtils.js._("Enter tags name..."),
            width: '100%', height: '3rem',
            source: function (query, response) {
                var item = query.split(/,\s*/).pop();
                // update the search query.
                $tags.jqxTextArea({ query: item });
                // remote search
                mJWids.myJqxSearchTagName(item, response);
            },
            renderer: function (itemValue, inputValue) {
                var terms = inputValue.split(/,\s*/);
                // remove the current input
                terms.pop();
                // add the selected item
                terms.push(itemValue);
                // add placeholder to get the comma-and-space at the end
                terms.push("");
                var value = terms.join(", ");
                return value;
            }
        });
        //.end
    });
})(jQuery);
