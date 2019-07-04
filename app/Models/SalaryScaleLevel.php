<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @class SalaryScaleLevel
 */
class SalaryScaleLevel extends AbstractModel
{
    // use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hr_salary_scale_level';

    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const CREATED_AT = 'salary_scale_level_create_time';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const UPDATED_AT = 'salary_scale_level_update_time';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const DELETED_AT = 'salary_scale_level_delete_time';
    /**
     * @var string Customize the names of the columns used to store the status.
     */
    const STATUS_COLUMN = 'salary_scale_level_status';

     /**
     * Primary key
     *
     * @var string
     */
    protected $primaryKey = 'salary_scale_level_id';

    /**
     * Column datafield prefix
     * @var string
     */
    public static $columnPrefix = 'salary_scale_level_';

    /**
     * jqx's grid columns & datafields!
     * @var array
     */
    protected static $jqxGridColumns = [
        [ 'datafield' => 'id' ],
        [
            'text' => 'Mã ngạch',
            'datafield' => 'code',
            'width' => 128,
            'pinned' => true,
        ],
        [
            'text' => 'Tên ngạch',
            'datafield' => 'name',
            'width' => 256,
            'pinned' => true,
        ],
        [
            'text' => 'Bậc',
            'datafield' => 'level',
            'width' => 256,
            'filterable' => false,
        ],
        [
            'text' => 'Hệ số',
            'datafield' => ['rate', [
                'type' => 'number'
            ]],
            'width' => 128,
            // 'columntype' => 'number',
            'filtertype' => 'number',
            'cellsalign' => 'right',
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
        [ 'datafield' => 'create_account_id' ],
        [ 'datafield' => 'create_time' ],
        [ 'datafield' => 'update_time' ],
        [ 'datafield' => 'delete_account_id' ],
        [ 'datafield' => 'delete_time' ],
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
        return $this->hasOne(Account::class, Account::columnName('create_account_id'));
    }

    /**
     * Get the delete account.
     */
    public function deleteAccount()
    {
        return $this->hasOne(Account::class, Account::columnName('delete_account_id'));
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
        // die($qB->toSql());
        // ||Fetch
        $collect = $qB->get()->mapWithKeys(function($item) use ($options) {
            return [
                $item->id() => (
                    ('[' . $item->colVal('code') . '] ')
                    .('' . $item->colVal('name') . ' ')
                    . ('(' . $item->colVal('level') . ')')
                )
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
                if (($prop = 'salary_scale_level_status'). '_text' === $filter['field']) {
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