<?php

namespace App\Helpers\Jqx;

use Closure;

/**
 * @author
 */
class DataAdapter
{
    /**
     * References to others data sources!
     * @var array
     */
    protected $_refs = [];

    /**
     * Undocumented function
     *
     * @param [type] $name
     * @param DataAdapter $dataAdapter
     * @return void
     */
    public function makeRef($name, DataAdapter $dataAdapter) {
        $this->_refs[$name] = $dataAdapter;
    }

    /**
     * @var string
     */
    public $varSourceName = 'source';

    /**
     * @var string
     */
    public $varDataAdapterName = 'dataAdapter';

    /**
     * 
     * @var boolean
     */
    public $autoBind = Helper::UNDEF;

    /**
     * 
     * @var Closure|null
     */
    protected $_beforeLoadComplete = Helper::UNDEF;
    /**
     * 
     * @param $fnc Closure
     * @return void
     */
    public function beforeLoadComplete(Closure $fnc) {
        $this->_beforeLoadComplete = $fnc;
    }

    /**
     * 
     * @var Closure|null
     */
    protected $_loadComplete = Helper::UNDEF;
    /**
     * 
     * @param $fnc Closure
     * @return void
     */
    public function loadComplete(Closure $fnc) {
        $this->_loadComplete = $fnc;
    }

    /**
     * 
     * @var Closure|null
     */
    protected $_loadError = Helper::UNDEF;
    /**
     * 
     * @param $fnc Closure
     * @return void
     */
    public function loadError(Closure $fnc) {
        $this->_loadError = $fnc;
    }

    /**
     * Class's constructor
     */
    public function __construct(array $options = array())
    {
        // Init data
        // +++ source
        $this->_initSource($options);
    }

    /**
     * Self init source data
     * @return this
     */
    protected function _initSource() {
        //
        $this->_source['beforeprocessing'] = Helper::jsFunc(<<<JS
    function (_d) {
        {$this->varSourceName}.totalrecords = _d.TotalRows;
    }
JS
        );
    }

    /**
     * An array describing the fields in a particular record.
     * Each datafield must define the following members:
     * @var array
     */
    protected $_datafield = [
        // A string containing the data field's name.
        'name' => '',
        // The field's value in the data source.
        'value' => Helper::UNDEF,
        // A string containing the data field's type. Possible values: 'string', 'date', 'number', 'float', 'int', 'bool'.
        'type' => 'string',
        // (optional) - Sets the data formatting.
        // By setting the format, the jqxDataAdapter plug-in will try to format the data before loading it.
        // Example: { name: 'SubmitDate', type: 'date', format: "yyyy-MM-ddTHH:mm:ss-HH:mm" }
        'format' => Helper::UNDEF,
        // (optional) - A mapping to the data field. 
        // Example with XML data:
        // { name: 'CompanyName', map: 'm\\:properties&gt;d\\:CompanyName' }
        // Example with nested JSON data.
        //  { name: 'name', map: 'department&gt;name' },
        'map' => Helper::UNDEF,
        // determines the foreign collection associated to the data field.
        'values' => Helper::UNDEF,
    ];

    /** @var string */
    public const JQXACT_KEY = '_jqxact';
    /** @var string */
    public const JQXACT_READ = 'read';
    /** @var string */
    public const JQXACT_CREATE = 'create';
    /** @var string */
    public const JQXACT_STORE = 'store';
    /** @var string */
    public const JQXACT_EDIT = 'edit';
    /** @var string */
    public const JQXACT_UPDATE = 'update';
    /** @var string */
    public const JQXACT_DELETE = 'delete';
    /** @var string */
    public const JQXACT_DESTROY = 'destroy';

    /**
     * Default source
     * @var array
     */
    protected $_sourceData = [
        '_jqxact' => self::JQXACT_READ, // read | create | store | edit | update | delete | destroy
    ];

