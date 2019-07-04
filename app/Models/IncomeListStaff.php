<?php
namespace App\Models;

/**
 * @class IncomeListStaff
 */
class IncomeListStaff extends Account
{
    /**
     * jqx's grid columns & datafields!
     * @var array
     */
    protected static $jqxGridColumns = [
        [ 'datafield' => 'id' ],
        [
            'text' => 'Mã nhân viên',
            'datafield' => 'code',
            'width' => 90,
            'pinned' => true,
        ],
        [
            'text' => 'Họ và tên',
            'datafield' => 'fullname',
            'width' => 180,
            'pinned' => true,
        ],
        [
            'text' => 'Giới tính',
            'datafield' => 'gender',
            'width' => 60,
        ],
        [ 'datafield' => 'department_id' ], // required for foreign source
        [
            'text' => 'Phòng ban',
            'datafield' => [['department_name']],
        ],
        [
            'text' => 'Ngày sinh',
            'datafield' => ['birthday_time', [
                'type' => 'range',
            ]],
            'width' => 100,
            'filtertype' => 'date',
            'cellsformat' => 'dd/MM/yyyy',
            'cellsalign' => 'center',
        ],
        [
            'text' => 'Nơi sinh',
            'datafield' => 'birthday_place',
        ],
        [
            'text' => 'Mã số thuế',
            'datafield' => 'tax',
        ],
        [ 'datafield' => 'position_id' ],
        [
            'text' => 'Chức vụ',
            'datafield' => [ ['position_name'] ],
        ],
        [
            'text' => 'Thời gian thử việc',
            'datafield' => 'trial_time',
            'width' => 85,
            'filtertype' => 'date',
            'cellsalign' => 'center',
        ],
        [
            'text' => 'Thời gian làm chính thức',
            'datafield' => 'receipt_time',
            'width' => 85,
            'filtertype' => 'date',
            'cellsalign' => 'center',
        ],
        [
            'text' => 'Số CMND',
            'datafield' => 'id_number',
            'columngroup' => ['identity', [
                'text' => 'CMND',
            ]]
        ],
        [
            'text' => 'Ngày cấp',
            'datafield' => ['id_number_time', [
                'type' => 'range',
            ]],
            'filtertype' => 'date',
            'cellsformat' => 'dd/MM/yyyy',
            'cellsalign' => 'center',
            'width' => 100,
            'columngroup' => 'identity',
        ],
        [
            'text' => 'Nơi cấp',
            'datafield' => 'id_number_place',
            'columngroup' => 'identity',
        ],
        [
            'text' => 'Điện thoại bàn',
            'datafield' => 'desk_phone',
        ],
        [
            'text' => 'Số di động',
            'datafield' => 'mobile_phone',
        ],
        [
            'text' => 'Số sổ lao động',
            'datafield' => 'labor_book',
        ],
        [ 'datafield' => 'work_status', ],
        [
            'text' => 'Trạng thái làm việc',
            'datafield' => 'work_status_text',
            'filtertype' => 'list',
        ],
        [
            'text' => 'Số hộ chiếu',
            'datafield' => 'passport_number',
            'columngroup' => ['passport', [
                'text' => 'Hộ chiếu',
            ]]
        ],
        [
            'text' => 'Ngày cấp',
            'datafield' => ['passport_create_time', [
                'type' => 'range',
            ]],
            'filtertype' => 'date',
            'cellsformat' => 'dd/MM/yyyy',
            'cellsalign' => 'center',
            'width' => 100,
            'columngroup' => 'passport',
        ],
        [
            'text' => 'Ngày hết hạn',
            'datafield' => ['passport_end_time', [
                'type' => 'range',
            ]],
            'filtertype' => 'date',
            'cellsformat' => 'dd/MM/yyyy',
            'cellsalign' => 'center',
            'width' => 100,
            'columngroup' => 'passport',
        ],
        [
            'text' => 'Nơi cấp',
            'datafield' => 'passport_place',
            'columngroup' => 'passport',
        ],
        [ 'datafield' => 'status' ],
        [
            'text' => 'Trạng thái',
            'datafield' => 'status_text',
            'width' => 128,
            'filtertype' => 'list',
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
        if (in_array($dfd, [
            ])
        ) {
            $col = array_replace($col, [
                'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}'
            ]);
        }
        if ('work_status_text' === $col['datafield']) {
            $col['filteritems'] = array_values(static::workStatusList());
        }
        if ('status_text' === $col['datafield']) {
            $col['filteritems'] = static::statusList();
        }
        return $col;
    }

    /**
     *
     *
     * @param array $data Jqx request payload
     * @return void
     */
    public function jqxFetchRecordList(array $data, &$qB = null, &$totalRowsQB = null)
    {
        // Format data
		// +++
        $genderList = static::genderList();
        $statusList = static::statusList();
        $workStatusList = static::workStatusList(); 
        //Roles
        $roles = Roles::checkRoles();
        $permissons = Permissions::checkPermissons();
        $userID = \Auth::id();
        //danh sách phòng ban
        //rolesStaff => nhân viên
        //rolesHeadDepartment => trưởng phòng
        //rolesAccountant => kế toán
        //rolesBod => ban giám đốc
        //rolesAdmin => admin
        //rolesSuperAdmin => superadmin
        if($roles['rolesHeadDepartment']){
            $departmentList = Department::makeList(['rolesHead' => true]);
        }
		// Prepare the data
		$qB = $this->qBFromJqxRequestPayload($data, $totalRowsQB, [
            // Alter query builder
            'queryBuilder' => function(\Illuminate\Database\Eloquent\Builder $qB) 
            use ($roles, $userID, $departmentList, $permissons){
                $models = [
                    // 'com' => app()->make(Company::class),
                    'dep' => app()->make(Department::class),
                    'pos' => app()->make(Position::class),
                ];
                $modelTable = $this->getTable();
                /* // Join company
                $qB->leftJoin(
                    ($tableCom = $models['com']->getTable())
                    , ("{$tableCom}." . ($pK = $models['com']->getKeyName()))
                    , '=', "{$tableDep}." . $models['dep']->columnName($pK)
                ); */
                // Join department
                $qB->leftJoin(
                    ($tableDep = $models['dep']->getTable())
                    , ("{$tableDep}." . ($pK = $models['dep']->getKeyName()))
                    , '=', "{$modelTable}." . $this->columnName($pK)
                );
                // Join position
                $qB->leftJoin(
                    ($tablePos = $models['pos']->getTable())
                    , ("{$tablePos}." . ($pK = $models['pos']->getKeyName()))
                    , '=', "{$modelTable}." . $this->columnName($pK)
                );
                if(!$permissons['permissonsAdmin']){
                    //kiểm tra quyền nhân viên
                    if($roles['rolesStaff']){
                        $qB->where("{$modelTable}.".$this->columnName('id'), $userID);
                    }
                    //kiểm tra quyền trưởng phòng
                    if($roles['rolesHeadDepartment']){
                        //array id phong ban của trưởng phòng
                        $depArrID = array_keys($departmentList);
                        $qB->whereIn("{$modelTable}.".$this->columnName('department_id'), $depArrID);
                    }
                }

                //SẮP XẾP THEO CHỨC DANH
                $qB->orderBy("{$modelTable}.account_position_id", 'ASC');

                // Select
                $qB->select([
                    "{$modelTable}.*",
                    // "{$tableCom}.company_name",
                    "{$tableDep}.department_name",
                    "{$tablePos}.position_name",
                ]);
                // die($qB->toSql());
            },
            // Alter where conditions
            'where' => function(&$join, &$where, &$filter, &$condition, &$value) {
                if (in_array($filter['field'], [
                    'account_birthday_time',
                    'account_id_number_time'
                ])) {
                    $value = $filter['value'] = date('Y-m-d', $value);
                }
            },
        ]);
		// +++
        $rows = $qB->get()->map(function($row, $idx)
            use ($genderList, $statusList, $workStatusList) {
                $prop;
                $row->setColVal(($prop = 'gender'), $genderList[$row->colVal($prop)]);
                //
                $row->setColVal(($prop = 'birthday_time'), \std_date_str($row->colVal($prop)));
                $row->setColVal(($prop = 'id_number_time'), \std_date_str($row->colVal($prop)));
                $row->setColVal(($prop = 'passport_create_time'), \std_date_str($row->colVal($prop)));
                $row->setColVal(($prop = 'passport_end_time'), \std_date_str($row->colVal($prop)));
                $row->setColVal(($prop = 'trial_time'), \std_date_str($row->colVal($prop)));
                $row->setColVal(($prop = 'receipt_time'), \std_date_str($row->colVal($prop)));
                $row->setColVal(($prop = 'create_time'), \std_date_str($row->colVal($prop)));
                //
                // $row->setColVal(($prop = 'salary_basic'), numberFormatTax($row->colVal($prop)));
                // $row->setColVal(($prop = 'salary_insurance'), numberFormatTax($row->colVal($prop)));
                //
                $row->setColVal(($prop = 'status') . '_text', $statusList[$row->colVal($prop)]);
                $row->setColVal(($prop = 'work_status') . '_text', $workStatusList[$row->colVal($prop)]);
                //
                return $row;
            })
        ;
        //.end

        // Return
        return $rows;
	}

    /**
     * Fetch account's all salary summary info
     * @param int|string|array $accountId
     * @param array $options An array of options
     * @return array
     */
    public static function fetchAccountAllSummary($accountId, array $options = array())
    {
        // Get, format input
        $isArr = is_array($accountId);
        $accountId = array_filter((array)$accountId);

        // Define vars
        $models = [
            'slr' => app()->make(Salary\SummaryStaff::class),
            'slrD' => app()->make(Salary\SummaryStaffDetail::class),
        ];
        $cols = [
            // salary detail
            'slrd_account_id' => Salary\SummaryStaffDetail::columnName('account_id'),
            'slrd_salary_subtotal' => Salary\SummaryStaffDetail::columnName('salary_subtotal'),
            'slrd_salary_pit' => Salary\SummaryStaffDetail::columnName('salary_pit'),
            'slrd_pit' => Salary\SummaryStaffDetail::columnName('pit'),
            'slrd_salary_account_reduction_pit' => Salary\SummaryStaffDetail::columnName('salary_account_reduction_pit'),
            'slrd_salary_account_reduction_dependents_number' => Salary\SummaryStaffDetail::columnName('salary_account_reduction_dependents_number'),
            'slrd_salary_account_reduction_dependents_amount' => Salary\SummaryStaffDetail::columnName('salary_account_reduction_dependents_amount')
        ];
        $tbls = [
            'slr' => $models['slr']->getTable(),
            'slrD' => $models['slrD']->getTable(),
        ];

        // Create query builder
        // +++ 
        $qB = Salary\SummaryStaffDetail::select("{$tbls['slrD']}.*")
            ->join(
                $tbls['slr']
                , ("{$tbls['slr']}." . ($pK = $models['slr']->getKeyName()))
                , '=', "{$tbls['slrD']}." . Salary\SummaryStaffDetail::columnName($pK)
            )
            ->whereIn("{$tbls['slr']}.salary_type", [
                Salary\SummaryStaff::TYPE_110 // Bang luong tong hop thu nhap nhan vien
            ])
            ->whereIn($cols['slrd_account_id'], $accountId)
        ;
        // +++ || add filters?
        if (isset($options['timestamp_fr'])) {
            $year = date('Y', $options['timestamp_fr']);
            $month = date('m', $options['timestamp_fr']);
            $qB->whereRaw(
                "(`{$tbls['slr']}`.`salary_year` * 100 + `{$tbls['slr']}`.`salary_month`) >= "
                 . ($year * 100 + $month)
            );
        }
        if (isset($options['timestamp_to'])) {
            $year = date('Y', $options['timestamp_to']);
            $month = date('m', $options['timestamp_to']);
            $qB->whereRaw(
                "(`{$tbls['slr']}`.`salary_year` * 100 + `{$tbls['slr']}`.`salary_month`) <= "
                 . ($year * 100 + $month)
            );
        }
        unset($year, $month);
        // die($qB->toSql());
        // +++
        $qB2nd = clone $qB;
        $qB2nd->select($cols['slrd_account_id'])
            ->selectRaw("SUM(`{$cols['slrd_salary_subtotal']}`) AS `salary_subtotal`")
            ->selectRaw("SUM(`{$cols['slrd_salary_pit']}`) AS `salary_pit`")
            ->selectRaw("SUM(`{$cols['slrd_pit']}`) AS `pit`")
            ->selectRaw("SUM(`{$cols['slrd_salary_account_reduction_pit']}`) AS `salary_account_reduction_pit`")
            ->selectRaw("SUM(`{$cols['slrd_salary_account_reduction_dependents_number']}`) AS `salary_account_reduction_dependents_number`")
            ->selectRaw("SUM(`{$cols['slrd_salary_account_reduction_dependents_amount']}`) AS `salary_account_reduction_dependents_amount`")
            ->selectRaw("SUM("
                . "`{$cols['slrd_salary_account_reduction_dependents_number']}` "
                . " * `{$cols['slrd_salary_account_reduction_dependents_amount']}`"
                . ") AS `salary_account_reduction_dependents`"
            )
            ->selectRaw("COUNT(*) AS `cnt`")
            ->groupBy($cols['slrd_account_id'])
        ;

        // Fetch data
        // +++ list details records
        $dataList = [];
        $collect = $qB->with('salarySums')->get();
        $collect->map(function($var) use ($cols, &$dataList) {
            $accID = $var->{$cols['slrd_account_id']};
            $dataList[$accID] = $dataList[$accID] ?? [];
            $dataList[$accID][] = $var;
        });
        if (!empty($dataList)) {
            $dataList = $isArr ? $dataList : current($dataList);
        }
        // +++
        $collect2nd = $qB2nd->get();
        $dataSum = $collect2nd->mapWithKeys(function($var) use ($cols) {
            return [$var->{$cols['slrd_account_id']} => $var];
        })->toArray();
        if (!empty($dataSum)) {
            $dataSum = $isArr ? $dataSum : current($dataSum);
        }
        // +++
        $dataPay = [];
        $qB3rdOpts = array_replace($options, [
            'salary_type' => Salary\SummaryStaff::TYPE_110
        ]);
        $collect3rd = SalaryPay::fetchAccountSalaryPay($accountId, $qB3rdOpts);
        $collect3rd->map(function($var) use ($cols, &$dataPay) {
            $accID = $var->salaryDetail->{$cols['slrd_account_id']};
            $dataPay[$accID] = $dataPay[$accID] ?? [];
            $dataPay[$accID][] = $var;
        });
        if (!empty($dataPay)) {
            $dataPay = $isArr ? $dataPay : current($dataPay);
        }

        // Return
        return [$dataList, $dataSum, $dataPay];
    }
}