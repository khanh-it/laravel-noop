<?php
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @class Account
 */
class Account extends AbstractModel
{
    use SoftDeletes;
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tbl_account';

    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    // const CREATED_AT = 'account_created_at';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    // const UPDATED_AT = 'account_updated_at';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const DELETED_AT = 'account_deleted_at';
    /**
     * @var string Customize the names of the columns used to store the status.
     */
    // const STATUS_COLUMN = 'salary_status';

    /**
     * Salary fixed (luong khoan) type (day - tinh theo ngay) 0
     * @var int 0
     */
    const SLR_FIX_TYPE_0 = 0; 
    /**
     * Salary fixed (luong khoan) type (month - tinh theo thang) 1
     * @var int 1
     */
    const SLR_FIX_TYPE_1 = 1;
    /**
     * 
     * @return array
     */
    public static function getSlrFixedTypeList()
    {
        return [
            static::SLR_FIX_TYPE_0 => 'Ngày',
            static::SLR_FIX_TYPE_1 => 'Tháng'
        ];
    }

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'account_code',
        'account_title',
        'account_username',
        'account_password',
        'account_group_id',
        'account_department_id',
        'account_status',
        'account_image',
        'account_timekeeping_code',
        'account_first_name',
        'account_last_name',
        'account_fullname',
        'account_gender',
        'account_birthday_time',
        'account_birthday_place',
        'account_id_number',
        'account_id_number_time',
        'account_id_number_place',
        'account_passport_number',
        'account_passport_create_time',
        'account_passport_end_time',
        'account_passport_place',
        'account_married_id',
        'account_family_element_id',
        'account_element_id',
        'account_ethnic_id',
        'account_religion_id',
        'account_nation',
        'account_country_id',
        'account_culture',
        'account_target',
        'account_strength',
        'account_weaknesses',
        'account_interests',
        'account_address_often',
        'account_address_often_district_id',
        'account_address_now',
        'account_city',
        'account_district',
        'account_address_now_district_id',
        'account_position_id',
        'account_salary_position_money_quarterly',
        'account_trial_time',
        'account_receipt_time',
        'account_direct_manage_account_id',
        'account_indirect_manage_account_id',
        'account_labor_book',
        'account_tax',
        'account_work_status',
        'account_work_status_reason',
        'account_end_time',
        'account_end_time_account_id',
        'account_proselytism_time',
        'account_proselytism_place',
        'account_proselytism_position_id',
        'account_union_time',
        'account_union_position_id',
        'account_union_place',
        'account_army_create_time',
        'account_army_position_id',
        'account_army_level_id',
        'account_army_type_id',
        'account_army_unit',
        'account_army_end_time',
        'account_army_reason',
        'account_injury_start_time',
        'account_injury_rank',
        'account_injury_rate',
        'account_injury_mode',
        'account_day_leave_number',
        'account_nick',
        'account_foreign_language',
        'account_cv',
        'account_com_email',
        'account_com_email_password',
        'account_server_type',
        'account_sign',
        'account_pop3',
        'account_pop3_port',
        'account_pop3_ssl',
        'account_smtp',
        'account_smtp_port',
        'account_leave_message_on_server',
        'account_require_authentication',
        'account_smtp_ssl',
        'account_rating_avg',
        'account_note',
        'account_create_account_id',
        'account_create_time',
        'account_not_deleted',
        'account_delete_account_id',
        'account_delete_time',
        'account_footnote',
        'account_share_list_id',
        'account_reset_pass',
        'account_desk_phone',
        'account_mobile_phone',
        'account_number',
        'account_address_often_country_id',
        'account_address_now_country_id',
        'account_address_often_city_id',
        'account_address_now_city_id',
        'account_salary_scale_level_id',
        'account_salary_department_scale_level',
        'account_salary_basic',
        'account_salary_insurance',
        'account_salary_minimum_time',
        'account_salary_monthly_productivity_rate',
        'account_salary_quarterly_productivity',
        'account_salary_year_productivity',
        'account_salary_products',
        'account_salary_monthly_coefficient',
        'account_salary_responsibility_allowance_coefficient',
        'account_salary_responsibility_allowance',
        'account_salary_dependents_number',
        'account_salary_dependents_amount',
        'account_salary_tax',
        'account_salary_additional_corporate'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Primary key
     *
     * @var string
     */
    protected $primaryKey = 'account_id';

