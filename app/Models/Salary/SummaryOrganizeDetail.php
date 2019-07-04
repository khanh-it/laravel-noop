<?php

namespace App\Models\Salary;

use Illuminate\Database\Eloquent\Builder;
use App\Models;

/**
 * @class SummaryOrganizeDetail
 */
class SummaryOrganizeDetail extends Models\SalaryDetail
{
    /**
     * jqx's grid columns & datafields!
     * @$array
     */
    protected static $jqxGridColumns = [
        [ 'datafield' => 'id' ],
        [ 'datafield' => 'salary_id' ],
        [ 'datafield' => 'department_id' ],
        [
            'text' => 'Mã đơn vị',
            'datafield' => [['department_code']],
            'width' => 96,
            'filterable' => false,
            'sortable' => false,
            'editable' => false,
            'pinned' => true
        ],
        [
            'text' => 'Tên đơn vị',
            'datafield' => [['department_name']],
            'minwidth' => 192,
            'filterable' => false,
            'sortable' => false,
            'editable' => false,
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
     * Get SummaryOrganize.
     * @return SummaryOrganize|null
     */
    public function salary()
    {
        return $this->belongsTo(SummaryOrganize::class, $this->columnName('salary_id'));
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
            'department_code',
            'department_name',
            'details'
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
            'department_code',
            'details'
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
     * @param Department $department
     * @param array $salaryTSInfo Thong tin cham cong.
     * @param array $options
     * @return this
     */
    public function assignSalaryAndTimeSheetInfoSummary(Models\SalaryConfig $salaryConfig, Models\Department $department, array $salaryTSInfo, array $options = [])
    {
        // Get, format input(s)
        // ...

        // Set relationshiops
        $this->department()->associate($department);

        //
        $salarySubtotal = 0;
        $sumArr = [];
        if (!empty($salaryTSInfo)) {
            foreach ($salaryTSInfo as $salary) {
                $sumArr[$salary['salary_type']] += $salary['salary_subtotal'];
            }
        }
        if (!empty($sumArr)) {
            foreach ($sumArr as $slrType => $slrSubtotal) {
                $model = app()->make(Models\SalarySum::class);
                $model->setColVal('salary_id', $this->colVal('salary_id'));
                $model->setColVal('salary_type', $slrType);
                $model->setColVal('salary_subtotal', $slrSubtotal);
                $model->save();
                $this->salarySums()->save($model);
                // +++
                $salarySubtotal += $slrSubtotal;
            }
        }

        // Self update salary detail info
        $colPrefix = static::$columnPrefix;
        // 
        $slrdInfo = [
            ($colPrefix . ($col = 'salary_subtotal')) => $salarySubtotal,
        ];
        $this->updateSalaryDetailInfo($slrdInfo);
        //.end
        return $this;
    }

    /**
     * 
     *
     * @param array $data Jqx request payload
     * @return void
     */
    public function jqxFetchRecordList(SummaryOrganize $salaryModel, array $data, &$qB = null, &$totalRowsQB = null)
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
                    // Join department
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
        $rows = $qB->with('salarySums')->get()->map(function($row, $idx) {
                $prop;
                // ++++
                foreach ($row->salarySums as $slrSum) {
                    if (is_null($slrSum->colVal('for_salary_id'))) {
                        $row->{'TYPE_' . $slrSum->colVal('salary_type')} = $slrSum->colVal('salary_subtotal');
                    }
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
     * Report details by time
     *
     * @param array $options An array of options
     * @return void
     */
    public function fetchReportDataByTime(array $options, &$qB = null, &$totalRowsQB = null)
    {
        // Get, format input(s)
        $options = array_replace($options, [
            'salary_type' => Models\Salary::TYPE_120,
            'summary'     => true,
            'salarySums' => true
        ]);
        //
        return parent::fetchReportDataByTime($options, $qB, $totalRowsQB);
    }
}