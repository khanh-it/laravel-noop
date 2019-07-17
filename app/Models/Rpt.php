<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @class Rpt
 */
class Rpt extends AbstractModel
{
    /** @var int type "uses" */
    const TYPE_USES = 0;

    /** @var int type "viewed" */
    const TYPE_VIEWED = 10;

    /** @var int type "clicked" */
    const TYPE_CLICKED = 20;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tbl_rpt';

    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const CREATED_AT = 'rpt_created_at';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const UPDATED_AT = 'rpt_updated_at';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    // const DELETED_AT = 'rpt_deleted_at';
    /**
     * @var string Customize the names of the columns used to store the status.
     */
    // const STATUS_COLUMN = 'rpt_status';

    /**
     * Column datafield prefix
     * @var string
     */
    public static $columnPrefix = 'rpt_';

     /**
     * Primary key
     * @var string
     */
    protected $primaryKey = 'rpt_id';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'rpt_ads_id',
        'rpt_type',
        'rpt_session',
        'rpt_uri_fr',
        'rpt_uri_to',
        'rpt_ua',
        'rpt_ips',
        'rpt_browser',
        'rpt_platform',
        'rpt_extra',
        'rpt_created_at',
        'rpt_updated_at',
    ];

    /**
     * @return array
     */
    public static function typeList()
    {
        return [
            static::TYPE_USES => 'Tải',
            static::TYPE_VIEWED => 'Xem',
            static::TYPE_CLICKED => 'Click'
        ];
    }
}