    /**
     * Column datafield prefix
     * @var string
     */
    protected static $columnPrefix = 'account_';

    /**
     * jqx's grid columns & datafields!
     * @var array
     */
    protected static $jqxGridColumns = [
        [ 'datafield' => 'id' ],
        [
            'text' => 'Mã nhân viên',
            'datafield' => 'code',
            'width' => 90,
            'pinned' => true,
        ],
        [
            'text' => 'Họ và tên',
            'datafield' => 'fullname',
            'width' => 180,
            'pinned' => true,
        ],
        [
            'text' => 'Giới tính',
            'datafield' => 'gender',
            'width' => 60,
        ],
        [
            'text' => 'TK đăng nhập',
            'datafield' => 'username',
        ],
        [ 'datafield' => 'department_id' ], // required for foreign source
        [
            'text' => 'Phòng ban',
            'datafield' => [['department_name']],
        ],
        /* [
            'text' => 'Phòng ban',
            'datafield' => ['department_name', [
                'value' => 'account_department_id',
                'values' => [
                    'source' => '{!!departmentSource.localdata!!}',
                    'value' => 'department_id',
                    'name' => 'department_name'
                ],
            ]],
        ], */
        [
            'text' => 'Ngày sinh',
            'datafield' => ['birthday_time', [
                'type' => 'range',
            ]],
            'width' => 100,
            'filtertype' => 'date',
            'cellsformat' => 'dd/MM/yyyy',
            'cellsalign' => 'center',
        ],
        [
            'text' => 'Nơi sinh',
            'datafield' => 'birthday_place',
        ],
        [
            'text' => 'Số CMND',
            'datafield' => 'id_number',
            'columngroup' => ['identity', [
                'text' => 'CMND',
            ]]
        ],
        [
            'text' => 'Ngày cấp',
            'datafield' => ['id_number_time', [
                'type' => 'range',
            ]],
            'filtertype' => 'date',
            'cellsformat' => 'dd/MM/yyyy',
            'cellsalign' => 'center',
            'width' => 100,
            'columngroup' => 'identity',
        ],
        [
            'text' => 'Nơi cấp',
            'datafield' => 'id_number_place',
            'columngroup' => 'identity',
        ],
        [
            'text' => 'Số hộ chiếu',
            'datafield' => 'passport_number',
            'columngroup' => ['passport', [
                'text' => 'Hộ chiếu',
            ]]
        ],
        [
            'text' => 'Ngày cấp',
            'datafield' => ['passport_create_time', [
                'type' => 'range',
            ]],
            'filtertype' => 'date',
            'cellsformat' => 'dd/MM/yyyy',
            'cellsalign' => 'center',
            'width' => 100,
            'columngroup' => 'passport',
        ],
        [
            'text' => 'Ngày hết hạn',
            'datafield' => ['passport_end_time', [
                'type' => 'range',
            ]],
            'filtertype' => 'date',
            'cellsformat' => 'dd/MM/yyyy',
            'cellsalign' => 'center',
            'width' => 100,
            'columngroup' => 'passport',
        ],
        [
            'text' => 'Nơi cấp',
            'datafield' => 'passport_place',
            'columngroup' => 'passport',
        ]
        // [ 'text' => 'Dân tộc', 'datafield' => 'ethnic_id', ],
        // [ 'text' => 'Quốc tịch', 'datafield' => 'country_id', ],
        // [ 'text' => 'Trình độ văn hóa', 'datafield' => 'culture', ],
        /*,
        [
            'text' => 'Địa chỉ thường trú',
            'datafield' => 'address_often',
        ],
        [
            'text' => 'Quận huyện thường trú',
            'datafield' => 'address_often_district_id',
            'width' => 120,
        ],
        [
            'text' => 'Địa chỉ tạm trú',
            'datafield' => 'address_now',
            'width' => 120,
        ],
        [
            'text' => 'Quận huyện tạm trú',
            'datafield' => 'address_now_district_id',
            'width' => 120,
        ],
        [
            'text' => 'Thành phố',
            'datafield' => 'city',
            'width' => 120,
        ],
        [
            'text' => 'Quận huyện',
            'datafield' => 'district',
            'width' => 120,
        ]*/,
        // [ 'text' => 'Mã chấm công', 'datafield' => 'timekeeping_code', ],
        // [ 'text' => 'Đơn vị chấm công', 'datafield' => 'don_vi_cham_cong', ],
        [
            'text' => 'Mã số thuế',
            'datafield' => 'tax',
        ],
        [ 'datafield' => 'salary_scale_level_id', ],
        [
            'text' => 'Bậc lương (HSL)',
            'datafield' => [['salary_scale_level_info']],
        ],
        [
            'text' => 'HSP',
            'datafield' => 'salary_department_scale_level',
            'filtertype' => 'number',
            'cellsalign' => 'right',
        ],
        [
            'text' => 'Lương cơ bản',
            'datafield' => ['salary_basic', [
                'type' => 'number',
            ]],
            'filtertype' => 'number',
            'cellsalign' => 'right',
        ],
        [
            'text' => 'Lương đóng bảo hiểm',
            'datafield' => ['salary_insurance', [
                'type' => 'number',
            ]],
            'filtertype' => 'number',
            'cellsalign' => 'right',
        ],
        [
            'text' => 'Phụ cấp công việc',
            'datafield' => ['salary_responsibility_allowance', [
                'type' => 'number',
            ]],
            'filtertype' => 'number',
            'cellsalign' => 'right',
        ],
        [
            'text' => 'Lương sản phẩm',
            'datafield' => ['salary_products', [
                'type' => 'number',
            ]],
            'filtertype' => 'number',
            'cellsalign' => 'right',
        ],
        [
            'text' => 'Lương t.gian tối thiểu',
            'datafield' => ['salary_minimum_time', [
                'type' => 'number',
            ]],
            'filtertype' => 'number',
            'cellsalign' => 'right',
        ],
        [
            'text' => 'Lương đoàn thể',
            'datafield' => ['salary_additional_corporate', [
                'type' => 'number',
            ]],
            'filtertype' => 'number',
            'cellsalign' => 'right',
        ],
        [
            'text' => 'Tháng',
            'datafield' => ['salary_monthly_coefficient', [
                'type' => 'number',
            ]],
            'filtertype' => 'number',
            'cellsalign' => 'right',
            'columngroup' => ['salary_coefficient', [
                'text' => 'Hệ số lương',
            ]]
        ],
        [
            'text' => 'Năng suất',
            'datafield' => ['salary_monthly_productivity_rate', [
                'type' => 'number',
            ]],
            'filtertype' => 'number',
            'cellsalign' => 'right',
            'columngroup' => 'salary_coefficient'
        ],
        [
            'text' => 'Năng suất quý',
            'datafield' => ['salary_quarterly_productivity', [
                'type' => 'number',
            ]],
            'filtertype' => 'number',
            'cellsalign' => 'right',
            'columngroup' => 'salary_coefficient'
        ],
        [
            'text' => 'Năng suất năm',
            'datafield' => ['salary_year_productivity', [
                'type' => 'number',
            ]],
            'filtertype' => 'number',
            'cellsalign' => 'right',
            'columngroup' => 'salary_coefficient'
        ],
        [
            'text' => 'Phụ cấp',
            'datafield' => ['salary_responsibility_allowance_coefficient', [
                'type' => 'number',
            ]],
            'filtertype' => 'number',
            'cellsalign' => 'right',
            'columngroup' => 'salary_coefficient'
        ],
        [
            'text' => 'Số lượng',
            'datafield' => ['salary_dependents_number', [
                'type' => 'number',
            ]],
            'filtertype' => 'number',
            'cellsalign' => 'right',
            'columngroup' => ['dependents', [
                'text' => 'Người phụ thuộc',
            ]]
        ],
        [
            'text' => 'Tiền giảm trừ',
            'datafield' => ['salary_dependents_amount', [
                'type' => 'number',
            ]],
            'filtertype' => 'number',
            'cellsalign' => 'right',
            'columngroup' => 'dependents'
        ],
        [
            'text' => 'Tỉ lệ đóng BH',
            'datafield' => ['salary_insurance_rate', [
                'type' => 'number',
            ]],
            'filtertype' => 'number',
            'cellsalign' => 'right',
        ],
        [
            'text' => 'Tham gia BHXH từ',
            'datafield' => 'tham_gia_bhxh_tu',
            'width' => 120,
        ],
        [
            'text' => 'Số thẻ BHYT',
            'datafield' => 'so_the_bhyt',
            'width' => 120,
        ],
        [
            'text' => 'Ngày hết hạn thẻ BHYT',
            'datafield' => 'ngay_het_han_bhyt',
            'width' => 120,
        ],
        [
            'text' => 'Nơi đăng ký KCB',
            'datafield' => 'noi_dang_ky_kcb',
            'width' => 120,
        ],
        [
            'text' => 'Trạng thái tham gia BH',
            'datafield' => 'trang_thai_tham_gia_bh',
            'width' => 120,
        ],
        [
            'text' => 'Số ngày phép được hưởng',
            'datafield' => ['day_leave_number', [
                'type' => 'number',
            ]],
            'filtertype' => 'number',
            'cellsalign' => 'right',
        ],
        [
            'text' => 'Tài khoản ngân hàng',
            'datafield' => 'tai_khoan_ngan_hang',
        ],
        [
            'text' => 'Mở tại ngân hàng',
            'datafield' => 'mo_tai_ngan_hang',
        ],
        [ 'datafield' => 'position_id' ],
        [
            'text' => 'Chức vụ',
            'datafield' => [ ['position_name'] ],
        ],
        [
            'text' => 'L.Chức vụ quý',
            'datafield' => ['salary_position_money_quarterly', [
                'type' => 'number',
            ]],
            'filtertype' => 'number',
            'cellsalign' => 'right',
        ],
        [
            'text' => 'Thời gian thử việc',
            'datafield' => 'trial_time',
            'width' => 85,
            'filtertype' => 'date',
            'cellsalign' => 'center',
        ],
        [
            'text' => 'Thời gian làm chính thức',
            'datafield' => 'receipt_time',
            'width' => 85,
            'filtertype' => 'date',
            'cellsalign' => 'center',
        ],
        [
            'text' => 'Điện thoại bàn',
            'datafield' => 'desk_phone',
        ],
        [
            'text' => 'Số di động',
            'datafield' => 'mobile_phone',
        ],
        [
            'text' => 'Số sổ lao động',
            'datafield' => 'labor_book',
        ],
        [ 'datafield' => 'work_status', ],
        [
            'text' => 'T.Thái làm việc',
            'datafield' => 'work_status_text',
            'filtertype' => 'list',
        ],
        /* [
            'text' => 'Lý do nghỉ việc',
            'datafield' => 'work_status_reason',
        ],
        [
            'text' => 'Ngày nghỉ việc',
            'datafield' => 'ngay_nghi_viec',
        ],
        [
            'text' => 'Người duyệt nghỉ',
            'datafield' => 'nguoi_duyet_nghi',
        ],*/
        [ 'datafield' => 'status' ],
        [
            'text' => 'Trạng thái',
            'datafield' => 'status_text',
            'width' => 128,
            'filtertype' => 'list',
        ],
        [ 'datafield' => 'com_email' ],
        [ 'datafield' => 'image' ],
        [ 'datafield' => 'mobile_phone' ],
    ];

