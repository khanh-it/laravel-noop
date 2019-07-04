<?php

namespace App\Models\Salary;

use Illuminate\Database\Eloquent\Builder;
use App\Models;

class IncomeTaxYearlyDetail extends Models\SalaryDetail
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
            'text' => 'Mã số thuế',
            'datafield' => [['account_tax']],
            'minwidth' => 130,
            'filterable' => false,
            'sortable' => false,
            'editable' => false,
            'cellsalign' => 'right',
            'pinned' => true
        ],
        [
            'text' => 'CMND',
            'datafield' => [['account_id_number']],
            'minwidth' => 130,
            'filterable' => false,
            'sortable' => false,
            'editable' => false,
            'cellsalign' => 'right',
            'pinned' => true
        ],
        [
            'text' => 'Tổng TN chịu thuế',
            'datafield' => ['salary_pit', [
                'type' => 'number'
            ]],
            'width' => 120,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
            'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}',
        ],
        [
            'text' => 'Số NPT',
            'datafield' => [['account_salary_dependents_number']],
            'width' => 64,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
            'editable' => false,
            'columngroup' => ['salary_account_reduction_pit', [
                'text' => 'Các khoản giảm trừ',
            ]],
        ],
        [
            'text' => 'Gia cảnh',
            'datafield' => ['salary_account_reductions', [
                'type' => 'number'
            ]],
            'width' => 128,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
            'editable' => false,
            'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}',
            'columngroup' => 'salary_account_reduction_pit'
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
            'columngroup' => 'salary_account_reduction_pit'
        ],
        [
            'text' => 'Thuế TNCN đã khấu trừ',
            'datafield' => ['salary_deduction_pit', [
                'type' => 'number'
            ]],
            'width' => 180,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
            'editable' => false,
            'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}',
        ],
        [
            'text' => 'Tổng số thuế phải nộp',
            'datafield' => ['pit', [
                'type' => 'number'
            ]],
            'minwidth' => 128,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
            'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}',
        ],
    ];

    /**
     * Get IncomeTaxYearly.
     * @return IncomeTaxYearly|null
     */
    public function salary()
    {
        return $this->belongsTo(IncomeTaxYearly::class, $this->columnName('salary_id'));
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
            'account_tax',
            'account_id_number',
        ])) {
            $col = array_replace($col, [
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
     * Ham tinh "thuế"
     * @param $type loại bảng lương
     * @param array $salaryTSInfo 
     * @param $columns 
     * @return double
     */
    public static function calSalaryTax($type, $salaryTSInfo, $columns) {
        $taxYear = 0;
        foreach($salaryTSInfo as $key){
            if($key['salary_type'] == $type){
                $taxYear += (double)$key[$columns]?:0;
            }
        }
        return $taxYear;
    }
    //.end

    
    /**
     * remove bảng lương trùng tháng
     * @param array $salaryTSInfo 
     */
    public static function removeSalarySameMonth($salaryTSInfo) {

        $arrUnique = array();
        foreach ($salaryTSInfo as $key => $value) {
            $unique = [
                'key' => $key,
                'salary_month' =>$value['salary_month'],
                'salary_type' =>$value['salary_type'],
            ];
            if($arrUnique){
                foreach($arrUnique as $v){
                    if($v['salary_type'] == $value['salary_type'] && $v['salary_month'] == $value['salary_month']){
                        if($value['department_id'] == 1){
                            unset($salaryTSInfo[$v['key']]);
                        }else{
                            unset($salaryTSInfo[$key]);
                        }
                        $push = false;
                        
                    }else {
                        $push = true;
                    }
                }
                ($push == true) ? array_push($arrUnique, $unique):'';
            }else {
                array_push($arrUnique, $unique);
            }
        }
        return array_values($salaryTSInfo);
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
        
        //loại bỏ các bảng lương cùng tháng và cùng bảng lương
        $salaryTSInfo = static::removeSalarySameMonth($salaryTSInfo);
        
        // tổng thu nhập chịu thuế
        $totalSlrDtailSlrPit = static::calSalaryTax(110, $salaryTSInfo, 'salary_detail_salary_pit');
        // bảo hiểm được khấu trừ
        $totalSlrDtailInsurance = static::calSalaryTax(0, $salaryTSInfo, 'salary_detail_salary_deduction_insurance');
        // số thuế thncn đã khấu trừ
        $totalSlrDtailDeductionPit = static::calSalaryTax(110, $salaryTSInfo, 'salary_detail_salary_deduction_pit');
        // Tổng thuế đã nộp
        $totalSlrDtailPit = static::calSalaryTax(110, $salaryTSInfo, 'salary_detail_pit');

        $depntNumber =  $account->colVal('salary_dependents_number');//số người phụ thuộc
        $depNTAmount =  $account->colVal('salary_dependents_amount');//số tiền gia cảnh /1 ngừoi

        // Self update salary detail info
        $colPrefix = static::$columnPrefix;
        
        $slrdInfo = [
            ($colPrefix . ($col = 'salary_pit')) => $totalSlrDtailSlrPit,
            ($colPrefix . ($col = 'salary_account_reductions')) => $depntNumber * $depNTAmount,
            ($colPrefix . ($col = 'salary_deduction_insurance')) => $totalSlrDtailInsurance,
            ($colPrefix . ($col = 'salary_deduction_pit')) => $totalSlrDtailDeductionPit,
            ($colPrefix . ($col = 'pit')) => $totalSlrDtailPit,
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
    public function jqxFetchRecordList(IncomeTaxYearly $salaryModel, array $data, &$qB = null, &$totalRowsQB = null)
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
                        "{$tableAcc}.account_salary_dependents_number",
                        "{$tableAcc}.account_id_number",
                        "{$tableAcc}.account_tax",
                    ]);
                },
        ]);
        // var_dump($data);die($qB->toSql());
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
}
