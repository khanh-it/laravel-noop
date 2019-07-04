<?php
namespace App\Models;

/**
 * @class Department
 */
class Department extends AbstractModel {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tbl_department';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const CREATED_AT = 'department_created_at';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const UPDATED_AT = 'department_updated_at';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const DELETED_AT = 'department_deleted_at';
    /**
     * @var string Customize the names of the columns used to store the status.
     */
    const STATUS_COLUMN = 'department_status';

    /** @var string type: Tong cong ty */
    const TYPE_10 = 10;
    /** @var string type: Cong ty con */
    const TYPE_20 = 20;
    /** @var string type: Chi nhanh */
    const TYPE_30 = 30;
    /** @var string type: Van phong dai dien  */
    const TYPE_40 = 40;
    /** @var string type: Van phong  */
    const TYPE_50 = 50;
    /** @var string type: Trung tam  */
    const TYPE_60 = 60;
    /** @var string type: Phong ban  */
    const TYPE_70 = 70;
    /** @var string type: Nhom  */
    const TYPE_80 = 80;
    /** @var string type: Phan xuong  */
    const TYPE_90 = 90;
    /**
     * Return type list
     * @return array
     */
    public static function typeList($type = null) {
        $list = [];
        $list[static::TYPE_10] = 'Tổng công ty';
        $list[static::TYPE_20] = 'Công ty con';
        $list[static::TYPE_30] = 'Chi nhánh';
        $list[static::TYPE_40] = 'Văn phòng đại diện';
        $list[static::TYPE_50] = 'Văn phòng';
        $list[static::TYPE_60] = 'Trung tâm';
        $list[static::TYPE_70] = 'Phòng ban';
        $list[static::TYPE_80] = 'Nhóm';
        $list[static::TYPE_90] = 'Phân xưởng';
        return $list;
    }

    /**
     * @var string
     */
    public static $recursivePrefix = '|---- ';

    /**
     * Indicates if the model should be timestamped.
     * @var bool
     */
    // public $timestamps = false;

     /**
     * Primary key
     *
     * @var string
     */
    protected $primaryKey = 'department_id';

    /**
     * Column datafield prefix
     * @var string
     */
    public static $columnPrefix = 'department_';

    /**
     * jqx's grid columns & datafields!
     * @var array
     */
    protected static $jqxGridColumns = [
        [ 'datafield' => 'id' ],
        [ 'datafield' => 'name' ],
        [
            'text' => 'Mã đơn vị',
            'width' => 128,
            'datafield' => 'code',
            'pinned' => true,
            'filterable' => false,
            'sortable' => false
        ],
        [
            'text' => 'Tên đơn vị',
            'datafield' => 'name_text',
            'filterable' => false,
            'sortable' => false
        ],
        [ 'datafield' => 'type' ],
        [
            'text' => 'Cấp tổ chức',
            'datafield' => 'type_text',
            'width' => 128,
            'filterable' => false,
            'sortable' => false
        ],
        [ 'datafield' => 'parent_id' ],
        [ 'datafield' => 'task' ],
        [
            'text' => 'Địa chỉ',
            'datafield' => 'address',
            'minwidth' => 128,
            'filterable' => false,
            'sortable' => false
        ],
        [ 'datafield' => 'level' ],
        [ 'datafield' => 'order' ],
        [ 'datafield' => 'salary_scale_level_id' ],
        [
            'text' => 'HSP',
            'datafield' => 'salary_scale_level_id_text',
            'width' => 64,
            'filterable' => false,
            'sortable' => false,
            'cellsalign' => 'right'
        ],
        [ 'datafield' => 'status' ],
        [
            'text' => 'Trạng thái',
            'datafield' => 'status_text',
            'width' => 128,
            'filterable' => false,
            'sortable' => false
        ],
        [ 'datafield' => 'tax_code' ],
        // [ 'datafield' => 'create_account_id' ],
        // [ 'datafield' => 'created_at' ],
        // [ 'datafield' => 'updated_at' ],
        // [ 'datafield' => 'not_deleted' ],
        // [ 'datafield' => 'delete_account_id' ],
        // [ 'datafield' => 'deleted_at' ],
    ];

