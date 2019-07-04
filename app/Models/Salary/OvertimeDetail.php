<?php

namespace App\Models\Salary;

use App\Models;

/**
 * @class Overtime
 */
class OvertimeDetail extends Models\SalaryDetail
{
    /**
     * jqx's grid columns & datafields!
     * @$array
     */
    protected static $jqxGridColumns = [
        [ 'datafield' => 'id' ],
        [ 'datafield' => 'salary_id' ],
        [ 'datafield' => 'account_id' ],
        [
            'text' => 'Mã N.Viên',
            'datafield' => [['account_code']],
            'width' => 80,
            'filterable' => false,
            'sortable' => false,
            'editable' => false,
            'pinned' => true
        ],
        [
            'text' => 'Họ và tên',
            'datafield' => [['account_fullname']],
            'minwidth' => 192,
            'filterable' => false,
            'sortable' => false,
            'editable' => false,
            'pinned' => true
        ],
        [
            'text' => 'Số ngày',
            'datafield' => ['time_ot_wkd', [
                'type' => 'number'
            ]],
            'width' => 64,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
            'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}',
            'columngroup' => ['wkd', [
                'text' => 'Ngày thường',
            ]]
        ],
        [
            'text' => 'Thành tiền',
            'datafield' => ['salary_ot_wkd', [
                'type' => 'number'
            ]],
            'width' => 128,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
            'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}',
            'columngroup' => 'wkd'
        ],
        [
            'text' => 'Số ngày',
            'datafield' => ['time_ot_sat', [
                'type' => 'number'
            ]],
            'width' => 64,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
            'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}',
            'columngroup' => ['sat', [
                'text' => 'Thứ 7',
            ]]
        ],
        [
            'text' => 'Thành tiền',
            'datafield' => ['salary_ot_sat', [
                'type' => 'number'
            ]],
            'width' => 128,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
            'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}',
            'columngroup' => 'sat'
        ],
        [
            'text' => 'Số ngày',
            'datafield' => ['time_ot_sun', [
                'type' => 'number'
            ]],
            'width' => 64,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
            'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}',
            'columngroup' => ['sun', [
                'text' => 'Chủ nhật',
            ]]
        ],
        [
            'text' => 'Thành tiền',
            'datafield' => ['salary_ot_sun', [
                'type' => 'number'
            ]],
            'width' => 128,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
            'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}',
            'columngroup' => 'sun'
        ],
        [
            'text' => 'Số ngày',
            'datafield' => ['time_ot_hol', [
                'type' => 'number'
            ]],
            'width' => 64,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
            'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}',
            'columngroup' => ['hol', [
                'text' => 'Ngày lễ',
            ]]
        ],
        [
            'text' => 'Thành tiền',
            'datafield' => ['salary_ot_hol', [
                'type' => 'number'
            ]],
            'width' => 128,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
            'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}',
            'columngroup' => 'hol'
        ],
        [
            'text' => 'Tổng cộng',
            'datafield' => ['salary_subtotal', [
                'type' => 'number'
            ]],
            'minwidth' => 128,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
            'editable' => false,
            'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}'
        ],
        // [ 'datafield' => 'created_by' ],
        // [ 'datafield' => 'created_at' ],
        // [ 'datafield' => 'updated_at' ],
        // [ 'datafield' => 'deleted_by' ],
        // [ 'datafield' => 'deleted_at' ],
    ];

    /**
     * Get Salary\Overtime.
     * @return Overtime|null
     */
    public function salary()
    {
        return $this->belongsTo(Overtime::class, $this->columnName('salary_id'));
    }

