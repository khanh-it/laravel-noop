<?php

namespace App\Models\Salary;

use App\Models;

/**
 * @class Productivity
 */
class ProductivityDetail extends Models\SalaryDetail
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
            'text' => 'HSL',
            'datafield' => ['salary_monthly_productivity_rate', [
                'type' => 'number'
            ]],
            'width' => 64,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
            'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}'
        ],
        [
            'text' => 'HSP',
            'datafield' => ['salary_department_scale_level', [
                'type' => 'number'
            ]],
            'width' => 64,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
            'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}'
        ],
        [
            'text' => 'Ngày công',
            'datafield' => ['time_worktime', [
                'type' => 'number'
            ]],
            'width' => 64,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
            'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}',
            'columngroup' => ['salary_basic', [
                'text' => 'Lương năng suất',
            ]]
        ],
        [
            'text' => 'Tỉ lệ',
            'datafield' => ['salary_monthly_coefficient', [
                'type' => 'number'
            ]],
            'width' => 64,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
            'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}',
            'columngroup' => 'salary_basic'
        ],
        [
            'text' => 'Tiền lương',
            'datafield' => ['salary_basic_final', [
                'type' => 'number'
            ]],
            'width' => 128,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
            'editable' => false,
            'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}',
            'columngroup' => 'salary_basic'
        ],
        [
            'text' => 'Đoàn thể',
            'datafield' => ['salary_additional_corporate', [
                'type' => 'number'
            ]],
            'width' => 128,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
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
            'text' => 'Tạm ứng',
            'datafield' => ['salary_deduction_advance', [
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
            'text' => 'Thuế TNCN',
            'datafield' => ['salary_deduction_pit', [
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
     * Get Salary\Productivity.
     * @return Productivity|null
     */
    public function salary()
    {
        return $this->belongsTo(Productivity::class, $this->columnName('salary_id'));
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
                'salary_monthly_productivity_rate',
                'salary_department_scale_level',
                'time_worktime',
                'salary_monthly_coefficient',
                'salary_additional_corporate',
                'salary_additional',
                'salary_deduction_advance',
                'salary_deduction_pit',
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
                'salary_basic_final',
                'salary_additional_corporate',
                'salary_additional',
                'salary_additional_subtotal',
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

    /**
     * Ham tinh "Luong nang suat :: tien luong"
     * @param double $ML Muc luong nang suat chung
     * @param double $NC workDays Ngay cong
     * @param double $C8 salaryScaleLevel Bac luong (HSL)
     * @param double $D8 salaryDepartmentScaleLevel Bac luong phong ban (HSP)
     * @param double $E8 timeWorktime  Cham cong: t.gian ngay cong trong thang
     * @param double $F8 salaryMonthlyProductivityRate Ti le hang thang, dung tinh luong nang suat
     * @return double
     */
    public static function calSalaryBasicFinal($ML, $NC, $C8, $D8, $E8, $F8) {
        $slr = 0;
        // =$D8*$ML*$C8*$E8/$NC*$F8
        $slr = $D8 * $ML * $C8 * $E8 / $NC * $F8;
        return $slr;
    }
    //.end

    /**
     * Ham tinh "Tong cong"
     * @param double $G8 salaryBasicFinal Tien luong CB (ket qua tu ham salaryBasicFinal())
     * @param double $H8 salaryAdditionalCorporate Luong bo sung doan the
     * @param double $I8 salaryAdditional Tien "bo sung"
     * @return double
     */
    public static function calSalaryAdditionalSubtotal($G8, $H8, $I8) {
        $slr = 0;
        // =SUM(G8:I8)
        $slr = $G8 + $H8 +$I8;
        return $slr;
    }
    //.end

    /**
     * @TODO: Ham tinh "Khau tru :: Thue TNCN"
     */
    public static function calSalaryDeductionPIT() {
        return 0;
    }
    //.end

    /**
     * Ham tinh "con lai"
     * @see MM 01.11.2018:
     *  + khong can tru "thue TNCN"
     * @param double $J8 salaryAdditionalSubtotal Tong cong (ket qua lay tu ham calSalaryAdditionalSubtota())
     * @param double $K8 salaryDeductionAdvance; // Khau tru: tam ung
     * @param double $L8 salaryDeductionPIT; // Khau tru: thue TNCN
     * @param double $M8 salaryDeductionOthers; // Khau tru: khac
     * @return double
     */
    public function calSalarySubtotal($J8, $K8, $L8, $M8) {
        // =ROUND($J8-SUM($K8:$K8),0)
        return round($J8 - ($K8 + /*$L8 +*/ $M8));
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
        //
        $result = parent::assignSalaryAndTimeSheetInfo($salaryConfig, $account, $salaryTSInfo, $options);
        // Get, format input(s)
        // +++ T.Tin cham cong theo tung nhan vien
        $timeSheetInfo = (array)$salaryTSInfo['time_sheet'][$this->colVal('account_id')];

        // Self update salary detail info
        $colPrefix = static::$columnPrefix;
        // Thong tin cham cong
        // +++ ngay cong
        $wdOfMonth = $salaryTSInfo['working_days_of_month'];
        // Thong tin salary configs
        // +++ Muc luong nang suat chung
        $salaryProductivityGeneral = $salaryConfig->colVal('salary_productivity_general');
        // +++ % BHXH
        $perEmplPaySocial = $salaryConfig->colVal('per_employees_pay_social');
        // 
        $slrdInfo = [
            ($colPrefix . ($col = 'salary_basic_final')) => ($salaryBasicFinal 
                = static::calSalaryBasicFinal(
                    $salaryProductivityGeneral,
                    $wdOfMonth,
                    $this->colVal('salary_monthly_productivity_rate'),
                    $this->colVal('salary_department_scale_level'),
                    $this->colVal('time_worktime'),
                    $this->colVal('salary_monthly_coefficient')
                )
            ),
            ($colPrefix . ($col = 'salary_additional_subtotal')) => ($salaryAdditionalSubtotal
                = static::calSalaryAdditionalSubtotal(
                    $salaryBasicFinal,
                    $this->colVal('salary_additional_corporate'),
                    $this->colVal('salary_additional')
                )
            ),
            // @TODO: giam tru: thue TNCN
            ($colPrefix . ($col = 'salary_deduction_pit')) => ($salaryDeductionPIT
                = static::calSalaryDeductionPIT()
            ),
            ($colPrefix . ($col = 'salary_subtotal')) => ($calSalarySubtotal
                = static::calSalarySubtotal(
                    $salaryAdditionalSubtotal,
                    $this->colVal('salary_deduction_advance'),
                    $salaryDeductionPIT,
                    $this->colVal('salary_deduction_others')
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
    public function jqxFetchRecordList(Productivity $salaryModel, array $data, &$qB = null, &$totalRowsQB = null)
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

                    // //tháng năm bảng lương
                    // $date = $salaryModel->colVal('year').'-'.$salaryModel->colVal('month');
                    // //nhân viên ngưng làm trong tháng
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
            'salary_type' => Models\Salary::TYPE_1,
        ]);
        //
        return parent::fetchReportDataByTime($options, $qB, $totalRowsQB);
    }
}