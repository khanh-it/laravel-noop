/**
 * system/utility/template.details
 */
(function($) {
    // On document ready
    $(function() {
        var $win = $(window);
        var mJWids = $.mainJqxWidgets;
        var phpData = mJWids.$layout.data('php') || {};
        // +++ du lieu nghiep vu
        var majorData = phpData.major || {};
        // +++
        var phrases = phpData.phrases || {};
        // +++ elements
        var $templateContentBox = $('#template_content-box');
        var $templateContent = $('#template_content');

        // CKEDITOR
        var editor = (function(){
            var eToolbarHeight = 165 /* ckeditor's toolbar height */;
            var opts = {
                // width: '100%',
                height: Math.round($templateContentBox.height() - eToolbarHeight) + 'px',
            }
            return (window.__editor = CKEDITOR.replace($templateContent.get(0), opts));
        })();
        //.end

        function _print(htmlBody, opts)
        {
            //
            opts = (typeof opts === "object" ? opts : {});
            //
            var newWindow = window.open('', '', [
                'height=' + screen.height,
                'width=' + screen.width,
                'fullscreen=yes' // only works in IE, but here for completeness
            ].join(',')),
            document = newWindow.document.open(),
            pageContent =
                '<!DOCTYPE html>\n' +
                '<html>\n' +
                '<head>\n' +
                    '<meta charset="utf-8" />\n' +
                    ('<title>' + (opts.title || '') + '</title>\n') +
                '</head>\n' +
                '<body>\n' + htmlBody + '\n</body>\n</html>'
            ;
            document.write(pageContent);
            document.close();
            newWindow.print();
            newWindow.close();
        }

        /**
         *
         */
        var mJWidsHandleActions = mJWids.handleActions;
        mJWids.handleActions = function handleActions(action, type) {
            // Case: update (luu thay doi)
            if ('update' === action) {
                // Show loading
                mJWids.$loader.jqxLoader('open');
                //
                $.post(phpData.routes.update, { "type": "update_template_content", "data": editor.getData() }, function(result) {
                    var msgErr = 'Lưu không thành công, vui lòng thử lại..!';
                    try {
                        result = (typeof result == 'string') ? $.parseJSON(result) : result;
                        if (result && result.status) {
                            msgErr = '';
                        }
                    } catch (error) {
                        console.log(error);
                    }
                    if (msgErr) {
                        alert(msgErr);
                    }
                })
                .complete(function(){
                    // Hide loading
                    mJWids.$loader.jqxLoader('close');
                });
            }
            //.end
            // Case: in (print)
            if ('print' === action) {
                _print(editor.getData(), 'print me now...');
            }
            //.end
            // Case: back (tro lai)
            if ('index' === action) {
                return window.location = phpData.routes.index;
            }
            //.end
        }

        /**
         * Alter toolbar item(s).
         */
        mJWids.$toolbar.on('initToolbarItems', function(evt, tbItems) {
            mJWids.removeToolbarItems(['create', 'show', 'update', 'delete', 'refresh', 'export_excel']);
            // toolbar: tro lai man hinh danh sach
            tbItems.push({
                "toolbar": "index",
                "class" : "btn btn-sm btn-info",
                "icon" : "glyphicon glyphicon-arrow-left",
                "text" : "Trở lại"
            });
            // toolbar: luu thay doi
            tbItems.push({
                "toolbar": "update",
                "class" : "btn btn-sm btn-warning",
                "icon" : "glyphicon glyphicon-floppy-disk",
                "text" : "Lưu",
                "title" : "Lưu bảng lương"
            });
            // toolbar: print
            tbItems.push({
                "toolbar": "print",
                "class" : "btn btn-sm btn-primary",
                "icon" : "glyphicon glyphicon-print",
                "text" : "In",
                "title" : "In tag"
            });
        });
        //.end
        /**
         * Remove grid context menu item(s).
         */
        mJWids.$grid.on('initMenuItems', function(evt, mnuItems) {
            mJWids.removeMenuItems(true);
        });
        //.end
    });
})(jQuery);