    /**
     * Alter jqx grid column info
     * @param array $col Column data
     * @return array
     */
    public static function jqxGridCol($col)
    {
        $dfd = static::jqxGridDatafieldByCol($col);
        if (in_array($dfd, [
                'salary_basic',
                'salary_insurance',
                'salary_minimum_time',
                'salary_additional_corporate',
                'salary_quarterly_productivity',
                'salary_year_productivity',
                'salary_products',
                'salary_monthly_coefficient',
                'salary_monthly_productivity_rate',
                'salary_responsibility_allowance_coefficient',
                'salary_responsibility_allowance',
                'salary_dependents_number',
                'salary_dependents_amount',
                'salary_position_money_quarterly',
            ])
        ) {
            $col = array_replace($col, [
                'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}'
            ]);
        }
        if ('work_status_text' === $col['datafield']) {
            $col['filteritems'] = array_values(static::workStatusList());
        }
        if ('status_text' === $col['datafield']) {
            $col['filteritems'] = array_values(static::statusList());
        }
        return $col;
    }

    /**
     * @var string Gender 0
     */
    const GENDER_0 = 0;
    /**
     * @var string Gender 1
     */
    const GENDER_1 = 1;
    /**
     * Return gender list
     *
     * @return void
     */
    public static function genderList() {
        $list = [
            static::GENDER_0 => 'Nữ',
            static::GENDER_1 => 'Nam',
        ];
        return $list;
    }

