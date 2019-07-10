<?php
namespace App\Models;

use App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

abstract class AbstractModel extends Model {

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Helper: get model's id
     * @return mixed
     */
    public function id()
    {
        return $this->{$this->primaryKey};
    }

    /**
     * @var string Status 0
     */
    const STATUS_0 = 0;

    /**
     * @var string Status 1
     */
    const STATUS_1 = 1;

    /**
     * Return status list
     *
     * @return void
     */
    public static function statusList() {
        $list = [
            static::STATUS_0 => 'Ngừng',
            static::STATUS_1 => 'Sử dụng',
        ];
        return $list;
    }

    /**
     * set status 1
     * @return $this
     */
    public function statusActive()
    {
        $property = \get_class($this) . '::STATUS_COLUMN';
        if (defined($property)) {
            $property = constant($property);
            $this->{$property} = static::STATUS_1;
        }
        return $this;
    }

    /**
     * set status 0
     * @return $this
     */
    public function statusUnactive()
    {
        $property = \get_class($this) . '::STATUS_COLUMN';
        if (defined($property)) {
            $property = constant($property);
            $this->{$property} = static::STATUS_0;
        }
        return $this;
    }

    /**
     * check status 1?
     * @return null|bool
     */
    public function isStatusActive()
    {
        $property = \get_class($this) . '::STATUS_COLUMN';
        if (defined($property)) {
            $property = constant($property);
            return static::STATUS_1 == $this->{$property};
        }
    }

    /**
     * Return jqx's grid columns
     *
     * @param null|array $datafields Datafields
     * @param null|array $columngroups Column groups
     * @return array
     */
    public static function jqxGridColumns(&$datafields = null, &$columngroups = null)
    {
        $classname = get_called_class();
        $datafields = [];
        $columngroups = [];
        $columns = static::$jqxGridColumns;
        // $permissons = Permissions::checkPermissons();
        if (!empty($columns))
        {
            $prefix = static::$columnPrefix;
            foreach ($columns as $idx => &$col)
            {
                // Modify?
                if (method_exists($classname, $method = 'jqxGridCol')) {
                    $col = $classname::$method($col);
                }
                // Datafields:
                list($dfdName, $dfdProps) = ($datafield = (array)$col['datafield']);
                if (is_array($dfdName)) {
                    $dfdName = $dfdName[0];
                } else {
                    $dfdName = $dfdName ? ($prefix . $dfdName) : '';
                }
                $datafield = (array)$dfdProps;;
                $datafield['name'] = $dfdName;
                if (method_exists($classname, $method = 'jqxGridDfd')) {
                    $datafield = $classname::$method($datafield);
                }
                $datafields[] = $datafield;
                //.end
                /* //permissons
                if ($permissons['permissonsEdit'] === false && $classname !== SalaryConfirmDetail::class) {
                    if(count($col) > 1){
                        $col['editable'] = false;
                    }
                }
                //end */
                // Column groups:
                if ($col['columngroup'])
                {
                    list($cgName, $cgProps) = ($columngroup = (array)$col['columngroup']);
                    $columngroup = (array)$cgProps;;
                    $columngroup['name'] = $cgName;
                    $columngroup = array_replace((array)$columngroups[$cgName], $columngroup);
                    if (method_exists($classname, $method = 'jqxGridClg')) {
                        $columngroup = $classname::$method($columngroup);
                    }
                    $columngroups[$cgName] = $columngroup;
                }
                //.end
                // Columns:
                if (is_null($col['text']))
                {
                    unset($columns[$idx]);
                }
                else
                {
                    $col['datafield'] = $datafield['name'];
                    if ($columngroup['name'])
                    {
                        $col['columngroup'] = $columngroup['name'];
                    }
                }
                //.end
                unset($dfdName, $dfdProps, $columngroup, $cgName, $cgProps);
            }
            if (!empty($columngroups))
            {
                $columngroups = array_values($columngroups);
            }
        }
        return $columns;
    }

    // ... Validate data when update or create
    // ... Get all validate errors
    // ... Extend method : getBy, getAll, SearchBy

    /** @var array Mapping jqx datafields */
    protected $datafieldsMapping = [];

