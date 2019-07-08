<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @class Ads
 */
class Ads extends AbstractModel
{
	/**
	 * @var String Secret key, used to generate token/hash
	 */
	const HASH_SECKEY = 'bjFy9mjEEU';

	/**
	 * @var String Hash encode method
	 */
	const HASH_ENCODE_METHOD = 'AES-256-CBC';

	/**
	 * Helper: encode data id
	 * @param string $id Data id
	 * @return string
	 */
    public static function encryptPriKey($id)
    {
	    $hash = @openssl_encrypt(
	        trim($id),
	        self::HASH_ENCODE_METHOD,
	        self::HASH_SECKEY
        );
	    return ($hash);
	}

	/**
	 * Helper: decode data id
	 * @param string $hash
	 * @return string
	 */
    public static function decryptPriKey($hash)
    {
        $hash = (trim($hash));
	    $id = @openssl_decrypt(
	        $hash,
	        self::HASH_ENCODE_METHOD,
	        self::HASH_SECKEY
        );
	    return $id;
    }

	/**
	 * Fetch data for resources
	 * @param int|string|array
	 * @return mixed
	 */
    public static function find4Resource($id)
    {
        return static::find($id);
    }

    /**
     * Helper: encode data
     * @param mixed $data
     * @return string
     */
    public static function adsSpecsEncode($data)
    {
        return \is_string($data) ? $data : @\json_encode($data);
    }
    /**
     * Helper: decode data
     * @param string $data
     * @return mixed
     */
    public static function adsSpecsDecode($data)
    {
        if (\is_string($data)) {
            return @\json_decode($data, true);
        }
        return $data;
    }

    // use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tbl_ads';

    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const CREATED_AT = 'ads_created_at';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const UPDATED_AT = 'ads_updated_at';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    // const DELETED_AT = 'ads_deleted_at';
    /**
     * @var string Customize the names of the columns used to store the status.
     */
    const STATUS_COLUMN = 'ads_status';

     /**
     * Primary key
     * @var string
     */
    protected $primaryKey = 'ads_id';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'ads_name',
        'ads_spec_width',
        'ads_spec_height',
        'ads_note',
        'ads_content',
        'ads_uses',
        'ads_status',
        'ads_created_at',
        'ads_updated_at',
    ];

    /**
     * Get ads' content
     * @return string
     */
    public function getAdsContent()
    {
        return $this->ads_content;
    }

    /**
     * Column datafield prefix
     * @var string
     */
    public static $columnPrefix = 'ads_';

    /**
     * jqx's grid columns & datafields!
     * @var array
     */
    protected static $jqxGridColumns = [
        [ 'datafield' => 'id' ],
        [ 'datafield' => 'hash' ],
        [ 'datafield' => 'type' ],
        [
            'text' => 'Ads name',
            'datafield' => 'name',
            'width' => 256,
            'pinned' => true,
        ],
        [
            'text' => 'Size#width(px)',
            'datafield' => 'spec_width',
            'width' => 128,
            'cellsalign' => 'right',
        ],
        [
            'text' => 'Size#height(px)',
            'datafield' => 'spec_height',
            'width' => 128,
            'cellsalign' => 'right',
        ],
        [
            'text' => 'Lượt click',
            'cellsalign' => 'right',
            'datafield' => 'uses',
            'width' => 96,
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
        return $this->hasOne(Account::class, 'ads_created_by');
    }

    /**
     * Get the delete account.
     */
    public function deleteAccount()
    {
        return $this->hasOne(Account::class, 'ads_deleted_by');
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
            // Alter query builder
            'queryBuilder' => function(\Illuminate\Database\Eloquent\Builder $qB, &$data) {},
            // Alter where conditions
            'where' => function(&$join, &$where, &$filter, &$condition, &$value)
                use ($taxableListFlip, $statusListFlip)
                {
                    if (($prop = 'ads_status'). '_text' === $filter['field']) {
                        $filter['field'] = $prop;
                        $value = $filter['value'] = $statusListFlip[$value];
                    }
                }
        ]);
        // var_dump($data);die($qB->toSql());
		// Format data
		// +++
        $rows = $qB->get()->map(function($row, $idx)
            use ($statusList, $taxableList) {
                $prop;
                $row->setColVal(($prop = 'hash'), rawurlencode(static::encryptPriKey($row->id())));
                $txt = '_text';
                $row->setColVal(($prop = 'status') . $txt, $statusList[$row->colVal($prop)]);
                //
                return $row;
            })
        ;
        //.end

        // Return
        return $rows;
	}

    /**
     * @Overloading magic __get
     * @param string $prop Object's property name
     * @return mixed
     */
    public function __get($prop)
    {
        $return = parent::__get($prop);
        if (('ads_specs' === $prop) && is_string($return)) {
            $return = ($this->{$prop} = static::adsSpecsDecode($return));
        }
        return $return;
    }

    /**
     * @Overloading magic __set
     * @param string $prop Object's property name
     * @param mixed $value Object's value
     * @return mixed
     */
    public function __set($prop, $value)
    {
        if (('ads_specs' === $prop) && is_array($value)) {
            $value = static::adsSpecsEncode($value);
        }
        return parent::__set($prop, $value);
    }
}
