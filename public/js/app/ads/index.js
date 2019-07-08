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
            if ('script' === evt.action) {
                //
                if (evt.row) {
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
            // toolbar: print
            tbItems.push({
                "toolbar": "script",
                "class" : "btn btn-sm btn-warning",
                "icon" : "glyphicon glyphicon-cog",
                "text" : "&lt;script /&gt;",
                "title" : "Embedded script"
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
    });
})(jQuery);
