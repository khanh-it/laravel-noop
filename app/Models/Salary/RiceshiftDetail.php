<?php

namespace App\Models\Salary;

use App\Models;

/**
 * @class Riceshift
 */
class RiceshiftDetail extends Models\SalaryDetail
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
            'text' => 'Phụ cấp',
            'datafield' => ['salary_additional', [
                'type' => 'number'
            ]],
            'width' => 128,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
            'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}'
        ],
        [
            'text' => 'Số ngày',
            'datafield' => ['time_worktime_wkd', [
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
            'datafield' => ['salary_worktime_wkd', [
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
            'datafield' => ['time_worktime_sat', [
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
            'datafield' => ['salary_worktime_sat', [
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
            'datafield' => ['time_worktime_sun', [
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
            'datafield' => ['salary_worktime_sun', [
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
            'datafield' => ['time_worktime_hol', [
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
            'datafield' => ['salary_worktime_hol', [
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
    ];

    /**
     * Get Salary\Riceshift.
     * @return Riceshift|null
     */
    public function salary()
    {
        return $this->belongsTo(Riceshift::class, $this->columnName('salary_id'));
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
    public static function calSalaryWorktimeWkd($D8, $price) {
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
    public static function calSalaryWorktimeSat($F8, $price) {
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
    public static function calSalaryWorktimeSun($H8, $price) {
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
    public static function calSalaryWorktimeHol($J8, $price) {
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
        if($salaryConfig['salary_config_department_id'] == $account['account_department_id']){
            // Get, format input(s)
            // +++ T.Tin ty le % huong luong
            $tssignAvgValues = (array)$salaryTSInfo['tssign_avg_values'];
            // +++ T.Tin cham cong theo tung nhan vien
            $timeSheetInfo = (array)$salaryTSInfo['time_sheet'][$this->colVal('account_id')];

            // tháng năm bảng lương 
            $year = $salaryTSInfo['year'];
            $month = $salaryTSInfo['month'];
            // +++ || chi tiet
            $tsInfoDetailsDOWUnitDay = (array)$timeSheetInfo['_detailsDOWUnitDay'];

            //phòng ban
            $departmentid =  $salaryConfig->colVal('department_id');
            
            //param
            $params = [
                'year' => $year,
                'month' => $month,
                'departmentid' => $departmentid,
                'account_id' => $this->colVal('account_id'),
                'riceShift' => true,
            ];
            // ngày nghỉ
            $tsItem = Models\TimeSheet::getTimeSheet( $params );

            //thông tin phòng ban có ngày t7,cn nghỉ
            $workingShiftType = Models\WorkingShift::workingShiftsOfDay(['department_id' => $departmentid]);
            
            if($workingShiftType['sat'][0] != 1){ 
                // $params = array_merge($params,[
                //     'riceShiftOT' => true,
                // ]);
                if($account['account_salary_basic']){
                    $tsItemOT = $tsInfoDetailsDOWUnitDay;
                    // Models\TimeSheet::getTimeSheet( $params );
                }
            }
            
            
            
            // ||Thong tin cham cong
            // +++ ngay cong
            $wdOfMonth = $salaryTSInfo['working_days_of_month'];
            // Thong tin salary configs
            // +++ % Luong com ca
            $riceshiftFeeWkd = $salaryConfig->colVal('riceshift_fee_wkd');
            $riceshiftFeeSat = $salaryConfig->colVal('riceshift_fee_sat');
            $riceshiftFeeSun = $salaryConfig->colVal('riceshift_fee_sun');
            $riceshiftFeeHol = $salaryConfig->colVal('riceshift_fee_hol');
            
            // Self update salary detail info
            $colPrefix = static::$columnPrefix;
            // 
            $slrdInfo = [
                // timesheet info
                ($colPrefix . ($col = 'time_worktime_wkd')) => ($timeWorktimewkd
                        = (1 * $tsInfoDetailsDOWUnitDay['worktime']['Wkd'] - $tsItem['wkd'])
                    ) + ($timeOtWkd
                        = (1 * $tsInfoDetailsDOWUnitDay['ot']['Wkd'])
                    )
                ,
                ($colPrefix . ($col = 'time_worktime_sat')) => ($timeWorktimeSat
                        = (1 * $tsInfoDetailsDOWUnitDay['worktime']['Sat'] - $tsItem['sat'])
                    )
                    + ($timeOtSat
                        = (1 * $tsInfoDetailsDOWUnitDay['ot']['Sat']) 
                    )
                ,
                ($colPrefix . ($col = 'time_worktime_sun')) => ($timeWorktimeSun
                        = (1 * $tsInfoDetailsDOWUnitDay['worktime']['Sun'] - $tsItem['sun'])
                    )
                    + ($timeOtSun
                        = (1 * $tsInfoDetailsDOWUnitDay['ot']['Sun']) 
                    )
                ,
                ($colPrefix . ($col = 'time_worktime_hol')) => ($timeWorktimeHol
                        = (1 * $tsInfoDetailsDOWUnitDay['worktime']['Hol'])
                    )
                    + ($timeOtHol
                        = (1 * $tsInfoDetailsDOWUnitDay['ot']['Hol'])
                    )
                ,
                ($colPrefix . ($col = 'time_ot_wkd')) => 0,
                ($colPrefix . ($col = 'time_ot_sat')) => 0,
                ($colPrefix . ($col = 'time_ot_sun')) => 0,
                ($colPrefix . ($col = 'time_ot_hol')) => 0,
                // salary info
                ($colPrefix . ($col = 'salary_worktime_wkd')) => ($salaryWorktimeWkd 
                    = static::calSalaryWorktimeWkd(
                        $timeWorktimewkd + $timeOtWkd, $riceshiftFeeWkd
                    )
                ),
                ($colPrefix . ($col = 'salary_worktime_sat')) => ($salaryWorktimeSat
                    = $tsItemOT ? 0 : static::calSalaryWorktimeSat($timeWorktimeSat + $timeOtSat, $riceshiftFeeSat) 
                ),
                ($colPrefix . ($col = 'salary_worktime_sun')) => ($salaryWorktimeSun
                    = $tsItemOT ? 0 : static::calSalaryWorktimeSun(
                        $timeWorktimeSun + $timeOtSun, $riceshiftFeeSun
                    )
                ),
                ($colPrefix . ($col = 'salary_worktime_hol')) => ($salaryWorktimeHol
                    = static::calSalaryWorktimeHol(
                        $timeWorktimeHol + $timeOtHol, $riceshiftFeeHol
                    )
                ),
                ($colPrefix . ($col = 'salary_subtotal')) => ($calSalarySubtotal
                    = static::calSalarySubtotal(
                        $salaryWorktimeWkd,
                        $salaryWorktimeSat,
                        $salaryWorktimeSun,
                        $salaryWorktimeHol
                    )
                ),
            ];
            $this->updateSalaryDetailInfo($slrdInfo);
            //.end
            return $result;
        }
    }

    /**
     * 
     *
     * @param array $data Jqx request payload
     * @return void
     */
    public function jqxFetchRecordList(Riceshift $salaryModel, array $data, &$qB = null, &$totalRowsQB = null)
    {
        // Define vars
        // +++ Thong tin cham cong, dung de tinh luong
        $salaryTSInfo = Models\TimeSheet::getSalaryInfoOfTime(
            $salaryModel->colVal('month'), $salaryModel->colVal('year')
        );
        //Roles
        $roles = Models\Roles::checkRoles();
        $permissons = Models\Permissions::checkPermissons();
        $userID = \Auth::id();
        //danh sách phòng ban
        //rolesStaff => nhân viên
        //rolesHeadDepartment => trưởng phòng
		// Prepare the data
        $qB = $this->qBFromJqxRequestPayload($data, $totalRowsQB, [
            // Alter query builder
            'queryBuilder' => function(\Illuminate\Database\Eloquent\Builder $qB)
                use ($salaryModel, $roles, $permissons, $userID) {
                    //tháng năm bảng lương
                    $date = $salaryModel->colVal('year').'-'.$salaryModel->colVal('month');
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

                    //nhân viên ngưng làm trong tháng
                    $qB->where(function($qB) use ($tableAcc,$date){
                        $qB->where("{$tableAcc}.account_end_time",'>',strtotime($date))
                        ->orWhere("{$tableAcc}.account_status",1);
                    });

                    //loại bỏ nhân viên khoán
                    $qB->where("{$tableAcc}.account_department_id",$salaryModel->colVal('department_id'));

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
        $rows = $qB->get()->map(function($row, $idx)
            use ($salaryTSInfo) {
                $prop;
                // ++++
                $salaryTSInfoItem = $salaryTSInfo[$row->colVal('account_id')];
                $row->{($prop = 'time_sheet_info')} = $salaryTSInfoItem;
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
            'salary_type' => Models\Salary::TYPE_40,
        ]);
        //
        return parent::fetchReportDataByTime($options, $qB, $totalRowsQB);
    }
}