<?php

namespace App\Models\Salary;

use Illuminate\Database\Eloquent\Builder;
use App\Models;

/**
 * @class SummaryStaffDetail
 */
class SummaryStaffDetail extends Models\SalaryDetail
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
            'text' => 'Mã nhân viên',
            'datafield' => [['account_code']],
            'width' => 96,
            'filterable' => false,
            'sortable' => false,
            'pinned' => true
        ],
        [
            'text' => 'Tên nhân viên',
            'datafield' => [['account_fullname']],
            'minwidth' => 192,
            'filterable' => false,
            'sortable' => false,
            'pinned' => true
        ],
        [
            'text' => 'Cơ bản',
            'datafield' => [['TYPE_0'], [
                'type' => 'number'
            ]],
            'width' => 128,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
        ],
        [
            'text' => 'Lương khoán',
            'datafield' => [['TYPE_82'], [
                'type' => 'number'
            ]],
            'width' => 128,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
        ],
        [
            'text' => 'Năng suất',
            'datafield' => [['TYPE_1'], [
                'type' => 'number'
            ]],
            'width' => 128,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
        ],
        [
            'text' => 'Năng suất quý',
            'datafield' => [['TYPE_50'], [
                'type' => 'number'
            ]],
            'width' => 128,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
        ],
        [
            'text' => 'NS quý chi bổ sung',
            'datafield' => [['TYPE_60'], [
                'type' => 'number'
            ]],
            'width' => 128,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
        ],
        [
            'text' => 'Ngoài giờ',
            'datafield' => [['TYPE_20'], [
                'type' => 'number'
            ]],
            'width' => 128,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
        ],
        [
            'text' => 'Ăn trưa',
            'datafield' => [['TYPE_30'], [
                'type' => 'number'
            ]],
            'width' => 128,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
        ],
        [
            'text' => 'Cơm ca',
            'datafield' => [['TYPE_40'], [
                'type' => 'number'
            ]],
            'width' => 128,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
        ],
        [
            'text' => 'Năng suất năm',
            'datafield' => [['TYPE_70'], [
                'type' => 'number'
            ]],
            'width' => 128,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
        ],
        [
            'text' => 'Chi khác',
            'datafield' => [['TYPE_100'], [
                'type' => 'number'
            ]],
            'width' => 128,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
        ],
        [
            'text' => 'Người phụ thuộc',
            'datafield' => [['account_salary_dependents_number'], [
                'type' => 'number'
            ]],
            'width' => 128,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
        ],
        [
            'text' => 'Tổng thu nhập tính thuế',
            'datafield' => ['salary_pit', [
                'type' => 'number'
            ]],
            'minwidth' => 128,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
        ],
        [
            'text' => 'Giá trị',
            'datafield' => ['pit', [
                'type' => 'number'
            ]],
            'minwidth' => 128,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
            'columngroup' => ['pit', [
                'text' => 'Tiền thuế',
            ]]
        ],
        [
            'text' => 'Đã thu',
            'datafield' => ['salary_deduction_pit', [
                'type' => 'number'
            ]],
            'minwidth' => 128,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
            'columngroup' => 'pit'
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
        ]
    ];

    /**
     * Get SummaryStaff.
     * @return SummaryStaff|null
     */
    public function salary()
    {
        return $this->belongsTo(SummaryStaff::class, $this->columnName('salary_id'));
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
        ])) {
            $col = array_replace($col, [
                'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}',
                'columntype' => 'custom',
                'createeditor' => "{!!window.mkJqxGridEditor(0, '{$dfd}')!!}",
                'initeditor' => "{!!window.mkJqxGridEditor(1, '{$dfd}')!!}",
                // 'geteditorvalue' => "{!!window.mkJqxGridEditor(2, '{$dfd}')!!}",
            ]);
        }
        if (!in_array($dfd, [
            'account_code',
        ])) {
            $col = array_replace($col, [
                'aggregates' => "{!!window.mkJqxGridAggregates('{$dfd}')!!}",
                'aggregatesrenderer' => "{!!window.jqxGridAggregatesRenderer!!}",
            ]);
        }
        return $col;
    }

    /**
     * Self update record's salary info
     * @param array $slrdItem Salary info item
     * @return bool
     */
    public function updateSalaryDetailInfo(array $slrdInfo)
    {
        $result = !!$this->fill($slrdInfo);
        return $result;
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
        // Call parent's
        $result = parent::assignSalaryAndTimeSheetInfo($salaryConfig, $account, $salaryTSInfo, $options);

        // Get, format input(s)
        $slrAccReductions = [];
        if (is_array($options['account_salary_reductions'])) {
            $slrAccReductions = (array)$options['account_salary_reductions'][$account->id()];
        }

        //
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

        // Self update salary detail info
        $colPrefix = static::$columnPrefix;
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
        // +++
        $slrdInfo = [
            ($colPrefix . ($col = 'salary_subtotal')) => $salarySubtotal,
            ($colPrefix . ($col = 'salary_pit')) => $salaryPIT,
            ($colPrefix . ($col = 'salary_account_reduction_pit')) => (1 * $slrAccReductions['pit']),
            ($colPrefix . ($col = 'salary_account_reduction_dependents_number')) => (1 * $slrAccReductions['dependents']['number']),
            ($colPrefix . ($col = 'salary_account_reduction_dependents_amount')) => (1 * $slrAccReductions['dependents']['amount']),
            ($colPrefix . ($col = 'salary_account_reductions')) => $slrAccReductions[0],
            ($colPrefix . ($col = 'pit')) => $PIT,
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
    public function jqxFetchRecordList(SummaryStaff $salaryModel, array $data, &$qB = null, &$totalRowsQB = null)
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
                    //nhân viên ngưng làm trong tháng
                    //tháng năm bảng lương
                    // $date = $salaryModel->colVal('year').'-'.$salaryModel->colVal('month');
                    // $qB->where(function($qB) use ($tableAcc,$date){
                    //     $qB->where("{$tableAcc}.account_end_time",'>',strtotime($date))
                    //     ->orWhere("{$tableAcc}.account_status",1);
                    // });
                    // Limit select

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

                    $qB->select([
                        "{$modelTable}.*",
                        "{$tableAcc}.account_code",
                        "{$tableAcc}.account_fullname",
                        "{$tableAcc}.account_salary_dependents_number",
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
        $rows = $qB->with('salarySums')->get()->map(function($row, $idx) {
                $prop;
                // ++++
                foreach ($row->salarySums as $slrSum) {
                    if (is_null($slrSum->colVal('for_salary_id'))) {
                        $row->{'TYPE_' . $slrSum->colVal('salary_type')} = $slrSum->colVal('salary_subtotal');
                    }
                }

                if($row->{'TYPE_82'}){
                    $row->{'TYPE_82'} = $row->{'TYPE_82'} + $row->{'TYPE_81'};
                }
                //
                return $row;
            })
        ;
        //.end
        // Return
        return $rows;
    }

    /**
     * Alter salary details's salary pays...
     * @param array $data
     *  + [at]
     *  + [amount]
     * @param array $options An array of options
     * @return void
     */
    public function alterSalaryPay($data, array $options = array())
    {
        // Start transaction
        if (!is_null($data)) {
            return \DB::transaction(function () use ($data) {
                // @var
                $slrDeductionPit = 0;
                // Remove old data
                foreach ($this->salaryPays as $slrPayEnt) {
                    $slrPayEnt->delete();
                }
                //.end
                // Add new data
                foreach ($data as $item) {
                    $at = strtotime($item['at']);
                    $amount = (1 * $item['amount']);
                    if ($at && ($amount > 0)) {
                        $model = app()->make(Models\SalaryPay::class);
                        $model->setColVal('salary_id', $this->colVal('salary_id'));
                        $model->setColVal('at', date('Y-m-d', $at));
                        $model->setColVal('amount', $amount);
                        $model->save();
                        $this->salaryPays()->save($model);
                        //
                        $slrDeductionPit += $amount;
                    }
                }
                //.end
                // Set data...
                return $this->updateSalaryDeductionPit($slrDeductionPit)->save();
            });
        }
        return false;
    }

    /**
     * Set salary_deduction_pit
     * @param double $slr
     * @param array $options
     * @return this
     */
    public function updateSalaryDeductionPit($slr, array $options = array())
    {
        $prevSlr = $this->colVal('salary_deduction_pit');
        $subtotal = $this->colVal('salary_subtotal');
        $this->setColVal('salary_deduction_pit', $slr);
        $this->setColVal('salary_subtotal', $subtotal + $prevSlr - $slr);
        // Return
        return $this;
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
            'salary_type' => Models\Salary::TYPE_110,
            'salarySums' => true
        ]);
        //
        return parent::fetchReportDataByTime($options, $qB, $totalRowsQB);
    }
}