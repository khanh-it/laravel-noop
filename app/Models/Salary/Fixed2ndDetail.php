<?php

namespace App\Models\Salary;

use App\Models;

class Fixed2ndDetail extends Models\SalaryDetail
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
            'text' => 'Ngày công',
            'datafield' => ['time_worktime', [
                'type' => 'number'
            ]],
            'width' => 72,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
            'editable' => false,
            'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}',
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
            'text' => 'Ứng đợt 1',
            'datafield' => ['salary_deduction_advance', [
                'type' => 'number'
            ]],
            'width' => 128,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
            'editable' => false,
            'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}',
            'columngroup' => ['salary_deduction', [
                'text' => 'Trừ',
            ]]
        ],
        [
            'text' => 'Thuế TNCN',
            'datafield' => ['salary_deduction_pit', [
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
        return $this->belongsTo(Fixed2nd::class, $this->columnName('salary_id'));
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
                'salary_additional',
                'salary_deduction_others',
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
                'time_worktime',
                'salary_fixed_final',
                'salary_additional',
                'salary_deduction_advance',
                'salary_deduction_pit',
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
        $fixed1st = Models\Salary\Fixed1st::lastSalaryFixedDetailsByTime($options['detail_options']['month'],$options['detail_options']['year']);
        // Get, format input(s)
        // ||Thong tin cham cong
        // +++ ngay cong
        $wdOfMonth = $options['detail_options']['working_days_of_month'];
        $salarySubtotal = 0;
        $salaryPIT = 0;
        $sumArr = [];
        if (!empty($salaryTSInfo)) {
            foreach ($salaryTSInfo as $salary) {
                $model = app()->make(Models\SalarySum::class);
                $model->setColVal('salary_id', $this->colVal('salary_id'));
                $model->setColVal('for_salary_id', $salary['salary_id']);
                $model->setColVal('salary_type', $salary['salary_type']);
                $model->setColVal('salary_subtotal', $salary['salary_subtotal']);
                $model->setColVal('salary_pit', $salary['salary_pit']);
                $model->save();
                $this->salarySums()->save($model);
                // +++ 
                $sumArr[$salary['salary_type']] = $sumArr[$salary['salary_type']] ?: [];
                $sumArr[$salary['salary_type']]['salary_subtotal'] += $salary['salary_subtotal'];
                $sumArr[$salary['salary_type']]['salary_pit'] += $salary['salary_pit'];
            }
        }
        if (!empty($sumArr)) {
            foreach ($sumArr as $slrType => $item) {
                $model = app()->make(Models\SalarySum::class);
                $model->setColVal('salary_id', $this->colVal('salary_id'));
                $model->setColVal('salary_type', $slrType);
                $model->setColVal('salary_subtotal', $item['salary_subtotal']);
                $model->setColVal('salary_pit', $item['salary_pit']);
                $model->save();
                $this->salarySums()->save($model);
                // +++
                $salarySubtotal += $item['salary_subtotal'];
                $salaryPIT += $item['salary_pit'];
            }
            unset($slrType, $item);
        }

        // +++ PIT
        $PIT = 0;
        $salaryPITFinal = max(0, $salaryPIT - $slrAccReductions[0]);
        if ($salaryPITFinal > 0) {
            // +++ @TODO: Tong thu nhap phai tra dung tinh thue!
            $PIT = Models\SalaryRule::calcPITSumByRuleType(
                $slrType = 'summary-staff',
                function($col) use ($salaryPITFinal) {
                    return $salaryPITFinal;
                }
            );
        }

        // +++ Lương ứng đợt 1
        $salaryDeductionAdvance = $fixed1st[$accountId = $this->colVal('account_id')]['salary_detail_salary_deduction_advance'];
        unset($fixed1st);
        // type khoán Tháng/ngày của nhân viên
        $typeFixedAcount = $account->colVal('salary_fixed_type');
        // tiền lương khoán của nhân viên
        $acountMoneyFixed = $account->colVal('salary_fixed');
        // nếu type là tháng thì không cần nhân với ngày, type ngày thì lấy số tiền công khoán 1 ngày nhân với ngày công
        $slrFixedUnitMonth = ($typeFixedAcount ==1 ) ? $acountMoneyFixed : ($acountMoneyFixed * $wdOfMonth);

        // Self update salary detail info
        $colPrefix = static::$columnPrefix;
        // 
        $slrdInfo = [
            // +++ Ngày công
            ($colPrefix . ($col = 'time_worktime')) => $wdOfMonth,
            // +++ Luong khoan
            ($colPrefix . ($col = 'salary_fixed_final')) => $slrFixedUnitMonth,
            // ++ lương ứng đợt 1
            ($colPrefix . ($col = 'salary_deduction_advance')) => $salaryDeductionAdvance,
            // +++ Khau tru: Thuế thu nhập cá nhân
            ($colPrefix . ($col = 'salary_deduction_pit')) => ($PIT),
            // +++ Con lai...
            ($colPrefix . ($col = 'salary_subtotal')) => ($calSalarySubtotal
                = (
                    $slrFixedUnitMonth
                    - $salaryDeductionAdvance
                    - $PIT
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
    public function jqxFetchRecordList(Fixed2nd $salaryModel, array $data, &$qB = null, &$totalRowsQB = null)
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
            'salary_type' => Models\Salary::TYPE_82,
        ]);
        //
        return parent::fetchReportDataByTime($options, $qB, $totalRowsQB);
    }
}