    /**
     * Helper: mapping jqx datafields
     * @param $datafield string Datafield
     * @return string
     */
    public function jqxDatafieldMapping($datafield)
    {
    	if (!is_string($datafield)) {
        	return $datafield;
    	}
        $dfd = $this->datafieldsMapping[$datafield];
        if (is_callable($dfd)) {
            $datafield = $dfd($datafield);
        } else {
            if ($dfd) {
                $datafield = $dfd;
            } else {
                // @TODO
                $subfix = '_text';
                if (strlen($datafield) === strpos($datafield, $subfix) + strlen($subfix)) {
                    $datafield = str_replace($subfix, '', $datafield);
                }
            }
        }
        return $datafield;
    }

    /**
     * Alter jqx grid column info
     * @param array $col Column data
     * @return array
     */
    public static function jqxGridDatafieldByCol($col)
    {
        $dfd = is_string($col['datafield']) ? $col['datafield']
            : (is_string($col['datafield'][0]) ? $col['datafield'][0]
                : $col['datafield'][0][0]
            )
        ;
        return $dfd;
    }

    /**
     * Make query builder from jqx request payload
     *
     * @param array $data Request payload
     * @param null $totalRowsQB Illuminate\Database\Eloquent\Builder
     * @param array $opts
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function qBFromJqxRequestPayload(array $data, &$totalRowsQB = null, array $opts = array())
    {
        // Get query builder instance!
        $qB = $this->whereRaw(1);
        if (\is_callable($opts['queryBuilder'])) {
            $opts['queryBuilder']($qB, $data, $opts);
        }
        // Filter
        $filterGroups = $data['filterGroups'];
        if (is_array($filterGroups) && !empty($filterGroups))
        {
            foreach ($filterGroups as $filterGroup)
            {
                $qB->where(function($join) use ($filterGroup, &$opts) {
                    // $field = $filterGroup['field'];
                    $filters = $filterGroup['filters'];
                    foreach ($filters as $filter)
                    {
                        // Format input
                        if (('numericfilter' === $filter['type'])) {
                            //
                            if ('true' === $filter['value'] || 'TRUE' === $filter['value']) {
                                $filter['value'] = 1;
                            }
                            //
                            if ('false' === $filter['value'] || 'FALSE' === $filter['value']) {
                                $filter['value'] = 0;
                            }
                        }
                        //.end
                        $value = $filter['value'];
                        $where = 'where';
                        if ($filter['operator'] === 'or')
                        {
                            $where = 'orWhere';
                        }
                        switch ($filter['condition'])
                        {
                            case "EMPTY":
                                $condition = "=";
                                $value = "";
                                break;
                            case "NOT_EMPTY":
                                $condition = "<>";
                                $value = "";
                                break;
                            case "CONTAINS":
                                $condition = "LIKE";
                                $value = "%{$value}%";
                                break;
                            case "DOES_NOT_CONTAIN":
                                $condition = "NOT LIKE";
                                $value = "%{$value}%";
                                break;
                            case "EQUAL":
                                $condition = "=";
                                break;
                            case "NOT_EQUAL":
                                $condition = "<>";
                                break;
                            case "GREATER_THAN":
                                $condition = ">";
                                break;
                            case "LESS_THAN":
                                $condition = "<";
                                break;
                            case "GREATER_THAN_OR_EQUAL":
                                $condition = ">=";
                                break;
                            case "LESS_THAN_OR_EQUAL":
                                $condition = "<=";
                                break;
                            case "STARTS_WITH":
                                $condition = "LIKE";
                                $value = "{$value}%";
                                break;
                            case "ENDS_WITH":
                                $condition = "LIKE";
                                $value = "%{$value}";
                                break;
                            case "NULL":
                                $where = "whereNull";
                                break;
                            case "NOT_NULL":
                                $where = "whereNotNull";
                                break;
                        }
                        //
                        if ('datefilter' == $filter['type']) { // exp: Wed Sep 25 1974 00:00:00 GMT+0700 (Indochina Time)
                            list($value) = explode(' GMT', $value);
                            $value = $filter['value'] = strtotime($value);
                        }
                        //.end
                        if (\is_callable($opts['where'])) {
                            $result = $opts['where']($join, $where, $filter, $condition, $value);
                            if (false === $result) {
                                continue;
                            }
                        }
                        $join->{$where}($this->jqxDatafieldMapping($filter['field']), $condition, $value);
                    }
                });
            }
        }
        // Make total rows query builder!
        // Noted: must clone before apply sort and paging!
        $totalRowsQB = clone $qB;
        //.end
        // Sort
        // || fetch latest first (if not)
        if (is_null($data['sortdatafield']))
        {
            $data['sortdatafield'] = $this->primaryKey;
        }
        if ($data['sortdatafield'])
        {
            $sortdatafield = $data['sortdatafield'];
            $sortorder = \strtolower(\trim($data['sortorder']));
            $sortorder = ($sortorder === 'asc') ? $sortorder : 'desc';
            if (\is_callable($opts['sort'])) {
                $opts['sort']($sortdatafield, $sortorder);
            }
            $qB->orderBy($this->jqxDatafieldMapping($sortdatafield), $sortorder);
        }
        // Paging
        if (is_numeric($data['pagesize']) && is_numeric($data['pagenum']))
        {
            if ($data['pagesize'] >= 0) {
                $qB
                    ->offset($data['pagesize'] * $data['pagenum'])
                    ->limit($data['pagesize'])
                ;
            }
        }
        // Debug?
        if (__FUNCTION__ === $_GET['debug']) {
            var_dump($data);
            die($qB->toSql());
        }
        // Return
        return $qB;
    }

    /**
     * Helper: parse jqx's datetime string
     * @param string $datetime
     * @param array $options An array of options
     * @return \DateTime|null
     */
    public static function parseDateTimeJqx($datetime, array $options = array())
    {
        return \DateTime::createFromFormat('D M d Y H:i:s e+', $datetime);
    }