    /**
     * Alter jqx grid column info
     * @param array $col Column data
     * @return array
     */
    public static function jqxGridCol($col)
    {
        $dfd = static::jqxGridDatafieldByCol($col);
        if (!in_array($dfd, [
                'account_code',
                'account_fullname',
            ])
        ) {
            $col = array_replace($col, [
                'columntype' => 'custom',
                'createeditor' => "{!!window.mkJqxGridEditor(0, '{$dfd}')!!}",
                'initeditor' => "{!!window.mkJqxGridEditor(1, '{$dfd}')!!}",
                // 'geteditorvalue' => "{!!window.mkJqxGridEditor(2, '{$dfd}')!!}",
            ]);
        }
        if (!in_array($dfd, [
                'account_code',
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
     * Ham tinh "Thanh tien ngay thuong"
     * @param double $D8 So ngay tang ca ngay thuong
     * @param double $price Don gia tang ca ngay thong
     * @return double
     */
    public static function calSalaryOTWkd($D8, $price) {
        // =D8*price
        return ($D8 * $price);
    }
    //.end

    /**
     * Ham tinh "Thanh tien thu 7"
     * @param double $F8 So ngay tang ca thu 7
     * @param double $price Don gia tang ca thu 7
     * @return double
     */
    public static function calSalaryOTSat($F8, $price) {
        // =F8*price
        return ($F8 * $price);
    }
    //.end

    /**
     * Ham tinh "Thanh tien chu nhat"
     * @param double $H8 So ngay tang ca chu nhat
     * @param double $price Don gia tang ca chu nhat
     * @return double
     */
    public static function calSalaryOTSun($H8, $price) {
        // =H8*price
        return ($H8 * $price);
    }
    //.end

    /**
     * Ham tinh "Thanh tien ngay le"
     * @param double $J8 So ngay tang ca ngay le
     * @param double $price Don gia tang ca ngay le
     * @return double
     */
    public static function calSalaryOTHol($J8, $price) {
        // =J8*price
        return ($J8 * $price);
    }
    //.end

    /**
     * Ham tinh "tong cong"
     * @param double $E8 Thanh tien ngay thuong
     * @param double $G8 Thanh tien ngay t7
     * @param double $I8 Thanh tien cn
     * @param double $K8 Thanh tien ngay le
     * @return double
     */
    public static function calSalarySubtotal($E8, $G8, $I8, $K8) {
        // =E8+G8+I8+K8
        return round($E8 + $G8 + $I8 + $K8);
    }
    //.end

    /**
     * 
     * @param SalaryConfig $salaryConfig
     * @param Account $account
     * @param array $salaryTSInfo Thong tin cham cong.
     * @param array $options
     * @return this
     */
    public function assignSalaryAndTimeSheetInfo(Models\SalaryConfig $salaryConfig, Models\Account $account, array $salaryTSInfo, array $options = [])
    {
        // Call parent's
        $result = parent::assignSalaryAndTimeSheetInfo($salaryConfig, $account, $salaryTSInfo, $options);
        // Get, format input(s)
        // +++ T.Tin ty le % huong luong
        $tssignAvgValues = (array)$salaryTSInfo['tssign_avg_values'];
        // +++ T.Tin cham cong theo tung nhan vien
        $timeSheetInfo = (array)$salaryTSInfo['time_sheet'][$this->colVal('account_id')];
        // ||Thong tin cham cong
        // +++ ngay cong
        $wdOfMonth = $salaryTSInfo['working_days_of_month'];
        // Thong tin salary configs
        // +++ % BHXH
        $overtimeFeeWkd = $salaryConfig->colVal('overtime_fee_wkd');
        $overtimeFeeSat = $salaryConfig->colVal('overtime_fee_sat');
        $overtimeFeeSun = $salaryConfig->colVal('overtime_fee_sun');
        $overtimeFeeHol = $salaryConfig->colVal('overtime_fee_hol');

        // Self update salary detail info
        $colPrefix = static::$columnPrefix;
        // 
        $slrdInfo = [
            ($colPrefix . ($col = 'salary_ot_wkd')) => ($salaryOTWkd 
                = static::calSalaryOTWkd($this->colVal('time_ot_wkd'), $overtimeFeeWkd)
            ),
            ($colPrefix . ($col = 'salary_ot_sat')) => ($salaryOTSat
                = static::calSalaryOTSat($this->colVal('time_ot_sat'), $overtimeFeeSat)
            ),
            ($colPrefix . ($col = 'salary_ot_sun')) => ($salaryOTSun
                = static::calSalaryOTSun($this->colVal('time_ot_sun'), $overtimeFeeSun)
            ),
            ($colPrefix . ($col = 'salary_ot_hol')) => ($salaryOTHol
                = static::calSalaryOTHol($this->colVal('time_ot_hol'), $overtimeFeeHol)
            ),
            ($colPrefix . ($col = 'salary_subtotal')) => ($calSalarySubtotal
                = static::calSalarySubtotal(
                    $salaryOTWkd,
                    $salaryOTSat,
                    $salaryOTSun,
                    $salaryOTHol
                )
            ),
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
    public function jqxFetchRecordList(Overtime $salaryModel, array $data, &$qB = null, &$totalRowsQB = null)
    {
        // Define vars
        // +++
        $roles = Models\Roles::checkRoles();
        $permissons = Models\Permissions::checkPermissons();
        $userID = \Auth::id();

		// Prepare the data
        $qB = $this->qBFromJqxRequestPayload($data, $totalRowsQB, [
            // Alter query builder
            'queryBuilder' => function(\Illuminate\Database\Eloquent\Builder $qB) 
                use ($salaryModel, $roles, $permissons, $userID) {
                    
                    // Relatives
                    $models = [
                        'acc' => app()->make(Models\Account::class),
                        'dep' => app()->make(Models\Department::class),
                    ];
                    $modelTable = $this->getTable();

                    // Limit: chi lay du lieu detail trong 1 master table!
                    $qB->where("{$modelTable}.".static::columnName('salary_id'), $salaryModel->id());

                    // Join account
                    $qB->leftJoin(
                        ($tableAcc = $models['acc']->getTable())
                        , ("{$tableAcc}." . ($pK = $models['acc']->getKeyName()))
                        , '=', "{$modelTable}." . $this->columnName($pK)
                    );

                    // //nhân viên ngưng làm trong tháng
                    //tháng năm bảng lương
                    // $date = $salaryModel->colVal('year').'-'.$salaryModel->colVal('month');
                    // $qB->where(function($qB) use ($tableAcc,$date){
                    //     $qB->where("{$tableAcc}.account_end_time",'>',strtotime($date))
                    //     ->orWhere("{$tableAcc}.account_status",1);
                    // });

                    if(!$permissons['permissonsAdmin']){
                        //kiểm tra quyền nhân viên
                        if($roles['rolesStaff']){
                            $qB->where("{$modelTable}.".$this->columnName('account_id'), $userID);
                        }
                        //kiểm tra quyền trưởng phòng
                        if($roles['rolesHeadDepartment']){
                            $departmentList = $models['dep']::makeList(['rolesHead' => true]);
                            //array id phong ban của trưởng phòng
                            $depArrID = array_keys($departmentList);
                            $qB->whereIn("{$tableAcc}.account_department_id", $depArrID);
                        }
                    }

                    //SẮP XẾP THEO CHỨC DANH
                    $qB->orderBy("{$tableAcc}.account_position_id", 'ASC');
                    
                    // Limit select
                    $qB->select([
                        "{$modelTable}.*",
                        "{$tableAcc}.account_code",
                        "{$tableAcc}.account_fullname",
                        "{$tableAcc}.account_department_id",
                    ]);
                },
            /*//
            'where' => function(&$join, &$where, &$filter, &$condition, &$value)
                use ($companyListFlip, $departmentListFlip, $typeListFlip) {
                    // +++ cong ty
                    if (($prop = $this->columnName('company_id')) . '_text' === $filter['field']) {
                        $filter['field'] = $prop;
                        $value = $filter['value'] = $companyListFlip[$value];
                    }
                    // +++ phong ban
                    if (($prop = $this->columnName('department_id')) . '_text' === $filter['field']) {
                        $filter['field'] = $prop;
                        $value = $filter['value'] = $departmentListFlip[$value];
                    }
                }
            ,*/
        ]);
        // var_dump($data);die($qB->toSql());
		// Format data
		// +++
        $rows = $qB->get()->map(function($row, $idx) {
                $prop;
                //
                return $row;
            })
        ;
        //.end
        
        // Return
        return $rows;
    }

    /**
     * Report details by time
     *
     * @param array $options An array of options
     * @return void
     */
    public function fetchReportDataByTime(array $options, &$qB = null, &$totalRowsQB = null)
    {
        // Get, format input(s)
        $options = array_replace($options, [
            'salary_type' => Models\Salary::TYPE_20,
        ]);
        //
        return parent::fetchReportDataByTime($options, $qB, $totalRowsQB);
    }
}