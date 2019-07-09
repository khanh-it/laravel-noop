<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @class Rel
 */
class Rel extends AbstractModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tbl_rel';

    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const CREATED_AT = 'rel_created_at';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const UPDATED_AT = 'rel_updated_at';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    // const DELETED_AT = 'rel_deleted_at';
    /**
     * @var string Customize the names of the columns used to store the status.
     */
    // const STATUS_COLUMN = 'rel_status';

    /**
     * Column datafield prefix
     * @var string
     */
    public static $columnPrefix = 'rel_';

     /**
     * Primary key
     * @var string
     */
    protected $primaryKey = 'rel_id';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'rel_type',
        'rel_ref01',
        'rel_ref02',
        'rel_created_at',
        'rel_updated_at',
    ];

    /** @var string type "ads.tag" */
    const TYPE_ADS_N_TAG = 'ads.tag';

    /**
     * Find records
     * @param int|string $ref01
     * @param array $opts An array of options
     * @return int
     */
    public static function findByRef1st($ref01, array $opts = [])
    {
        $result = static::whereRaw(1)
            ->where(static::columnName('ref01'), $ref01)
            ->get()->map(function($ent) {
                return $ent->colVal('ref02');
            })->all()
        ;
        return $result;
    }

    /**
     * Find records
     * @param int|string $ref02
     * @param array $opts An array of options
     * @return int
     */
    public static function findByRef2nd($ref02, array $opts = [])
    {
        $result = static::whereRaw(1)
            ->where(static::columnName('ref02'), $ref02)
            ->get()->map(function($ent) {
                return $ent->colVal('ref01');
            })->all()
        ;
        return $result;
    }

    /**
     * Destroy records
     * @param int|string $ref01
     * @param array $opts An array of options
     * @return int
     */
    public static function destroyByRef1st($ref01, array $opts = [])
    {
        $result = static::whereRaw(1)
            ->where(static::columnName('ref01'), $ref01)
            ->delete()
        ;
        return $result;
    }

    /**
     * Destroy records
     * @param int|string $ref02
     * @param array $opts An array of options
     * @return int
     */
    public static function destroyByRef2nd($ref02, array $opts = [])
    {
        $result = static::whereRaw(1)
            ->where(static::columnName('ref02'), $ref02)
            ->delete()
        ;
        return $result;
    }
}
