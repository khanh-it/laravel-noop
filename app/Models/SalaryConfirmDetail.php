<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryConfirmDetail extends AbstractModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tbl_salary_confirm_detail';

    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const CREATED_AT = 'salary_confirm_detail_created_at';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const UPDATED_AT = 'salary_confirm_detail_updated_at';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const DELETED_AT = 'salary_confirm_detail_deleted_at';


    /**
     * @var string trạng thái xác nhận từ user(chưa xác nhận).
     */
    const STATUS_UNCONFIRMED = 'UNCONFIRMED';
    /**
     * @var string trạng thái xác nhận từ user(xác nhận).
     */
    const STATUS_CONFIRMED = 'CONFIRMED';

    /**
     * Return status list
     * @return void
     */
    public static function statusList() {
        $list = [
            static::STATUS_UNCONFIRMED => 'Chưa xác nhận',
            static::STATUS_CONFIRMED => 'Đã xác nhận',
        ];
        return $list;
    }

    /**
     * Primary key
     *
     * @var string
     */
    protected $primaryKey = 'salary_confirm_detail_id';

    /**
     * Column datafield prefix
     * @var string
     */
    public static $columnPrefix = 'salary_confirm_detail_';

    /**
     * jqx's grid columns & datafields!
     * @$array
     */
    protected static $jqxGridColumns = [
        [ 'datafield' => 'id' ],
        [ 'datafield' => 'confirm_id' ],
        [ 'datafield' => 'company_id' ],
        [ 'datafield' => 'department_id' ],
        [
            'text' => 'Đơn vị',
            'datafield' => [['department_name']],
            'width' => 0,
            'filterable' => false,
            'sortable' => false,
            'pinned' => true,
            'hidden' => true
        ],
        [ 'datafield' => 'account_id' ],
        [
            'text' => 'Mã N.Viên',
            'datafield' => [['account_code']],
            'width' => 96,
            'filterable' => true,
            'sortable' => false,
            'pinned' => true
        ],
        [
            'text' => 'Họ và tên',
            'datafield' => [['account_fullname']],
            'minwidth' => 150,
            'filterable' => true,
            'sortable' => false,
            'pinned' => true
        ],
        ['datafield' => 'status'],
        [
            'text' => 'Xác nhận',
            'datafield' => ['status_text'],
            'width' => 128,
            'cellsalign' => 'center',
            'sortable' => false,
            'editable' => false,
            'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}'
        ],
        [
            'text' => 'Ngày xác nhận',
            'datafield' => ['time', [
                'type' => 'range',
            ]],
            'width' => 150,
            'filterable' => false,
            'filtertype' => 'date',
            'editable' => false,
            'cellsformat' => 'dd/MM/yyyy',
            'cellsalign' => 'center',
        ],
        [
            'text' => 'Ghi chú',
            'datafield' => 'note',
            'sortable' => false,
            'filterable' => false,
            'columntype' => 'textbox',
            'editable' => true,
            'minwidth' => 128,
            'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}',
        ],
        [ 'datafield' => 'created_by' ],
        [ 'datafield' => 'created_at' ],
    ];

    /**
     * Alter jqx grid column info
     * @param array $col Column data
     * @return array
     */
    public static function jqxGridCol($col)
    {
        $dfd = static::jqxGridDatafieldByCol($col);
        if ('status_text' === $dfd) {
            $col['filteritems'] = array_values(static::statusList());
            $col['filtertype'] = 'list';
        }

        if (!in_array($dfd, [
            'account_code',
            'account_fullname',
            'time','note'
        ])) {
            $col = array_replace($col, [
                'columntype' => 'custom',
                'createeditor' => "{!!window.mkJqxGridEditor(0, '{$dfd}')!!}",
                'initeditor' => "{!!window.mkJqxGridEditor(1, '{$dfd}')!!}",
                // 'geteditorvalue' => "{!!window.mkJqxGridEditor(2, '{$dfd}')!!}",
            ]);
        }

        if (in_array($dfd, [
                'account_fullname',
                'status_text',
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
     * Get salary.
     * @return salaryConfirm|null
     */
    public function salaryConfirm()
    {
        return $this->belongsTo(SalaryConfirm::class, $this->columnName('confirm_id'));
    }

    /**
     * Get the department model.
     */
    public function department()
    {
        return $this->belongsTo(Department::class, $this->columnName('department_id'));
    }

    /**
     * Get the account model.
     */
    public function account()
    {
        return $this->belongsTo(Account::class, $this->columnName('account_id'));
    }

    /**
     * Get the create account.
     */
    public function createAccount()
    {
        return $this->belongsTo(Account::class, $this->columnName('created_by'));
    }

    /**
     * Get the create account.
     */
    public function updateAccount()
    {
        return $this->belongsTo(Account::class, $this->columnName('updated_by'));
    }

    /**
     * Helper: set account/dep/company info
     * @param Account $account
     * @return this
     */
    public function setAccount(Account $account)
    {
        $department = $account->department;
        //
        $this->account()->associate($account);
        if ($department) {
            $this->department()->associate($department);
        } else {
            $this->department()->dissociate();
        }
    }

    /**
     * Get the delete account.
     */
    public function deleteAccount()
    {
        return $this->belongsTo(Account::class, $this::columnName('deleted_by'));
    }


    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        "salary_confirm_detail_id",
        "salary_confirm_detail_confirm_id",
        "salary_confirm_detail_department_id",
        "salary_confirm_detail_account_id",
        "salary_confirm_detail_status",
        "salary_confirm_detail_note",
        "salary_confirm_detail_time",
        "salary_confirm_detail_created_by",
        "salary_confirm_detail_created_at",
        "salary_confirm_detail_updated_at",
        "salary_confirm_detail_updated_by",
        "salary_confirm_detail_deleted_by",
        "salary_confirm_detail_deleted_at",
    ];

    /**
     * Self update record's salaryconfirm info
     * @param array $slrdItem salaryconfirm info item
     * @return bool
     */
    public function updateSalaryDetailInfo(array $slrdInfo)
    {
        $result = !!$this->fill($slrdInfo);
        return $result;
    }

    /**
     * 
     * @param Account $account
     * @param array $options 
     * @return this
     */
    public function assignSalaryConfirmDetail(Account $account, array $options = [])
    {
        // Get, format input(s)
        // Self update salary detail info
        $colPrefix = static::$columnPrefix;
        // 
        $slrdInfo = [
            // ($colPrefix . ($col = 'time')) => time(),
            ($colPrefix . ($col = 'created_by')) => \Auth::id(),
        ];

        $this->updateSalaryDetailInfo($slrdInfo);
        //.end
        return $result;
    }

    /**
     * 
     *
     * @param array $data Jqx request payload
     * @return void
     */
    public function jqxFetchRecordList(SalaryConfirm $salaryConfirmModel, array $data, &$qB = null, &$totalRowsQB = null)
    {
        // Define vars
        // +++ thong tin cong ty
        $statusList = static::statusList();
        $statusListFlip = array_flip($statusList);
        // Prepare the data
        $roles = Roles::checkRoles();
        $permissons = Permissions::checkPermissons();
        $userID = \Auth::id();
        $qB = $this->qBFromJqxRequestPayload($data, $totalRowsQB, [
            // Alter query builder
            'queryBuilder' => function(\Illuminate\Database\Eloquent\Builder $qB)
                use ($salaryConfirmModel, $roles, $userID, $permissons) {
                    // Relatives
                    $models = [
                        // 'com' => app()->make(Company::class),
                        'dep' => app()->make(Department::class),
                        'acc' => app()->make(Account::class),
                    ];
                    $modelTable = $this->getTable();

                    // Limit: chi lay du lieu detail trong 1 master table!
                    $qB->where("{$modelTable}.".static::columnName('confirm_id'), $salaryConfirmModel->id());
                    // Join account
                    $qB->leftJoin(
                        ($tableAcc = $models['acc']->getTable())
                        , ("{$tableAcc}." . ($pK = $models['acc']->getKeyName()))
                        , '=', "{$modelTable}." . $this->columnName($pK)
                    );

                    if(!$permissons['permissonsAdmin']){
                        //kiểm tra quyền nhân viên
                        if($roles['rolesStaff'] || $roles['rolesHeadDepartment']){
                            $qB->where("{$modelTable}.".$this->columnName('account_id'), $userID);
                        }
                        // //kiểm tra quyền trưởng phòng
                        // if($roles['rolesHeadDepartment']){
                        //     //array id phong ban của trưởng phòng
                        //     $departmentList = $models['dep']::makeList(['rolesHead' => true]);
                        //     $depArrID = array_keys($departmentList);
                        //     $qB->whereIn("{$modelTable}.".$this->columnName('department_id'), $depArrID);
                        // }
                    }

                    //SẮP XẾP THEO CHỨC DANH
                    $qB->orderBy("{$tableAcc}.account_position_id", 'ASC');
                    // Limit select
                    $qB->select([
                        "{$modelTable}.*",
                        // "{$tableCom}.company_name",
                        // "{$tableDep}.department_name",
                        "{$tableAcc}.account_code",
                        "{$tableAcc}.account_fullname",
                    ]);
                    
                }
            ,
            //
            // Alter where conditions
            'where' => function(&$join, &$where, &$filter, &$condition, &$value)
                use ($statusListFlip) {
                    // +++
                    if (($prop = static::columnName('status')) . '_text' === $filter['field']) {
                        $filter['field'] = $prop;
                        $value = $filter['value'] = $statusListFlip[$value];
                        
                    }
                    if (in_array($filter['field'], [
                        'salary_confirm_detail_time',
                    ])) {
                        $value = $filter['value'] = date('Y-m-d', $value);
                    }
                }
        ]);
        //var_dump($date);die($qB->toSql());
        // Format data
        // +++
		// +++
        $rows = $qB->get()->map(function($row, $idx) use ($statusList){
                $prop;
                // Thong tin don vi (cong ty / phong ban) de quy
                $row->department_name = $row->department->departmentNameRecursive();
                $row->setColVal(($prop = 'status') . '_text', $statusList[$row->colVal('status')]);
                if($row->colVal('time')){
                    $row->setColVal($prop = 'time', \std_date_str($row->colVal($prop)));
                }
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
     * @param int|string $slrId findMatchSalaryConfirm id
     * @return mixed
     */
    public static function findMatchSalaryConfirm($id, $slrId)
    {
        $model = app()->make(static::class);
        $isArr = \is_array($id);
        $id = (array)$id;
        $query = static
            ::whereIn($model->getKeyName(), $id)
            ->where(static::columnName('confirm_id'), $slrId)
        ;
        $return = $isArr ? $query->get() : $query->first();
        return $return;
    }
}