    /**
     * @var string Work status 1
     */
    const WORK_STATUS_1 = 1;
    /**
     * @var string Work status 2
     */
    const WORK_STATUS_2 = 2;
    /**
     * @var string Work status 3
     */
    const WORK_STATUS_3 = 3;
    /**
     * @var string Work status 4
     */
    const WORK_STATUS_4 = 4;
    /**
     * @var string Work status 5
     */
    const WORK_STATUS_5 = 5;
    /**
     * Return work status list
     *
     * @return void
     */
    public static function workStatusList() {
        $list = [
            static::WORK_STATUS_1 => 'Đang thử việc',
            static::WORK_STATUS_2 => 'Làm việc chính thức',
            static::WORK_STATUS_3 => 'Đã nghỉ hưu',
            static::WORK_STATUS_4 => 'Xin nghỉ',
            static::WORK_STATUS_5 => 'Bị sa thải',
        ];
        return $list;
    }

    /**
     * Get the department.
     */
    protected function department()
    {
        return $this->belongsTo(Department::class, 'account_department_id');
    }

    /**
     * Get the salary_scale_level.
     */
    public function salaryScaleLevel()
    {
        return $this->belongsTo(SalaryScaleLevel::class, $this->columnName('salary_scale_level_id'));
    }
    /**
     * Get the salary_scale_level.
     * @return float
     */
    public function getSlrScaleLevelRate()
    {
        return $this->salaryScaleLevel ? (1 * $this->salaryScaleLevel->colVal('rate')) : 0;
    }
    /**
     * Get the salary_scale_level of department.
     * @return float
     */
    public function getDepSlrScaleLevelRate()
    {
        $rate = (1 * $this->colVal('salary_department_scale_level'));
        if ((0 === $rate) && $this->department) {
            $rate = $this->department->getSlrScaleLevelRate();
        }
        return $rate;
    }

