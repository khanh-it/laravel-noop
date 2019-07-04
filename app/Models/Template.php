<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @class Template
 */
class Template extends AbstractModel
{
    // use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tbl_template';

    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const CREATED_AT = 'template_created_at';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const UPDATED_AT = 'template_updated_at';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    // const DELETED_AT = 'template_deleted_at';
    /**
     * @var string Customize the names of the columns used to store the status.
     */
    const STATUS_COLUMN = 'template_status';

     /**
     * Primary key
     * @var string
     */
    protected $primaryKey = 'template_id';

    /** @var string type 0 (he thong) */
    const TYPE_0 = 0;
    /**
     * Return type list
     * @return void
     */
    public static function typeList() {
        $list = [
            static::TYPE_0 => 'Hệ thống',
        ];
        return $list;
    }
    /**
     * set type 0 (system)
     * @return void
     */
    public function typeSystem()
    {
        $this->template_type = static::TYPE_0;
        return $this;
    }
    /**
     * Is type 0 (system) ?
     * @return boolean
     */
    public function isTypeSystem()
    {
        $this->template_type == static::TYPE_0;
    }

    /**
     * Column datafield prefix
     * @var string
     */
    public static $columnPrefix = 'template_';

    /**
     * jqx's grid columns & datafields!
     * @var array
     */
    protected static $jqxGridColumns = [
        [ 'datafield' => 'id' ],
        [ 'datafield' => 'type' ],
        [
            'text' => 'Mã mẫu in',
            'datafield' => 'code',
            'width' => 128,
            'pinned' => true,
        ],
        [
            'text' => 'Tên mẫu in',
            'datafield' => 'name',
            'width' => 256,
            'pinned' => true,
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
        return $this->hasOne(Account::class, 'template_created_by');
    }

    /**
     * Get the delete account.
     */
    public function deleteAccount()
    {
        return $this->hasOne(Account::class, 'template_deleted_by');
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
                    if (($prop = 'template_type'). '_text' === $filter['field']) {
                        $filter['field'] = $prop;
                        $value = $filter['value'] = $typeListFlip[$value];
                    }
                    if (($prop = 'template_status'). '_text' === $filter['field']) {
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