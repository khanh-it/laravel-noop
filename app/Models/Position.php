<?php
namespace App\Models;

/**
 * @class Position
 */
class Position extends AbstractModel {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hr_position';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const CREATED_AT = 'position_create_time';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const DELETED_AT = 'position_delete_time';
    /**
     * @var string Customize the names of the columns used to store the status.
     */
    const STATUS_COLUMN = 'position_status';

     /**
     * Primary key
     *
     * @var string
     */
    protected $primaryKey = 'position_id';

    /**
     * Column datafield prefix
     * @var string
     */
    public static $columnPrefix = 'position_';

    /**
     * jqx's grid columns & datafields!
     * @var array
     */
    protected static $jqxGridColumns = [];

    /**
     * Alter jqx grid column info
     * @param array $col Column data
     * @return array
     */
    public static function jqxGridCol($col)
    {
        return $col;
    }

    /**
     * Get the accounts.
     */
    public function accounts()
    {
        return $this->hasMany(Account::class, Account::columnName('account_position_id'));
    }

    /**
     * Get records simple as list
     * @param array $options An array of options
     * @return mixed
     */
    public static function makeList(array $options = array())
    {
        // Create query builder
        $qB = static::whereRaw(1);
        // ||Filter
        if (!empty($options['status']))
        {
            $qB->where(static::STATUS_COLUMN, $options['status']);
        }
        // die($qB->toSql());
        // ||Fetch
        $collect = $qB->get();
        $postList = static::_makeListBuild($collect, $options);
        return $postList;
    }

    /**
     *
     * @return array
     */
    protected static function _makeListBuild($posList, array &$opts = array(), &$return = array())
    {}

    /**
     * @param array $data Jqx request payload
     * @return void
     */
    public function jqxFetchRecordList(array $data, &$qB = null, &$totalRowsQB = null)
    {}
}