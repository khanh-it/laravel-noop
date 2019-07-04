<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class SalaryConfirm extends AbstractModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tbl_salary_confirm';

    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const CREATED_AT = 'salary_confirm_created_at';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const UPDATED_AT = 'salary_confirm_updated_at';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const DELETED_AT = 'salary_confirm_deleted_at';
    /**
     * @var string status UNCONFIRMED(Chưa xác nhận hết).
     */
    const STATUS_UNCONFIRMED = 'UNCONFIRMED';
    /**
     * @var string status ALL_CONFIRMED(đã xác nhân tất cả).
     */
    const STATUS_ALL_CONFIRMED = 'ALL_CONFIRMED';

    /**
     * Return status list
     * @return void
     */
    public static function statusList() {
        $list = [
            static::STATUS_UNCONFIRMED => 'Chưa hoàn tất',
            static::STATUS_ALL_CONFIRMED => 'Hoàn tất',
        ];
        return $list;
    }

    /**
     * Primary key
     * @var string
     */
    protected $primaryKey = 'salary_confirm_id';

    /**
     * Get salaryConfirm_from data
     * @return string
     */
    public function salaryConfirmFrom()
    {
        $value = $this->salary_confirm_from;
        if (!$value) {
            $value = "{$this->salary_confirm_year}-{$this->salary_confirm_month}-01 00:00:00";
        }
        return $value;
    }
    /**
     * Get salaryConfirm_to data
     * @return string
     */
    public function salaryConfirmTo()
    {
        $value = $this->salary_confirm_to;
        if (!$value) {
            $value = strtotime("{$this->salary_confirm_year}-{$this->salary_confirm_month}-01");
            $value = date('Y-m-t 23:59:59', $value);
        }
        return $value;
    }
    /**
     * Get salaryConfirm from-to data
     * @return \DatePeriod
     */
    public function salaryConfirmPeriod()
    {
        $from = new \DateTime($this->salaryConfirmFrom());
        $to = new \DateTime($this->salaryConfirmTo());
        $interval = \DateInterval::createFromDateString('1 day');
        $period = new \DatePeriod($from, $interval, $to);
        return $period;
    }

    /**
     * Calculate work days of month
     *
     * @param array $options
     * @return double
     */
    public function calWorktime(array $options = array())
    {
        $workDays = WorkingShift::workingDaysOfMonth($this->salaryPeriod(), null, [
            'department_id' => $this->colVal('department_id')
        ]);
        return $workDays;
    }

    /**
     * Column datafield prefix
     * @var string
     */
    public static $columnPrefix = 'salary_confirm_';

    /**
     * jqx's grid columns & datafields!
     * @var array
     */
    protected static $jqxGridColumns = [
        [ 'datafield' => 'id' ],
        [ 'datafield' => 'department_id' ], // @TODO: Fix overwrite when form reset
        [ 'datafield' => 'month' ],
        [ 'datafield' => 'year' ],
        [
            'text' => 'Tháng / Năm',
            'datafield' => [['month_year']],
            'width' => 164,
            'cellsalign' => 'right',
            'filtertype' => 'range',
        ],
        [
            'text' => 'Tên bảng lương xác nhận',
            'datafield' => 'name',
        ],
        [
            'text' => 'Số Chứng từ',
            'datafield' => [ 'receipt' ],
            'sortable' => false,
            'cellsalign' => 'center',
            'filterable' => false,
            'minwidth' => 128,
        ],
        [
            'text' => 'Đơn vị',
            'datafield' => [[ 'department_id_text' ]],
            // 'minwidth' => 256,
            'sortable' => false,
        ],
        [
            'text' => 'Trạng thái xác nhận',
            'sortable' => false,
            'cellsalign' => 'center',
            'width' => 128,
            'datafield' => [[ 'status_text' ]],
        ],
        [
            'text' => 'Ghi chú',
            'sortable' => false,
            'filterable' => false,
            'minwidth' => 128,
            'datafield' => 'note',
        ],
    ];

    /**
     * Alter jqx grid column info
     * @param array $col Column data
     * @return array
     */
    public static function jqxGridCol($col)
    {
        $dfd = static::jqxGridDatafieldByCol($col);
        if ('department_id_text' === $dfd) {
            $col['filteritems'] = array_values(Department::makeList());
            $col['filtertype'] = 'list';
        }
        if ('status_text' === $dfd) {
            $col['filteritems'] = array_values(static::statusList());
            $col['filtertype'] = 'list';
        }

        if (in_array($dfd, [
                'month_year',
            ])
        ) {
            $col = array_replace($col, [
                'aggregates' => "{!!window.mkJqxGridAggregates('{$dfd}')!!}",
                'aggregatesrenderer' => "{!!window.jqxGridAggregatesRenderer!!}",
            ]);
        }
        return $col;
    }

    /**
     * Get the create account.
     */
    public function createAccount()
    {
        return $this->belongsTo(Account::class, static::columnName('created_by'));
    }

    /**
     * Get the delete account.
     */
    public function deleteAccount()
    {
        return $this->belongsTo(Account::class, static::columnName('deleted_by'));
    }

    /**
     * Get company info.
     * @return Company
     */
    public function company()
    {
        return $this->belongsTo(Company::class, static::columnName('company_id'));
    }

    /**
     * Get department info.
     * @return Department
     */
    public function department()
    {
        return $this->belongsTo(Department::class, static::columnName('department_id'));
    }

    /**
     * @override
     * @param array $options = Array
     * @return mixed
     */
    public function save(array $options = array())
    {
        // Process wth relationships
        if (!$this->{$this->columnName('company_id')} 
            && $this->department
        ) {
            $this->{$this->columnName('company_id')}
                = $this->department->{$this->department->columnName('company_id')}
            ;
        }

        if (!$this->{$this->columnName('created_by')}) {
            $this->{$this->columnName('created_by')}
                = \Auth::id();
            ;
        }
        //.end
        return parent::save($options);
    }

    /**
     * Get/set salary details.
     * @return mixed
     */
    public function salaryConfirmDetails()
    {
        return $this->hasMany(SalaryConfirmDetail::class, SalaryConfirmDetail::columnName('confirm_id'));
    }

    /**
     * Get records simple as list
     * @param array $options An array of options
     * @return mixed
     */
    public static function makeListCompany(array $options = array())
    {
        return Company::makeList($options);
    }

    /**
     * Make a list of months in year
     * @param array $options An array of options
     * @return array
     */
    public static function makeListMonth(array $options = array())
    {
        $return = [];
        for ($i = 1; $i <= 12; $i++) {
            $return[$i] = "Tháng {$i}";
        }
        return $return;
    }

    /**
     * Make a list of quarters in year
     * @param array $options An array of options
     * @return array
     */
    public static function makeListQuarter(array $options = array())
    {
        $return = [];
        for ($i = 1; $i <= 4; $i++) {
            $return[$i] = "Quý {$i}";
        }
        return $return;
    }

    /**
     * Make a list of years
     * @param array $options An array of options
     * @return array
     */
    public static function makeListYear(array $options = array())
    {
        $return = [];
        $curY = intval(date('Y'));
        for ($i = $curY; $i >= $curY - 5; $i--) {
            $return[$i] = "{$i}";
        }
        return $return;
    }

    /**
     * Self populate details records
     * @param array $options An array of options
     * @return array
     */
    public function populateSalaryConfirmDetails(array $options = array())
    {
        // Fetch data
        $accounts = Account::findAllByOrganizationUnit($departmentId = $this->colVal('department_id'));
        // +++
        // Thong tin chi tiet
        if (!empty($accounts))
        {
            //thông tin tháng năm bảng lương
            $date = $this->colVal('year').'-'.$this->colVal('month');
            // Detail model class
            $modelClass = get_class($this->salaryConfirmDetails()->getRelated());
            //
            foreach ($accounts as $account)
            {
                // kiểm tra nhân viên đã ngưng làm chưa
                if($account['account_end_time'] > strtotime($date) || $account['account_status'] == 1){
                    $model = app()->make($modelClass);
                    $model->setAccount($account);
                    $model->assignSalaryConfirmDetail($account, $options);
                    $model->save();
                    $this->salaryConfirmDetails()->save($model);
                }
            }
        }
        return count($accounts);
    }

    /**
     * 
     *
     * @param array $data Jqx request payload
     * @return void
     */
    public function jqxFetchRecordList(array $data, &$qB = null, &$totalRowsQB = null, array $options = array())
    {
        // Define vars
        // +++
        $departmentList = Department::makeList();
        $departmentListFlip = array_flip($departmentList);
        // +++
        $statusList = static::statusList();
        $statusListFlip = array_flip($statusList);
        //Roles
        $roles = Roles::checkRoles();
        $permissons = Permissions::checkPermissons();
        // Prepare the data
        $qB = $this->qBFromJqxRequestPayload($data, $totalRowsQB, [
            // Alter query builder
            'queryBuilder' => function(\Illuminate\Database\Eloquent\Builder $qB) 
            use ($roles, $departmentList, $permissons) {
                $models = [
                    'dep' => app()->make(Department::class),
                ];
                $modelTable = $this->getTable();
                // Join department
                $qB->leftJoin(
                    ($tableDep = $models['dep']->getTable())
                    , ("{$tableDep}." . ($pK = $models['dep']->getKeyName()))
                    , '=', "{$modelTable}." . $this->columnName($pK)
                );
                if(!$permissons['permissonsAdmin']){
                    //kiểm tra quyền trưởng phòng hoặc nhân viên
                    if($roles['rolesHeadDepartment'] || $roles['rolesStaff']){
                        $depArrID = array_keys($departmentList);
                        $qB->whereIn("{$tableDep}.".$pK, $depArrID);
                    }
                }

                // Select
                $qB->select([
                    "{$modelTable}.*",
                    "{$tableDep}.department_name as department_id_text",
                ]);
            },

            // Alter where conditions
            'where' => function(&$join, &$where, &$filter, &$condition, &$value)
                use ($departmentListFlip, $statusListFlip) {
                    // +++
                    if (($prop = $this->columnName('department_id')) . '_text' === $filter['field']) {
                        $value = $filter['value'] = $departmentListFlip[$value];
                    }
                    // +++
                    if (($prop = static::columnName('status')) . '_text' === $filter['field']) {
                        $filter['field'] = $prop;
                        $value = $filter['value'] = $statusListFlip[$value];
                    }
                }
        ]);
        // var_dump($data);die($qB->toSql());
        // +++
        $rows = $qB->get()->map(function($row, $idx) use($statusList){
                // +++ thang/nam
                $row->{($prop = 'month_year')} = ($row->colVal('month') . ' / ' . $row->colVal('year'));
                // +++ subtotal
                $row->{($prop = 'status_text')} = $statusList[$row->colVal('status')];
                //
                return $row;
            })
        ;
        //.end
        
        // Return
        return $rows;
        
	}
    
    /**
     * Find records match
     * @param int|string|array $id Record ids
     * @param int|string $type Type
     * @return mixed
     */
    public static function findMatchType($id, $type)
    {
        $model = app()->make(static::class);
        $isArr = \is_array($id);
        $id = (array)$id;
        $query = static
            ::whereIn($model->getKeyName(), $id)
            ->where(static::columnName('type'), $type)
        ;
        $return = $isArr ? $query->get() : $query->first();
        return $return;
    }

}
