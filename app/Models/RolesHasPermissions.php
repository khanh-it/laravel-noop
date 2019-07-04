<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolesHasPermissions extends AbstractModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'role_has_permissions';

    /**
     * jqx's grid columns & datafields!
     * @var array
     */
    protected static $jqxGridColumns = [];
    /**
     * Column datafield prefix
     * @var string
     */
    protected static $columnPrefix = '';
    /**
     * Alter jqx grid column info
     * @param array $col Column data
     * @return array
     */
    // public static function jqxGridCol($col) { return $col; }

    /**
     * Get Roles.
     * @return Roles|null
     */
    public function role()
    {
        return $this->belongsTo(Roles::class, $this->columnName('role_id'));
    }

    /**
     * Get Permissions .
     * @return Permissions|null
     */
    public function permission()
    {
        return $this->belongsTo(Permissions::class, $this->columnName('permission_id'));
    }
}
