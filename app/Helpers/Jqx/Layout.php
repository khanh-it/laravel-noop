<?php

namespace App\Helpers\Jqx;

use App\Helpers\Jqx\Layout\Item;
use Closure;

/**
 * @author
 */
class Layout extends Widget
{
    /**
     * jQuery Selector to init element!
     * @var string
     */
    public $selector = '#jqxLayout';

    /**
     * Widget instance name!
     * @var string
     */
    public $varInstanceName  = 'jqxLayoutMain';

    /**
     * @var string
     */
    public $varLayoutName  = 'layout';

    /**
     * Get, format jqx grid's filter, sort, paging,...params
     * @return array
     */
    public static function requestPayload()
    {
        // @var Illuminate\Http\Request
        $request = request();
        $data = $request->only([
            //
            "_jqxact",
            // sorting
            "sortdatafield",
            "sortorder",
            // paging
            "pagenum",
            "pagesize",
            "recordstartindex",
            "recordendindex",
            // filter
            "filterGroups",
            // group
            "groupGroups"
        ]);
        // var_dump($data); die();
        $data[($key = 'pagenum')] = intval($data[$key]);
        $data[($key = 'pagesize')] = intval($data[$key]);
        $data[($key = 'recordstartindex')] = intval($data[$key]);
        $data[($key = 'recordendindex')] = intval($data[$key]);
        // Filter groups
        $filterGroups = [];
        foreach ((array)$data[($key = 'filterGroups')] as $fGroup) {
            $filterGroups[$fGroup['field']] = $fGroup;
        }
        $data[$key] = $filterGroups;
        unset($filterGroups);
        //.end
        $data[($key = 'groupGroups')] = (array)($data[$key]);
        //.end
        // var_dump($data); die();
        return $data;
    }

    /**
     * 
     */
    protected function _onRequest($_act, $callback, array $options = array())
    {
        // Get request's payload!
        $data = static::requestPayload();
        // +++
        $_act = (array)$_act;
        //
        list($act) = explode('_', trim($data["_jqxact"]));
        if (in_array($act, $_act)) {
            return call_user_func($callback, $data, $this, $options);
        }
    }
    /**
     * @proxy $this::_onRequest
     */
    public function onRequestRead($callback, array $options = array())
    {
        return $this->_onRequest('read', $callback, $options);
    }
    /**
     * @proxy $this::_onRequest
     */
    public function onRequestCreate($callback, array $options = array())
    {
        return $this->_onRequest(['create', 'store'], $callback, $options);
    }
    /**
     * @proxy $this::_onRequest
     */
    public function onRequestUpdate($callback, array $options = array())
    {
        return $this->_onRequest(['edit', 'update'], $callback, $options);
    }
    /**
     * @proxy $this::_onRequest
     */
    public function onRequestDelete($callback, array $options = array())
    {
        return $this->_onRequest(['delete', 'destroy'], $callback, $options);
    }

    /**
     * jqx's grid standard response
     * @param mixed $rows
     * @param integer $totalRows
     * @return use Illuminate\Http\Response
     */
    public function responseJqxGrid($rows, $totalRows = 0) {
        return response()->json([
            'TotalRows' => $totalRows,
            'Rows' => $rows,
		]);
    }

    /**
     * Layout's props
     * @var array
     */
    protected $_props = [
        // Sets the widget's theme.
        'theme' => 'bootstrap',
        // Sets or gets wheter a custom context menu will appear when certain elements of the widget are right-clicked.
        'contextMenu' => Helper::UNDEF,
        // Sets or gets the layout's width.
        'width' => '100%',
        // Sets the default minimumn width for groups which are horizontally aligned within their parent group.
        'minGroupWidth' => Helper::UNDEF,
        // Sets or gets the layout's height.
        'height' => '100%',
        // Sets the default minimumn height for groups which are vertically aligned within their parent group.
        'minGroupHeight' => Helper::UNDEF,
        // Sets or gets the layout. This property determines the position of the layout elements and their characteristics. The layout array always contains one root item of type 'layoutGroup'.
        'layout' => Helper::UNDEF,
        // Sets or gets wheter panels can be dynamically resized.
        'resizable' => Helper::UNDEF,
        // Sets or gets a value indicating whether widget's elements are aligned to support locales using right-to-left fonts.
        'rtl' => Helper::UNDEF,
    ];

