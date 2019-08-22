<?php

namespace App\Helpers\Jqx;

use Illuminate\Http\Request;
use Closure;

/**
 * @author
 */
class Grid extends Widget
{
    /**
     * @var bool
     */
    public $useJqxLayout = true;

     /**
      * @var DataAdapter
      */
     public $dataAdapter = null;

     /**
      * jQuery Selector to init element!
      * @var string
      */
     public $selector = '#jqxGrid';

    /**
     * Widget instance name!
     * @var string
     */
    public $varInstanceName  = 'jqxGridMain';

    /**
     * Grid's props
     * @var array
     */
    protected $_props = [
        //
        'width' => '100%',
        //
        'height' => '100%',
        //
        'theme' => 'bootstrap',
        //
        'columnsreorder' => Helper::UNDEF,
        //
        'showfilterrow' => true,
        //
        'showfiltermenuitems' => Helper::UNDEF,
        //
        'filterable' => true,
        //
        'autoshowfiltericon' => Helper::UNDEF,
        //
        'filtermode' => Helper::UNDEF, // default | excel
        //
        'sortable' => true,
        //
        'sorttogglestates' => 1,
        //
        'columnsresize' => true,
        //
        'pageable' => true,
        'pagesize' => 30,
        'pagesizeoptions' => ['15', '30', '45','80'],
        //
        'virtualmode' => true,
        //
        'showstatusbar' => Helper::UNDEF,
        //
        'statusbarheight' => Helper::UNDEF, // 20,
        //
        'ready' => Helper::UNDEF,
        //
        'selectedrowindex' => 0,
        //
        'showemptyrow' => false,
        //
        // 'autosavestate' => true,
        //
        // 'autoloadstate' => true,
        /*
         |  '0'-disables toggling
         |  '1'-enables togging. Click on a column toggles the sort direction
         |  '2'-enables remove sorting option
         */
        'sorttogglestates' => 2,
        // groupping props:
        'groups' => Helper::UNDEF,
        'groupable' => Helper::UNDEF,
        'groupsrenderer' => Helper::UNDEF,
        'groupcolumnrenderer' => Helper::UNDEF,
        'groupsexpandedbydefault' => Helper::UNDEF,
        'showgroupsheader' => Helper::UNDEF,
        //
        'editable' => Helper::UNDEF,
        //
        'selectionmode' => Helper::UNDEF,
        //
        'editmode' => Helper::UNDEF,
        //
        'altrows' => Helper::UNDEF,
        //
        'showaggregates' => Helper::UNDEF,
    ];

    /**
     * Events
     * @var array
     */
    protected $_events = [
        // This event is triggered when a row is clicked.
        'rowclick' => Helper::UNDEF,
        // This event is triggered when a row is selected.
        'rowselect' => Helper::UNDEF,
        //
        'contextmenu' => Helper::UNDEF
    ];

    /**
     * js data
     * @var array
     */
    protected $_jsdata = [];