    /**
     * Get full column name with prefix
     *
     * @param string|array $colName Column name
     * @return string
     */
    public static function columnName($colName)
    {
        $isArr = is_array($colName);
        $colName = (array)$colName;
        foreach ($colName as $idx => $col) {
            $colName[$idx] = static::$columnPrefix . $col;
        }
        return $isArr ? $colName : $colName[0];
    }

    /**
     * Get column value with prefix
     *
     * @param string $colName Column name without prefix
     * @return mixed
     */
    public function colVal($colName)
    {
        return $this->attributes[static::columnName($colName)];
    }

    /**
     * Set column value with prefix
     *
     * @param string $colName Column name without prefix
     * @param mixed $value The value to set
     * @return this
     */
    public function setColVal($colName, $value)
    {
        $this->attributes[static::columnName($colName)] = $value;
        return $this;
    }

    /**
     * Get records simple as list
     * @param array $options An array of options
     * @return mixed
     */
    public static function makeListActive(array $options = array())
    {
        $options['status'] = static::STATUS_1;
        return static::makeList($options);
    }

    /**
     * Get records simple as list
     * @param array $options An array of options
     * @return mixed
     */
    public static function makeListUnactive(array $options = array())
    {
        $options['status'] = static::STATUS_0;
        return static::makeList($options);
    }

    /**
     * @Overloading magic __get
     * @param string $prop Object's property name
     * @return mixed
     */
    public function __get($prop)
    {
        $meth = 'get' . \ucfirst(Str::camel($prop));
        if (\method_exists($this, $meth)) {
            return $this->{$meth}();
        }
        return parent::__get($prop);
    }

    /**
     * @Overloading magic __set
     * @param string $prop Object's property name
     * @param mixed $value Object's value
     * @return mixed
     */
    public function __set($prop, $value)
    {
        $meth = 'set' . \ucfirst(Str::camel($prop));
        if (\method_exists($this, $meth)) {
            return $this->{$meth}($value);
        }
        return parent::__set($prop, $value);
    }

    /**
     * @Overloading parent::fill
     * @param array $data
     * @return mixed
     */
    public function fill(array $data)
    {
        if (!empty($data) && !empty($this->fillable)) {
            foreach ($data as $key => $value) {
                if (\in_array($key, $this->fillable)) {
                    $meth = 'set' . \ucfirst(Str::camel($key));
                    if (\method_exists($this, $meth)) {
                        $this->{$meth}($value);
                        $data[$key] = $this->{$key};
                    }
                }
            }
        }
        return parent::fill($data);;
    }
}
