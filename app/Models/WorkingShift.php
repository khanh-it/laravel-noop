<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @class WorkingShift
 */
class WorkingShift extends AbstractModel
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hr_working_shift';

    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const CREATED_AT = 'working_shift_created_at';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const UPDATED_AT = 'working_shift_updated_at';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const DELETED_AT = 'working_shift_deleted_at';
    /**
     * @var string Customize the names of the columns used to store the status.
     */
    // const STATUS_COLUMN = 'working_shift_status';

    /** @var string type 0 (nghi - khong lam viec) */
    const TYPE_0 = 0;
    /** @var string type 1 (lam viec binh thuong - ca ngay) */
    const TYPE_1 = 1;
    /** @var string type 2 (lam ca dau - nghi ca cuoi) */
    const TYPE_2 = 2;
    /** @var string type 3 (lam ca cuoi - nghi ca dau) */
    const TYPE_3 = 3;
    /**
     * Return type list
     * @return array
     */
    public static function typeList() {
        $list = [
            static::TYPE_0 => 'Nghỉ',
            static::TYPE_1 => 'Bình thường',
            static::TYPE_2 => 'Ca đầu',
            static::TYPE_3 => 'Ca cuối',
        ];
        return $list;
    }
    /**
     * set type 0
     * @return void
     */
    public function typeOff()
    {
        $this->time_sheet_type = static::TYPE_0;
        return $this;
    }
    /**
     * set type 1
     * @return void
     */
    public function typeNormal()
    {
        $this->time_sheet_type = static::TYPE_1;
        return $this;
    }
    /**
     * set type 2
     * @return void
     */
    public function type1st()
    {
        $this->time_sheet_type = static::TYPE_2;
        return $this;
    }
    /**
     * set type 3
     * @return void
     */
    public function type2nd()
    {
        $this->time_sheet_type = static::TYPE_2;
        return $this;
    }
    /**
     * is type 0
     * @return bool
     */
    public function isTypeOff()
    {
        return $this->time_sheet_type == static::TYPE_0;
    }
    /**
     * is type 1
     * @return bool
     */
    public function isTypeNormal()
    {
        return $this->time_sheet_type == static::TYPE_1;
    }
    /**
     * is type 2
     * @return bool
     */
    public function isType1st()
    {
        return $this->time_sheet_type == static::TYPE_2;
    }
    /**
     * is type 3
     * @return bool
     */
    public function isType2nd()
    {
        return $this->time_sheet_type == static::TYPE_3;
    }
    /**
     * Get number of shifts a day by type
     * @param int|string $type Type
     * @return int
     */
    public static function shiftsOfDayByType($type)
    {
        $shifts = 0;
        switch ($type)
        {
            case static::TYPE_1:
                $shifts = [ 1, 1 ];
                break;
            case static::TYPE_2:
                $shifts = [ 1, 0 ];
                    break;
            case static::TYPE_3:
                $shifts = [ 0, 1 ];
                break;
            case static::TYPE_0:
            default:
                $shifts = [ 0, 0 ];
        }
        return $shifts;
    }

    /** @var integer OT flag (Cho phep lam them sau ca) */
    const OT_FLAG_0 = 0;
    const OT_FLAG_1 = 1;

    /** @var integer OT salary flag (Cho phep lam them sau ca) */
    const OT_SALARY_FLAG_0 = 0;
    const OT_SALARY_FLAG_1 = 1;

     /**
     * Primary key
     * @var string
     */
    protected $primaryKey = 'working_shift_id';

    /**
     * Column datafield prefix
     * @var string
     */
    public static $columnPrefix = 'working_shift_';

    /**
     * jqx's grid columns & datafields!
     * @var array
     */
    protected static $jqxGridColumns = [
        [ 'datafield' => 'id' ],
        [
            'text' => 'Mã',
            'datafield' => 'code',
            'width' => 128,
            'pinned' => true
        ],
        [
            'text' => 'Tên',
            'datafield' => 'name',
        ],
        [ 'datafield' => 'department_id' ],
        [
            'text' => 'Đơn vị',
            'datafield' => 'department_id_text',
            // 'width' => 128,
            'sortable' => false,
        ],
        [
            'text' => 'Bắt đầu',
            'datafield' => '1st_begin',
            'width' => 90,
            'filterable' => false,
            'sortable' => false,
            'columngroup' => ['_1st', [
                'text' => 'Ca đầu',
            ]]
        ],
        [
            'text' => 'Kết thúc',
            'datafield' => '1st_end',
            'width' => 90,
            'filterable' => false,
            'sortable' => false,
            'columngroup' => '_1st'
        ],
        [
            'text' => 'Bắt đầu',
            'datafield' => '2nd_begin',
            'width' => 90,
            'filterable' => false,
            'sortable' => false,
            'columngroup' => ['_2nd', [
                'text' => 'Ca cuối',
            ]]
        ],
        [
            'text' => 'Kết thúc',
            'datafield' => '2nd_end',
            'width' => 90,
            'filterable' => false,
            'sortable' => false,
            'columngroup' => '_2nd'
        ],
        [ 'datafield' => 'late', ],
        [ 'datafield' => 'early', ],
        [ 'datafield' => 'OT_flag', ],
        [ 'datafield' => 'OT_salary_flag', ],
        [ 'datafield' => 'OT_begin', ],
        [ 'datafield' => 'OT_end', ],
        [ 'datafield' => 'OT_min', ],
        [ 'datafield' => 'OT_floor', ],
        [ 'datafield' => 'type_mon', ],
        [ 'datafield' => 'type_tue', ],
        [ 'datafield' => 'type_wed', ],
        [ 'datafield' => 'type_thu', ],
        [ 'datafield' => 'type_fri', ],
        [ 'datafield' => 'type_sat', ],
        [ 'datafield' => 'type_sun', ],
    ];

    /**
     * Alter jqx grid column info
     * @param array $col Column data
     * @return array
     */
    public static function jqxGridCol($col)
    {
        $dfd = static::jqxGridDatafieldByCol($col);
        if ('department_id_text' === $dfd) {
            $col['filteritems'] = array_values(Department::makeList());
            $col['filtertype'] = 'list';
        }
        return $col;
    }

    /**
     * Get the create account.
     */
    public function createAccount()
    {
        return $this->belongsTo(Account::class, static::columnName('created_by'));
    }

    /**
     * Get the delete account.
     */
    public function deleteAccount()
    {
        return $this->belongsTo(Account::class, static::columnName('deleted_by'));
    }

    /**
     * Get department info.
     * @return Department
     */
    public function department()
    {
        return $this->belongsTo(Department::class, static::columnName('department_id'));
    }

    /**
     * @var array of WorkingShift
     */
    protected static $_store = [];

    /**
     * Thong tin ca lam viec mac dinh tu DB
     * @param array $options An array of options
     * @return WorkingShift|null
     */
    public static function defaultWSData(array $options = array())
    {
        // Get, format input(s)
        // ...

        //
        if (!static::$_store[$departmentId]) {
            // Create query builder
            // +++ 
            $depIdArr = [];
            if ($options['department_id']) {
                $depModel = Department::find($options['department_id']);
                if ($depModel) {
                    $depIdArr = array_keys((array)$depModel->findAllAncestor());
                }
                unset($depModel);
            }
            // +++ 
            $qB = static::whereRaw(1)
                ->where(function($_qB) use ($depIdArr) {
                    $_qB->whereNull($colDepartmentId = static::columnName('department_id'));
                    if (!empty($depIdArr)) {
                        $_qB->orWhereIn($colDepartmentId, $depIdArr);
                    }
                })
            ;
            // Fetch data
            $records = $qB->get();
            $finalRecord = $records->filter(function($row) {
                return is_null($row->colVal('department_id'));
            })->first();
            // ||Loop recursive find record by department
            if ($records->count() && !empty($depIdArr)) {
                $recordWithDep = null;
                foreach ($depIdArr as $depId) {
                    foreach ($records as $record) {
                        if (is_null($recordWithDep) && $depId && ($depId == $record->colVal('department_id'))) {
                            $recordWithDep = $record;
                            break;
                        }
                    }
                }
            }
            // dd($depIdArr, $records, $recordWithDep ? $recordWithDep->toArray() : null, $finalRecord->toArray());
            static::$_store[$departmentId] = ($recordWithDep ?: $finalRecord);
        }
        // die($qB->toSql());
        // Return
        return static::$_store[$departmentId];
    }

    /**
     * Thong tin, so ca lam viec 1 ngay
     * @param array $options An array of options
     * @return array
     */
    public static function workingShiftsOfDay(array $options = array())
    {
        $record = static::defaultWSData($options);
        $result = [];
        if ($record) {
            $result = [
                ($dType = 'mon') => static::shiftsOfDayByType($record->colVal('type_' . $dType)),
                ($dType = 'tue') => static::shiftsOfDayByType($record->colVal('type_' . $dType)),
                ($dType = 'wed') => static::shiftsOfDayByType($record->colVal('type_' . $dType)),
                ($dType = 'thu') => static::shiftsOfDayByType($record->colVal('type_' . $dType)),
                ($dType = 'fri') => static::shiftsOfDayByType($record->colVal('type_' . $dType)),
                ($dType = 'sat') => static::shiftsOfDayByType($record->colVal('type_' . $dType)),
                ($dType = 'sun') => static::shiftsOfDayByType($record->colVal('type_' . $dType)),
                // ($dType = 'hol') => static::shiftsOfDayByType($record->colVal('type_' . $dType)),
            ];
        }
        // dd($result);
        return $result;
    }

    /**
     * Thong tin, gio lam theo tung ca trong 1 ngay (bat dau, ket thuc,...)
     *
     * @param array $options An array of options
     * @return array
     */
    public static function workingShiftTimeline(array $options = array())
    {
        $record = static::defaultWSData($options);
        $result = [];
        if ($record)
        {
            $result['begin1st'] = $begin1st = $record->colVal('1st_begin');
            $result['end1st'] = $end1st = $record->colVal('1st_end');
            $result['begin2nd'] = $begin2nd = $record->colVal('2nd_begin');
            $result['end2nd'] = $end2nd = $record->colVal('2nd_end');
        }
        // dd($result);
        return $result;
    }

    /**
     * Thong tin, so gio lam viec 1 ca / ngay (don vi: gio - hour(s))
     *
     * @param array $options An array of options
     * @return array
     */
    public static function workingHoursOfShift(array $options = array())
    {
        $result = [];
        $timeline = static::workingShiftTimeline($options);
        if (!empty($timeline))
        {
            $result = [
                (strtotime($timeline['end1st']) - strtotime($timeline['begin1st'])) / 60 /* min */ / 60 /* sec*/,
                (strtotime($timeline['end2nd']) - strtotime($timeline['begin2nd'])) / 60 /* min */ / 60 /* sec*/,
            ];
        }
        // dd($result);
        return $result;
    }

    /**
     * Thong tin, t.gian nghi giua ca trong 1 ngay (don vi: gio - hour(s))
     *
     * @param array $options An array of options
     * @return float
     */
    public static function breakTimeBetweenShifts(array $options = array())
    {
        $result = 0;
        $timeline = static::workingShiftTimeline($options);
        if (!empty($timeline))
        {
            $result = (strtotime($timeline['begin2nd']) - strtotime($timeline['end1st'])) / 60 /* min */ / 60 /* sec*/;
        }
        // dd($result);
        return $result;
    }
    
    /**
     * Thong tin, so gio lam viec 1 ngay
     *
     * @param array $options An array of options
     * @return float
     */
    public static function workingHoursOfDay(array $options = array())
    {
        $workingHoursOfShift = static::workingHoursOfShift($options);
        return array_sum($workingHoursOfShift);;
    }

    /**
     * Get number of working days of month
     * @param int|string $month Month
     * @param int|string $year Year
     * @param array $options
     * @return float
     */
    public static function workingDaysOfMonth($month, $year = null, array $options = array())
    {
        $workingDaysOfMonth = 0;

        if (!($month instanceof \DatePeriod))
        {
            $from = "{$year}-{$month}-01 00:00:00";
            $to = date('Y-m-t 23:59:59', strtotime($from));
            $from = new \DateTime($from);
            $to = new \DateTime($to);
            $interval = \DateInterval::createFromDateString('1 day');
            $month = new \DatePeriod($from, $interval, $to);
            $year = null;
        }
        if ($month instanceof \DatePeriod && is_null($year))
        {
            $workingShiftsOfDay = static::workingShiftsOfDay($options);
            $workingShiftsOfDay = array_map(function($vals){
                return array_sum($vals);
            }, $workingShiftsOfDay);
            $datePeriod = $month;
            foreach ($datePeriod as $dt) {
                $D = strtolower($dt->format('D'));
                $workingDaysOfMonth += intval($workingShiftsOfDay[$D]);
            }
            $workingDaysOfMonth = ($workingDaysOfMonth / 2);
        }
        return $workingDaysOfMonth;
    }

    /**
     * Thong tin, so gio lam viec 1 ngay trong tuan
     *
     * @param array $options An array of options
     * @return array
     */
    public static function workingHoursOfDayInWeek(array $options = array())
    {
        // So ca (buoi) lam viec / 1 ngay
        $workingShiftsOfDay = static::workingShiftsOfDay($options);
        // So gio lam viec / 1 ca (buoi)
        list($shift1stWrkHs, $shift2ndWrkHs) = (array)static::workingHoursOfShift($options);
        // Return;
        $result = [];
        foreach ($workingShiftsOfDay as $dateType => $wrkShiftsOfDay) {
            list($wrkShiftsOfDay1st, $wrkShiftsOfDay2nd) = (array)$wrkShiftsOfDay;
            $result[$dateType] = ($wrkShiftsOfDay1st * $shift1stWrkHs)
                + ($wrkShiftsOfDay2nd * $shift2ndWrkHs)
            ;
        }
        // dd($result);
        return $result;
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
        $departmentList = Department::makeList();
        $departmentListFlip = array_flip($departmentList);
        // +++
		// Prepare the data
        $qB = $this->qBFromJqxRequestPayload($data, $totalRowsQB, [
            // Alter query builder
            // 'queryBuilder' => function(\Illuminate\Database\Eloquent\Builder $qB) {},
            //
            'where' => function(&$join, &$where, &$filter, &$condition, &$value)
                use ($departmentListFlip) {
                    if (($prop = $this->columnName('department_id')) . '_text' === $filter['field']) {
                        $value = $filter['value'] = $departmentListFlip[$value];
                    }
                }
            ,
        ]);
        // var_dump($data);die($qB->toSql());
		// Format data
		// +++
        $rows = $qB->get()->map(function($row, $idx)
            use ($departmentList) {
                $row->setColVal(($prop = 'department_id') . '_text', $departmentList[$row->colVal($prop)]);
                //
                return $row;
            })
        ;
        //.end

        // Return
        return $rows;
	}

	public static function workingHours()
	{
	    $results = [];
	    for ($i = 0; $i < 48; $i++) {
	        $results[date('H:i:s', $i * 30 * 60)] = date('H:i A', $i * 30 * 60);
	    }
	    return $results;
	}
}