    /**
     * @var array Default column
     */
    protected $_column = [
        // sets the column text.
        'text' => '',
        // sets the column datafield.
        'datafield' => Helper::UNDEF,
        // sets the column's displayfield. The displayfield specifies the field in the data source from which the column to retrieve strings for display.
        'displayfield' => Helper::UNDEF,
        // enables or disables the sorting.
        'sortable' => Helper::UNDEF,
        // enables or disables the filtering.
        'filterable' => Helper::UNDEF,
        // sets the column's initialization filter. A $.jqx.filter object is expected.
        'filter' => Helper::UNDEF,
        // enables or disables whether the column can be hidden.
        'hideable' => Helper::UNDEF,
        // hides or shows the column.
        'hidden' => Helper::UNDEF,
        // sets whether the user can group by this column.
        'groupable' => Helper::UNDEF,
        // sets whether the menu button is displayed when the user moves the mouse cursor over the column's header.
        'menu' => Helper::UNDEF,
        // determines whether the column will be exported when the Grid's export method is called.
        'exportable' => Helper::UNDEF,
        // determines the name of the column's parent group.
        'columngroup' => Helper::UNDEF,
        // determines whether tooltips are enabled.
        'enabletooltips' => true,
        // renderer - sets a custom column renderer. This can be used for changing the built-in rendering of the column's header.
        'renderer' => Helper::UNDEF,
        // callback function that is called when the column is rendered. You can use it to set additional settings to the column's header element.
        // function (columnHeaderElement) { /* Your code here. */}
        'rendered' => Helper::UNDEF,
        // cellsrenderer - sets a custom rendering function.
        // The cellsrenderer function is called when a cell in the column is rendered.
        // You can use it to override the built-in cells rendering.
        // The cellsRenderer function has 6 parameters passed by jqxGrid - row index, data field, cell value, defaultHtml string that is rendered by the grid, column's settings and the entire row's data as JSON object.
        /* function (row, columnfield, value, defaulthtml, columnproperties) {
            if (value < 20) {
                return '' + value + '';
            }
            else {
                return '' + value + '';
            }
        } */
        'cellsrenderer' => Helper::UNDEF,
        //
        'createeditor' => Helper::UNDEF,
        //
        'initeditor' => Helper::UNDEF,
        //
        'geteditorvalue' => Helper::UNDEF,
        // sets the column header's alignment to 'left', 'center' or 'right'
        'align' => 'center',
        // sets the column width.
        'width' => Helper::UNDEF,
        // sets the column's min width.
        'minwidth' => 120,
        // sets the column's max width.
        'maxwidth' => Helper::UNDEF,
        // enables or disables the cells editing
        'editable' => Helper::UNDEF,
        // classname - sets a custom CSS class for the column's header
        'classname' => Helper::UNDEF,
        // sets a custom CSS class for the column's cells. The value could be a "String" or "Function".
        // Apply a CSS class to all cells in the column.
        'cellclassname' => Helper::UNDEF,
        // pins or unpins the column. If the column is pinned, it will be displayed as frozen and will be visible when the user horizontally scrolls the grid contents.
        'pinned' => Helper::UNDEF,
        // enables or disables whether null values are allowed.
        'nullable' => Helper::UNDEF,
        // enables or disables the column dragging
        'draggable' => Helper::UNDEF,
        // enables or disables the column resizing.
        'resizable' => Helper::UNDEF,
        /* sets the column's type. Possible values:
            'number' - readonly column with numbers.
            'checkbox' - readonly checkbox when the editing is disabled. Checkbox input when editing is enabled.
                + threestatecheckbox - determines whether the checkbox has an indeterminate state when the value is null. The default value is false.
            'numberinput' - sets a number input editor as a default editor for the column. Requires: jqxnumberinput.js
            'dropdownlist' - sets a dropdownlist editor as a default editor for the column. Requires: jqxlistbox.js and jqxdropdownlist.js
            'combobox' - sets a combobox editor as a default editor for the column. Requires: jqxlistbox.js and jqxcombobox.js
            'datetimeinput' - sets a datetimeinput editor as a default editor for the column. Requires: jquery.global.js, jqxcalendar.js and jqxdatetimeinput.js
            'textbox' - sets a textbox editor as a default editor for the column.
            'template' - sets a custom editor as a default editor for the column. The editor should be created in the "createeditor" callback. The editor should be synchronized with the cell's value in the "initeditor" callback. The editor's value should be retrieved in the "geteditorvalue" callback.
            'custom' - sets a custom editor as a default editor for a cell. That setting enables you to have multiple editors in a Grid column. The editors should be created in the "createeditor" callback - it is called for each row when the "columntype=custom". The editors should be synchronized with the cell's value in the "initeditor" callback. The editor's value should be retrieved in the "geteditorvalue" callback.
        */
        'columntype' => 'textbox',
        /* sets the filter's type.
            'textbox' - basic text field.
            'input' - input field with dropdownlist for choosing the filter condition. *Only when "showfilterrow" is true.
            'checkedlist' - dropdownlist with checkboxes that specify which records should be visible and hidden.
            'list' - dropdownlist which specifies the visible records depending on the selection.
            'number' - numeric input field. *Only when "showfilterrow" is true.
            'bool' - filter for boolean data. *Only when "showfilterrow" is true.
            'date' - filter for dates.
            'range' - filter for date ranges. *Only when "showfilterrow" is true.
            'custom' - allows you to create custom filter menu widgets. *Only when "showfilterrow" is false.
        */
        'filtertype' => 'input',
        // sets the items displayed in the list filter - when the "showfilterrow" property value is true and the filter's type is "list" or "checkedlist".
        // The expected value is Array or jqxDataAdapter instance
        'filteritems' => Helper::UNDEF,
        /* sets the formatting of the grid cells. Possible Number strings:
            "d" - decimal numbers.
            "f" - floating-point numbers.
            "n" - integer numbers.
            "c" - currency numbers.
            "p" - percentage numbers.
        */
        'cellsformat' => Helper::UNDEF,
        // cellsalign - sets the cells alignment to 'left', 'center' or 'right'.
        'cellsalign' => Helper::UNDEF,
        //
        'aggregates' => Helper::UNDEF,
        'aggregatesrenderer' => Helper::UNDEF,
    ];