    /**
     * Alter jqx grid column info
     * @param array $col Column data
     * @return array
     */
    public static function jqxGridCol($col)
    {
        /* $dfd = static::jqxGridDatafieldByCol($col);
        if ('type_text' === $dfd) {
            $col['filteritems'] = array_values(static::typeList());
            $col['filtertype'] = 'list';
        }
        if ('status_text' === $dfd) {
            $col['filteritems'] = array_values(static::statusList());
            $col['filtertype'] = 'list';
        } */
        return $col;
    }

    /**
     * Get the accounts.
     */
    public function accounts()
    {
        return $this->hasMany(Account::class, Account::columnName('department_id'));
    }

    /**
     * Get the company.
     */
    public function company()
    {
        return $this->belongsTo(Company::class, $this->columnName('company_id'));
    }

    /**
     * Get the departments.
     */
    public function departments()
    {
        return $this->hasMany(Department::class, Department::columnName('parent_id'));
    }

    /**
     * Get the company (self).
     */
    public function parent()
    {
        return $this->belongsTo(Department::class, $this->columnName('parent_id'));
    }

    /**
     * Get the salary_scale_level.
     */
    public function salaryScaleLevel()
    {
        return $this->belongsTo(SalaryScaleLevel::class, $this->columnName('salary_scale_level_id'));
    }
    /**
     * Get the salary_scale_level.
     * @return float
     */
    public function getSlrScaleLevelRate()
    {
        return $this->salaryScaleLevel ? (1 * $this->salaryScaleLevel->colVal('rate')) : 0;
    }

    /**
     * Get records simple as list
     * @param array $options An array of options
     * @return mixed
     */
    public static function makeList(array $options = array())
    {
        //roles
        $roles = Roles::checkRoles();
        $permissons = Permissions::checkPermissons();
        $idDepUser = \Auth::User()->account_department_id;
        // Create query builder
        $qB = static::whereRaw(1);
        //
        $depListStaff = [];
        // ||Filter
        // +++
        if ($options['flag_company']) {
            $options['parent_id'] = null; // Top level records
            $options['type'] = [static::TYPE_10, static::TYPE_20];
        }
        // +++
        /* if (array_key_exists('parent_id', $options))
        {
            $parentId = is_array($options['parent_id']) ? $options['parent_id'] : [ $options['parent_id'] ];
            $qB->where(function($_qB) use ($parentId) {
                foreach ($parentId as $pId)
                {
                    if (is_null($pId))
                    {
                        $_qB->orWhereNull(static::columnName('parent_id'));
                    }
                    else
                    {
                        $_qB->orWhere(static::columnName('parent_id'), $pId);
                    }
                }
            });
            unset($parentId);
        } */
        // +++
        if (!empty($options['type']))
        {
            $types = \array_filter((array)$options['type']);
            $qB->whereIn(static::columnName('type'), $types);
            unset($types);
        }
        // +++
        if (!empty($options['status']))
        {
            $qB->where(static::STATUS_COLUMN, $options['status']);
        }

        if(!$permissons['permissonsAdmin']){
            //kiểm tra quyền trưởng phòng
            if($roles['rolesHeadDepartment']){
                $qB->where(static::columnName('id'), $idDepUser)
                    ->orWhere(static::columnName('id'), 1)
                    ->orWhere(static::columnName('parent_id'), $idDepUser);
            }
        }
        // die($qB->toSql());
        // ||Fetch
        $collect = $qB->get();
        $depList = static::_makeListBuild($collect, $options);
        if(!$permissons['permissonsAdmin']){
            $key = key($depList);
            if($roles['rolesStaff']){
                $depListStaff[$key] = $depList[$key];
                $depListStaff[\Auth::User()->account_department_id] = $depList[\Auth::User()->account_department_id];
                return $depListStaff;
            }
            if($roles['rolesHeadDepartment']){
                if($options['rolesHead']){
                    unset($depList[$key]);
                }
            }
        }
        return $depList;
    }

