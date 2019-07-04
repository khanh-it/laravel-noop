<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class Roles extends AbstractModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const CREATED_AT = 'created_at';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const UPDATED_AT = 'updated_at';
    
    /** @var string (NHÂN VIÊN) */
    const ROLES_STAFF = 'STAFF';
    /** @var string (TRƯỞNG PHÒNG) */
    const ROLES_HEAD_DEPARTMENT = 'HEAD_DEPARTMENT';
    /** @var string (KẾ TOÁN) */
    const ROLES_ACCOUNTANT = 'ACCOUNTANT';
    /** @var string (BAN GIÁM ĐỐC) */
    const ROLES_BOD = 'BOD';
    /** @var string (ADMIN) */
    const ROLES_ADMIN = 'ADMIN';
    /** @var string (SIÊU ADMIN) */
    const ROLES_SUPER_ADMIN = 'SUPER_ADMIN';

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
            'text' => 'Tên vai trò',
            'datafield' => 'name',
            'pinned' => true,
        ],
        [
            'text' => 'Đơn vị bảo vệ',
            'datafield' => 'guard_name',
            'pinned' => true,
        ],
        [
            'text' => 'Quyền hạn',
            'datafield' => [['role_has_permissions'], [
                'type' => 'number'
            ]],
            'width' => 128,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
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
        return $this->hasMany(RolesHasPermissions::class, RolesHasPermissions::columnName('role_id'));
    }

    /**
     * Get Salary\Basic.
     * @return Basic|null
     */
    public function modelHasRole()
    {
        return $this->hasMany(ModelHasRole::class, ModelHasRole::columnName('model_id'));
    }

    /**
     * Alter jqx grid column info
     * @param array $col Column data
     * @return array
     */
    public static function jqxGridCol($col)
    {
        $dfd = static::jqxGridDatafieldByCol($col);
        if (in_array($dfd, [
            'role_has_permissions',
        ])) {
            $col = array_replace($col, [
                'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}',
                'columntype' => 'custom',
                'createeditor' => "{!!window.mkJqxGridEditor(0, '{$dfd}')!!}",
                'initeditor' => "{!!window.mkJqxGridEditor(1, '{$dfd}')!!}",
                // 'geteditorvalue' => "{!!window.mkJqxGridEditor(2, '{$dfd}')!!}",
            ]);
        } 
        return $col;
    }

    public static function makeList(array $options = array()) {
        // Create query builder
        $self = app()->make(static::class);
        $collect = static::all()->mapWithKeys(function($item) use ($self, $options) {
            return [$item[$self->getKeyName()] => ($item[static::columnName('name')])
            ];
        });
        
        if(!static::RolesSuperAdmin()){
            $role = Role::findByName(static::ROLES_SUPER_ADMIN);
            unset($collect[$role->id]);
        }
        return $collect->toArray();
    }
    /**
     *
     *
     * @param array $data Jqx request payload
     * @return void
     */
    public function jqxFetchRecordList(array $data, &$qB = null, &$totalRowsQB = null) {
        // Format data
		// Prepare the data
		$qB = $this->qBFromJqxRequestPayload($data, $totalRowsQB, []);
		// +++
        $rows = $qB->withCount('roleHasPermissions')->get()->map(function($row, $idx){
                $prop;
                if(Permissions::count() === $row->role_has_permissions_count){
                    $row->role_has_permissions = 'FULL';
                }else{
                    $row->role_has_permissions = $row->role_has_permissions_count;
                }
                unset($row->role_has_permissions_count);
                return $row;
            })
        ;
        //.end

        // Return
        return $rows;
    }
    
    //nhân viên
    public static function RolesStaff() {
        return (Auth::User()->hasRole(static::ROLES_STAFF));
    }

    //trưởng phòng
    public static function RolesHeadDepartment() {
        return (Auth::User()->hasRole(static::ROLES_HEAD_DEPARTMENT));
    }

    //kế toán
    public static function RolesAccountant() {
        return (Auth::User()->hasRole(static::ROLES_ACCOUNTANT));
    }

    //ban giám đốc
    public static function RolesBod() {
        return (Auth::User()->hasRole(static::ROLES_BOD));
    }

    //admin
    public static function RolesAdmin() {
        return (Auth::User()->hasRole(static::ROLES_ADMIN));
    }

    //super admin
    public static function RolesSuperAdmin() {
        return (Auth::User()->hasRole(static::ROLES_SUPER_ADMIN));
    }

    /**
     * Get records simple as list
     * @param array $options An array of options
     * @return mixed
     */
    public static function checkRoles(array $options = array())
    {
        //roles
        return $roles = [
                'rolesStaff' => static::RolesStaff(),
                'rolesHeadDepartment' => static::RolesHeadDepartment(),
                'rolesAccountant' => static::RolesAccountant(),
                'rolesBod' => static::RolesBod(),
                'rolesAdmin' => static::RolesAdmin(),
                'rolesSuperAdmin' => static::RolesSuperAdmin(),
            ];
    }
}