    /**
     * @var array Columns
     */
    protected $columns = [];

    /**
     * Column group entry
     * @var array
     */
   protected $_columngroup = [
        // sets the column group's text.
        'text' => Helper::UNDEF,
        // sets the column header's parent group name.
        'parentgroup' => Helper::UNDEF,
        // sets the column header's name.
        'name' => Helper::UNDEF,
        // sets the column header's alignment to 'left', 'center' or 'right'.
        'align' => 'center',
   ];

    /**
     * Column groups
     * @var array
     */
   protected $_columngroups = [];

    /**
     * Class's construct
     * @param array|null $options An array of options
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);
        // Init data
        // +++ column(s)
        $this->addColumn([
            'text' => 'No.',
            'sortable' => false,
            'filterable' => false,
            'editable' => false,
            'pinned' => true,
            'groupable' => false,
            'draggable' => false,
            'columntype' => 'number',
            'width' => 30,
            'align' => 'center',
            'cellsalign' => 'center',
            'cellsrenderer' => "{!!function(row) { return '<div class=\"text-center\" style=\"padding:8px 4px;\">' + (row + 1) + '</div>'; }!!}",
        ]);
        // +++ dataAdapter
        $this->dataAdapter = new DataAdapter();
    }

    /**
     *
     *
     * @return App\Helpers\Jqx\Grid\DataAdapter
     */
    public function addDataAdapter($name, array $constructOpts = array()) {
        $dataAdpater = new DataAdapter($constructOpts);
        $this->dataAdapter->makeRef($name, $dataAdpater);
        // Return
        return $dataAdpater;
    }

    /**
     * Add column
     *
     * @param array $data
     * @return this
     */
    public function addColumn(array $column) {
        return $this->addColumns([ $column ]);
    }

    /**
     * Add columns
     *
     * @param array $columns
     * @return this
     */
    public function addColumns(array $columns, $datafields = null, $columngroups = null) {
        // Format columns
        foreach ($columns as $index => &$column) {
            //
            if (!is_null($column['width']) && is_null($column['minwidth'])) {
                $column['minwidth'] = Helper::UNDEF;
            }
            //
            $column = array_replace(
                $this->_column,
                array_intersect_key($column, $this->_column)
            );
            $this->_columns[] = Helper::rmUndef($column);
        }
        if (is_array($datafields)) {
            $this->dataAdapter->addDatafields($datafields);
        }
        if (is_array($columngroups)) {
            $this->addColumnGroups($columngroups);
        }
        // Return
        return $this;
    }

    /**
     * Add column group
     *
     * @param array $data
     * @return this
     */
    public function addColumnGroup(array $columngroup) {
        return $this->addColumnGroups([ $columngroup ]);
    }

    /**
     * Add column groups
     *
     * @param array $columngroups
     * @return this
     */
    public function addColumnGroups(array $columngroups) {
        // Format column groups
        foreach ($columngroups as $index => &$columngroup) {
            //
            $columngroup = array_replace(
                $this->_columngroup,
                array_intersect_key($columngroup, $this->_columngroup)
            );
            $this->_columngroups[] = Helper::rmUndef($columngroup);
        }
        // Return
        return $this;
    }

