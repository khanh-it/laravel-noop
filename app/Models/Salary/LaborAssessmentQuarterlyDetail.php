<?php

namespace App\Models\Salary;

use Illuminate\Database\Eloquent\Builder;
use App\Models;

class LaborAssessmentQuarterlyDetail extends Models\SalaryDetail
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
            'text' => 'Số tiền',
            'datafield' => ['salary_assessment_quarterly', [
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
            'text' => 'Chức vụ',
            'datafield' => [['account_salary_position_money_quarterly'], [
                'type' => 'number'
            ]],
            'minwidth' => 128,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
            'editable' => false,
            'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}'
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
            'editable' => false,
            'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}'
        ],
    ];

    /**
     * Get Salary\LaborAssessment.
     * @return LaborAssessment|null
     */
    public function salary()
    {
        return $this->belongsTo(LaborAssessmentQuarterly::class, $this->columnName('salary_id'));
    }

    /**
     * Get records simple as list
     * @param array $options An array of options
     * @return mixed
     */
    public static function makeListTitlesPersonal(array $options = array())
    {
        return Models\SalaryTitlesPersonal::makeList($options);
    }

    /**
     * Return jqx's grid columns
     * @param null|array $datafields Datafields
     * @param null|array $columngroups Column groups
     * @return array
     */
    public static function jqxGridColumns2nd(&$datafields = null, &$columngroups = null)
    {
        $jqxGridColumns = static::$jqxGridColumns; // backup
        // Create columns
        $columns = [];
        // +++ T.tin các bảng xếp loại
        $titlesPersonalList = static::makeListTitlesPersonal();
        foreach ($titlesPersonalList as $idx => $items) {
            $columns[] = [
                'text' => ($text = $items['name']),
                'datafield' => [[ ($dfd = "titles.{$items['code']}") ]],
                'width' => 128,
                'cellsalign' => 'center',
                'filterable' => false,
                'sortable' => false,
                'editable' => false,
                'columngroup' => ['assessment_quarterly', [
                    'text' => 'Xếp loại',
                ]],
            ];
            $columns[] = [ 'datafield' => [[$dfd]] ];
        }
        //
        if($columns){
            $jqxGColumnsEnd = array_slice(static::$jqxGridColumns,5);
            array_splice(static::$jqxGridColumns,5);
            static::$jqxGridColumns = array_merge(static::$jqxGridColumns, $columns, $jqxGColumnsEnd);
        }
        $return = parent::jqxGridColumns($datafields, $columngroups);
        // 
        static::$jqxGridColumns = $jqxGridColumns;// revert
        //
        return $return;
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

        $slrPositionQuarterly = doubleval($account->account_salary_position_money_quarterly);
        // Get, format input(s)
        // ...
        // Self update salary detail info
        $colPrefix = static::$columnPrefix;
        // Thong tin cham cong
        // 
        $slrdInfo = [
            ($colPrefix . ($col = 'salary_assessment_quarterly')) => ($salaryAssessmentQuarterly
                = 0
            ),
            ($colPrefix . ($col = 'salary_subtotal')) => ($calSalarySubtotal
                = $slrPositionQuarterly + $salaryAssessmentQuarterly
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
    public function jqxFetchRecordList(LaborAssessmentQuarterly $salaryModel, array $data, &$qB = null, &$totalRowsQB = null)
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

                    //loại bỏ nhân viên khoán và ngưng việc
                    $qB->whereNotNull("{$tableAcc}.account_salary_basic")
                    ->where("{$tableAcc}.account_status", 1);

                    //SẮP XẾP THEO CHỨC DANH
                    $qB->orderBy("{$tableAcc}.account_position_id", 'ASC');

                    // Limit select
                    $qB->select([
                        "{$modelTable}.*",
                        "{$tableAcc}.account_code",
                        "{$tableAcc}.account_fullname",
                        "{$tableAcc}.account_salary_position_money_quarterly"
                    ]);
                },
        ]);
		// Format data
		// +++
        $rows = $qB->get()->map(function($row, $idx) {
                $prop;
                if($row->colVal('salary_titles_id')){
                    $titlesPersonal = Models\SalaryTitlesPersonal::find($row->colVal('salary_titles_id'));
                    if($titlesPersonal){
                        $row->{($props="titles.{$titlesPersonal->colVal('code')}")} = 'X';
                    }
                }
                //
                return $row;
            });
        
        // Return
        return $rows;
    }

    /**
     * Get records simple as list
     * @param array $options An array of options
     * @return mixed
     */
    public static function getColumsTitles(array $options = array())
    {
        // Create columns
        $columns = [];
        // +++ T.tin các bảng xếp loại
        $titlesPersonalList = static::makeListTitlesPersonal();

        foreach ($titlesPersonalList as $idx => $items) {
            $columns[] = "titles.{$items['code']}";
        }

        return $columns;
    }
}
