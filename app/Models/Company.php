<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @class Company
 */
class Company extends AbstractModel
{
    // use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tbl_company';

    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    // const CREATED_AT = 'company_create_time';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    // const UPDATED_AT = 'company_update_time';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    // const DELETED_AT = 'company_delete_time';
    /**
     * @var string Customize the names of the columns used to store the status.
     */
    const STATUS_COLUMN = 'company_status';

    /**
     * Primary key
     * @var string
     */
    protected $primaryKey = 'company_id';

    /**
     * Column datafield prefix
     * @var string
     */
    public static $columnPrefix = 'company_';

    /**
     * jqx's grid columns & datafields!
     * @var array
     */
    protected static $jqxGridColumns = [
        [ 'datafield' => 'id' ],
    ];

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
     * Get the create account.
     */
    public function createAccount()
    {
        return $this->hasOne(Account::class, static::$columnPrefix . 'create_account_id');
    }

    /**
     * Get the create account.
     */
    public function deleteAccount()
    {
        return $this->hasOne(Account::class, static::$columnPrefix . 'delete_account_id');
    }

    /**
     * Get records simple as list
     * @param array $options An array of options
     * @return mixed
     */
    public static function makeList(array $options = array())
    {
        // @TODO: xu ly de qui!
        $collect = static::all()->mapWithKeys(function($item){
            return [
                $item['company_id']
                => $item['company_name']
            ];
        });
        return $collect->toArray();
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
        $statusList = static::statusList();
        $statusListFlip = array_flip($statusList);
		// Prepare the data
        $qB = $this->qBFromJqxRequestPayload($data, $totalRowsQB, [
            'where' => function(&$join, &$where, &$filter, &$condition, &$value) use ($statusListFlip) {
                if (($prop = 'company_company_id'). '_text' === $filter['field']) {
                    $filter['field'] = $prop;
                    $value = $filter['value'] = $statusListFlip[$value];
                }
            }
        ]);
        // var_dump($data);die($qB->toSql());
		// Format data
		// +++
		$rows = $qB->get()->map(function($row, $idx) use ($statusList) {
            $prop;
            $row->{($prop = 'company_status') . '_text'} = $statusList[$row->{$prop}];
            //
			return $row;
		});
        //.end
        
        // Return
        return $rows;
	}
}