    /**
     * Get columns
     *
     * @return array
     */
    public function getColumns() {
        return $this->_columns;
    }

    /**
     * Set prop groups
     *
     * @param array $groups
     * @return this
     */
    public function setPropGroups(array $groups)
    {
        $props = [
            'groups' => Helper::UNDEF,
            'groupable' => Helper::UNDEF,
            'groupsexpandedbydefault' => Helper::UNDEF,
            'groupsrenderer' => Helper::UNDEF,
			// 'groupcolumnrenderer' => Helper::UNDEF,
        ];
        if (!empty($groups)) {
            $props = [
                'groups' => $groups,
                'groupable' => true,
                'groupsexpandedbydefault' => true
            ];
            /* $props['groupsrenderer'] = Helper::jsFunc(<<<JS
    function (text, group, expanded) {
        // var \$jqxGrid = {$this->varInstanceName};
        // console.log(text, group, expanded, \$jqxGrid);
    }
JS
            ); */
            // @TODO: temp fix lost rows when grouped
            $this->setPropOffPaging();
        }
        $this->_props = array_replace($this->_props, $props);
        return $this;
    }

    /**
     * Set prop turn off paging
     * @return this
     */
    public function setPropOffPaging()
    {
        $props = [
            'pageable' => false,
			'pagesize' => -1,
			'virtualmode' => false,
        ];
        $this->_props = array_replace($this->_props, $props);
        return $this;
    }

    /**
     * Set prop 'showfiltermenuitems'
     * @return this
     */
    public function setPropShowFilterMenuItems($flag)
    {
        $props = [
			'showfilterrow' => $flag ? false : $this->_props['showfilterrow'],
			'showfiltermenuitems' => $flag,
        ];
        $this->_props = array_replace($this->_props, $props);
        return $this;
    }

    /**
     * Set prop 'showaggregates'
     * @return this
     */
    public function setPropShowAggregates($flag = true)
    {
        $props = [
            'showstatusbar' => $flag,
            'statusbarheight' => 25,
			'showaggregates' => $flag,
        ];
        $this->_props = array_replace($this->_props, $props);
        return $this;
    }

    /**
     * Self init source data
     * @return this
     */
    protected function _initSource()
    {
        $source = $this->dataAdapter->getSource();
        //
        if (Helper::UNDEF === $source['filter']) {
            $source['filter'] = Helper::jsFunc(<<<JS
    function () {
        // update the grid and send a request to the server.
        $('{$this->selector}').jqxGrid('updatebounddata', 'filter');
    }
JS
            );
        }
        //
        if (Helper::UNDEF === $source['sort']) {
            $source['sort'] = Helper::jsFunc(<<<JS
    function () {
        // update the grid and send a request to the server.
        $('{$this->selector}').jqxGrid('updatebounddata', 'sort');
    }
JS
            );
        }
        $this->dataAdapter->setSource($source);
    }

    /**
     * @return string
     */
    public function html($content = array())
    {
        $id = $content['id'];
        if (is_null($id)) {
            if (strpos($this->selector, '#') !== false) {
                $id = str_replace('#', '', $this->selector);
            }
        }
        $id = $id ?: 'jqxGrid';
        return "<div id=\"{$id}\">{$content['body']}</div>";
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
        $this->_initSource();
        $dataAdapterJS = trim($this->dataAdapter);
        //
        $props = array_replace($this->_props, [
            'source' => Helper::jsFunc($this->dataAdapter->varDataAdapterName),
            'rendergridrows' => Helper::jsFunc('function(_p) { return _p.data; }'),
            'columns' => $this->_columns,
            'columngroups' => empty($this->_columngroups) ? Helper::UNDEF : $this->_columngroups,
        ]);
        $jqxGridJS = Helper::toJson($props);
        //
        $script = <<<JS
(function($){
    {$dataAdapterJS}
    // Grid
    var {$this->varInstanceName} = $('{$this->selector}');
    // +++ data
    {$jsDataJS}
    // +++ events
    {$eventsJS}
    // +++ init
    {$this->varInstanceName}.jqxGrid({$jqxGridJS});
})(jQuery);
JS;
        // Return
        return trim($script);
    }
}
