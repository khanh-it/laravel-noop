<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Permissions extends AbstractModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'permissions';

    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const CREATED_AT = 'created_at';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const UPDATED_AT = 'updated_at';
    
    /** @var string (XEM THÔNG TIN BẢN THÂN) */
    const PERMISSIONS_VIEW_PERSONAL = 'VIEW_PERSONAL';
    /** @var string (XEM THÔNG TIN 1 PHÒNG BAN) */
    const PERMISSIONS_VIEW_DEPARTMENT = 'VIEW_DEPARTMENT';
    /** @var string (XEM TẤT CẢ THÔNG TIN NHÂN VIÊN) */
    const PERMISSIONS_VIEW_ALL_USER = 'VIEW_ALL_USER';
    /** @var string (XEM THÔNG TIN NHÂN VIÊN 1 PHÒNG BAN) */
    const PERMISSIONS_VIEW_USER_DEPARTMENT = 'VIEW_USER_DEPARTMENT';
    /** @var string (XEM TẤT CẢ PHÒNG BÀN) */
    const PERMISSIONS_VIEW_ALL_DEPARTMENT = 'VIEW_ALL_DEPARTMENT';
    /** @var string (TẠO) */
    const PERMISSIONS_CREATE = 'CREATE';
    /** @var string (SỬA) */
    const PERMISSIONS_EDIT = 'EDIT';
    /** @var string (XÓA) */
    const PERMISSIONS_DELETE = 'DELETE';
    /** @var string (IN/EXCEL) */
    const PERMISSIONS_PRINT_EXCEL = 'PRINT_EXCEL';
    /** @var string (CHẤM CÔNG) */
    const PERMISSIONS_TIMEKEEPING = 'TIMEKEEPING';
    /** @var string (CẤU HÌNH LƯƠNG) */
    const PERMISSIONS_CONFIG_SALARY = 'CONFIG_SALARY';
    /** @var string (XÁC NHẬN LƯƠNG TẤT CẢ NHÂN VIÊN) */
    const PERMISSIONS_CONFIRMED_ALL = 'CONFIRMED_ALL';
    /** @var string (ADMIN) */
    const PERMISSIONS_ADMIN = 'ADMIN';

    /**
     * Primary key
     *
     * @var string
     */
    protected $primaryKey = 'id';
    /**
     * Column datafield prefix
     * @var string
     */
    protected static $columnPrefix = '';
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'guard_name',
        'created_at',
        'updated_at',
    ];

    /**
     * jqx's grid columns & datafields!
     * @var array
     */
    protected static $jqxGridColumns = [
        [ 'datafield' => 'id' ],
        [
            'text' => 'Tên Phân quyền',
            'datafield' => 'name',
            'pinned' => true,
        ],
        [
            'text' => 'Đơn vị bảo vệ',
            'datafield' => 'guard_name',
            'pinned' => true,
        ],
        
        [
            'text' => 'Ngày tạo',
            'datafield' => ['created_at', [
                'type' => 'range',
            ]],
            'filtertype' => 'date',
            'cellsformat' => 'dd/MM/yyyy',
            'cellsalign' => 'center',
        ],
    ];

    /**
     * Get/set salary details.
     * @return mixed
     */
    public function roleHasPermissions()
    {
        return $this->hasMany(RolesHasPermissions::class, RolesHasPermissions::columnName('permission_id'));
    }

    /**
     * Alter jqx grid column info
     * @param array $col Column data
     * @return array
     */
    public static function jqxGridCol($col)
    {
        return $col;
    }

    //QUYỀN XEM THÔNG TIN BẢN THÂN PERMISSIONS_VIEW_PERSONAL
    public static function permissonsViewPersonal() {
        return (Auth::User()->can(static::PERMISSIONS_VIEW_PERSONAL) || Auth::User()->can(static::PERMISSIONS_ADMIN));
    }

    //QUYỀN XEM THÔNG TIN 1 PHONGF BAN PERMISSIONS_VIEW_DEPARTMENT
    public static function permissonsViewDepartment() {
        return (Auth::User()->can(static::PERMISSIONS_VIEW_DEPARTMENT) || Auth::User()->can(static::PERMISSIONS_ADMIN));
    }

    //QUYỀN XEM TẤT CẢ THÔNG TIN NHÂN VIÊN PERMISSIONS_VIEW_ALL_USER
    public static function permissonsViewAllUser() {
        return (Auth::User()->can(static::PERMISSIONS_VIEW_ALL_USER) || Auth::User()->can(static::PERMISSIONS_ADMIN));
    }

    //QUYỀN XEM THÔNG TIN NHÂN VIÊN CỦA 1 PHÒNG BAN PERMISSIONS_VIEW_USER_DEPARTMENT
    public static function permissonsViewUserDepartment() {
        return (Auth::User()->can(static::PERMISSIONS_VIEW_USER_DEPARTMENT) || Auth::User()->can(static::PERMISSIONS_ADMIN));
    }

    //QUYỀN XEM THÔNG TIN TẤT CẢ PHÒNG BAN PERMISSIONS_VIEW_ALL_DEPARTMENT
    public static function permissonsViewAllDepartment() {
        return (Auth::User()->can(static::PERMISSIONS_VIEW_ALL_DEPARTMENT) || Auth::User()->can(static::PERMISSIONS_ADMIN));
    }

    //QUYỀN TẠO PERMISSIONS_CREATE
    public static function permissonsCreate() {
        return (Auth::User()->can(static::PERMISSIONS_CREATE) || Auth::User()->can(static::PERMISSIONS_ADMIN));
    }

    //QUYỀN SỬA PERMISSIONS_EDIT
    public static function permissonsEdit() {
        return (Auth::User()->can(static::PERMISSIONS_EDIT) || Auth::User()->can(static::PERMISSIONS_ADMIN));
    }

    //QUYỀN XÓA PERMISSIONS_DELETE
    public static function permissonsDelete() {
        return (Auth::User()->can(static::PERMISSIONS_DELETE) || Auth::User()->can(static::PERMISSIONS_ADMIN));
    }
    
    //QUYỀN IN/EXCEL PERMISSIONS_PRINT_EXCEL
    public static function permissonsPrintExcel() {
        return (Auth::User()->can(static::PERMISSIONS_PRINT_EXCEL) || Auth::User()->can(static::PERMISSIONS_ADMIN));
    }

    //QUYỀN CHẤM CÔNG PERMISSIONS_DELETE
    public static function permissonsTimekeeping() {
        return (Auth::User()->can(static::PERMISSIONS_TIMEKEEPING) || Auth::User()->can(static::PERMISSIONS_ADMIN));
    }

    //QUYỀN CẤU HÌNH BẢNG LƯƠNG PERMISSIONS_CONFIG_SALARY
    public static function permissonsConfigSalary() {
        return (Auth::User()->can(static::PERMISSIONS_CONFIG_SALARY) || Auth::User()->can(static::PERMISSIONS_ADMIN));
    }

    //QUYỀN ADMIN SỰ DỤNG TẤT CẢ PERMISSIONS_ADMIN
    public static function permissonsConfirmAll() {
        return (Auth::User()->can(static::PERMISSIONS_CONFIRMED_ALL) || Auth::User()->can(static::PERMISSIONS_ADMIN));
    }

    //QUYỀN ADMIN SỰ DỤNG TẤT CẢ PERMISSIONS_ADMIN
    public static function permissonsAdmin() {
        return (Auth::User()->can(static::PERMISSIONS_ADMIN));
    }

    /**
     * Get records simple as list
     * @param array $options An array of options
     * @return mixed
     */
    public static function checkPermissons(array $options = array())
    {
        //permisson
        return $permissons = [
                'permissonsViewPersonal' => static::permissonsViewPersonal(),
                'permissonsViewDepartment' => static::permissonsViewDepartment(),
                'permissonsViewAllUser' => static::permissonsViewAllUser(),
                'permissonsViewUserDepartment' => static::permissonsViewUserDepartment(),
                'permissonsViewAllDepartment' => static::permissonsViewAllDepartment(),
                'permissonsCreate' => static::permissonsCreate(),
                'permissonsEdit'   => static::permissonsEdit(),
                'permissonsDelete' => static::permissonsDelete(),
                'permissonsPrintExcel' => static::permissonsPrintExcel(),
                'permissonsTimekeeping' => static::permissonsTimekeeping(),
                'permissonsConfigSalary' => static::permissonsConfigSalary(),
                'permissonsConfirmAll' => static::permissonsConfirmAll(),
                'permissonsAdmin' => static::permissonsAdmin(),
            ];
    }
}