    /**
     *
     * @return array
     */
    protected static function _makeListBuild($depList, array &$opts = array(), &$return = array())
    {
        // Init options
        if (!$opts['prefix']) {
            $opts['prefix'] = static::$recursivePrefix;
        }
        if (is_null($opts['_level'])) {
            $opts['_level'] = 0;
        }
        $_level = $opts['_level'];
        //
        $depNameTxt = 'name_text';
        foreach ($depList as $idx => $row)
        {
            if ($row->colVal('parent_id') == ($parentId = $opts['parent_id']))
            {
                $depId = $row->id();
                $row->setColVal($depNameTxt, \str_repeat($opts['prefix'], $_level) . ($row->colVal('name') . " [{$depId}]"));
                $return[$depId] = $row;
                $opts['parent_id'] = $depId;
                $opts['_level'] += 1;
                static::_makeListBuild($depList, $opts, $return);
                $opts['parent_id'] = $parentId;
                $opts['_level'] = $_level;
            }
        }
        // Flatten --> make list for <select />
        if (0 === $opts['_level']) {
            $flatReturn = [];
            foreach ($return as $depId => $row) {
                $flatReturn[$depId] = $row->colVal($depNameTxt);
            }
        }
        // Return
        return (true === $opts['full']) ?  $return : $flatReturn;
    }

    /**
     * @param array $data Jqx request payload
     * @return void
     */
    public function jqxFetchRecordList(array $data, &$qB = null, &$totalRowsQB = null)
    {
        // Define vars
        // +++
        $typeList = static::typeList();
        $typeListFlip = array_flip($typeList);
        // +++
        $statusList = static::statusList();
        $statusListFlip = array_flip($statusList);
        // Prepare the data
        $qB = $this->qBFromJqxRequestPayload($data, $totalRowsQB, [
            // Alter query builder
            'queryBuilder' => function(\Illuminate\Database\Eloquent\Builder $qB, $data) {
                // Set default query conditions
                $key = ($col = $this->columnName('status')) . '_text';
                if (!$data['filterGroups'][$key]) {
                    $qB->where($col, static::STATUS_1);
                }
                unset($col, $key);
            },
            // Alter where conditions
            'where' => function(&$join, &$where, &$filter, &$condition, &$value)
                use ($typeListFlip, $statusListFlip) {
                    if (($prop = $this->columnName('type')) . '_text' === $filter['field']) {
                        $value = $filter['value'] = $typeListFlip[$value];
                    }
                    if (($prop = $this->columnName('status')) . '_text' === $filter['field']) {
                        $value = $filter['value'] = $statusListFlip[$value];
                    }
                }
            ,
        ]);
        // var_dump($data);die($qB->toSql());
        // Format data
        // +++
        $rows = $qB->with('salaryScaleLevel')->get()->map(function($row, $idx)
            use ($statusList, $typeList) {
                $prop;
                $row->setColVal(($prop = 'type') . '_text', $typeList[$row->colVal($prop)]);
                $row->setColVal(($prop = 'salary_scale_level_id') . '_text', numberFormatTax(
                    $row->getSlrScaleLevelRate()
                ));
                $row->setColVal(($prop = 'status') . '_text', $statusList[$row->colVal($prop)]);
                //
                return $row;
            })
        ;
        //.end

        // Return
        $opts = ['full' => true];
        $results = self::_makeListBuild($rows, $opts);
        return array_values($results);
    }

    /**
     * Find all descendant
     * @param int|string $parentId Parent id
     * @param array $options An array of options
     * @return void
     */
    public static function findAllDescendant($parentId, array $options = array())
    {
        $opts = [
            'parent_id' => $parentId,
            'full' => true
        ];
        $rows = static::makeList($opts);
        // Return
        return $rows;
    }

    /**
     * Find all ancestor
     * @param array $options An array of options
     * @return string
     */
    public function findAllAncestor(array $options = array())
    {
        $ancestor = [];
        $parent = $this;
        do {
            if ($ancestor[$id = $parent->id()]) {
                break;
            }
            $ancestor[$id] = $parent;
            $parent = $this->parent;
        } while ($parent);
        // Return
        return $ancestor;
    }

    /**
     * Return department name recursive
     * @param array $options An array of options
     * @return string
     */
    public function departmentNameRecursive(array $options = array())
    {
        $names = [];
        $parent = $this;
        do {
            if ($names[$id = $parent->id()]) {
                break;
            }
            $names[$id] = $parent->colVal($prop = 'name') . " [{$id}]";
            $parent = $this->parent;
        } while ($parent);
        //
        $names = array_reverse($names);
        // Return
        return implode($options['join'] ?: static::$recursivePrefix, $names);
    }
}