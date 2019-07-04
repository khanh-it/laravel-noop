<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @class TimeSheetSign
 */
class TimeSheetSign extends AbstractModel
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hr_time_sheet_sign';

    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const CREATED_AT = 'time_sheet_sign_created_at';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const UPDATED_AT = 'time_sheet_sign_updated_at';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const DELETED_AT = 'time_sheet_sign_deleted_at';
    /**
     * @var string Customize the names of the columns used to store the status.
     */
    const STATUS_COLUMN = 'time_sheet_sign_status';

    /** @var string type: worktime 1 day */
    const TYPE_WORKTIME = 10;
    /** @var string type: OT */
    const TYPE_OT = 20;
    /** @var string type: OT (khong luong) */
    const TYPE_OT_NO_SALARY = 21;
    /** @var string type: leave (khong luong)  */
    const TYPE_LV_WORKTIME = 30;
    /** @var string type: leave (om)  */
    const TYPE_LV_SICK = 31;
    /** @var string type: leave (thai san)  */
    const TYPE_LV_MATERNITY = 32;
    /**
     * Return type list
     * @return array
     */
    public static function typeList() {
        $list = [
            static::TYPE_WORKTIME => 'Giờ làm',
            static::TYPE_OT => 'Tăng ca',
            static::TYPE_LV_WORKTIME => 'Nghỉ thường',
            static::TYPE_LV_SICK => 'Nghỉ ốm',
            static::TYPE_LV_MATERNITY => 'Nghỉ thai sản',
        ];
        return $list;
    }
    /**
     * set type TYPE_WORKTIME
     * @return this
     */
    public function typeWorkDayFull()
    {
        $this->time_sheet_sign_type = static::TYPE_WORKTIME;
        return $this;
    }
    /**
     * check type TYPE_WORKTIME
     * @return bool
     */
    public function isTypeWorkDayFull()
    {
        return $this->time_sheet_sign_type == static::TYPE_WORKTIME;
    }
    /**
     * set type TYPE_OT
     * @return this
     */
    public function typeOT()
    {
        $this->time_sheet_sign_type = static::TYPE_OT;
        return $this;
    }
    /**
     * check type TYPE_OT
     * @return bool
     */
    public function isTypeOT()
    {
        return $this->time_sheet_sign_type == static::TYPE_OT;
    }
    /**
     * set type TYPE_OT_NO_SALARY
     * @return this
     */
    public function typeOTNoSalary()
    {
        $this->time_sheet_sign_type = static::TYPE_OT_NO_SALARY;
        return $this;
    }
    /**
     * check type TYPE_OT_NO_SALARY
     * @return bool
     */
    public function isTypeOTNoSalary()
    {
        return $this->time_sheet_sign_type == static::TYPE_OT_NO_SALARY;
    }
    /**
     * set type TYPE_LV_WORKTIME
     * @return this
     */
    public function typeLeaveNoSalary()
    {
        $this->time_sheet_sign_type = static::TYPE_LV_WORKTIME;
        return $this;
    }
    /**
     * check type TYPE_LV_WORKTIME
     * @return bool
     */
    public function isTypeLeaveNoSalary()
    {
        return $this->time_sheet_sign_type == static::TYPE_LV_WORKTIME;
    }
    /**
     * set type TYPE_LV_SICK
     * @return this
     */
    public function typeLeaveSick()
    {
        $this->time_sheet_sign_type = static::TYPE_LV_SICK;
        return $this;
    }
    /**
     * check type TYPE_LV_SICK
     * @return bool
     */
    public function isTypeLeaveSick()
    {
        return $this->time_sheet_sign_type == static::TYPE_LV_SICK;
    }
    /**
     * set type TYPE_LV_MATERNITY
     * @return this
     */
    public function typeLeaveMaternity()
    {
        $this->time_sheet_sign_type = static::TYPE_LV_MATERNITY;
        return $this;
    }
    /**
     * check type TYPE_LV_MATERNITY
     * @return bool
     */
    public function isTypeLeaveMaternity()
    {
        return $this->time_sheet_sign_type == static::TYPE_LV_MATERNITY;
    }

    /** @var string default 0 (mac dinh?) */
    const DEFAULT_0 = '0';
    /** @var string default 1 (mac dinh?) */
    const DEFAULT_1 = '1';
    /**
     * Return default list
     * @return void
     */
    public static function defaultList() {
        $list = [
            static::DEFAULT_0 => 'Không',
            static::DEFAULT_1 => 'Có',
        ];
        return $list;
    }
    /**
     * set default 0
     * @return void
     */
    public function defaultNo()
    {
        $this->time_sheet_sign_default = static::DEFAULT_0;
        return $this;
    }
    /**
     * set default 1
     * @return void
     */
    public function defaultYes()
    {
        $this->time_sheet_sign_default = static::DEFAULT_1;
        return $this;
    }
    /**
     * is default 0
     * @return bool
     */
    public function isDefaultNo()
    {
        return $this->time_sheet_sign_default == static::DEFAULT_0;
    }
    /**
     * is default 1
     * @return bool
     */
    public function isDefaultYes()
    {
        return $this->time_sheet_sign_default == static::DEFAULT_1;
    }

    /**
     * 
     *
     * @param array $options
     * @return void
     */
    public static function setDefaultNoAll(array $options = array())
    {
        $query = static::whereRaw(1);
        // Filters
        if ($options['except']) {
            $exceptID = array_filter((array)$options['except']);
            if (!empty($exceptID)) {
                $query->whereNotIn(static::columnName('id'), $exceptID);
            }
        }
        return $query->update([
            static::columnName('default') => static::DEFAULT_0
        ]);
    }

    /**
     * Primary key
     *
     * @var string
     */
    protected $primaryKey = 'time_sheet_sign_id';

    /**
     * Column datafield prefix
     * @var string
     */
    public static $columnPrefix = 'time_sheet_sign_';

    /**
     * jqx's grid columns & datafields!
     * @var array
     */
    protected static $jqxGridColumns = [
        [ 'datafield' => 'id' ],
        [
            'text' => 'Ký hiệu',
            'datafield' => 'code',
            'width' => 90,
            'pinned' => true
        ],
        [ 'datafield' => 'type' ],
        [
            'text' => 'Loại',
            'datafield' => 'type_text',
            'width' => 136,
            'filtertype' => 'checkedlist',
        ],
        [
            'text' => 'Tên',
            'datafield' => 'name',
        ],
        [ 'datafield' => 'value' ], [
            'text' => 'Tỷ lệ hưởng lương (%)',// 'N.Thường',
            'datafield' => 'value_text',
            'width' => 168,
            'sortable' => false,
            'filterable' => false,
            'cellsalign' => 'right',
            /* 'columngroup' => ['values', [ 'text' => 'Tỷ lệ hưởng lương (%)', ]] */
        ],
        /* [ 'datafield' => 'value_t7' ],
        [
            'text' => 'Thứ 7',
            'datafield' => 'value_t7_text',
            'width' => 70,
            'sortable' => false,
            'filterable' => false,
            'cellsalign' => 'right',
            'columngroup' => 'values'
        ],
        [ 'datafield' => 'value_cn' ],
        [
            'text' => 'Chủ nhật',
            'datafield' => 'value_cn_text',
            'width' => 70,
            'sortable' => false,
            'filterable' => false,
            'cellsalign' => 'right',
            'columngroup' => 'values'
        ],
        [ 'datafield' => 'value_lt' ],
        [
            'text' => 'Lễ/Tết',
            'datafield' => 'value_lt_text',
            'width' => 70,
            'sortable' => false,
            'filterable' => false,
            'cellsalign' => 'right',
            'columngroup' => 'values'
        ],*/
        [ 'datafield' => 'default' ], [
            'text' => 'Mặc định',
            'datafield' => 'default_text',
            'width' => 100,
            'sortable' => false,
            'filtertype' => 'list',
        ]/*,
        [
            'text' => 'Ghi chú',
            'sortable' => false,
            'filterable' => false,
            'datafield' => 'note',
            'minwidth' => 200,
        ]*/,
        [ 'datafield' => 'status' ], [
            'text' => 'Trạng thái',
            'datafield' => 'status_text',
            'width' => 100,
            'sortable' => false,
            'filtertype' => 'list',
        ],
        // [ 'datafield' => 'created_by' ],
        // [ 'datafield' => 'created_at' ],
        // [ 'datafield' => 'updated_at' ],
        // [ 'datafield' => 'deleted_by' ],
        // [ 'datafield' => 'deleted_at' ],
    ];

    /**
     * Alter jqx grid column info
     * @param array $col Column data
     * @return array
     */
    public static function jqxGridCol($col)
    {
        $dfd = static::jqxGridDatafieldByCol($col);
        if ('type_text' === $dfd) {
            $col['filteritems'] = array_values(static::typeList());
        }
        if ('default_text' === $dfd) {
            $col['filteritems'] = array_values(static::defaultList());
        }
        if ('status_text' === $dfd) {
            $col['filteritems'] = array_values(static::statusList());
        }
        return $col;
    }

    /**
     * Get the create account.
     */
    public function createAccount()
    {
        return $this->hasOne(Account::class, static::$columnPrefix . 'create_account_id');
    }

    /**
     * Get the create account.
     */
    public function deleteAccount()
    {
        return $this->hasOne(Account::class, static::$columnPrefix . 'delete_account_id');
    }

    /**
     * Get records simple as list
     * @param array $options An array of options
     * @return mixed
     */
    public static function makeList(array $options = array())
    {
        $self = app()->make(static::class);
        $collect = static::all()->mapWithKeys(function($item) use ($self, $options) {
            return [$item[$self->getKeyName()] => (
                    (true === $options['full']) ? [
                        ($prop = 'code') => $item[static::columnName($prop)],
                        ($prop = 'name') => $item[static::columnName($prop)],
                        ($prop = 'type') => $item[static::columnName($prop)],
                        // ($prop = 'value') => $item[static::columnName($prop)],
                        // ($prop = 'default') => $item[static::columnName($prop)],
                    ]
                    : $item[static::columnName('name')]
                )
            ];
        });
        return $collect->toArray();
    }

    /**
     * Find first default record
     * @param array $options An array of options
     * @return mixed
     */
    public static function find1stDefault(array $options = array())
    {
        $self = app()->make(static::class);
        $record = static::where([
            $self->columnName('type') => static::TYPE_WORKTIME,
            $self->columnName('default') => static::DEFAULT_1,
            $self->columnName('status') => static::STATUS_1
        ])->first();
        return $record;
    }

    /**
     * Cal average value(s) of time sheet sign
     * @param array $options An array of options
     * @return array
     */
    public static function calAvgValues(array $options = array())
    {
        $avg = [];
        $qb = static::whereRaw(1)
            ->select([
                static::columnName($colNameType = 'type') . ' AS type',
                \DB::raw('AVG(' . static::columnName('value') . ') AS ' . ($colNameAvgValue = 'avg_value')),
                // $colNameValueT7 = \DB::raw('AVG(' . static::columnName('value_t7') . ') AS avg_value_t7'),
                // $colNameValueCn = \DB::raw('AVG(' . static::columnName('value_cn') . ') AS avg_value_cn'),
                // $colNameValueLt = \DB::raw('AVG(' . static::columnName('value_lt') . ') AS avg_value_lt'),
            ])
            ->where(static::columnName('status'), static::STATUS_1)
            ->groupBy([ static::columnName('type') ])
        ; // die($qb->toSql());
        foreach($qb->get() as $record) {
            $record = $record->toArray();
            $avgKey = '';
            switch ($record[$colNameType]) {
                case static::TYPE_WORKTIME:
                    $avgKey = 'time_worktime';
                    break;
                case static::TYPE_OT:
                    $avgKey = 'time_ot';
                    break;
                case static::TYPE_OT_NO_SALARY:
                    $avgKey = 'time_ot_no_salary';
                    break;
                case static::TYPE_LV_WORKTIME:
                    $avgKey = 'time_lv_worktime';
                    break;
                case static::TYPE_LV_SICK:
                    $avgKey = 'time_lv_sick';
                    break;
                case static::TYPE_LV_MATERNITY:
                    $avgKey = 'time_lv_maternity';
                    break;
            }
            $avg[$avgKey] = TimeSheet::round($record[$colNameAvgValue]);
        } unset($record);
        // Return
        return $avg;
    }

    /**
     * 
     *
     * @param array $data Jqx request payload
     * @return void
     */
    public function jqxFetchRecordList(array $data, &$qB = null, &$totalRowsQB = null)
    {
        // Define vars
        // +++
        $typeList = static::typeList();
        $typeListFlip = array_flip($typeList);
        // +++
        $defaultList = static::defaultList();
        $defaultListFlip = array_flip($defaultList);
        // +++
        $statusList = static::statusList();
        $statusListFlip = array_flip($statusList);
		// Prepare the data
        $qB = $this->qBFromJqxRequestPayload($data, $totalRowsQB, [
            //
            'queryBuilder' => function(\Illuminate\Database\Eloquent\Builder $qB, $data) {
                // Set default query conditions
                $key = ($col = $this->columnName('status')) . '_text';
                if (!$data['filterGroups'][$key]) {
                    $qB->where($col, static::STATUS_1);
                }
                unset($col, $key);
            },
            //
            'where' => function(&$join, &$where, &$filter, &$condition, &$value)
                use ($typeListFlip, $defaultListFlip, $statusListFlip) {
                    if (($prop = $this->columnName('default')) . '_text' === $filter['field']) {
                        $filter['field'] = $prop;
                        $value = $filter['value'] = $defaultListFlip[$value];
                    }
                    if (($prop = $this->columnName('status')) . '_text' === $filter['field']) {
                        $filter['field'] = $prop;
                        $value = $filter['value'] = $statusListFlip[$value];
                    }
                }
        ]);
        // var_dump($data);die($qB->toSql());
		// Format data
		// +++
        $rows = $qB->get()->map(function($row, $idx)
            use ($typeList, $defaultList, $statusList) {
                $prop;
                $row->setColVal(($prop = 'type') . '_text', $typeList[$row->colVal($prop)]);
                $row->setColVal(($prop = 'value') . '_text', numberFormatTax($row->colVal($prop)));
                // $row->setColVal(($prop = 'value_t7') . '_text', numberFormatTax($row->colVal($prop)));
                // $row->setColVal(($prop = 'value_cn') . '_text', numberFormatTax($row->colVal($prop)));
                // $row->setColVal(($prop = 'value_lt') . '_text', numberFormatTax($row->colVal($prop)));
                $row->setColVal(($prop = 'default') . '_text', $defaultList[$row->colVal($prop)]);
                $row->setColVal(($prop = 'status') . '_text', $statusList[$row->colVal($prop)]);
                //
                return $row;
            })
        ;
        //.end
        
        // Return
        return $rows;
	}
}