    /**
     * Default source
     * @var array
     */
    protected $_source  = [
        // (current url) A string containing the URL to which the request is sent.
        'url' => '?', // <-- current url
        'cache' => Helper::UNDEF,
        // Data to be sent to the server.
        'data' => [],
        // data array or data string pointing to a local data source.
        'localdata' => Helper::UNDEF,
        // the data's type. Possible values: 'xml', 'json', 'jsonp', 'tsv', 'csv', 'local', 'array', 'observablearray'.
        'datatype' => 'json',
        // The type of request to make ("POST" or "GET"), default is "GET".
        'type' => 'GET',
        // A string containing the Id data field.
        'id' => null,
        // A string describing where the data begins and all other loops begin from this element.
        'root' => 'Rows',
        // A string describing the information for a particular record.
        'record' => Helper::UNDEF,
        // An array describing the fields in a particular record. Each datafield must define the following members:
        'datafields' => [],
        // determines the initial page number when paging is enabled.
        'pagenum' => Helper::UNDEF,
        // determines the page size when paging is enabled.
        'pagesize' => Helper::UNDEF,
        // callback function called when the current page or page size is changed.
        // function (pagenum, pagesize, oldpagenum) {}
        'pager' => Helper::UNDEF,
        // determines the initial sort column. The expected value is a data field name.
        'sortcolumn' => Helper::UNDEF,
        // determines the sort order. The expected value is 'asc' for (A to Z) sorting or 'desc' for (Z to A) sorting.
        'sortdirection' => Helper::UNDEF,
        // callback function called when the sort column or sort order is changed.
        // function (column, direction) {}
        'sort' => Helper::UNDEF,
        // callback function called when a filter is applied or removed.
        // function (filters, recordsArray) {}
        'filter' => Helper::UNDEF,
        // callback function, called when a new row is/are added. If multiple rows are added, the rowid and rowdata parameters are arrays of row ids and rows.
        /* function (rowid, rowdata, position, commit) {
            // synchronize with the server - send insert command
            // call commit with parameter true if the synchronization with the server is successful 
            //and with parameter false if the synchronization failed.
            commit(true);
        } */
        'addrow' => Helper::UNDEF,
        // callback function, called when a row is deleted. If multiple rows are deleted, the rowid parameter is an array of row ids.
        /* function (rowid, commit) {
            // synchronize with the server - send delete command
            // call commit with parameter true if the synchronization with the server is successful 
            //and with parameter false if the synchronization failed.
            commit(true);
        } */
        'deleterow' => Helper::UNDEF,
        // callback function, called when a row is updated. If multiple rows are added, the rowid and rowdata parameters are arrays of row ids and rows.
        /* function (rowid, newdata, commit) {
            // synchronize with the server - send update command
            // call commit with parameter true if the synchronization with the server is successful 
            // and with parameter false if the synchronization failed.
            commit(true);
        } */
        'updaterow' => Helper::UNDEF,
        // extend the default data object sent to the server.
        /* function (data) {
            data.featureClass = "P";;
            data.style = "full";
            data.maxRows = 50;
        } */
        'processdata' => Helper::UNDEF,
        // Before the data is sent to the server, you can fully override it by using the 'formatdata' function of the source object. The result that the 'formatdata' function returns is actually what will be sent to the server.
        /* function (data) {
            return "my data";
        } */
        'formatdata' => Helper::UNDEF,
        // Use this option, If you want to explicitly pass in a content-type.
        // Default is "application/x-www-form-urlencoded".
        'contenttype' => Helper::UNDEF,
        //
        'beforeprocessing' => Helper::UNDEF,
    ];

    /**
     * An array of datafield
     * @var array
     */
    protected $_datafields = []; 

    /**
     * Add datafield
     *
     * @param array $data
     * @return this
     */
    public function addDatafield(array $datafield) {
        return $this->addDatafields([ $datafield ]);
    }