    /**
     * Fetch all accounts info of organization unit (departments)
     * @param array $options An array of options
     * @return array
     */
    public static function findAllByOrganizationUnit($departmentId, array $options = array())
    {
        $depIdArr = Department::makeList([
            'parent_id' => $departmentId
        ]);
        $depIdArr = array_keys($depIdArr);
        $depIdArr[] = (1 * $departmentId);
        //
        $result = [];
        if (!empty($depIdArr))
        {
            $query = static::whereRaw(1)
                ->whereIn(static::columnName("department_id"), array_filter((array)$depIdArr))
            ;
            if (isset($options['alter_query'])
                && is_callable($options['alter_query'])
            ) {
                $options['alter_query']($query);
            }
            // die($query->toSql());
            $result = $query->get();
        }
        return $result;
    }

    /**
     *
     *
     * @param array $data Jqx request payload
     * @return void
     */
    public function jqxFetchRecordList(array $data, &$qB = null, &$totalRowsQB = null) {
        // Format data
		// +++
        $genderList = static::genderList();
        $statusList = static::statusList();
        $statusListFlip = array_flip($statusList);
        $workStatusList = static::workStatusList();
        // +++
        //Roles
        $roles = Roles::checkRoles();
        $permissons = Permissions::checkPermissons();
        $userID = \Auth::id();
        //danh sách phòng ban

		// Prepare the data
		$qB = $this->qBFromJqxRequestPayload($data, $totalRowsQB, [
            // Alter query builder
            'queryBuilder' => function(\Illuminate\Database\Eloquent\Builder $qB)
            use ($roles, $userID, $permissons) {
                $models = [
                    // 'com' => app()->make(Company::class),
                    'dep' => app()->make(Department::class),
                    'pos' => app()->make(Position::class),
                    'slrScaleLv' => app()->make(SalaryScaleLevel::class),
                ];
                $modelTable = $this->getTable();
                /* // Join company
                $qB->leftJoin(
                    ($tableCom = $models['com']->getTable())
                    , ("{$tableCom}." . ($pK = $models['com']->getKeyName()))
                    , '=', "{$tableDep}." . $models['dep']->columnName($pK)
                ); */
                // Join department
                $qB->leftJoin(
                    ($tableDep = $models['dep']->getTable())
                    , ("{$tableDep}." . ($pK = $models['dep']->getKeyName()))
                    , '=', "{$modelTable}." . $this->columnName($pK)
                );
                // Join position
                $qB->leftJoin(
                    ($tablePos = $models['pos']->getTable())
                    , ("{$tablePos}." . ($pK = $models['pos']->getKeyName()))
                    , '=', "{$modelTable}." . $this->columnName($pK)
                );
                // Join SalaryScaleLevel
                $qB->leftJoin(
                    ($tableSlrScaleLv = $models['slrScaleLv']->getTable())
                    , ("{$tableSlrScaleLv}." . ($pK = $models['slrScaleLv']->getKeyName()))
                    , '=', "{$modelTable}." . $this->columnName($pK)
                );

                if(!$permissons['permissonsAdmin']){
                    //kiểm tra quyền nhân viên
                    if($roles['rolesStaff']){
                        $qB->where("{$modelTable}.".$this->columnName('id'), $userID);
                    }
                    //kiểm tra quyền trưởng phòng
                    if($roles['rolesHeadDepartment']){
                        $departmentList = $models['dep']::makeList(['rolesHead' => true]);
                        //array id phong ban của trưởng phòng
                        $depArrID = array_keys($departmentList);
                        $qB->whereIn("{$modelTable}.".$this->columnName('department_id'), $depArrID);
                    }
                }

                //SẮP XẾP THEO CHỨC DANH
                $qB->orderBy("{$modelTable}.account_position_id", 'ASC');
                
                // Select
                $qB->select([
                    "{$modelTable}.*",
                    // "{$tableCom}.company_name",
                    "{$tableDep}.department_name",
                    "{$tablePos}.position_name",
                ]);
                $qB->selectRaw(
                    "CONCAT("
                        . "'(', {$tableSlrScaleLv}.salary_scale_level_rate, ') ', "
                        . "{$tableSlrScaleLv}.salary_scale_level_code"
                    . ") AS salary_scale_level_info"
                );
                // die($qB->toSql());
            },
            // Alter where conditions
            'where' => function(&$join, &$where, &$filter, &$condition, &$value) use (
                $statusListFlip
            ) {
                if (in_array($filter['field'], [
                    'account_birthday_time',
                    'account_id_number_time'
                ])) {
                    $value = $filter['value'] = date('Y-m-d', $value);
                }
                if (in_array($filter['field'], [
                    'account_status_text',
                ])) {
                    $filter['value'] = $value = $statusListFlip[$value];
                }
            },
        ]);
		// +++
        $rows = $qB->get()->map(function($row, $idx)
            use ($genderList, $statusList, $workStatusList) {
                $prop;
                $row->setColVal(($prop = 'gender'), $genderList[$row->colVal($prop)]);
                //
                $row->setColVal(($prop = 'birthday_time'), \std_date_str($row->colVal($prop)));
                $row->setColVal(($prop = 'id_number_time'), \std_date_str($row->colVal($prop)));
                $row->setColVal(($prop = 'passport_create_time'), \std_date_str($row->colVal($prop)));
                $row->setColVal(($prop = 'passport_end_time'), \std_date_str($row->colVal($prop)));
                $row->setColVal(($prop = 'trial_time'), \std_date_str($row->colVal($prop)));
                $row->setColVal(($prop = 'receipt_time'), \std_date_str($row->colVal($prop)));
                $row->setColVal(($prop = 'create_time'), \std_date_str($row->colVal($prop)));
                //
                // $row->setColVal(($prop = 'salary_basic'), numberFormatTax($row->colVal($prop)));
                // $row->setColVal(($prop = 'salary_insurance'), numberFormatTax($row->colVal($prop)));
                //
                $row->setColVal(($prop = 'status') . '_text', $statusList[$row->colVal($prop)]);
                $row->setColVal(($prop = 'work_status') . '_text', $workStatusList[$row->colVal($prop)]);
                //
                return $row;
            })
        ;
        //.end

        // Return
        return $rows;
	}

    /**
	 * Calculate salary fixed unit price
	 * @param array $options An array of options
     * @return double
	 */
    public function calSalaryFixedUnitPriceDay(array $options = array())
    {
        $result = 0;
        $slrFixed = (1 * $this->colVal('salary_fixed'));
        $slrFixedType = $this->colVal('salary_fixed_type');
        // Don gia tinh theo ngay!
        if (static::SLR_FIX_TYPE_0 == $slrFixedType) {
            $result = $slrFixed;
        }
        // Don gia tinh theo thang!
        if (static::SLR_FIX_TYPE_1 == $slrFixedType) {
            if (!isset($options['working_days_of_month'])) {
                throw new \Exception('Option `working_days_of_month` is required!');
            }
            $result = ($slrFixed / (1 * $options['working_days_of_month']));
        }
        return $result;
    }
}