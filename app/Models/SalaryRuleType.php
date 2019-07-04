<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @class SalaryRuleType
 */
class SalaryRuleType extends AbstractModel
{
    // use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hr_salary_rule_type';

    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const CREATED_AT = 'salary_rule_type_created_at';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const UPDATED_AT = 'salary_rule_type_updated_at';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    // const DELETED_AT = 'salary_rule_type_deleted_at';

    /**
     * Primary key
     * @var string
     */
    protected $primaryKey = 'salary_rule_type_id';

    /**
     * Column datafield prefix
     * @var string
     */
    public static $columnPrefix = 'salary_rule_type_';

    /**
     * jqx's grid columns & datafields!
     * @var array
     */
    protected static $jqxGridColumns = [
        [ 'datafield' => 'id' ],
        [ 'datafield' => 'code' ],
        [
            'text' => 'Tên',
            'datafield' => 'name',
            'width' => '45%',
        ],
        [
            'text' => 'Ghi chú',
            'filterable' => false,
            'datafield' => 'note',
            'width' => '55%',
        ],
        [ 'datafield' => 'create_account_id' ],
        [ 'datafield' => 'created_at' ],
        [ 'datafield' => 'updated_at' ],
        [ 'datafield' => 'delete_account_id' ],
        [ 'datafield' => 'deleted_at' ],
    ];

    /**
     * Get the create account.
     */
    public function createAccount()
    {
        return $this->hasOne(Account::class, 'salary_rule_type_create_account_id');
    }

    /**
     * Get the create account.
     */
    public function deleteAccount()
    {
        return $this->hasOne(Account::class, 'salary_rule_type_delete_account_id');
    }

    /**
     * 
     */
    public function makeList(array $options = array())
    {
        $collect = $this->all()->mapWithKeys(function($item){
            return [$item[$this->primaryKey] => $item['salary_rule_type_name']];
        });
        return $collect;
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
		// Prepare the data
        $qB = $this->qBFromJqxRequestPayload($data, $totalRowsQB, []);
        // var_dump($data);die($qB->toSql());
		// Format data
		// +++
		$rows = $qB->get()->map(function($row, $idx) use ($statusList) {
            // $prop; $txt = '_text';
            // $row->{($prop = '') . $txt} = $statusList[$row->{$prop}];
            //
			return $row;
		});
        //.end
        
        // Return
        return $rows;
	}
}