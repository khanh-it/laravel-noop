<?php

namespace App\Models\Salary;

use App\Models;

/**
 * @class ProductivityYearlyDetail
 */
class ProductivityYearlyDetail extends Models\SalaryDetail
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
            'datafield' => ['salary_year_productivity', [
                'type' => 'number'
            ]],
            'width' => 64,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
            'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}',
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
            'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}',
        ],
        [
            'text' => 'Số tiền',
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
            'text' => 'Trừ khác',
            'datafield' => ['salary_deduction_others', [
                'type' => 'number'
            ]],
            'width' => 128,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
            'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}',
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
     * Get Salary\Productivity.
     * @return Productivity|null
     */
    public function salary()
    {
        return $this->belongsTo(ProductivityYearly::class, $this->columnName('salary_id'));
    }

    /**
     * Alter jqx grid column info
     * @param array $col Column data
     * @return array
     */
    public static function jqxGridCol($col)
    {
        $dfd = static::jqxGridDatafieldByCol($col);
        if (!in_array($dfd, [])
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
        // ...

        // Self update salary detail info
        $colPrefix = static::$columnPrefix;
        // Thong tin cham cong
        //...
        // Thong tin salary configs
        // +++ Don gia tien luong nang suat quy
        $yearlyFee = (1 * $salaryConfig->colVal('yearly_fee'));
        // 
        $slrdInfo = [
            ($colPrefix . ($col = 'salary_additional_subtotal')) => ($salaryAdditionalSubtotal
                = (
                    $this->colVal('salary_year_productivity')
                    * $this->colVal('salary_department_scale_level')
                    * $yearlyFee
                )
            ),
            ($colPrefix . ($col = 'salary_deduction_others')) => ($salaryDeductionOthers
                = 0
            ),
            ($colPrefix . ($col = 'salary_subtotal')) => ($calSalarySubtotal
                = $salaryAdditionalSubtotal - $salaryDeductionOthers
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
    public function jqxFetchRecordList(ProductivityYearly $salaryModel, array $data, &$qB = null, &$totalRowsQB = null)
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
}