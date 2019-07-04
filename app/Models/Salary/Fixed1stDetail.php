<?php

namespace App\Models\Salary;

use App\Models;

class Fixed1stDetail extends Models\SalaryDetail
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
            'text' => 'Lương tháng',
            'datafield' => ['salary_fixed_final', [
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
            'text' => 'Lương BHXH',
            'datafield' => ['salary_insurance', [
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
            'text' => 'Ứng đợt 1',
            'datafield' => ['salary_deduction_advance', [
                'type' => 'number'
            ]],
            'width' => 128,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
            'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}',
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
            'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}',
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
            'text' => 'CĐP',
            'datafield' => ['salary_deduction_social', [
                'type' => 'number'
            ]],
            'width' => 128,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
            'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}',
            'columngroup' => ['salary_deduction', [
                'text' => 'Trừ',
            ]]
        ],
        [
            'text' => 'khác',
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
    ];

    /**
     * Get Salary\Fixed.
     * @return Fixed|null
     */
    public function salary()
    {
        return $this->belongsTo(Fixed1st::class, $this->columnName('salary_id'));
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
                'salary_deduction_advance',
                'salary_additional',
                'salary_deduction_social',
                'salary_deduction_others'
            ])
        ) {
            $col = array_replace($col, [
                'columntype' => 'custom',
                'createeditor' => "{!!window.mkJqxGridEditor(0, '{$dfd}')!!}",
                'initeditor' => "{!!window.mkJqxGridEditor(1, '{$dfd}')!!}",
            ]);
        }
        if (in_array($dfd, [
                'account_fullname',
                'salary_fixed_final',
                'salary_insurance',
                'salary_deduction_social',
                'salary_deduction_insurance',
                'salary_deduction_others',
                'salary_subtotal',
                'salary_additional',
                'salary_deduction_advance',

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
    * Ham tinh "Khau tru :: cong doan phi"
    * @param double $G8 salaryFixedFinal Tien luong CB (ket qua tu ham salaryFixedFinal())
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
        $timeSheetInfo = (array)$salaryTSInfo['time_sheet'][$accountId = $this->colVal('account_id')];
        // ||Thong tin cham cong
        // +++ ngay cong
        $wdOfMonth = $salaryTSInfo['working_days_of_month'];
        // Thong tin salary configs
        // +++
        $slrCnfSlrFixed = (array)$salaryConfig->salary_config_salary_fixed;
        $lastSalaryFixedDetails =& $options['last_salary_fixed_details'];
        if (is_array($lastSalaryFixedDetails) && !empty($lastSalaryFixedDetails)) {
            $lastSlrFixedDetail = $lastSalaryFixedDetails[$accountId];
            if ($lastSlrFixedDetail) {
                $slrCnfSlrFixed['deduction_others'] = $lastSlrFixedDetail->colVal('salary_deduction_others');
            }
        }
        unset($lastSalaryFixedDetails, $lastSlrFixedDetail);
        // +++ Don gia luong khoan
        // $slrFixedUnitPriceDay = $account->calSalaryFixedUnitPriceDay([
        //     'working_days_of_month' => $wdOfMonth
        // ]);
        
        // if (!$slrFixedUnitPriceDay) {
        //     $slrFixedUnitPriceDay = $salaryConfig->calSalaryFixedUnitPriceDay([
        //         'working_days_of_month' => $wdOfMonth
        //     ]);
        // }
        // type khoán Tháng/ngày của nhân viên
        $typeFixedAcount = $account->colVal('salary_fixed_type');
        // tiền lương khoán của nhân viên
        $acountMoneyFixed = $account->colVal('salary_fixed');
        // nếu type là tháng thì không cần nhân với ngày, type ngày thì lấy số tiền công khoán 1 ngày nhân với ngày công
        $slrFixedUnitMonth = ($typeFixedAcount ==1 ) ? $acountMoneyFixed : ($acountMoneyFixed * $wdOfMonth);
        // +++ % BHXH + BHYT + BHTN
        $perEmplPays = $salaryConfig->calPerEmployeesPays();
        // +++ % Cong doan phi
        $perEmplPayUnionCost = $salaryConfig->colVal('per_employees_pay_union_cost')?:1;

        // Self update salary detail info
        $colPrefix = static::$columnPrefix;
        // 
        $slrdInfo = [
            // +++ Luong khoan
            // ($colPrefix . ($col = 'salary_fixed')) => $slrFixedUnitPriceDay,
            ($colPrefix . ($col = 'salary_fixed_final')) => $slrFixedUnitMonth,
            // +++ Khau tru: cong doan phi
            ($colPrefix . ($col = 'salary_deduction_social')) => ($salaryDeductionSocial
                = static::calSalaryDeductionSocial(
                    $slrFixedUnitMonth,
                    $perEmplPayUnionCost
                )
            ),
            // +++ Khau tru: BHXH
            ($colPrefix . ($col = 'salary_deduction_insurance')) => ($salaryDeductionInsurance
                = static::calSalaryDeductionInsurance(
                    $this->colVal('salary_insurance'),
                    $perEmplPays
                )
            ),
            //+++ Khau tru: khac
            ($colPrefix . ($col = 'salary_deduction_others')) => ($salaryDeductionOthers
                = 0 //(1 * $slrCnfSlrFixed['deduction_others'])
            ),
            // +++ Con lai...
            // ($colPrefix . ($col = 'salary_subtotal')) => ($calSalarySubtotal
            //     = (
            //         $salaryFixedFinal
            //         - $salaryDeductionSocial
            //         - $salaryDeductionInsurance
            //         - $salaryDeductionOthers
            //     )
            // ),
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
    public function jqxFetchRecordList(Fixed1st $salaryModel, array $data, &$qB = null, &$totalRowsQB = null)
    {
        // Define vars
        // +++
        //Roles
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


                    //nhân viên ngưng làm trong tháng
                    //tháng năm bảng lương
                    // $date = $salaryModel->colVal('year').'-'.$salaryModel->colVal('month');
                    // $qB->where(function($qB) use ($tableAcc,$date){
                    //     $qB->where("{$tableAcc}.account_end_time",'>',strtotime($date))
                    //     ->orWhere("{$tableAcc}.account_status",1);
                    // });

                    // Limit select
                    $qB->select([
                        "{$modelTable}.*",
                        "{$tableAcc}.account_code",
                        "{$tableAcc}.account_fullname",
                    ]);
                },
        ]);
		// Format data
		// +++
        $rows = $qB->get()->map(function($row, $idx) {
                $prop;
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
            'salary_type' => Models\Salary::TYPE_81,
        ]);
        //
        return parent::fetchReportDataByTime($options, $qB, $totalRowsQB);
    }
}
