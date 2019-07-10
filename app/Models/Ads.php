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
     * Collection to string
     * @param Collection $clt
     * @return string
     */
    public static function cltToStr($clt)
    {
        $return = [];
        foreach ($clt as $ent) {
            $return[] = $ent->colVal('name') . ' [' . $ent->id() . ']';
        }
        $return = \implode(', ', $return);

        return $return;
    }

    /**
     * Get ads' content
     * @return string
     */
    public function getAdsContent()
    {
        return $this->colVal('content');
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
            'width' => 200,
            'pinned' => true,
        ],
        [
            'text' => 'Width(px)',
            'datafield' => 'spec_width',
            'width' => 80,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
        ],
        [
            'text' => 'Height(px)',
            'datafield' => 'spec_height',
            'width' => 80,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
        ],
        [
            'text' => 'Lượt click',
            'cellsalign' => 'right',
            'datafield' => 'uses',
            'width' => 80,
            'filterable' => false,
        ],
        [
            'text' => 'Tags',
            'datafield' => [ ['tags'] ],
            // 'width' => 120,
            'sortable' => false,
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
            'width' => 90,
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
        // +++
        $models = [
            'tag' => app()->make(Tag::class),
            'rel' => app()->make(Rel4AdsNTag::class)
        ];
        $tables = [
            '_' => $modelTable = $this->getTable(),
            'tag' => $models['tag']->getTable(),
            'rel' => $models['rel']->getTable()
        ];

		// Prepare the data
        $qB = $this->qBFromJqxRequestPayload($data, $totalRowsQB, [
            // Alter query builder
            'queryBuilder' => function(\Illuminate\Database\Eloquent\Builder $qB, &$data)
                use (&$models, &$tables)
                {
                    /* // Join "rel"
                    $qB->leftJoin(
                        ($tables['rel']),
                        function ($join) use ($models, $tables) {
                            $join->on(
                                ("{$tables['rel']}." . $models['rel']->columnName('ref01'))
                                , '=', "{$tables['_']}." . $this->getKeyName()
                            )->where(
                                ("{$tables['rel']}." . $models['rel']->columnName('type')),
                                Rel4AdsNTag::TYPE_ADS_N_TAG
                            );
                        }
                    );
                    // Join "tag"
                    $qB->leftJoin(
                        ($tables['tag'])
                        , ("{$tables['tag']}." . ($pK = $models['tag']->getKeyName()))
                        , '=', "{$tables['rel']}." . $models['rel']->columnName('ref02')
                    ); */
                    // dd($qB->toSql());
                }
            ,
            // Alter where conditions
            'where' => function(&$join, &$where, &$filter, &$condition, &$value)
                use ($taxableListFlip, $statusListFlip, &$models, &$tables)
                {
                    if (($prop = 'ads_status'). '_text' === $filter['field']) {
                        $filter['field'] = $prop;
                        $value = $filter['value'] = $statusListFlip[$value];
                    }
                    // tags
                    if (($prop = 'tags') === $filter['field']) {
                        // $filter['field'] = 'tag_name';
                        return false;
                    }
                }
        ]);
        // var_dump($data);die($qB->toSql());
        // Format data
		// +++
        $rows = $qB->get()->map(function($row, $idx)
            use ($statusList, $taxableList) {
                $prop;
                // +++ tags
                $tags = Tag::cltToStr(Tag::find(Rel4AdsNTag::findByRef1st($row->id())));
                $row->{($prop = 'tags')} = $tags;
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
     * Helper: slit tags by string
     */
    public static function splitTags($tags, array $opts = [])
    {
        $tags = \trim($tags);
        $tags = \array_filter(\explode($opts['delimiter'] ?? ',', $tags));
        $data = [];
        foreach ($tags as $tag) {
            $tag = \trim($tag);
            if (\preg_match('/^([^\[\]]*)(\[(\d+)\]$)?/i', $tag, $m)) {
                $data[\trim($m[1])] = $m[3];
            }
        }
        return $data;
    }

    /**
     * Set ads's tags
     * @param string $tags
     * @return int
     */
    public function useTags($_tags)
    {
        // Remove previous tags
        Rel4AdsNTag::destroyByRef1st($this->id());
        // Add new tags,...
        $effected = 0;
        $tags = static::splitTags($_tags);
        if (!empty($tags)) {
            foreach ($tags as $tagName => $tagId) {
                $tagEnt = Tag::findOneOrCreateByName($tagName);
                if ($tagEnt) {
                    $refEnt = app()->make(Rel4AdsNTag::class);
                    $refEnt->setColVal('ref01', $this->id());
                    $refEnt->setColVal('ref02', $tagEnt->id());
                    $effected += !!$refEnt->save();
                }
            }
        }
        return $effected;
    }

    /**
     * Find data for reportDashboard
     * @param array $opts An array of options
     * @return array
     */
    public static function rptDashboard(array $opts = [])
    {
        // Total
        $totalEnt = static::selectRaw('COUNT(*) AS total')
            ->where(static::columnName('status'), static::STATUS_1)
            ->first()
        ;
        // Latest
        $latestEnts = static::whereRaw(1)
            ->where(static::columnName('status'), static::STATUS_1)
            ->orderBy(static::columnName('id'), 'DESC')
            ->limit($opts['limit'] ?? 10)
            ->get()
        ;
        $result = [
            'total' => $totalEnt->total,
            'items' => $latestEnts
        ];
        return $result;
    }
}
