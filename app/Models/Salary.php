<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
/**
 * @class Salary
 */
class Salary extends AbstractModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tbl_salary';

    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const CREATED_AT = 'salary_created_at';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const UPDATED_AT = 'salary_updated_at';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    // const DELETED_AT = 'salary_deleted_at';
    /**
     * @var string Customize the names of the columns used to store the status.
     */
    // const STATUS_COLUMN = 'salary_status';

    /** @var string type 0 (basic) */
    const TYPE_0 = 0;
    /** @var string type 1 (nang suat) */
    const TYPE_1 = 1;
    /** @var string type 10 (co ban tong hop) */
    const TYPE_10 = 10;
    /** @var string type 11 (nang suat tong hop) */
    const TYPE_11 = 11;
    /** @var string type 20 (luong ngoai gio) */
    const TYPE_20 = 20;
    /** @var string type 30 (an trua) */
    const TYPE_30 = 30;
    /** @var string type 40 (com ca) */
    const TYPE_40 = 40;
    /** @var string type 50 (nang suat quy) */
    const TYPE_50 = 50;
    /** @var string type 60 (nang suat quy chi bo sung) */
    const TYPE_60 = 60;
    /** @var string type 70 (nang suat nam) */
    const TYPE_70 = 70;
    /** @var string type 80 (khoan) */
    const TYPE_80 = 80;
    /** @var string type 81 (khoan 1) */
    const TYPE_81 = 81;
    /** @var string type 82 (khoan 2) */
    const TYPE_82 = 82;
    /** @var string type 90 (xếp loại lao động quý) */
    const TYPE_90 = 90;
    /** @var string type 100 (chi khac) */
    const TYPE_100 = 100;
    /** @var string type 110 (tong hop thu nhap nhan vien) */
    const TYPE_110 = 110;
    /** @var string type 120 (tong hop thu nhap phong ban) */
    const TYPE_120 = 120;
    /** @var string type 127 (thue thu nhap nam) */
    const TYPE_127 = 127;
    /**
     * Return type list
     * @return void
     */
    public static function typeList() {
        $list = [
            static::TYPE_0 => 'Lương cơ bản',
            static::TYPE_1 => 'Lương năng suất',
            static::TYPE_10 => 'Lương cơ bản tổng hợp',
            static::TYPE_11 => 'Lương năng suất tổng hợp',
            static::TYPE_20 => 'Lương ngoài giờ',
            static::TYPE_30 => 'Lương ăn trưa',
            static::TYPE_40 => 'Lương cơm ca',
            static::TYPE_50 => 'Lương năng suất quý',
            static::TYPE_60 => 'Lương năng suất quý chi bổ sung',
            static::TYPE_70 => 'Lương năng suất năm',
            static::TYPE_80 => 'Lương khoán',
            static::TYPE_81 => 'Lương khoán kỳ 1',
            static::TYPE_82 => 'Lương khoán kỳ 2',
            static::TYPE_90 => 'Lương xếp loại lao động quý',
            static::TYPE_100 => 'Lương chi khác',
            static::TYPE_110 => 'Lương tổng hợp thu nhập nhân viên',
            static::TYPE_120 => 'Lương tổng hợp thu nhập phòng ban',
            static::TYPE_127 => 'Thuế thu nhập năm',
        ];
        return $list;
    }
    /**
     * Return type list keys
     * @return void
     */
    public static function typeListKeys() {
        $list = [
            static::TYPE_0 => 'basic',
            static::TYPE_1 => 'productivity',
            static::TYPE_10 => 'basic-summary',
            static::TYPE_11 => 'productivity-summary',
            static::TYPE_20 => 'overtime',
            static::TYPE_30 => 'lunch',
            static::TYPE_40 => 'riceshift',
            static::TYPE_50 => 'preproductivity-quarterly',
            static::TYPE_60 => 'productivity-quarterly',
            static::TYPE_70 => 'productivity-yearly',
            static::TYPE_80 => 'fixed',
            static::TYPE_81 => 'fixed-1st',
            static::TYPE_82 => 'fixed-2nd',
            static::TYPE_90 => 'labor-assessment-quarterly',
            static::TYPE_100 => 'others',
            static::TYPE_110 => 'summary-staff',
            static::TYPE_120 => 'summary-organize',
            static::TYPE_127 => 'income-tax-yearly',
        ];
        return $list;
    }
    /**
     * set type 0 (basic)
     * @return void
     */
    public function typeBasic()
    {
        $this->salary_type = static::TYPE_0;
        return $this;
    }
    /**
     * set type 1 (productivity)
     * @return void
     */
    public function typeProductivity()
    {
        $this->salary_type = static::TYPE_1;
        return $this;
    }
    /**
     * set type 10 (basic summary)
     * @return void
     */
    public function typeBasicSummary()
    {
        $this->salary_type = static::TYPE_10;
        return $this;
    }
    /**
     * set type 11 (productivity summary)
     * @return void
     */
    public function typeProductivitySummary()
    {
        $this->salary_type = static::TYPE_11;
        return $this;
    }
    /**
     * set type 20 (overtime)
     * @return void
     */
    public function typeOvertime()
    {
        $this->salary_type = static::TYPE_20;
        return $this;
    }
    /**
     * set type 30 (lunch)
     * @return void
     */
    public function typeLunch()
    {
        $this->salary_type = static::TYPE_30;
        return $this;
    }
    /**
     * set type 40 (riceshift)
     * @return void
     */
    public function typeRiceshift()
    {
        $this->salary_type = static::TYPE_40;
        return $this;
    }
    /**
     * set type 50 (preproductivity quarterly)
     * @return void
     */
    public function typePreproductivityQuarterly()
    {
        $this->salary_type = static::TYPE_50;
        return $this;
    }
    /**
     * set type 60 (productivity quarterly)
     * @return void
     */
    public function typeProductivityQuarterly()
    {
        $this->salary_type = static::TYPE_60;
        return $this;
    }
    /**
     * set type 70 (productivity yearly)
     * @return void
     */
    public function typeProductivityYearly()
    {
        $this->salary_type = static::TYPE_70;
        return $this;
    }
    /**
     * set type 80 (fixed)
     * @return void
     */
    public function typeFixed()
    {
        $this->salary_type = static::TYPE_80;
        return $this;
    }
    /**
     * set type 81 (fixed-1st)
     * @return void
     */
    public function typeFixed1st()
    {
        $this->salary_type = static::TYPE_81;
        return $this;
    }
    /**
     * set type 82 (fixed-2nd)
     * @return void
     */
    public function typeFixed2nd()
    {
        $this->salary_type = static::TYPE_82;
        return $this;
    }
    /**
     * set type 90 (Labor Assessment Quarterly)
     * @return void
     */
    public function typeLaborAssessmentQuarterly()
    {
        $this->salary_type = static::TYPE_90;
        return $this;
    }
    /**
     * set type 100 (others)
     * @return void
     */
    public function typeOthers()
    {
        $this->salary_type = static::TYPE_100;
        return $this;
    }
    /**
     * set type 110 (summary staff)
     * @return void
     */
    public function typeSummaryStaff()
    {
        $this->salary_type = static::TYPE_110;
        return $this;
    }
    /**
     * set type 120 (summary organize)
     * @return void
     */
    public function typeSummaryOrganize()
    {
        $this->salary_type = static::TYPE_120;
        return $this;
    }
    /**
     * set type 127 (income tax yearly)
     * @return void
     */
    public function typeIncomeTaxYearly()
    {
        $this->salary_type = static::TYPE_127;
        return $this;
    }
    /**
     * is type 0 (basic)
     * @return bool
     */
    public function isTypeBasic()
    {
        return $this->salary_type == static::TYPE_0;
    }
    /**
     * is type 1 (productivity)
     * @return bool
     */
    public function isTypeProductivity()
    {
        return $this->salary_type == static::TYPE_1;
    }
    /**
     * is type 0 (basic summary)
     * @return bool
     */
    public function isTypeBasicSummary()
    {
        return $this->salary_type == static::TYPE_10;
    }
    /**
     * is type 11 (productivity summary)
     * @return bool
     */
    public function isTypeProductivitySummary()
    {
        return $this->salary_type == static::TYPE_11;
    }
    /**
     * is type 20 (overtime)
     * @return bool
     */
    public function isTypeOvertime()
    {
        return $this->salary_type == static::TYPE_20;
    }
    /**
     * is type 30 (lunch)
     * @return bool
     */
    public function isTypeLunch()
    {
        return $this->salary_type == static::TYPE_30;
    }
    /**
     * is type 40 (riceshift)
     * @return bool
     */
    public function isTypeRiceshift()
    {
        return $this->salary_type == static::TYPE_40;
    }
    /**
     * is type 50 (productivity quarterly)
     * @return bool
     */
    public function isTypePreproductivityQuarterly()
    {
        return $this->salary_type == static::TYPE_50;
    }
    /**
     * is type 60 (productivity quarterly)
     * @return bool
     */
    public function isTypeProductivityQuarterly()
    {
        return $this->salary_type == static::TYPE_60;
    }
    /**
     * is type 70 (productivity yearly)
     * @return bool
     */
    public function isTypeProductivityYearly()
    {
        return $this->salary_type == static::TYPE_70;
    }
    /**
     * is type 80 (fixed)
     * @return bool
     */
    public function isFixed()
    {
        return $this->salary_type == static::TYPE_80;
    }
    /**
     * is type 81 (fixed-1st)
     * @return bool
     */
    public function isFixed1st()
    {
        return $this->salary_type == static::TYPE_81;
    }
    /**
     * is type 82 (fixed-2nd)
     * @return bool
     */
    public function isFixed2nd()
    {
        return $this->salary_type == static::TYPE_82;
    }
    /**
     * is type 90 (Labor Assessment Quarterly)
     * @return bool
     */
    public function isTypeLaborAssessmentQuarterly()
    {
        return $this->salary_type == static::TYPE_90;
    }
    /**
     * is type 100 (others)
     * @return bool
     */
    public function isTypeOthers()
    {
        return $this->salary_type == static::TYPE_100;
    }
    /**
     * is type 110 (summary staff)
     * @return bool
     */
    public function isTypeSummaryStaff()
    {
        return $this->salary_type == static::TYPE_110;
    }
    /**
     * is type 120 (summary organize)
     * @return bool
     */
    public function isTypeSummaryOrganize()
    {
        return $this->salary_type == static::TYPE_120;
    }
    /**
     * is type 127 (income tax yearly)
     * @return bool
     */
    public function isTypeIncomeTaxYearly()
    {
        return $this->salary_type == static::TYPE_127;
    }

    /** @var string PIT flag 0 (khong tinh thue TNCN) */
    const PIT_FLAG_0 = 0;
    /** @var string PIT flag 1 (co tinh thue TNCN) */
    const PIT_FLAG_1 = 1;
    /**
     * Return PIT flag list
     * @return array
     */
    public static function pitFlagList() {
        $list = [
            static::PIT_FLAG_1 => 'Có',
            static::PIT_FLAG_0 => 'Không',
        ];
        return $list;
    }
    /**
     * Set PIT flag: yes (1)
     * @return this
     */
    public function PITFlagYes()
    {
        $this->salary_pit_flag = static::PIT_FLAG_1;
        return $this;
    }
    /**
     * Set PIT flag: no (0)
     * @return this
     */
    public function PITFlagNo()
    {
        $this->salary_pit_flag = static::PIT_FLAG_0;
        return $this;
    }
    /**
     * Check PIT flag: yes (1) ?!
     * @return bool
     */
    public function isPITFlagYes()
    {
        return static::PIT_FLAG_1 == $this->salary_pit_flag;
    }
    /**
     * Check PIT flag: no (0) ?!
     * @return bool
     */
    public function isPITFlagNo()
    {
        return static::PIT_FLAG_0 == $this->salary_pit_flag;
    }

    /**
     * Primary key
     * @var string
     */
    protected $primaryKey = 'salary_id';

    /**
     * Get salary_from data
     * @return string
     */
    public function salaryFrom()
    {
        $value = $this->salary_from;
        if (!$value) {
            $value = "{$this->salary_year}-{$this->salary_month}-01 00:00:00";
        }
        return $value;
    }
    /**
     * Get salary_to data
     * @return string
     */
    public function salaryTo()
    {
        $value = $this->salary_to;
        if (!$value) {
            $value = strtotime("{$this->salary_year}-{$this->salary_month}-01");
            $value = date('Y-m-t 23:59:59', $value);
        }
        return $value;
    }
    /**
     * Get salary from-to data
     * @return \DatePeriod
     */
    public function salaryPeriod()
    {
        $from = new \DateTime($this->salaryFrom());
        $to = new \DateTime($this->salaryTo());
        $interval = \DateInterval::createFromDateString('1 day');
        $period = new \DatePeriod($from, $interval, $to);
        return $period;
    }

    /**
     * Calculate work days of month
     *
     * @param array $options
     * @return double
     */
    public function calWorktime(array $options = array())
    {
        $workDays = WorkingShift::workingDaysOfMonth($this->salaryPeriod(), null, [
            'department_id' => $this->colVal('department_id')
        ]);
        return $workDays;
    }

    /**
     * Column datafield prefix
     * @var string
     */
    public static $columnPrefix = 'salary_';

    /**
     * jqx's grid columns & datafields!
     * @var array
     */
    protected static $jqxGridColumns = [
        [ 'datafield' => 'id' ],
        [ 'datafield' => 'department_id' ], // @TODO: Fix overwrite when form reset
        [ 'datafield' => 'month' ],
        [ 'datafield' => 'year' ],
        [
            'text' => 'Tháng / Năm',
            'datafield' => [['month_year']],
            'width' => 164,
            'cellsalign' => 'right',
            'filtertype' => 'range',
        ],
        /* [
            'text' => 'Tháng',
            'datafield' => 'month',
            'width' => 80,
            'cellsalign' => 'right',
        ],
        [
            'text' => 'Năm',
            'datafield' => 'year',
            'width' => 80,
            'cellsalign' => 'right',
        ], */
        [
            'text' => 'Tên bảng lương',
            'datafield' => 'name',
        ],
        [
            'text' => 'Số Chứng từ',
            'datafield' => [ 'receipt' ],
            'hidden' => true,
        ],
        [ 'datafield' => 'type' ],
        /* [ 'datafield' => 'company_id' ],
        [
            'text' => 'Công ty',
            'datafield' => [ ['company_name'] ],
            'sortable' => false,
            'minwidth' => 256,
        ], */
        [
            'text' => 'Đơn vị',
            'datafield' => [ 'department_id_text' ],
            // 'minwidth' => 256,
            'sortable' => false,
        ],
        [
            'text' => 'PIT?',
            'datafield' => ['pit_flag', [
                'type' => 'int'
            ]],
            'width' => 64,
            'cellsalign' => 'right',
            'sortable' => false,
            'columntype' => 'checkbox',
            'filtertype' => 'bool',
            'hidden' => true,
        ],
        [
            'text' => 'Tổng tiền lương',
            'datafield' => ['subtotal', [
                'type' => 'number'
            ]],
            'width' => 128,
            'cellsalign' => 'right',
            'sortable' => false,
            'filtertype' => 'number',
            'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}'
        ],
        [
            'text' => 'Ghi chú',
            'sortable' => false,
            'filterable' => false,
            'minwidth' => 128,
            'datafield' => 'note',
        ],
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
        if (in_array($dfd, [
                'month_year',
                'subtotal',
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
     * Get company info.
     * @return Company
     */
    public function company()
    {
        return $this->belongsTo(Company::class, static::columnName('company_id'));
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
     * @override
     * @param array $options = Array
     * @return mixed
     */
    public function save(array $options = array())
    {
        // Process wth relationships
        if (!$this->{$this->columnName('company_id')} 
            && $this->department
        ) {
            $this->{$this->columnName('company_id')}
                = $this->department->{$this->department->columnName('company_id')}
            ;
        }
        //.end
        return parent::save($options);
    }

    /**
     * Get salary config.
     * @return SalaryConfig
     */
    public function salaryConfig()
    {
        return $this->hasOne(SalaryConfig::class, SalaryConfig::columnName('salary_id'));
    }

    /**
     * Get/set salary details.
     * @return mixed
     */
    public function salaryDetails()
    {
        return $this->hasMany(SalaryDetail::class, SalaryDetail::columnName('salary_id'));
    }

    /**
     * Self associate salary config.
     * @return boolean
     */
    public function selfAssociateSalaryConfig()
    {
        if (is_null($this->salaryConfig))
        {
            $salaryConfig = SalaryConfig::clone1stDefault([
                'department_id' => $this->colVal('department_id')
            ]);
            if ($salaryConfig) {
                $this->salaryConfig()->save($salaryConfig);
                return $salaryConfig;
            }
        }
    }

    /**
     * Self assign data
     * @return this
     */
    public function selfAssignData()
    {
        // 
        // +++ T.Tin cham cong: so ngay lam trong thang!
        $this->setColVal('worktime', $worktime = $this->calWorktime());
        //
        return $this;
    }
 
    /**
     * Helper for populateSalaryDetails
     * @param array $options An array of options
     * @return mixed
     */
    public function psd_getSalaryInfoOfTime(array $options = array())
    {
        // Thong tin cham cong.
        $salaryTSInfo = (array)TimeSheet::getSalaryInfoOfTime(
            $this->colVal('month'),
            $this->colVal('year'),
            [
                'fetch_tssign_avg_values' => true,
                'department_id' => $this->colVal('department_id')
            ]
        ); 
        //
        return $salaryTSInfo;
    }

    /**
     * Self populate details records
     * @param array $options An array of options
     * @return array
     */
    public function populateSalaryDetails(array $options = array())
    {
        // Fetch data
        $accounts = Account::findAllByOrganizationUnit($departmentId = $this->colVal('department_id'));
        // +++

        // Thong tin chi tiet
        if (!empty($accounts))
        {
            //thông tin tháng năm bảng lương
            $date = $this->colVal('year').'-'.$this->colVal('month');
            // Thong tin cham cong.
            $salaryTSInfo = $this->psd_getSalaryInfoOfTime();
            // Thong tin salary configs.
            $salaryConfig = $this->salaryConfig;
            // Detail model class
            $modelClass = get_class($this->salaryDetails()->getRelated());
            // Detail options
            $detailOptions = $options['detail_options'] ?? [];
            //
            foreach ($accounts as $account)
            {
                //kiểm tra type nằm trong config không
                if(!in_array($this->colVal('type'), config('salary')['type'])){
                    // kiểm tra nhân viên đã ngưng làm chưa
                    if($account['account_end_time'] > strtotime($date) || $account['account_status'] == 1){
                        $model = app()->make($modelClass);
                        $model->assignSalaryAndTimeSheetInfo($salaryConfig, $account, $salaryTSInfo, $detailOptions);
                        $model->save();
                        $this->salaryDetails()->save($model);
                    }
                    
                }else{
                    $model = app()->make($modelClass);
                    $model->assignSalaryAndTimeSheetInfo($salaryConfig, $account, $salaryTSInfo, $detailOptions);
                    $model->save();
                    $this->salaryDetails()->save($model);
                }

            }
        }
        return count($accounts);
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
     * Make a list of days in month
     * @param array $options An array of options
     * @return array
     */
    public static function makeListDay(array $options = array())
    {
        $return = [];
        for ($i = 1; $i <= 31; $i++) {
            $return[$i] = "Ngày {$i}";
        }
        return $return;
    }

    /**
     * Make a list of months in year
     * @param array $options An array of options
     * @return array
     */
    public static function makeListMonth(array $options = array())
    {
        $return = [];
        for ($i = 1; $i <= 12; $i++) {
            $return[$i] = "Tháng {$i}";
        }
        return $return;
    }

    /**
     * Make a list of quarters in year
     * @param array $options An array of options
     * @return array
     */
    public static function makeListQuarter(array $options = array())
    {
        $return = [];
        for ($i = 1; $i <= 4; $i++) {
            $return[$i] = "Quý {$i}";
        }
        return $return;
    }

    /**
     * Make a list of years
     * @param array $options An array of options
     * @return array
     */
    public static function makeListYear(array $options = array())
    {
        $return = [];
        $curY = intval(date('Y'));
        for ($i = $curY; $i >= $curY - 5; $i--) {
            $return[$i] = "{$i}";
        }
        return $return;
    }

    /**
     * 
     *
     * @param array $data Jqx request payload
     * @return void
     */
    public function jqxFetchRecordList(array $data, &$qB = null, &$totalRowsQB = null, array $options = array())
    {
        // Define vars
        // +++
        $departmentList = Department::makeList();
        $departmentListFlip = array_flip($departmentList);
        // +++
        $typeList = static::typeList();
        $typeListFlip = array_flip($typeList);
        //Roles
        $roles = Roles::checkRoles();
        $permissons = Permissions::checkPermissons();
        // Prepare the data
        $qB = $this->qBFromJqxRequestPayload($data, $totalRowsQB, [
            // Alter query builder
            'queryBuilder' => function(\Illuminate\Database\Eloquent\Builder $qB) 
            use ($roles, $departmentList, $permissons) {
                $models = [
                    // 'com' => app()->make(Company::class),
                    'dep' => app()->make(Department::class),
                ];
                $modelTable = $this->getTable();
                // Join department
                $qB->leftJoin(
                    ($tableDep = $models['dep']->getTable())
                    , ("{$tableDep}." . ($pK = $models['dep']->getKeyName()))
                    , '=', "{$modelTable}." . $this->columnName($pK)
                );
                /* 
                // Join company
                $qB->leftJoin(
                    ($tableCom = $models['com']->getTable())
                    , ("{$tableCom}." . ($pK = $models['com']->getKeyName()))
                    , '=', "{$tableDep}." . $models['dep']->columnName($pK)
                ); */
                if(!$permissons['permissonsAdmin']){
                    //kiểm tra quyền 
                    if($roles['rolesHeadDepartment'] || $roles['rolesStaff']){
                        $depArrID = array_keys($departmentList);
                        $qB->whereIn("{$tableDep}.".$pK, $depArrID);
                    }
                }

                // Select
                $qB->select([
                    "{$modelTable}.*",
                    // "{$tableCom}.company_name",
                    // "{$tableDep}.department_name",
                ]);
                // Filter:
                // +++ by type?
                /* if (!is_null($this->salary_type)) {
                    $qB->where($this->columnName('type'), $this->salary_type);
                } */
            },
            //
            'where' => function(&$join, &$where, &$filter, &$condition, &$value)
                use ($departmentListFlip) {
                    if (($prop = $this->columnName('department_id')) . '_text' === $filter['field']) {
                        $value = $filter['value'] = $departmentListFlip[$value];
                    }
                    // Filter type 'range'...
                    if ('month_year' === $filter['field']) {
                        //
                        $cols = [
                            ($prop = 'month') => $this->columnName($prop),
                            ($prop = 'year') => $this->columnName($prop),
                        ];
                        // Flags checK
                        // +++ this is salary type = 'quarterly'
                        $isQuarterly = $this->isTypePreproductivityQuarterly()
                            || $this->isTypeProductivityQuarterly()
                        ;
                        // +++ this is salary type = 'yearly'
                        $isYearly = $this->isTypeProductivityYearly();
                        //
                        $value = static::parseDateTimeJqx($value);
                        $year = (1 * $value->format('Y'));
                        $month = (1 * $value->format('m'));
                        // Case: loai bang luong 'nam'.
                        if ($isYearly) {
                            $filter['field'] = static::columnName("year");
                            $filter['value'] = $value = $year;
                        // Case: bang luong 'thang', 'quy'
                        } else {
                            // Case: loai bang luong 'quy' --> format d.lieu 'thang' thanh 'quy'.
                            if ($isQuarterly) {
                                $month = ceil($month / 3); // @TODO: formular "quarterly"
                            }
                            $join->whereRaw("(`{$cols['year']}` * 100 + `{$cols['month']}`) {$condition} " . ($year * 100 + $month));
                            return false;
                        }
                    }
                }
            ,
        ]);
        // var_dump($data);die($qB->toSql());
        // Format data
        $collect = $qB->get();
        // +++
        $arrID = $collect->pluck($this->getKeyName())->toArray();
        $salarySubtotal = static::fetchSumSalarySubtotal($arrID);
        // +++
        $rows = $collect->map(function($row, $idx)
            use ($departmentList, $salarySubtotal) {
                //
                $row->setColVal(($prop = 'department_id') . '_text', $departmentList[$row->colVal($prop)]);
                // +++ thang/nam
                $row->{($prop = 'month_year')} = ($row->colVal('month') . ' / ' . $row->colVal('year'));
                // +++ subtotal
                $row->setColVal($prop = 'subtotal', $salarySubtotal[$row->id()]);
                //
                return $row;
            })
        ;
        //.end
        
        // Return
        return $rows;
        
	}
    
    /**
     * Find records match
     * @param int|string|array $id Record ids
     * @param int|string $type Type
     * @return mixed
     */
    public static function findMatchType($id, $type)
    {
        $model = app()->make(static::class);
        $isArr = \is_array($id);
        $id = (array)$id;
        $query = static
            ::whereIn($model->getKeyName(), $id)
            ->where(static::columnName('type'), $type)
        ;
        $return = $isArr ? $query->get() : $query->first();
        return $return;
    }
    
    /**
     * Find records match
     * @param int|string|array $departmentId 
     * @param int|string $month
     * @param int|string $year
     * @return mixed
     */
    public static function findByDepartmentAndTime($departmentId, $month, $year, array $options = array())
    {
        $qB = static::whereRaw(1)
            ->where(static::columnName('department_id'), $departmentId)
            ->where(static::columnName('month'), $month)
            ->where(static::columnName('year'), $year)
        ;
        // Fetch with details?
        if ($options['with_details']) {
            $qB->with('salaryDetails')
            ->orderBy(static::columnName('id'),'DESC')->limit(1);//lấy 1 salary mới nhất
        }
        // Return
        return $qB->get();
    }

    /**
     * Calculate summary of columns
     * @return double
     */
    public function calSummary()
    {
        $summary = [];
        $columns = [];
        $colPrefix = SalaryDetail::$columnPrefix;
        $colPrefix = [
            'id' => "_id",
            'salary' => "{$colPrefix}salary_",
            'time' => "{$colPrefix}time_",
        ];
        // Duyet tat ca record details --> sum.
        foreach ($this->salaryDetails as $salaryDetail)
        {
            // Duyet qua columns, xac dinh columns co the summary?
            if (empty($columns))
            {
                foreach ($salaryDetail->attributes as $attr => $__val__)
                {
                    if ((strpos($attr, $colPrefix['salary']) === 0
                            || strpos($attr, $colPrefix['time']) === 0
                        ) && (substr($attr, -3) != $colPrefix['id'])
                    ) {
                        $columns[] = $attr;
                    }
                }
            }
            //.end
            foreach ($columns as $col)
            {
                $summary[$col] += (1 * $salaryDetail->{$col});
            }
        }
        // Return
        return $summary;
    }

    /**
     * Fetrch + calculate sum of salary's subtotal (based on details)
     * @param int|string|array $salaryIds 
     * @param array $options An array of options
     * @return array
     */
    public static function fetchSumSalarySubtotal($salaryIds, array $options = [])
    {
        // Get, format input(s)
        $salaryIds = (array)$salaryIds;

        //
        $cols = [
            ($prop = 'salary_id') => SalaryDetail::columnName($prop),
            ($prop = 'salary_subtotal') => SalaryDetail::columnName($prop)
        ];
        $qB = SalaryDetail::selectRaw(
                "SUM(`{$cols['salary_subtotal']}`) as `salary_subtotal`"
            )
            ->addSelect([
                $cols['salary_id']
            ])
            ->groupBy($cols['salary_id'])
            ->whereIn($cols['salary_id'], $salaryIds)
        ;
        // die($qB->toSql());

        // Fetch data
        $collect = $qB->get();
        $result = $collect->mapWithKeys(function($slrEnt) {
            return [$slrEnt->salary_detail_salary_id => $slrEnt->salary_subtotal];
        })->toArray();

        // Return
        // dd($result);
        return $result;
    }

    /**
     * Fetch du lieu luong tong hop cua tung nhan vien
     * @param $month int|string
     * @param $year int|string
     * @param $options array An array of options
     * @return array
     */
    public static function fetchAccountAllSalary($month, $year, array &$options = [])
    {
        // Define vars
        $models = [
            'slr' => app()->make(static::class),
            'slrD' => app()->make(SalaryDetail::class),
            'acc' => app()->make(Account::class),
        ];
        $tblSlr = $models['slr']->getTable();
        $tblSlrDetail = $models['slrD']->getTable();
        $tblAccount = $models['acc']->getTable();
        // +++ loai bang luong: nang suat quy[, chi bo sung]
        $slrTypeProductivityQuarterlyArr = [
            static::TYPE_50,
            static::TYPE_60,
        ];
        $slrTypeProductivityQuarterlyStr = implode(', ', $slrTypeProductivityQuarterlyArr);
        // +++ loai bang luong: nang suat nam
        $slrTypeProductivityYearlyArr = [
            static::TYPE_70,
        ];
        $slrTypeProductivityYearlyStr = implode(', ', $slrTypeProductivityYearlyArr);

        // Format input(s), option(s)
        $month = intval($month);
        $year = intval($year);
        $quarterly = ceil($month / 3);

        // Build query...
        $qB = static::withoutGlobalScope('type')
            -> select([
                "{$tblSlr}.salary_id",
                "{$tblSlr}.salary_type",
                "{$tblSlr}.salary_pit_flag",
                // "{$tblSlr}.salary_month",
                // "{$tblSlr}.salary_year",
                // "{$tblSlr}.salary_created_at",
                "{$tblSlrDetail}.*",
                "{$tblSlrDetail}.salary_detail_account_id AS account_id",
                "{$tblAccount}.account_department_id AS department_id",
                "{$tblSlrDetail}.salary_detail_salary_subtotal AS salary_subtotal",
            ])
            // Join SalaryScaleLevel
            ->leftJoin(
                $tblSlrDetail
                , "{$tblSlr}." . ($pk = $models['slr']->getKeyName($pK))
                , '=', ("{$tblSlrDetail}." . $models['slrD']->columnName($pk))
            )
            // Join Account
            ->leftJoin(
                $tblAccount
                , "{$tblAccount}." . ($pk = $models['acc']->getKeyName($pK))
                , '=', ("{$tblSlrDetail}." . $models['slrD']->columnName($pk))
            )
            // Filter(s)
            ->whereRaw(<<<SQL
(1 = IF(
    /* case: bang luong nang suat quy[, chi bo sung] */
    {$tblSlr}.`salary_type` IN ({$slrTypeProductivityQuarterlyStr}), ({$tblSlr}.`salary_month` = {$quarterly} AND {$tblSlr}.`salary_year` = {$year} AND MONTH({$tblSlr}.`salary_created_at`) = {$month} AND YEAR({$tblSlr}.`salary_created_at`) = {$year}),
    IF (
        /* case: bang luong nang suat nam */
        {$tblSlr}.`salary_type` IN ({$slrTypeProductivityYearlyStr}), ({$tblSlr}.`salary_year` = {$year} AND MONTH({$tblSlr}.`salary_created_at`) = {$month} AND YEAR({$tblSlr}.`salary_created_at`) = {$year}),
        /* case: cac bang luong khac, tinh theo thang/nam */
        ({$tblSlr}.`salary_month` = {$month} AND {$tblSlr}.`salary_year` = {$year})
    )
))
SQL
)
        ;
        // Add filter(s)
        // +++
        if (!(true === $options['include_salary_summary'])) {
            $qB->whereNotIn("salary_type", [
                static::TYPE_110,
                static::TYPE_120
            ]);
        }
        // +++
        if (isset($options['account_id'])) {
            $qB->whereIn("salary_detail_account_id", (array)$options['account_id']);
        }
        // die($qB->toSql());
        //SẮP XẾP THEO CHỨC DANH
        $qB->orderBy("{$tblAccount}.account_position_id", 'ASC');
        // Fetch data
        $collect = $qB->get();
        // +++ extend data: salary with PIT
        $accountIds = [];
        $typeListKeys = static::typeListKeys();
        $collect->map(function($slrEnt) use (&$accountIds, $typeListKeys) {
            // +++ Tong thu nhap phai tra dung tinh thue!
            $slrEnt->salary_pit = 0;
            if ($slrEnt->isPITFlagYes()) {
                $slrEnt->salary_pit = SalaryRule::calcPITSumByRuleType(
                    $slrType = $typeListKeys[$slrEnt->salary_type],
                    function($col) use ($slrEnt) {
                        return $slrEnt->{SalaryDetail::columnName($col)};
                    }
                );
            }
            // Save account's id for later uses...
            $accountIds[] = $slrEnt->account_id;
        });
        unset($typeListKeys);
        // +++ fetch account's salary reductions?!
        if (array_key_exists(($optKey = 'account_salary_reductions'), $options)) {
            $options[$optKey] = static::fetchAccountSalaryReductions($accountIds);
        }
        unset($accountIds);

        // Format output?
        $result = [];
        $formatter = (isset($options['formatter']) && is_callable($options['formatter']))
            ? $options['formatter']
            : function($slrEnt) use (&$result) {
                $result[$slrEnt->account_id] = $result[$slrEnt->account_id] ?: [];
                $result[$slrEnt->account_id][] = $slrEnt->only([
                    'salary_id',
                    'salary_type',
                    'salary_pit_flag',
                    'account_id',
                    'department_id',
                    'salary_subtotal',
                    'salary_pit'
                ]);
            }
        ;
        $collect->map($formatter);

        // Return
        return $result;
    }

    /**
     * Fetch du lieu luong tong hop cua tung don vi (cong ty /phong ban)
     * @param $month int|string
     * @param $year int|string
     * @param $options array An array of options
     * @return array
     */
    public static function fetchDepartmentAllSalary($month, $year, array $options = [])
    {
        // Get, format input(s)
        // +++ 
        $departmentIds = null;
        if (isset($options['department_id'])) {
            $departmentIds = array_keys(
                (array)Department::findAllDescendant($departmentId = $options['department_id'])
            );
        }

        // Fetch data
        $result = [];
        $fetchAccountAllSalaryOpts = [
            /**
             * @var Closure
             * @param $slrEnt 
             * @return void
             */
            'formatter' => function($slrEnt) use (&$result, $departmentIds) {
                $depId = $slrEnt->department_id;
                // Filter: by department..!
                if ($depId && is_array($departmentIds) && !in_array($depId, $departmentIds)) {
                    return;
                }
                $result[$depId] = $result[$depId] ?: [];
                $result[$depId][] = $slrEnt->only([
                    'salary_id',
                    'salary_type',
                    'account_id',
                    'department_id',
                    'salary_subtotal',
                    'salary_pit'
                ]);

            }
        ];
        static::fetchAccountAllSalary($month, $year, $fetchAccountAllSalaryOpts);

        // Return;
        return $result;
    }

    /**
     * Fetch + calculate account's reductions (cac khoan giam tru cua nhan vien)
     * @param int|string|array $accountIds An array of account's id
     * @param array $options An array of options
     * @return mixed
     */
    public static function fetchAccountSalaryReductions($accountIds, array $options = array())
    {
        // Debug only
        // $accountIds = Account::pluck(Account::columnName('id'))->toArray();

        // Define var(s)
        $result = [];
        // +++
        $colPrefix = Account::columnName('');
        // +++
        $item = [];

        //
        $accounts = Account::select([
            "{$colPrefix}id",
            "{$colPrefix}department_id",
            "{$colPrefix}salary_dependents_number",
            "{$colPrefix}salary_dependents_amount",
        ])->find($accountIds);
        foreach ($accounts as $account) {
            // 
            $item['PIT'] = 0;
            $item[$prop = 'salary_dependents_number'] = 1 * $account->colVal($prop);
            $item[$prop = 'salary_dependents_amount'] = 1 * $account->colVal($prop);
            // T.Tin quy dinh luong
            $slrConf = SalaryConfig::find1stDefault([
                'department_id' => $account->colVal('department_id')
            ]);
            if ($slrConf) {
                $item['PIT'] = (1 * $slrConf->colVal('PIT'));
                if (!$item['salary_dependents_amount']) {
                    $item['salary_dependents_amount'] = (1 * $slrConf->colVal('PIT_dependencer'));
                }
            }
            $result[$account->id()] = [
                0 => ($item['PIT'] + (
                    $item['salary_dependents_number'] * $item['salary_dependents_amount']
                )),
                'pit' => $item['PIT'],
                'dependents' => [
                    'number' => $item['salary_dependents_number'],
                    'amount' => $item['salary_dependents_amount']
                ]
            ];
        }
        unset($account, $item, $slrConf);

        // Return;
        return $result;
    }

    /**
     * Fetch du lieu thue cua tung nhan vien
     * @param $year int|string
     * @param $options array An array of options
     * @return array
     */
    public static function fetchTaxAccountAllSalary($year, array &$options = [])
    {
        // Define vars
        $models = [
            'slr' => app()->make(static::class),
            'slrD' => app()->make(SalaryDetail::class),
            'acc' => app()->make(Account::class),
        ];
        $tblSlr = $models['slr']->getTable();
        $tblSlrDetail = $models['slrD']->getTable();
        $tblAccount = $models['acc']->getTable();

        // +++ loai bang luong: cơ bản,tổng thu nhập nhân viên
        $slrTypeBascicAndSummaryStaffArr = [
            static::TYPE_110,
            static::TYPE_0,
        ];
        // Format input(s), option(s)
        $year = intval($year);
        
        // Build query...
        $qB = static::withoutGlobalScope('type')
            -> select([
                "{$tblSlr}.salary_id",
                "{$tblSlr}.salary_type",
                "{$tblSlr}.salary_pit_flag",
                "{$tblSlr}.salary_month",
                "{$tblSlrDetail}.*",
                "{$tblSlrDetail}.salary_detail_account_id AS account_id",
                "{$tblAccount}.account_department_id AS department_id",
                "{$tblSlrDetail}.salary_detail_salary_subtotal AS salary_subtotal",
            ])
            // Join SalaryScaleLevel
            ->leftJoin(
                $tblSlrDetail
                , "{$tblSlr}." . ($pk = $models['slr']->getKeyName($pK))
                , '=', ("{$tblSlrDetail}." . $models['slrD']->columnName($pk))
            )
            // Join Account
            ->leftJoin(
                $tblAccount
                , "{$tblAccount}." . ($pk = $models['acc']->getKeyName($pK))
                , '=', ("{$tblSlrDetail}." . $models['slrD']->columnName($pk))
            );

        $qB->whereIn("{$tblSlr}.salary_type", $slrTypeBascicAndSummaryStaffArr )
        ->where("{$tblSlr}.salary_year", $year )
        // ->whereIn("{$tblSlr}.salary_month",(array)$options['monthTax'])
        ->where("{$tblSlrDetail}.salary_detail_account_id", $options['account_id']);
        
        //SẮP XẾP THEO CHỨC DANH
        $qB->orderBy("{$tblAccount}.account_position_id", 'ASC');
        
        // Fetch data
        $collect = $qB->get();
        // +++ fetch account's salary reductions?!
        if (array_key_exists(($optKey = 'account_salary_reductions'), $options)) {
            $options[$optKey] = static::fetchAccountSalaryReductions((array)$options['account_id']);
        }

        // Format output?
        $result = [];
        $formatter = function($slrEnt) use (&$result) {
                $result[] = $slrEnt->only([
                    'salary_id',
                    'salary_month',
                    'salary_type',
                    'salary_pit_flag',
                    'account_id',
                    'department_id',
                    'salary_subtotal',
                    'salary_detail_salary_deduction_insurance',
                    'salary_pit',
                    'salary_detail_salary_pit',
                    'salary_detail_salary_deduction_pit',
                    'salary_detail_pit',
                ]);
            }
        ;
        
        $collect->map($formatter);
        // Return
        return $result;
    }

    /**
     * Helper: return salary's full name
     * @param array $options An array of options
     * @return string
     */
    public function getFullName(array $options = array())
    {
        $typeList = static::typeList();
        $fullname = $typeList[$type = $this->colVal('type')] ?? '';
        // Case: quarterly
        if ($this->isTypePreproductivityQuarterly()
            || $this->isTypeProductivityQuarterly()
        ) {
            $fullname .= (' quý ' . $this->colVal('month') . '/' . $this->colVal('year'));
        // Case: yearly
        } else if ($this->isTypeProductivityYearly()) {
            $fullname .= (' ' . $this->colVal('year'));
        // Case: others
        } else {
            $fullname .= (' ' . $this->colVal('month') . '/' . $this->colVal('year'));
        }
        return $fullname;
    }
}