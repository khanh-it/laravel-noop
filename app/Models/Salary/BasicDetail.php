<?php

namespace App\Models\Salary;

use App\Models;

/**
 * @class Basic
 */
class BasicDetail extends Models\SalaryDetail
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
            'text' => 'Lương theo HĐLĐ',
            'datafield' => ['salary_basic', [
                'type' => 'number'
            ]],
            'width' => 128,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
            'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}'
        ],
        [
            'text' => 'Phụ cấp',
            'datafield' => ['salary_responsibility_allowance', [
                'type' => 'number'
            ]],
            'width' => 128,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
            'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}'
        ],
        [
            'text' => 'Lương nộp BHXH',
            'datafield' => ['salary_insurance', [
                'type' => 'number'
            ]],
            'width' => 128,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
            'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}'
        ],
        [
            'text' => 'K.Lương',
            'datafield' => ['time_lv_worktime', [
                'type' => 'number'
            ]],
            'width' => 64,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
            'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}',
            'columngroup' => ['salary_time', [
                'text' => 'Lương thời gian',
            ]]
        ],
        [
            'text' => 'Lương cơ bản',
            'datafield' => ['salary_basic_final', [
                'type' => 'number'
            ]],
            'width' => 128,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
            'editable' => false,
            'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}',
            'columngroup' => 'salary_time'
        ],
        [
            'text' => 'Ốm',
            'datafield' => ['time_lv_sick', [
                'type' => 'number'
            ]],
            'width' => 64,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
            'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}',
            'columngroup' => ['salary_insurance', [
                'text' => 'Tiền BHXH',
            ]]
        ],
        [ 'datafield' => 'time_lv_sick_slrpc' ],
        [
            'text' => 'Thai Sản',
            'datafield' => ['time_lv_maternity', [
                'type' => 'number'
            ]],
            'minwidth' => 64,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
            'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}',
            'columngroup' => 'salary_insurance'
        ],
        [
            'text' => 'Tiền BHXH',
            'datafield' => ['salary_insurance_final', [
                'type' => 'number'
            ]],
            'width' => 128,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
            'editable' => false,
            'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}',
            'columngroup' => 'salary_insurance'
        ],
        [
            'text' => 'Bổ sung',
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
            'text' => 'Tổng cộng',
            'datafield' => ['salary_additional_subtotal', [
                'type' => 'number'
            ]],
            'width' => 128,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
            'editable' => false,
            'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}'
        ],
        [
            'text' => 'CĐP, CTXH',
            'datafield' => ['salary_deduction_social', [
                'type' => 'number'
            ]],
            'width' => 128, 
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
            'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}',
            'columngroup' => ['salary_deduction', [
                'text' => 'Khấu trừ',
            ]]
        ],
        [
            'text' => 'BHXH',
            'datafield' => ['salary_deduction_insurance', [
                'type' => 'number'
            ]],
            'width' => 128,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
            'editable' => false,
            'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}',
            'columngroup' => 'salary_deduction'
        ],
        [
            'text' => 'Khác',
            'datafield' => ['salary_deduction_others', [
                'type' => 'number'
            ]],
            'width' => 128,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
            'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}',
            'columngroup' => 'salary_deduction'
        ],
        [
            'text' => 'Còn lại',
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
     * Get Salary\Basic.
     * @return Basic|null
     */
    public function salary()
    {
        return $this->belongsTo(Basic::class, $this->columnName('salary_id'));
    }

    /**
     * Alter jqx grid column info
     * @param array $col Column data
     * @return array
     */
    public static function jqxGridCol($col)
    {
        $dfd = static::jqxGridDatafieldByCol($col);
        if (in_array($dfd, [
                'salary_basic',
                'salary_responsibility_allowance',
                'salary_insurance',
                'time_lv_worktime',
                'time_lv_sick',
                'time_lv_maternity',
                'salary_additional',
                'salary_deduction_social',
                'salary_deduction_insurance',
                'salary_deduction_others'
            ])
        ) {
            $col = array_replace($col, [
                'columntype' => 'custom',
                'createeditor' => "{!!window.mkJqxGridEditor(0, '{$dfd}')!!}",
                'initeditor' => "{!!window.mkJqxGridEditor(1, '{$dfd}')!!}",
                // 'geteditorvalue' => "{!!window.mkJqxGridEditor(2, '{$dfd}')!!}",
            ]);
        }
        if (in_array($dfd, [
                'account_fullname',
                'salary_basic',
                'salary_responsibility_allowance',
                'salary_basic_final',
                'salary_insurance',
                'salary_insurance_final',
                'salary_additional',
                'salary_deduction_social',
                'salary_deduction_insurance',
                'salary_deduction_others',
                'salary_subtotal',
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
     * Ham tinh "luong CB"
     * @param double $NC workDays Ngay cong
     * @param double $C8 salaryBasic Muc luong theo HD
     * @param double $D8 salaryResponsibilityAllowance Phu cap
     * @param double $F8 timeLvKlg Nghi: khong luong
     * @param double $H8 timeLvSick Nghi: om
     * @param double $I8 timeLvMaternity Nghi: thai san
     * @return double
     */
    public static function calSalaryBasicFinal($NC, $C8, $D8, $F8, $H8, $I8) {
        $slr = 0;
        // =IF($NC>($F8+$H8+$I8),($C8+$D8)/$NC*($NC-$F8-$H8-$I8),0)
        if ($NC > ($F8 + $H8 + $I8)) {
            $slr = ($C8 + $D8) / $NC * ($NC - $F8 - $H8 - $I8);
        }
        return $slr;
    }
    //.end

    /**
     * @TODO: Ham tinh "tien BHXH"
     * @param double $NC workDays Ngay cong
     * @param double $E8 salaryInsurance Muc tien luong nop BHXH
     * @param double $H8 timeLvSick Nghi: om
     * @param double $I8 timeLvMaternity Nghi: thai san
     * @return double
     */
    public static function calSalaryInsuranceFinal($NC, $E8, $H8, $I8) {
        $slr = 0;
        // =IF($I8=$NC,$E8,IF($I8<$NC,$E8/$NC*$I8+$E8/24*$H8*75%,0))
        if ($I8 == $NC) {
            $slr = $E8;
        } else {
            if ($I8 < $NC) {
                // slr = ($E8 / $NC * $I8 + $E8 / $NC * $H8 * 0.75);
                $slr = (
                    /* Thai san */ (($E8 / $NC) * $I8)
                    +  /* Nghi om */ (($E8 / $NC) * ($H8/*  * 0.75*/))
                );
            }
        }
        return $slr;
    }
    //.end

    /**
     * Ham tinh "Tong cong"
     * @param double $NC workDays Ngay cong
     * @param double $G8 salaryBasicFinal Tien luong CB (ket qua tu ham salaryBasicFinal())
     * @param double $I8 timeLvMaternity Nghi: thai san
     * @param double $J8 salaryInsuranceFinal Tien BHXH (ket qua tu ham calSalaryInsuranceFinal())
     * @param double $K8 salaryAdditional Tien "bo sung"
     * @return double
     */
    public static function calSalaryAdditionalSubtotal($NC, $G8, $I8, $J8, $K8) {
        $slr = 0;
        // =IF($I8=$NC,$J8+$K8,IF($I8<$NC,$J8+$G8+$K8,0))
        if ($I8 == $NC) {
            $slr = $J8 + $K8;
        } else {
            if ($I8 < $NC) {
                $slr = $G8 + $J8 + $K8;
            }
        }
        return $slr;
    }
    //.end

    /**
    * Ham tinh "Khau tru :: cong doan phi"
    * @param double $G8 salary_basic Tien luong HĐ
    * @param double $unionCost % cong doan phi
    * @return double
    */
    public static function calSalaryDeductionSocial($G8, $unionCost) {
        // =$G8*$unionCost(%)
        return ($G8 * $unionCost / 100);
    }

   //.end
    /**
     * Ham tinh "Khau tru :: BHXH"
     * @param double $E8 salaryInsurance Muc tien luong nop BHXH
     * @param double $D16 // Ty le nop BHXH
     * @return double
     */
    public static function calSalaryDeductionInsurance($E8, $D16) {
        // =$E8*$D$16(%)
        return ($E8 * $D16 / 100);
    }
    //.end

    /**
     * Ham tinh "con lai"
     * @param double $L8 salaryAdditionalSubtotal Tong cong (ket qua lay tu ham calSalaryAdditionalSubtota())
     * @param double $M8 salaryDeductionSocial; // Khau tru: CĐP, CTXH
     * @param double $N8 salaryDeductionInsurance; // Khau tru: BHXH
     * @param double $O8 salaryDeductionOthers; // Khau tru: khac
     * @return double
     */
    public static function calSalarySubtotal($L8, $M8, $N8, $O8) {
        // =ROUND($L8-SUM($M8:$O8),0)
        return round($L8 - ($M8 + $N8 + $O8));
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
        if($account['account_status'] == 1 && $account['account_salary_basic']){//kiểm tra tài khoản nghỉ việc
            // Get, format input(s)
            // +++ T.Tin ty le % huong luong
            $tssignAvgValues = (array)$salaryTSInfo['tssign_avg_values'];
            // +++ T.Tin cham cong theo tung nhan vien
            $timeSheetInfo = (array)$salaryTSInfo['time_sheet'][$this->colVal('account_id')];
            // ||Thong tin cham cong
            // +++ ngay cong
            $wdOfMonth = $salaryTSInfo['working_days_of_month'];
            // Thong tin salary configs
            // +++ % BHXH + BHYT + BHTN
            $perEmplPays = $salaryConfig->calPerEmployeesPays();
            // +++ % Cong doan phi
            $perEmplPayUnionCost = $salaryConfig->colVal('per_employees_pay_union_cost')?:1;

            // Self update salary detail info
            $colPrefix = static::$columnPrefix;
            // 
            $slrdInfo = [
                ($colPrefix . ($col = 'salary_basic_final')) => ($salaryBasicFinal 
                    = static::calSalaryBasicFinal(
                        $wdOfMonth,
                        $this->colVal('salary_basic'),
                        $this->colVal('salary_responsibility_allowance'),
                        $this->colVal('time_lv_worktime'),
                        $this->colVal('time_lv_sick'),
                        $this->colVal('time_lv_maternity')
                    )
                ),
                ($colPrefix . ($col = 'salary_insurance_final')) => ($salaryInsuranceFinal
                    = static::calSalaryInsuranceFinal(
                        $wdOfMonth,
                        $this->colVal('salary_insurance'),
                        $this->timeColWithSlrPc('time_lv_sick', $tssignAvgValues['time_lv_sick']),
                        $this->colVal('time_lv_maternity')
                    )
                ),
                ($colPrefix . ($col = 'salary_additional_subtotal')) => ($salaryAdditionalSubtotal
                    = static::calSalaryAdditionalSubtotal(
                        $wdOfMonth,
                        $salaryBasicFinal,
                        $this->colVal('time_lv_maternity'),
                        $salaryInsuranceFinal,
                        $this->colVal('salary_additional')
                    )
                ),
                ($colPrefix . ($col = 'salary_deduction_social')) => ($salaryDeductionSocial
                    = static::calSalaryDeductionSocial(
                        $this->colVal('salary_basic'),//LƯƠNG HỢP ĐỒNG LAO ĐỘNG
                        $perEmplPayUnionCost//1% CĐP
                    )
                ),
                ($colPrefix . ($col = 'salary_deduction_insurance')) => ($salaryDeductionInsurance
                    = static::calSalaryDeductionInsurance(
                        $this->colVal('salary_insurance'),
                        $perEmplPays
                    )
                ),
                ($colPrefix . ($col = 'salary_subtotal')) => ($calSalarySubtotal
                    = static::calSalarySubtotal(
                        $salaryAdditionalSubtotal,
                        $salaryDeductionSocial,
                        $salaryDeductionInsurance,
                        $this->colVal('salary_deduction_others')
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
    public function jqxFetchRecordList(Basic $salaryModel, array $data, &$qB = null, &$totalRowsQB = null)
    {
        $userID = \Auth::id();
        //Roles
        $roles = Models\Roles::checkRoles();
        $permissons = Models\Permissions::checkPermissons();
        //danh sách phòng ban
        if($roles['rolesHeadDepartment']){
            $departmentList = Models\Department::makeList(['rolesHead' => true]);
        }
        // Define vars
        // +++
        // Prepare the data
        $qB = $this->qBFromJqxRequestPayload($data, $totalRowsQB, [
            // Alter query builder
            'queryBuilder' => function(\Illuminate\Database\Eloquent\Builder $qB)
                use ($salaryModel, $userID, $roles, $idDepUser, $departmentList, $permissons) {
                    // Relatives
                    $models = [
                        'acc' => app()->make(Models\Account::class),
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

                    // nhân viên ngưng làm trong tháng
                    // +++ tháng năm bảng lương
                    // @khanhdtp noted: xu ly filter du lieu khi them moi.
                    // khong duoc xu ly filter du lieu o day, vi se mat du lieu xem theo lich su.
                    /* $date = $salaryModel->colVal('year') .'-' . $salaryModel->colVal('month');
                    $qB->where(function($qB) use ($tableAcc,$date) {
                        $qB->where("{$tableAcc}.account_end_time", '>', strtotime($date))
                            ->orWhere("{$tableAcc}.account_status", 1)
                        ;
                    }); */
                    if(!$permissons['permissonsAdmin']){
                        //kiểm tra quyền nhân viên
                        if($roles['rolesStaff']){
                            $qB->where("{$modelTable}.".$this->columnName('account_id'), $userID);
                        }
                        //kiểm tra quyền trưởng phòng
                        if($roles['rolesHeadDepartment']){
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
        
		// Format data
		// +++
        $rows = $qB->get()->map(function($row, $idx) {
                $prop;
                // ++++
                //
                return $row;
            });
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
            'salary_type' => Models\Salary::TYPE_0
        ]);
        //
        return parent::fetchReportDataByTime($options, $qB, $totalRowsQB);
    }
}