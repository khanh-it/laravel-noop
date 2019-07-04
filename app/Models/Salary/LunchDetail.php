<?php

namespace App\Models\Salary;

use App\Models;

/**
 * @class Lunch
 */
class LunchDetail extends Models\SalaryDetail
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
            'datafield' => ['time_worktime', [
                'type' => 'number'
            ]],
            'width' => 96,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
            'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}'
        ],
        [
            'text' => 'Số tiền',
            'datafield' => ['salary_subtotal', [
                'type' => 'number'
            ]],
            'minwidth' => 192,
            'cellsalign' => 'right', 
            'filterable' => false,
            'sortable' => false,
            'editable' => false,
            'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}'
        ],
    ];

    /**
     * Get Salary\Lunch.
     * @return Lunch|null
     */
    public function salary()
    {
        return $this->belongsTo(Lunch::class, $this->columnName('salary_id'));
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
     * Ham tinh "so tien (tong cong)"
     * @param double $D8 So ngay cong cua nhan vien trong thang
     * @param double $HS Tien an trua (dong/thang)
     * @param double $NC Tong so ngay cong trong thang
     * @return double
     */
    public static function calSalarySubtotal($D8, $HS, $NC) {
        // =D8*HS/NC
        return round($D8 * $HS / $NC);
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
        // ||Thong tin cham cong
        // +++ T.Tin cham cong theo tung nhan vien
        //$timeSheetInfo = (array)$salaryTSInfo['time_sheet'][$this->colVal('account_id')];
        
        // tháng năm bảng lương 
        $year = $salaryTSInfo['year'];
        $month = $salaryTSInfo['month'];
        // +++ Tong so ngay cong trong thang
        $wdOfMonth = $salaryTSInfo['working_days_of_month'];
        // Thong tin salary configs
        // +++ % Tien an trua (dong/thang)
        $lunchFee = $salaryConfig->colVal('lunch_fee'); 

        //phòng ban
        $departmentid =  $salaryConfig->colVal('department_id');
        
        //param
        $option = [
            'year' => $year,
            'month' => $month,
            'departmentid' => $departmentid,
            'account_id' => $this->colVal('account_id'),
        ];
        // ngày nghỉ
        $tsItem = Models\TimeSheet::getTimeSheet( $option );

        // Self update salary detail info
        $colPrefix = static::$columnPrefix;
        // 
        $slrdInfo = [
            //cập nhật ngày công
            ($colPrefix . ($col = 'time_worktime')) => ($calSalaryWorkTime
                = ($this->colVal('time_worktime') - $tsItem )
            ), 
            ($colPrefix . ($col = 'salary_subtotal')) => ($calSalarySubtotal
                = static::calSalarySubtotal(
                    $calSalaryWorkTime,
                    // $this->colVal('time_worktime'),
                    $lunchFee,
                    $wdOfMonth
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
    public function jqxFetchRecordList(Lunch $salaryModel, array $data, &$qB = null, &$totalRowsQB = null)
    {
        // Define vars
        // +++
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

                    //nhân viên ngưng làm trong tháng trở đi
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

                    //loại bỏ nhân viên khoán
                    $qB->whereNotNull("{$tableAcc}.account_salary_basic");
                    
                    //SẮP XẾP THEO CHỨC DANH
                    $qB->orderBy("{$tableAcc}.account_position_id", 'ASC');
                    
                    // Limit select
                    $qB->select([
                        "{$modelTable}.*",
                        "{$tableAcc}.account_code",
                        "{$tableAcc}.account_fullname",
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
            'salary_type' => Models\Salary::TYPE_30,
        ]);
        //
        return parent::fetchReportDataByTime($options, $qB, $totalRowsQB);
    }
}