    /**
     * Add datafields
     *
     * @param array $datafields
     * @return this
     */
    public function addDatafields(array $datafields) {
        // Auto detect source's id?
        if (!$this->_source['id'] && empty($this->_datafields) && $datafields[0]['name']) {
            if (false !== strpos($datafields[0]['name'], '_id')) {
                $this->_source['id'] = $datafields[0]['name'];
            }
        }
        // Format datafields
        foreach ($datafields as $index => &$datafield) {
            $datafield = array_replace(
                $this->_datafield,
                array_intersect_key($datafield, $this->_datafield)
            );
            $this->_datafields[] = Helper::rmUndef($datafield);
        }
        return $this;
    }

    /**
     * Get datafields
     *
     * @return array
     */
    public function getDatafields() {
        return $this->datafields;
    }

    /**
     * Set 'source' props
     *
     * @param array $source
     * @return this
     */
    public function setSource(array $source) {
        $this->_source = array_replace(
            $this->_source,
            array_intersect_key($source, $this->_source)
        );
        return $this;
    }

    /**
     *
     * @return this
     */
    public function setSourceLocaldata(array $localdata) {
        return $this->setSource([
            'url' => Helper::UNDEF,
            'data' => Helper::UNDEF,
            'root' => Helper::UNDEF,
            'type' => Helper::UNDEF,
            'sort' => Helper::UNDEF,
            'filter' => Helper::UNDEF,
            'beforeprocessing' => Helper::UNDEF,
			'localdata' => Helper::jsFunc(Helper::toJson($localdata)),
			'datatype' => 'array'
        ]);
    }

    /**
     * Get 'source' props
     * @return array
     */
    public function getSource() {
        return $this->_source;
    }

    /**
     * Get source as json
     * @return string
     */
    public function getSourceAsJson()
    {
        $source = array_replace($this->_source, [
            $key = 'data' => array_replace($this->_sourceData, (array)$this->_source[$key]),
            'datafields' => $this->_datafields
        ]);
        $source = Helper::toJson($source);
        //
        return trim($source);
    }

    /**
     * Get data adapter props as json
     * @return string
     */
    public function getPropsAsJson()
    {
        return Helper::toJson([
            'autoBind' => $this->autoBind,
            'beforeLoadComplete' => $this->_beforeLoadComplete,
            'loadComplete' => $this->_loadComplete,
            'loadError' => $this->_loadError
        ]);
    }

    /**
     * Overloading methods
     *
     * @param string $name
     * @param array $arguments
     * @return void
     */
    public function __call(string $name,  array $arguments)
    {
        $kname = 'setSource';
        if (strpos($name, $kname) === 0) {
            $prop = str_replace($kname, '', $name);
            $ucProp = ucfirst($prop);
            if ($prop === $ucProp) {
                $prop = strtolower($prop);
                if (\array_key_exists($prop, $this->_source)) {
                    return $this->setSource([ $prop => $arguments[0] ]);
                }
            }
        }
        trigger_error("Call to undefined method " . __CLASS__ . "::{$name}()", E_USER_ERROR);
    }

    /**
     * Overloading methods
     */
    public function __toString()
    {
        //
        $script = [];
        $refs = array_replace($this->_refs, ['' => $this]);
        foreach ($refs as $nameOfDA => $dA) {
            $varSourceName = $dA->varSourceName;
            $varDataAdapterName = $dA->varDataAdapterName;
            if ($nameOfDA) {
                $nameOfDA = strtolower($nameOfDA);
                $varSourceName = ($nameOfDA . ucfirst($varSourceName));
                $varDataAdapterName = ($nameOfDA . ucfirst($dA->varDataAdapterName));
            }
            $script[] = <<<JS
// Source
var {$varSourceName} = {$dA->getSourceAsJson()};
// Data adapter
var {$varDataAdapterName} = new $.jqx.dataAdapter({$varSourceName}, {$dA->getPropsAsJson()});
JS;
        }
        // Return;
        $script = trim(implode(PHP_EOL, $script));
        return $script;
    }
}
