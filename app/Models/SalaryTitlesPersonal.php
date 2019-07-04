<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model;

class SalaryTitlesPersonal extends AbstractModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tbl_salary_titles';

    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const CREATED_AT = 'salary_titles_created_at';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const UPDATED_AT = 'salary_titles_updated_at';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const DELETED_AT = 'salary_titles_deleted_at';
    /**
     * @var string Customize the names of the columns used to store the status.
     */
    const STATUS_COLUMN = 'salary_titles_status';
    /**
     * @var string Customize the names of the columns used to store the value.
     */
    const TYPE_COLUMN = 'salary_titles_type';
    /**
     * @var string Customize TYPE_PERSONAL .
     */
    const TYPE_PERSONAL = 'PERSONAL';

     /**
     * Primary key
     *
     * @var string
     */
    protected $primaryKey = 'salary_titles_id';

    /**
     * Column datafield prefix
     * @var string
     */
    public static $columnPrefix = 'salary_titles_';

    /**
     * jqx's grid columns & datafields!
     * @var array
     */
    protected static $jqxGridColumns = [
        [ 'datafield' => 'id' ],
        [
            'text' => 'Mã danh hiệu',
            'datafield' => 'code',
            'width' => 128,
            'pinned' => true,
        ],
        [
            'text' => 'Tên danh hiệu',
            'datafield' => 'name',
            'width' => 256,
            'pinned' => true,
        ],
        [
            'text' => 'Số tiền',
            'datafield' => ['money', [
                'type' => 'number'
            ]],
            'width' => 256,
            // 'columntype' => 'number',
            'filtertype' => 'number',
            'cellsalign' => 'right',
            'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}'
        ],
        [
            'text' => 'Ghi chú',
            'filterable' => false,
            'datafield' => 'note',
        ],
        [ 'datafield' => 'status' ],
        [
            'text' => 'Trạng thái', 
            'datafield' => 'status_text',
            'width' => 128,
            'filtertype' => 'list',
        ],
        [ 'datafield' => 'created_by' ],
        [ 'datafield' => 'created_at' ],
        [ 'datafield' => 'updated_at' ],
        [ 'datafield' => 'deleted_by' ],
        [ 'datafield' => 'deleted_at' ],
    ]; 

    /**
     * Alter jqx grid column info
     * @param array $col Column data
     * @return array
     */
    public static function jqxGridCol($col)
    {
        if ('status_text' === $col['datafield']) {
            $col['filteritems'] = array_values(static::statusList());
        }
        return $col;
    }

    /**
     * Get the create account.
     */
    public function createAccount()
    {
        return $this->hasOne(Account::class, Account::columnName('created_by'));
    }

    /**
     * Get the delete account.
     */
    public function deleteAccount()
    {
        return $this->hasOne(Account::class, Account::columnName('deleted_by'));
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
        if (!is_null($options['status'])) {
            $qB->where(static::STATUS_COLUMN, $options['status']);
        }

        $qB->where(static::TYPE_COLUMN, static::TYPE_PERSONAL);
        // die($qB->toSql());
        // ||Fetch
        $collect = $qB->get()->mapWithKeys(function($item) use ($options) {
            return [
                "titles.{$item->colVal('code')}" => [
                    'code'  => $item->colVal('code'),
                    'name'  => $item->colVal('name'),
                    'money' => $item->colVal('money'),
                    'id'    => $item->id()
                ]
            ];
        });
        return $collect->toArray();
    }

    
    /**
     * set status 1
     *
     * @return void
     */
    public function typePersonal()
    {
        $personal = static::TYPE_COLUMN; 
        $this->{$personal} = static::TYPE_PERSONAL;
        return $this;
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
            'queryBuilder' => function(\Illuminate\Database\Eloquent\Builder $qB) {
                // Limit: chi lay du lieu type PERSONAL table!
                $qB->where(static::columnName('type'), static::TYPE_PERSONAL);
            },
            'where' => function(&$join, &$where, &$filter, &$condition, &$value) use ($statusListFlip) {
                if (($prop = 'salary_titles_status'). '_text' === $filter['field']) {
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
            $row->setColVal(($prop = 'status') . '_text', $statusList[$row->colVal($prop)]);
            //
			return $row;
		});
        //.end
        
        // Return
        return $rows;
    }
    
}
 