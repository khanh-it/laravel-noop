<?php

namespace App\Models;

/**
 * @class TimeSheetDetail
 */
class TimeSheetDetail extends AbstractModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hr_time_sheet_detail';

    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const CREATED_AT = 'time_sheet_detail_created_at';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const UPDATED_AT = 'time_sheet_detail_updated_at';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    // const DELETED_AT = 'time_sheet_detail_deleted_at';
    /**
     * @var string Customize the names of the columns used to store the status.
     */
    // const STATUS_COLUMN = 'time_sheet_detail_status';

     /**
     * Primary key
     *
     * @var string
     */
    protected $primaryKey = 'time_sheet_detail_id';

    /**
     * Column datafield prefix
     * @var string
     */
    public static $columnPrefix = 'time_sheet_detail_';

    /** @var string */
    public static $dataDateFormat = 'd-m(D)';

    /**
     * jqx's grid columns & datafields!
     * @var array
     */
    protected static $jqxGridColumns = [
        [ 'datafield' => 'id' ],
        [ 'datafield' => 'time_sheet_id' ],
        [ 'datafield' => 'company_id' ],
        [ 'datafield' => 'department_id' ],
        [
            'text' => 'Đơn vị',
            'datafield' => [['department_name']],
            'width' => 0,
            'filterable' => false,
            'sortable' => false,
            'pinned' => true,
            'hidden' => true
        ],
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
            'width' => 190,
            'filterable' => false,
            'sortable' => false,
            'pinned' => true
        ],
        /*[
            'text' => 'Vị trí công việc',
            'datafield' => [['account_position']],
            'width' => 128,
            'filterable' => false,
            'sortable' => false,
        ],*/
        [ 'datafield' => 'data' ],
        [ 'datafield' => 'created_by' ],
        [ 'datafield' => 'created_at' ],
        [ 'datafield' => 'updated_at' ],
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
        /* if ('' === $col['datafield']) {
            // $col['filteritems'] = [];
        } */
        return $col;
    }

    /**
     * Get the company model.
     */
    public function company()
    {
        return $this->belongsTo(Company::class, $this->columnName('company_id'));
    }

    /**
     * Get the department model.
     */
    public function department()
    {
        return $this->belongsTo(Department::class, $this->columnName('department_id'));
    }

    /**
     * Get the account model.
     */
    public function account()
    {
        return $this->belongsTo(Account::class, $this->columnName('account_id'));
    }

    /**
     * Helper: set account/dep/company info
     * @param Account $account
     * @return this
     */
    public function setAccount(Account $account)
    {
        $department = $account->department;
        //
        $this->account()->associate($account);
        if ($department) {
            $this->department()->associate($department);
        } else {
            $this->department()->dissociate();
        }
    }

    /**
     * Get the create account.
     */
    public function createAccount()
    {
        return $this->belongsTo(Account::class, $this->columnName('created_by'));
    }

    /**
     * Get time sheet.
     * @return TimeSheet|null
     */
    public function tSheet()
    {
        return $this->belongsTo(TimeSheet::class, $this->columnName('time_sheet_id'));
    }

    /**
     * Get time sheet items
     */
    public function tsItems()
    {
        return $this->hasMany(TimeSheetItem::class, TimeSheetItem::columnName('time_sheet_detail_id'), static::columnName('id'));
    }

    /**
     * Get records simple as list
     * @param array $options An array of options
     * @return mixed
     */
    public static function makeListCompany(array $options = array())
    {
        return Company::makeList($options);
    }

    /**
     * Get records simple as list
     * @param array $options An array of options
     * @return mixed
     */
    public static function makeListDepartment(array $options = array())
    {
        return Department::makeList($options);
    }

    /**
     * Return jqx's grid columns
     * @param null|array $datafields Datafields
     * @param null|array $columngroups Column groups
     * @return array
     */
    public static function jqxGridColumns2nd(\DatePeriod $datePeriod, &$datafields = null, &$columngroups = null)
    {
        $jqxGridColumns = static::$jqxGridColumns; // backup
        // Create columns
        $columns = [];
        // +++ T.tin ngay le/tet
        $holidayInfo = Holiday::getHolidayInfoByTime(
            $datePeriod->getStartDate(),
            $datePeriod->getEndDate()
        );
        foreach ($datePeriod as $idx => $dt) {
            $holDtKey = Holiday::makeDtKey($dt);
            $holDtKey = $holidayInfo[$holDtKey] ? '(Hol)' : '';
            $columns[] = [
                'text' => ($text = ($dt->format(static::$dataDateFormat) . $holDtKey)),
                'datafield' => [[ ($dfd = "data.date.{$idx}") . "_text[{$text}]" ]],
                'filterable' => false,
                'sortable' => false,
                'width' => 128,
                // xu ly render ky hieu cham cong;
                'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}'
            ];
            $columns[] = [ 'datafield' => [[$dfd]] ];
        }
        //
        static::$jqxGridColumns = array_merge(static::$jqxGridColumns, $columns);
        // var_dump(static::$jqxGridColumns);die();
        //
        $return = parent::jqxGridColumns($datafields, $columngroups);
        // 
        static::$jqxGridColumns = $jqxGridColumns;// revert
        //
        return $return;
    }

    /**
     * 
     *
     * @param array $data Jqx request payload
     * @return void
     */
    public function jqxFetchRecordList(TimeSheet $timesheetModel, array $data, &$qB = null, &$totalRowsQB = null)
    {
        // Define vars
        // +++ thong tin cong ty
        $companyList = static::makeListCompany();
        $companyListFlip = array_flip($companyList);
        // +++ thong tin phong ban
        // $departmentList = static::makeListDepartment();
        // $departmentListFlip = array_flip($departmentList);
        // Prepare the data
        $roles = Roles::checkRoles();
        $permissons = Permissions::checkPermissons();
        $userID = \Auth::id();
        //danh sách phòng ban
        //rolesStaff => nhân viên
        //rolesHeadDepartment => trưởng phòng
        //rolesAccountant => kế toán
        //rolesBod => ban giám đốc
        //rolesAdmin => admin
        //rolesSuperAdmin => superadmin
        if($roles['rolesHeadDepartment']){
            $departmentList = Department::makeList(['rolesHead' => true]);
        }
        
        $qB = $this->qBFromJqxRequestPayload($data, $totalRowsQB, [
            // Alter query builder
            'queryBuilder' => function(\Illuminate\Database\Eloquent\Builder $qB)
                use ($timesheetModel, $roles, $userID, $departmentList, $permissons) {
                    
                    
                    // Relatives
                    $models = [
                        // 'com' => app()->make(Company::class),
                        'dep' => app()->make(Department::class),
                        'acc' => app()->make(Account::class),
                    ];
                    $modelTable = $this->getTable();

                    // Limit: chi lay du lieu detail trong 1 master table!
                    $qB->where("{$modelTable}.".static::columnName('time_sheet_id'), $timesheetModel->id());
                    /* // Join company
                    $qB->leftJoin(
                        ($tableCom = $models['com']->getTable())
                        , ("{$tableCom}." . ($pK = $models['com']->getKeyName()))
                        , '=', "{$modelTable}." . $this->columnName($pK)
                    );
                    // Join department
                    $qB->leftJoin(
                        ($tableDep = $models['dep']->getTable())
                        , ("{$tableDep}." . ($pK = $models['dep']->getKeyName()))
                        , '=', "{$modelTable}." . $this->columnName($pK)
                    ); */
                    // Join account
                    $qB->leftJoin(
                        ($tableAcc = $models['acc']->getTable())
                        , ("{$tableAcc}." . ($pK = $models['acc']->getKeyName()))
                        , '=', "{$modelTable}." . $this->columnName($pK)
                    );

                    //nhân viên ngưng làm trong tháng
                    //tháng năm chấm công
                    // $date = $timesheetModel->colVal('year').'-'.$timesheetModel->colVal('month');
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
                            //array id phong ban của trưởng phòng
                            $depArrID = array_keys($departmentList);
                            $qB->whereIn("{$modelTable}.".$this->columnName('department_id'), $depArrID);
                        }
                    }

                    //SẮP XẾP THEO CHỨC DANH
                    $qB->orderBy("{$tableAcc}.account_position_id", 'ASC');
                    // Limit select
                    $qB->select([
                        "{$modelTable}.*",
                        // "{$tableCom}.company_name",
                        // "{$tableDep}.department_name",
                        "{$tableAcc}.account_code",
                        "{$tableAcc}.account_fullname",
                    ]);
                    
                }
            ,
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
        //var_dump($date);die($qB->toSql());
        // Format data
        // +++
		// +++
        $rows = $qB->with('tsItems')->get()->map(function($row, $idx)
            use ($companyList, $departmentList) {
                $prop;
                // $row->{($prop = 'account_position')} = $row->account->account_position;
                // +++ +++
                $print = [];
                foreach ($row->tsItems()->with('tsSign')->get() as $tsItem) {
                    // Thong tin don vi (cong ty / phong ban) de quy
                    $row->department_name = $row->department->departmentNameRecursive();
                    // Dates...
                    $dateIndex = $tsItem->{$tsItem->columnName('date_index')};
                    $tsSign = $tsItem->tsSign;
                    $row->{($prop = "data.date.{$dateIndex}")} = array_replace(
                        (array)$row->{$prop},
                        [$tsSign->{$tsSign->columnName('id')} => [
                            ($prop = 'time_hours') => ($h = $tsItem->{$tsItem->columnName($prop)}),
                            ($prop = 'time_from') => ($from=timeStrRemoveParts($tsItem->{$tsItem->columnName($prop)})),
                            ($prop = 'time_to') => ($to=timeStrRemoveParts($tsItem->{$tsItem->columnName($prop)}))
                        ]]
                    );
                    $print[$dateIndex]=[
                        'code' => $tsSign->{$tsSign->columnName('code')},
                        'time' => countHours($from,$to)?:$h,
                    ];
                    // array_push($print,$prop);
                } unset($dateIndex, $hours, $signId);
                // unset unuses data
                unset($row->account);
                //
                $row->print = $print;
                unset($print);
                return $row;
            })
        ;
        //.end
        // Return
        return $rows;
    }

    /**
     * 
     * @param array $options An array of options
     * @return array
     */
    public function populateTimeSheetItems(array $options = array())
    {
        //
        $count = 0;
        $tSheet = $this->tSheet;
        if ($tSheet)
        {
            //
            $datePeriod = $tSheet->timeSheetPeriod();
            $tSheetSign = TimeSheetSign::find1stDefault();
            $wrkHours = (array)WorkingShift::workingHoursOfDayInWeek([
                'department_id' => $tSheet->colVal('department_id')
            ]);
            // +++ T.tin ngay le/tet
            $holidayInfo = Holiday::getHolidayInfoByTime(
                $datePeriod->getStartDate(),
                $datePeriod->getEndDate()
            );
            foreach ($datePeriod as $dateIndex => $datetime)
            {
                // Xu ly truong hop ngay le / tet
                $holDtKey = Holiday::makeDtKey($datetime);
                if ($holidayInfo[$holDtKey]) {
                    continue;
                }
                //
                $dateType = strtolower($datetime->format('D'));
                // ...
                $tsiModel = app()->make(TimeSheetItem::class);
                $tsiModel->tsDetail()->associate($this);
                $tsiModel->tsSign()->associate($tSheetSign);
                $tsiModel->setColVal('date_index', $dateIndex);
                $tsiModel->setColVal('time_hours', $wrkHours[$dateType]);
                $this->tsItems()->save($tsiModel);
                ++$count;
            }
        }
        return $count;
    }
    
    /**
     * Find records match
     * @param int|string|array $id Record ids
     * @param int|string $tsId Time sheet id
     * @return mixed
     */
    public static function findMatchTSheet($id, $tsId)
    {
        $model = app()->make(static::class);
        $isArr = \is_array($id);
        $id = (array)$id;
        $query = static
            ::whereIn($model->getKeyName(), $id)
            ->where(static::columnName('time_sheet_id'), $tsId)
        ;
        $return = $isArr ? $query->get() : $query->first();
        return $return;
    }

    /**
     * 
     * @return array
     */
    public function getSalaryInfoOfTime(array $options = array())
    {
        // Get, init options
        // Thong tin: so gio lam viec trong ngay
        $workingHoursOfDay = $options['working_hours_of_day'] ?: 1;
        // 
        $dtPeriod = (array)$options['datetime_period'];
        // Thong tin ngay le/tet
        $holidayInfo = (array)$options['holiday_info'];
        // T.gian nghi giua ca trong 1 ngay
        $wrkShiftTl = $wrkShiftTimeline = (array)$options['working_shift_timeline'];
        $wrkShiftTl = [
            'begin1st' => strtotime($wrkShiftTl['begin1st']),
            'end1st' => strtotime($wrkShiftTl['end1st']),
            'begin2nd' => strtotime($wrkShiftTl['begin2nd']),
            'end2nd' => strtotime($wrkShiftTl['end2nd'])
        ];
        

        // Du lieu sum cham cong!
        $keySlrPc = 'slrpc';
        $itemTSheet = [
            // Worktime
            ($prop = 'worktime') => 0,
            // Leave
            ($prop = 'leave_worktime') => 0,
            ($prop = 'leave_sick') => 0,
            ($prop = 'leave_maternity') => 0,
            // OT
            ($prop = 'ot') => 0,
            ($prop = 'ot_no_salary') => 0,
            // Details
            ($propDetailsDate = '_detailsDate') => [],
            ($propDetailsDOW = '_detailsDOW') => [], // date of week
            ($propDetailsDOWUnitDay = '_detailsDOWUnitDay') => [],
        ];
        // $cnt = [];
        foreach ($this->tsItems()->with('tsSign')->get() as $tsItem) {
            // Dl ky hieu cham cong
            $tsSign = $tsItem->tsSign;
            // Thong tin ngay trong thang?
            $dateIndex = $tsItem->colVal('date_index');
            $dt = $dtPeriod[$dateIndex];
            // T.Tin cham cong: t.gian lam viec
            $timeHours = (
                (1 * $tsItem->colVal('time_hours')) / $workingHoursOfDay
            );
            $timeFromToHours = (
                (1 * $tsItem->calTimeByFromTo([
                    'exclude_fr' => $wrkShiftTl['end1st'],
                    'exclude_to' => $wrkShiftTl['begin2nd'],
                ])) / $workingHoursOfDay
            );
            $time = $timeHours + $timeFromToHours;
            // Thong tin: % ti le huong luong
            // $tsSignValue = $tsSign->colVal('value');
            //
            if ($tsSign->isTypeWorkDayFull()) {
                $itemTSheet[$prop = 'worktime'] += $time;
            }
            if ($tsSign->isTypeOT()) {
                $itemTSheet[$prop = 'ot'] += $time;
            }
            if ($tsSign->isTypeOTNoSalary()) {
                $itemTSheet[$prop = 'ot_no_salary'] += $time;
            }
            if ($tsSign->isTypeLeaveNoSalary()) {
                $itemTSheet[$prop = 'leave_worktime'] += $time;
            }
            if ($tsSign->isTypeLeaveSick()) {
                $itemTSheet[$prop = 'leave_sick'] += $time;
            }
            if ($tsSign->isTypeLeaveMaternity()) {
                $itemTSheet[$prop = 'leave_maternity'] += $time;
            }
            // Deatails
            if ($dt && $prop) {
                // by date
                // || init (if empty)
                if (is_null($itemTSheet[$propDetailsDate][$prop])) {
                    $itemTSheet[$propDetailsDate][$prop] = [];
                }
                $itemTSheet[$propDetailsDate][$prop][$dt->format('Y-m-d')] += $time;
                //.end
                // by date of week::
                $dtKey = $dt->format('D');
                $dtKeyWkd = null;
                if ($holidayInfo[Holiday::makeDtKey($dt)]) {
                    $dtKey = Holiday::DT_KEY_HOL;
                } else {
                    if (!($dtKey == 'Sat' || $dtKey == 'Sun')) {
                        $dtKeyWkd = Holiday::DT_KEY_WKD;
                    }
                }
                // ====
                $itemTSheet[$propDetailsDOW][$prop] // init (if empty)
                    = $itemTSheet[$propDetailsDOW][$prop] ?: []
                ;
                $itemTSheet[$propDetailsDOW][$prop][$dtKey] += $time;
                if ($dtKeyWkd) {
                    $itemTSheet[$propDetailsDOW][$prop][$dtKeyWkd] += $time;
                }
                //.end
                // ==== sum 0|1 of items > 0
                $itemTSheet[$propDetailsDOWUnitDay][$prop] // init (if empty)
                    = $itemTSheet[$propDetailsDOWUnitDay][$prop] ?: []
                ;
                $itemTSheet[$propDetailsDOWUnitDay][$prop][$dtKey] += ($timeUnitDay = ($time > 0 ? 1.0 : 0));
                if ($dtKeyWkd) {
                    $itemTSheet[$propDetailsDOWUnitDay][$prop][$dtKeyWkd] += $timeUnitDay;
                }
                unset($timeUnitDay);
                //.end
            }
        }
        // Round values
        foreach ($itemTSheet as $key => &$val) {
            $val = is_numeric($val) ? TimeSheet::round($val) : $val;
        }
        // Return
        return $itemTSheet;
    }

    /**
     * Get detail time sheet
     * @param int|string $month
     * @param int|string $year
     * @param int|string $departmentid
     * @param array $options
     * @return array
     */
    public static function getTimeSheetDetail( array $options = array() )
    {
        // Get, format input()
        // +++ 
        $query = static::whereRaw(1)
            ->where(static::columnName('time_sheet_id'), $options['query'])
            ->where(static::columnName('account_id'), $options['account_id'])
            ->select('time_sheet_detail_id')
            ->get()->first();

        $params =[
            'tsdetail_id' => $query["time_sheet_detail_id"],
            'date'  => $options['date'],
            'riceShiftOT' => $options['riceShiftOT']
        ];
        $timeSheetItem = TimeSheetItem::getTimeSheetItem( $params );
        // dd($return);
        // Return
        return $timeSheetItem;
    }
}