    /**
     * Layout's props
     * @var array
     */
    protected $_events = [
        // Bind to the create event by type: jqxLayout.
        'create' => <<<JS
    function(event) { $(document).trigger('jqxLayoutCreated', [event]); }
JS
,
        // Bind to the pin event by type: jqxLayout.
        'pin' => Helper::UNDEF,
        // Bind to the resize event by type: jqxLayout.
        'resize' => Helper::UNDEF,
        // This event is triggered when a group has been unpinned.
        'unpin' => Helper::UNDEF,
    ];

    /**
     * @var Item
     */
    protected $item;

    /**
     * @var array Items
     */
    protected $_items = [];

    /**
     * Init default layout
     * @param array $options An array of options
     * @return void
     */
    public function initDefaultLayout(array $options = array())
    {
        $this->addItem([
            "type" => 'layoutGroup',
            "orientation" => 'horizontal'
        ], function($jLI) {
            // documentGroup
            $jLI->addItem([
                "type" => 'layoutGroup',
                "orientation" => 'vertical',
                "width" => '100%',
            ], function($jLI) {
                // Layout options:
                $lOpts = $this->getOptions();
                // +++ show bottom panels
                $hideBottomPanels = (true === $lOpts['hide_bottom_panels']);
                $bottomPanelsOpts = !$hideBottomPanels ? [
                    "type" => 'tabbedGroup',
                    "height" => '30%',
                    "pinnedHeight" => 30,
                ] : [];
                if (\is_callable($lOpts['bottom_panels'])) {
                    $bottomPanelsCallback = $lOpts['bottom_panels']($bottomPanelsOpts);
                }
                // documentGroup
                $jLI->addItem([
                    "type" => 'documentGroup',
                    "height" => (100 - doubleval($bottomPanelsOpts['height'])) . '%',
                    "minHeight" => '25%',
                ], function($jLI) {
                    $jLI->addItem([
                        "type" => 'documentPanel',
                        "title" => '',
                        "contentContainer" => 'MainPanel',
                    ]);
                });
                // bottom tabbedGroup
                if (!$hideBottomPanels) {
                    $jLI->addItem($bottomPanelsOpts, $bottomPanelsCallback ?: function($jLI) {
                        $jLI->addItem([
                            "type" => 'layoutPanel',
                            "title" => 'Panel',
                            "contentContainer" => 'SubPanel',
                            "selected" => true
                        ]);
                    });
                }
            });
            // right tabbedGroup
            /* $jLI->addItem([
                "type" => 'tabbedGroup',
                "width" => '10%',
            ], function($jLI) {
                $jLI->addItem([
                    "type" => 'layoutPanel',
                    "title" => 'Solution Explorer',
                    "contentContainer" => 'SolutionExplorerPanel'
                ]);
                $jLI->addItem([
                    "type" => 'layoutPanel',
                    "title" => 'Properties',
                    "contentContainer" => 'PropertiesPanel'
                ]);
            }); */
        });
        // Return
        return $this;
    }

    /**
     * Add item
     *
     * @param array $item
     * @param Closure $callback Add sub item?
     * @return this
     */
    public function addItem(array $item, $callback = null)
    {
        // Add items
        $item = new Item($item);
        $this->_items[] = $item;
        // Case: add sub items
        if ($callback instanceof Closure) {
            $callback($item);
        }
        return $this;
    }

    /**
     * Get items
     * @return array
     */
    public function getItems() {
        return $this->_items;
    }

    /**
     * Overloading methods
     */
    public function __toString()
    {
        // JS of js data
        $jsDataJS = $this->getJsDataJS();
        // JS of events
        $eventsJS = $this->getEventsJS();
        // JS init
        $props = array_replace($this->_props, [
            'layout' => Helper::jsFunc(Helper::toJson($this->_items)),
        ]);
        $jqxLayoutJS = Helper::toJson($props);
        // Return
        $script = <<<JS
(function($){
    // Layout
    var {$this->varInstanceName} = $('{$this->selector}');
    // +++ data
    {$jsDataJS}
    // +++ events
    {$eventsJS}
    // +++ init widget
    {$this->varInstanceName}.jqxLayout({$jqxLayoutJS});
})(jQuery);
JS;
        return trim($script);
    }
}
