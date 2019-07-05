<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @class Tag
 */
class Tag extends AbstractModel
{
    // use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tbl_tag';

    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const CREATED_AT = 'tag_created_at';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const UPDATED_AT = 'tag_updated_at';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    // const DELETED_AT = 'tag_deleted_at';
    /**
     * @var string Customize the names of the columns used to store the status.
     */
    const STATUS_COLUMN = 'tag_status';

     /**
     * Primary key
     * @var string
     */
    protected $primaryKey = 'tag_id';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'tag_type',
        'tag_name',
        'tag_note',
        'tag_uses',
        'tag_status',
        'tag_created_at',
        'tag_updated_at',
    ];

    /** @var string type 0 (he thong) */
    const TYPE_0 = 0;
    /**
     * Return type list
     * @return void
     */
    public static function typeList() {
        $list = [
            static::TYPE_0 => 'Zero',
        ];
        return $list;
    }

    /**
     * Column datafield prefix
     * @var string
     */
    public static $columnPrefix = 'tag_';

    /**
     * jqx's grid columns & datafields!
     * @var array
     */
    protected static $jqxGridColumns = [
        [ 'datafield' => 'id' ],
        [ 'datafield' => 'type' ],
        [
            'text' => 'Tag name',
            'datafield' => 'name',
            'width' => 256,
            'pinned' => true,
        ],
        [
            'text' => 'Số lần dùng',
            'cellsalign' => 'right',
            'datafield' => 'uses',
            'width' => 128,
            'filterable' => false,
        ],
        [
            'text' => 'Ghi chú',
            'filterable' => false,
            'sortable' => false,
            'datafield' => 'note',
        ],
        [
            'text' => 'Sử dụng?',
            'datafield' => ['status', [
                'type' => 'int'
            ]],
            'width' => 94,
            'cellsalign' => 'right',
            'sortable' => false,
            'columntype' => 'checkbox',
            'filtertype' => 'bool',
        ],
        [ 'datafield' => 'created_at' ],
        [ 'datafield' => 'updated_at' ],
    ];

    /**
     * Alter jqx grid column info
     * @param array $col Column data
     * @return array
     */
    public static function jqxGridCol($col)
    {
        if ('status_text' === $col['datafield']) {
            $col['filteritems'] = static::statusList();
        }
        return $col;
    }

    /**
     * Get the create account.
     */
    public function createAccount()
    {
        return $this->hasOne(Account::class, 'tag_created_by');
    }

    /**
     * Get the delete account.
     */
    public function deleteAccount()
    {
        return $this->hasOne(Account::class, 'tag_deleted_by');
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
        $typeList = static::typeList();
        $typeListFlip = array_flip($typeList);
        // +++
        $statusList = static::statusList();
        $statusListFlip = array_flip($statusList);

		// Prepare the data
        $qB = $this->qBFromJqxRequestPayload($data, $totalRowsQB, [
            // Alter query builder
            'queryBuilder' => function(\Illuminate\Database\Eloquent\Builder $qB, &$data) {},
            // Alter where conditions
            'where' => function(&$join, &$where, &$filter, &$condition, &$value)
                use ($typeListFlip, $taxableListFlip, $statusListFlip)
                {
                    if (($prop = 'tag_type'). '_text' === $filter['field']) {
                        $filter['field'] = $prop;
                        $value = $filter['value'] = $typeListFlip[$value];
                    }
                    if (($prop = 'tag_status'). '_text' === $filter['field']) {
                        $filter['field'] = $prop;
                        $value = $filter['value'] = $statusListFlip[$value];
                    }
                }
        ]);
        // var_dump($data);die($qB->toSql());
		// Format data
		// +++
        $rows = $qB->get()->map(function($row, $idx)
            use ($statusList, $typeList, $taxableList) {
                $prop;
                $txt = '_text';
                $row->setColVal(($prop = 'type') . $txt, $typeList[$row->colVal($prop)]);
                $row->setColVal(($prop = 'status') . $txt, $statusList[$row->colVal($prop)]);
                //
                return $row;
            })
        ;
        //.end

        // Return
        return $rows;
	}
}
