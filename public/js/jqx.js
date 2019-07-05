/**
 *
 */
(function($){
    var _type = "custom"; // toolbar item type
    var _pos = 'last'; // toolbar position
    var _sep = false; // seperator?
    //
    var mJWids = $.mainJqxWidgets = $.mainJqxWidgets || {
        /**
         * @var {object} default jqx layout component
         */
        $layout: $('#jqxLayout'),
        /**
         * @var {object} default jqx loader component
         */
        $loader: $("#jqxLoader")
            .on('create', function(event) {
                mJWids.$loader.css({ zIndex: 99999 });
            })
            .each(function(){
                $(this).jqxLoader({ text: "", isModal: true, width: 60, height: 40 });
            })
        ,
        /**
         * Define page main toolbar
         * @see https://www.jqwidgets.com/jquery-widgets-documentation/documentation/jqxtoolbar/jquery-toolbar-getting-started.htm
         */
        $toolbar: $('#page-toolbar').jqxToolBar({
            width: '100%', height: 'auto', tools: '', initTools: function(){}
        }),
        /**
         * @var {object} default jqx grid component
         */
        $grid: $('#jqxGrid')
            /**
             * Helper: refreshdata of jqxGrid
             * @return void
             */
            .on('jqxGrid.refreshdata', function() {
                var pos = mJWids.$grid.jqxGrid('scrollposition');
                mJWids.$grid.jqxGrid('refreshdata');
                mJWids.$grid.jqxGrid('scrolloffset', pos.top, pos.left);
            })
            /**
             * Helper: refreshdata of jqxGrid
             * @return void
             */
            .on('jqxGrid.updatebounddata', function() {
                var pos = mJWids.$grid.jqxGrid('scrollposition');
                var groups = mJWids.$grid.jqxGrid('groups');
                mJWids.$grid.jqxGrid('updatebounddata');
                $.map(groups, function(dfd) {
                    mJWids.$grid.jqxGrid('addgroup', dfd);
                });
                mJWids.$grid.jqxGrid('scrolloffset', pos.top, pos.left);
            })
        ,
        /**
         * @var {object} helper
         */
        helper: {
            /**
             * @return string
             */
            bindHref: function(href, id) {
                var _this = mJWids;
                var rowSelected = _this.getRowSelected();
                if ((null === id || undefined === id) && rowSelected) {
                    id = rowSelected.rowid;
                }
                if (id && href) {
                    href = href.replace('_id_', encodeURIComponent(id));
                }
                return href;
            },
            /**
             * Helper: fill form data
             * @param {object} targetform The form
             * @param {object} data
             * @return void
             */
            fillFormData: function(form, data) {
                var _this = mJWids;
                // Fill data
                var $targetForm = $(form).filter('form');
                if ($targetForm.length) {
                    // Set form data
                    // var rowSelected = _this.getRowSelected();
                    // data = !data ? (rowSelected && rowSelected.row) : data;
                    if (data) {
                        // @TODO: fix timeout with jqxNumberInput
                        setTimeout(function() {
                            var $ele, value;
                            for (var prop in data) {
                                value = (null === data[prop]) ? '' : data[prop];
                                $ele = $targetForm.find('[name="' + prop + '"]');
                                if ($ele.length && !(/*null === value || */undefined === value)) {
                                    // Special case(s): jqxwidget(s)
                                    var $jqx = null;
                                    var dataKey = $.trim($ele.attr('id')).match(/jqx\w+$/);
                                    dataKey = (dataKey || [])[0];
                                    if (dataKey && $ele.data(dataKey)) {
                                        $jqx = $ele;
                                        $ele = $($ele.data(dataKey).instance.element);
                                    }
                                    if ($ele.is(':checkbox')) {
                                        $ele.prop('checked', ($ele.val() == value));
                                    } else {
                                        $ele.val(value);
                                    }
                                    // @TODO: open this??? $ele.trigger('change');
                                }
                            }
                        }, 128);
                    }
                    // Reset form
                    $targetForm.get(0).reset();
                }
            },
            /**
             * Reset form's validation states
             * @param {object} targetform Dom form
             * @param {object} response Form's submit response
             * @return void
             */
            resetFormValidationStates: function(targetform) {
                var $targetForm = $(targetform);
                $targetForm.parent()
                    .find('.form-resp').remove().end()
                    .find('.form-group')
                        .removeClass('has-success has-warning has-error')
                        .filter('.gen-feedback')
                            .find('.form-control-feedback').remove().end()
                        .end()
                    .end()
                ;
            },
            /**
             * Render form's validation states
             * @param {object} targetform Dom form
             * @param {object} response Form's submit response
             * @return void
             */
            renderFormValidationStates: function(targetform, response) {
                var $targetForm = $(targetform);
                var states = {
                    'success': {
                        'fgclass': 'has-success',
                        'hbclass': 'bg-success text-success',
                        'fbclass': 'glyphicon glyphicon-ok form-control-feedback',
                    },
                    'warning': {
                        'fgclass': 'has-warning',
                        'hbclass': 'bg-warning text-warning',
                        'fbclass': 'glyphicon glyphicon-warning-sign form-control-feedback',
                    },
                    'errors': {
                        'fgclass': 'has-error',
                        'hbclass': 'bg-danger text-danger',
                        'fbclass': 'glyphicon glyphicon-remove form-control-feedback',
                    }
                };
                // Reset previous validation states
                this.resetFormValidationStates($targetForm);
                //.end
                // Render validation states
                $.each(states, function(state, stateOpts) {
                    var items = (response && response[state]);
                    if (items) {
                        $.each(items, function(name, msgs) {
                            //
                            var $inputEle, $inputPrEle, $fgEle;
                            try {
                                $inputEle = $targetForm.find('[name="' + name + '"]');
                                $inputPrEle = $inputEle.parent();
                                if ($inputPrEle.hasClass('input-group')) {
                                    $inputPrEle = $inputPrEle.parent();
                                }
                                $fgEle = $targetForm.find('.form-group').has($inputEle);
                            } catch (e) {
                                //...
                            }
                            // Build
                            var html = '<div class="help-block form-resp form-resp-' + (state + (' ' + stateOpts.hbclass)) + '">';
                            html += '<ul>';
                            $.each(msgs, function(idx2, msg) {
                                html += ('<li>' + msg + '</li>');
                            });
                            html += '</ul></div>';
                            // +++ render
                            $fgEle.addClass(stateOpts.fgclass);
                            if ($fgEle.hasClass('gen-feedback') && $inputPrEle.length) {
                                $fgEle.addClass('has-feedback');
                                $inputPrEle.append('<span class="' + stateOpts.fbclass + '" aria-hidden="true"></span>');
                            }
                            if ($inputPrEle.length) {
                                $inputPrEle.append(html);
                            } else {
                                $targetForm.before(html);
                            }
                        });
                    }
                });
            },
            /**
             * @param {string} msg Confirm message
             * @param {function} callback A callback function
             * @return void
             */
            confirm: function(msg, callback) {
                var result = window.confirm(msg);
                callback(result);
            },
            /**
             * Patch: fix jqxNumberInput will not trigger events for <input /> elements
             * @param {object} options
             * @return jQuery instance of dummy element
             */
            jqxNumberInput: function(inputEles, options) {
                var $inputEles = $(inputEles).hide();
                var $dummyEles = null;
                // Format input(s)
                $inputEles.each(function() {
                    var $this = $(this);
                    var opts = $.extend({
                        "width": "100%",
                        "height": "100%",
                        "spinButtons": true,
                        "min": 1  * ($this.attr('min') || 0),
                        "max": 1  * ($this.attr('max') || Number.MAX_SAFE_INTEGER),
                        "value": this.value
                    }, options);
                    var _optDigits = 1 * $this.data('digits');
                    if (!isNaN(_optDigits)) {
                        opts.digits = _optDigits;
                    }
                    var _optDecimalDigits = 1 * $this.data('decimals');
                    if (!isNaN(_optDecimalDigits)) {
                        opts.decimalDigits = _optDecimalDigits;
                    }
                    //.end
                    var $dummy = $('<div />')
                        .attr({ 'class' : $this.attr('class') })
                        .insertAfter(this)
                        // Event handler(s) for jqxNumberInput
                        .on('change', function(event) {
                            $this.val(event.args.value).trigger('change', [event]);
                        })
                        // .on('textchanged', cb)
                        // .on('valueChanged', cb)
                    ;
                    $dummy.jqxNumberInput(opts);
                    // Auto sync data with input elements
                    $this.on('change', function(event, jqxNumberInputEvt) {
                        // Case: event fired by normal trigger
                        if (event && !jqxNumberInputEvt) {
                            $dummy.jqxNumberInput('val', this.value);
                        }
                    });
                    // Add return result!
                    $dummyEles = (null === $dummyEles) ? $dummy : $dummyEles.add($dummy);
                });
                return $dummyEles;
            },

            /**
             * check permissons user
             * @param {object} options
             * @return array permissons
             */
            checkPermissons: function(permissons) {
                let $permissons = [];

                if(permissons.create === false){
                    $permissons.push('create');
                }

                if(permissons.delete === false){
                    $permissons.push('delete');
                }

                if(permissons.print_export === false){
                    $permissons.push('export::xls','print','export-details::xls');
                }

                if(permissons.update === false){
                    $permissons.push('update');
                }

                return $permissons;
            },

        },
        /**
         * context menu
         */
        menu: null,
        // List of default toolbar items
        tbItems: [
            {
                "toolbar": "create",
                "class" : "btn btn-sm btn-primary",
                "icon" : "glyphicon glyphicon-plus",
                "text" : $.myUtils.js._("Thêm")
            },
            {
                "toolbar": "show",
                "class" : "btn btn-sm btn-info",
                "icon" : "glyphicon glyphicon-info-sign",
                "text" : $.myUtils.js._("Xem")
            },
            {
                "toolbar": "update",
                "class" : "btn btn-sm btn-success",
                "icon" : "glyphicon glyphicon-edit",
                "text" : $.myUtils.js._("Sửa")
            },
            {
                "toolbar": "delete",
                "class" : "btn btn-sm btn-danger",
                "icon" : "glyphicon glyphicon-minus",
                "text" : $.myUtils.js._("Xóa")
            }/*,
            {
                "toolbar": "export::xls",
                "class" : "btn btn-sm btn-primary",
                "icon" : "glyphicon glyphicon-export",
                "text" : $.myUtils.js._("Xuất: Excel")
            },
            {
                "toolbar": "export::pdf",
                "class" : "btn btn-sm btn-primary",
                "icon" : "glyphicon glyphicon-export",
                "text" : $.myUtils.js._("Xuất: PDF")
            }*/,
            {
                "toolbar": "refresh",
                "class" : "btn btn-sm btn-default",
                "icon" : "glyphicon glyphicon-refresh",
                "text" : $.myUtils.js._("Nạp"),
                "sep" : true // seperator
            }
        ],
        /**
         * Add toolbar item
         */
        addToolbar: function(cb, type, sep, pos) {
            type = (typeof type === 'string') ? type : _type;
            pos = (typeof pos === 'string') ? pos : _pos;
            sep = (typeof sep === 'boolean') ? sep : _sep;
            if (typeof cb === 'string') {
                var html = cb;
                cb = function(type, $tool) { $tool.html(html); }
            }
            this.$toolbar.jqxToolBar('addTool', type, pos, sep, cb);
        },
        /**
         * Remove toolbar item(s)
         */
        removeToolbarItems: function(tbItem) {
            if (true === tbItem) {
                this.tbItems.splice(0, this.tbItems.length);
                return this;
            }
            var tbItems = (tbItem instanceof Array) ? tbItem : [tbItem];
            for (var k in tbItems) {
                var toolbar = tbItems[k];
                for (var i in this.tbItems) {
                    if (this.tbItems[i].toolbar === toolbar) {
                        this.tbItems.splice(1 * i, 1);
                    }
                }
            }
        },
        // Grid's context menu props
        menuData: {
            'width': 150,
            'autoOpenPopup': false,
            'mode': 'popup',
            'source': [
                {
                    "value" : "create",
                    "html" : '<i class="glyphicon glyphicon-plus"></i> Thêm',
                },
                {
                    "value" : "show",
                    "html" : '<i class="glyphicon glyphicon-info-sign"></i> Xem',
                },
                {
                    "value" : "update",
                    "html" : '<i class="glyphicon glyphicon-edit"></i> Sửa',
                    "default": true
                },
                {
                    "value" : "delete",
                    "html" : '<i class="glyphicon glyphicon-minus"></i> Xóa',
                },
                { "label" : "|" }, // seperator
                {
                    "value" : "refresh",
                    "html" : '<i class="glyphicon glyphicon-refresh"></i> Nạp',
                }
            ]
        },
        /**
         * Remove toolbar item(s)
         */
        removeMenuItems: function(mnuItem) {
            if (true === mnuItem) {
                this.menuData.source.splice(0, this.menuData.source.length);
                return this;
            }
            var mnuItems = (mnuItem instanceof Array) ? mnuItem : [mnuItem];
            var source = this.menuData.source || [];
            for (var k in mnuItems) {
                var value = mnuItems[k];
                for (var i in source) {
                    if (source[i].value === value) {
                        source.splice(1 * i, 1);
                    }
                }
            }
        },
        //.end
        /**
         * Get page's defined routes (if any)
         * @param {string|undefined} name Route name
         * @return mixed
         */
        getRoutes: function(action) {
            var phpdata = this.$layout.data('php') || {};
            var routes = phpdata.routes || {};
            if (action) {
                routes = this.helper.bindHref(routes[action]);
            }
            return routes;
        },
        /**
         * @param object $grid The grid (if any)
         * @return null|object
         */
        getRowSelected: function($grid, rowindex) {
            var _this = this;
            if (!$grid) {
                $grid = _this.$grid;
            }
            rowindex = (undefined === rowindex) ? $grid.jqxGrid('getselectedrowindex') : rowindex;
            var rowdata = null;
            var rowid = null;
            if (rowindex >= 0) {
                rowdata = $grid.jqxGrid('getrowdata', rowindex);
                if (rowdata) {
                    var source = $grid.jqxGrid('source');
                    rowid = rowdata[((source && source._source) || {}).id];
                }
                $.extend(rowdata, { 'rowid': rowid });
            }
            return rowdata;
        },

        /**
         *
         * @param {string} action
         * @param {string} type
         * @return mixed
         */
        handleActions: function(action, type)
        {
            var _this = this;
            //
            action = ($.trim(action).toLowerCase().split('::') || []);
            var subact = $.trim(action[1]);
            action = $.trim(action[0]);
            // Fire event, handle action
            var evt = {
                "type": type,
                "action": action,
                "subact": subact,
                "row": _this.getRowSelected(),
                "break" : false
            };
            _this.$layout.triggerHandler('actionclick', [evt]);
            // --> return if break
            if (true === evt.break) {
                return;
            }
            //.end
            // Get data
            var row = _this.getRowSelected();
            var url = _this.getRoutes(action);
            // +++
            var isCreate = ('create' === action);
            var isShow = ('show' === action);
            var isUpdate = ('update' === action);
            var isDelete = ('delete' === action);
            var isRefresh = ('refresh' === action);
            var isExport = (0 === action.indexOf('export'));
            // Case: export(s)?
            if (isExport) {
                return _this.$grid.jqxGrid('exportdata'
                    , subact/* dataType */
                    , 'data_' + (new Date().getTime().toString())/* fileName */
                    , true/* exportHeader */
                    , null/* rows */
                    , true/* exportHiddenColumns */
                    , url/* serverURL */
                    , 'UTF-8'/** charSet */
                );
            }
            //.end
            // Case: refresh
            if (isRefresh) {
                return _this.$grid.triggerHandler('jqxGrid.updatebounddata');
            }
            // Case: update | delete | details
            if ((isUpdate || isDelete || isShow)) {
                url = row ? url : '';
                if (url) {
                    // case delete
                    if (isDelete) {
                        return _this.helper.confirm($.myUtils.js._('Vui lòng xác nhận xóa dữ liệu!'), function(result) {
                            if (!result) return;
                            // Show page's loader
                            _this.$loader.jqxLoader('open');
                            // Send request delete record
                            $.post(url, {}, function(result) {
                                // Close loading.
                                _this.$loader.jqxLoader('close');
                                // console.log('delete result: ', result);
                                if (result && result.status) {
                                    return _this.$grid.jqxGrid('updatebounddata');
                                }
                            });
                        });
                    }
                    // Case: update
                    else if (isUpdate) {
                        // ...
                    }
                    // Case: view
                    else if (isShow) {
                        // ...
                    }
                }
            }
            // Others
            // ---> redirect?
            if (url) {
                location.assign(url);
            }
        },
        //.end

        /**
         *
         */
        prepareWindowCRUD: function(_win)
        {
            var mJWids = this;
            var $jqxWindow = $(_win);
            // Create target iframe for target form(s)
            var $targetForm = $jqxWindow.find('form.targetform').each(function() {
                var $targetForm = $(this);
                var $targetIframe = $($targetForm.data('targetiframe'));
                if (!$targetIframe.length) {
                    var iframeName = new Date().getTime();
                    $targetIframe = $('<iframe style="display:none;" hidden />')
                        .attr('name', iframeName)
                        .on('load', function(){
                            var iframe = $targetIframe.get(0);
                            var contentDocument = iframe.contentDocument || iframe.contentWindow.document;
                            var text = $.trim($(contentDocument.body).text());
                            var response = null;
                            var err = ('' == text) ? new Error('empty') : null;
                            if (text) {
                                try {
                                    response = JSON.parse(text);
                                    err = null;
                                } catch (e) {
                                    err = e;
                                }
                                $targetForm.trigger('response', [err, response]);
                            }
                        })
                    ;
                    $targetForm
                        .attr('target', iframeName)
                        .data('targetiframe', $targetIframe)
                        .before($targetIframe)
                    ;
                }
                // Auto reset form's validation states when close window
                $jqxWindow.on('closed', function(){
                    mJWids.helper.resetFormValidationStates($targetForm);
                });
                //.end
            });
            //.end

            // Handle window's buttons click
            $jqxWindow.find('.jqx-window-inner-footer .btn').on('click', function(event) {
                event.preventDefault();
                //
                var $this = $(this);
                // Window actions
                var winact = $.trim($this.data('windowaction'));
                var winactCB = null;
                switch (winact) {
                    // case: hide|close window
                    case 'hide': case 'close': {
                        winactCB = function() {
                            $jqxWindow.jqxWindow(winact);
                        };
                    } break;
                }
                //
                var route = $.trim($this.data('route'));
                if (route) {
                    var jqxact = $.trim(route.split('.').pop());
                    // @TODO: validate
                    // +++
                    if (!$targetForm.length) {
                        return console.log('target form not found!');
                    }
                    // Reset form validation states
                    mJWids.helper.resetFormValidationStates($targetForm);
                    // +++ require rowdata?
                    var editMode = ('|edit|update|'.indexOf(jqxact) >= 0);
                    var rowSelected = mJWids.getRowSelected();
                    if (editMode && !rowSelected) {
                        return console.log('`rowdata` is required!');
                    }
                    // Create "_jqxact" data
                    var $formMethod = $targetForm.find('input[name="_jqxact"]');
                    if (!$formMethod.length) {
                        $formMethod = $('<input type="hidden" hidden name="_jqxact" />').prependTo($targetForm);
                    }
                    $formMethod.val(jqxact);
                    //.end
                    // Show page's loader
                    mJWids.$loader.jqxLoader('open');
                    // Set action + submit form
                    var action = $targetForm.data('action.' + route);
                    if (undefined === action || null === action) {
                        action = mJWids.getRoutes(route);
                    }
                    if (action) {
                        var boundAct = mJWids.helper.bindHref(action);
                        if (boundAct) {
                            action = boundAct;
                        }
                        $targetForm.attr('action', action);
                    }
                    // +++
                    return $targetForm.one('response', function(event, err, response) {
                        // console.log('form response: ', err, response);
                        // Close page's loader
                        mJWids.$loader.jqxLoader('close');
                        // Any error?
                        if (err) {
                            return $targetForm.trigger('responseERROR', [err]);
                        }
                        // Case: OK
                        var status = 1 * (response && response.status);
                        $targetForm.trigger('response' + (status ? 'OK' : 'NG'), [response, winactCB]);
                        // @TODO: If failed --> no reset form!
                        var noResetFlag = !!$this.data('formaction.noreset') || !status || editMode;
                        // Render response info, warnings, errors
                        mJWids.helper.renderFormValidationStates($targetForm, response);
                        // --> reset form data?!
                        !noResetFlag && $targetForm.get(0).reset();
                        // Fire callback?
                        if (status && winactCB) {
                            winactCB();
                        }
                    })
                    .submit();
                }
                // Fire winact callback?!
                winactCB && winactCB();
            });
            //.end
        },
        //.end
        /**
         *
         */
        __init: function __init() {
            var _this = this;
            // Grid
            //.end

            // Self destroy
            delete this['__init'];
        },
        /**
         *
         */
        __ready: function __ready() {
            var _this = this;

            var phpData = mJWids.$layout.data('php') || {};
            // kiểm tra hiện thị toolbar
            const permissons = phpData.permissons;
            // Fire event, allow sub script to modify toolbar items
            this.$toolbar.triggerHandler('initToolbarItems', [this.tbItems]);

            if(permissons){
                const $permissons = mJWids.helper.checkPermissons(permissons);
                mJWids.removeToolbarItems($permissons);
                mJWids.removeMenuItems($permissons);
            }
            //.end
            // Render toolbar item(s)
            $.each(this.tbItems, function(index, tbItem) {
                var html = ('<a href="'
                        + $.trim(tbItem.href || 'javascript:void(0);')
                        + '" class="' + $.trim(tbItem.class)
                        + '" role="button" data-toolbar="' + $.trim(tbItem.toolbar)
                        + '" data-index="' + $.trim(index)
                        + '" title="' + $.trim(tbItem.title || tbItem.text)
                        + '" target="' + $.trim(tbItem.target)
                    + '">')
                    + '<i class="' + tbItem.icon + '"></i> ' + tbItem.text
                + '</a>';
                _this.addToolbar(html, tbItem.type, tbItem.sep);
            });
            // +++ Binding events
            this.$toolbar.on('click', 'a[data-toolbar]', function(event) {
                event.preventDefault();
                _this.handleActions($(this).data('toolbar'), 'toolbar');
            });
            //.end
            // Grid
            this.$grid.triggerHandler('initMenuItems', [this.menuData]);
            // +++ context menu?!
            this.menu = $('<div></div>');
            if (this.menuData && this.menuData.source.length) {
                this.menu
                    .jqxMenu(this.menuData)
                    .on('itemclick', function (event) {
                        var $liItem = $(event.args);
                        var itemValue = $.trim($liItem.attr('item-value'));
                        return itemValue && _this.handleActions(itemValue, 'menu');
                    })
                ;
                // +++
                this.$grid.on('contextmenu', function() { return false; });
            }
            // +++
            this.$grid.on('rowclick', function (event) {
                // _this.$grid.jqxGrid('selectionmode', "singlerow");
                var args = event.args;
                if (args.rightclick) {
                    _this.$grid.jqxGrid('selectrow', args.rowindex);
                    var scrollTop = $(window).scrollTop();
                    var scrollLeft = $(window).scrollLeft();
                    _this.menu.jqxMenu('open',
                        parseInt(args.originalEvent.clientX) + scrollLeft,
                        parseInt(args.originalEvent.clientY) + scrollTop
                    );
                    return false;
                }
            });
            // +++ simulate column double click
            this.$grid.on('columnclick', function(event) {
                var colClickEvt = _this.$grid.data('columnClickEvent');
                if (colClickEvt) {
                    var period = (event.timeStamp - colClickEvt.timeStamp);
                    if (period > 0 && period <= 256) {
                        event.type = 'columndoubleclick';
                        _this.$grid.trigger(event);
                    }
                }
                _this.$grid.data('columnClickEvent', event);
            });
            // +++ Run default menu action on doubleclick!
            this.$grid.on('rowdoubleclick', function (event) {
                $.each(_this.menuData.source, function(index, srcItem){
                    if (true === srcItem.default) {
                        _this.handleActions(srcItem.value, 'menu');
                        return false;
                    }
                });
            });
            //.end
            // Self destroy
            delete this['__ready'];
        }
    };
    //.end
    // Init jqx widgets manager
    mJWids.__init();

    // On document ready
    $(function($) {
        //
        mJWids.__ready();
    });
    //.end
})(jQuery);
