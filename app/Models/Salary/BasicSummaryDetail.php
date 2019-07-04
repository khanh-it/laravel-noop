<?php

namespace App\Models\Salary;

use App\Models;

/**
 * @class BasicSummaryDetail
 */
class BasicSummaryDetail extends Models\SalaryDetail
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
            'text' => 'Mã đơn vị',
            'datafield' => [['department_code']],
            'width' => 80,
            'filterable' => false,
            'sortable' => false,
            'editable' => false,
            'pinned' => true
        ],
        [
            'text' => 'Tên đơn vị (bộ phận)',
            'datafield' => [['department_name']],
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
        // if (!in_array($dfd, [])) {
            $col = array_replace($col, [
                'columntype' => 'custom',
                'createeditor' => "{!!window.mkJqxGridEditor(0, '{$dfd}')!!}",
                'initeditor' => "{!!window.mkJqxGridEditor(1, '{$dfd}')!!}",
                // 'geteditorvalue' => "{!!window.mkJqxGridEditor(2, '{$dfd}')!!}",
            ]);
        // }
        if (!in_array($dfd, [
                'department_code',
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
     * @param Models\Department $department
     * @param Illuminate\Database\Eloquent\Collection $salaryRows
     * @param Models\SalaryConfig $salaryConfig
     * @param array $salaryTSInfo Thong tin cham cong.
     * @param array $options
     * @return this
     */
    public function assignSalaryAndTimeSheetInfoSummary(Models\Department $department, $salaryRows, array $options = [])
    {
        // Get, format input(s)
        // ...

        // Set relationshiops
        if($department['department_parent_id'] == 1 && !$department['department_deleted_at']) { //lấy phòng ban không khoán
            $this->department()->associate($department);

            // Self update salary detail info
            if (!empty($salaryRows))
            {
                $slrdInfo = [];
                foreach ($salaryRows as $slrRow)
                {
                    $slrSummary = $slrRow->calSummary();
                    foreach ($slrSummary as $col => $sum)
                    {
                        $slrdInfo[$col] += $sum;
                    }
                }
                $this->updateSalaryDetailInfo($slrdInfo);
            }
            //.end
            return $this;
        }
        
    }

    /**
     * 
     *
     * @param array $data Jqx request payload
     * @return void
     */
    public function jqxFetchRecordList(BasicSummary $salaryModel, array $data, &$qB = null, &$totalRowsQB = null)
    {
        // Define vars
        // +++
        //Roles
        $roles = Models\Roles::checkRoles();
        $permissons = Models\Permissions::checkPermissons();
        
		// Prepare the data
        $qB = $this->qBFromJqxRequestPayload($data, $totalRowsQB, [
            // Alter query builder
            'queryBuilder' => function(\Illuminate\Database\Eloquent\Builder $qB)
                use ($salaryModel, $roles, $permissons) {
                    // Limit: chi lay du lieu detail trong 1 master table!
                    $qB->where(static::columnName('salary_id'), $salaryModel->id());
                    // Relatives
                    $models = [
                        'dep' => app()->make(Models\Department::class),
                    ];
                    $modelTable = $this->getTable();
                    // Join account
                    $qB->leftJoin(
                        ($tableDep = $models['dep']->getTable())
                        , ("{$tableDep}." . ($pK = $models['dep']->getKeyName()))
                        , '=', "{$modelTable}." . $this->columnName($pK)
                    );

                    if(!$permissons['permissonsAdmin']){
                        //kiểm tra quyền nhân viên
                        // if($roles['rolesStaff']){
                        //     $qB->where("{$modelTable}.".$this->columnName('account_id'), $userID);
                        // }
                        //kiểm tra quyền trưởng phòng
                        if($roles['rolesHeadDepartment']){
                            $departmentList = $models['dep']::makeList(['rolesHead' => true]);
                            //array id phong ban của trưởng phòng
                            $depArrID = array_keys($departmentList);
                            $qB->whereIn("{$modelTable}.". $this->columnName('department_id'), $depArrID);
                        }
                    }

                    $qB->where("{$tableDep}.department_parent_id",1)
                    ->whereNull("{$tableDep}.department_deleted_at");//lấy phòng ban không khoán,và ban ko bị xóa
                    
                    // Limit select
                    $qB->select([
                        "{$modelTable}.*",
                        "{$tableDep}.department_code",
                        "{$tableDep}.department_name",
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
            'salary_type' => Models\Salary::TYPE_10,
            'summary'     => true
        ]);
        //
        return parent::fetchReportDataByTime($options, $qB, $totalRowsQB);
    }
}