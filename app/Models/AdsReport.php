<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @class AdsReport
 */
class AdsReport extends AbstractModel
{
    // use SoftDeletes;

    /**
     * @var Ads
     */
    protected $_ads = null;

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
     * Primary key
     * @var string
     */
    protected $primaryKey = 'rpt_id';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [];

    /**
     * Column datafield prefix
     * @var string
     */
    public static $columnPrefix = 'rpt_';

    /**
     * jqx's grid columns & datafields!
     * @var array
     */
    protected static $jqxGridColumns = [
        [ 'datafield' => 'id' ],
        [ 'datafield' => 'ads_id' ],
        [ 'datafield' => 'session' ],
        [
            'text' => 'Loại',
            'datafield' => ['type', [
                'type' => 'int'
            ]],
            'width' => 60,
            'sortable' => false,
            'columntype' => 'string',
            'filtertype' => 'list',
        ],
        [
            'text' => 'IP',
            'datafield' => 'ips',
            'width' => 120,
            'filterable' => true,
            'sortable' => true,
        ],
        [
            'text' => 'Uri tải',
            'datafield' => 'uri_fr',
            'filterable' => false,
            'sortable' => false,
        ],
        [
            'text' => 'Uri click',
            'datafield' => 'uri_to',
            'filterable' => false,
            'sortable' => false,
        ],
        [
            'text' => 'Platform/Browser',
            'datafield' => 'ua',
            'filterable' => false,
            'sortable' => false,
        ],
        [
            'text' => 'Thời gian',
            'width' => 200,
            'datafield' => 'created_at',
            'cellsalign' => 'center',
            'filtertype' => 'range',
            // 'sortable' => false,
        ],
        [ 'datafield' => 'updated_at' ],
    ];

    /**
     * Alter jqx grid column info
     * @param array $col Column data
     * @return array
     */
    public static function jqxGridCol($col)
    {
        $dfd = (static::jqxGridDatafieldByCol($col));
        if ('type' === $dfd) {
            $col['filteritems'] = \array_values(Rpt::typeList());
        }
        return $col;
    }

    /**
     * @param Ads $ads
     * @return void
     */
    public function forAds(Ads $ads, array $opts = [])
    {
        $this->_ads = $ads;
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
        // if (!($this->_ads instanceof Ads)) { throw new \Exception('$ads is missing!'); }
        // +++
        $typeList = Rpt::typeList();
        $typeListFlip = \array_flip($typeList);
        // +++
        $models = [
            'ads' => app()->make(Ads::class),
        ];
        $tables = [
            '_' => $modelTable = $this->getTable(),
            'ads' => $models['ads']->getTable()
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
                    // Limit by relationships?!
                    if ($this->_ads) {
                        $qB->where(static::columnName('ads_id'), $this->_ads->id());
                    }
                    // Limit by primary id
                    if (!empty($data['pid'])) {
                        $qB->whereIn(static::columnName('id'), $data['pid']);
                    }
                    // dd($qB->toSql());
                }
            ,
            // Alter where conditions
            'where' => function(&$join, &$where, &$filter, &$condition, &$value)
                use (&$models, &$tables, $typeList, $typeListFlip)
                {
                    if (($prop = 'rpt_type') === $filter['field']) {
                        $value = $filter['value'] = $typeListFlip[$value];
                    }
                    // Filter type 'range'...
                    if ('rpt_created_at' === $filter['field']) {
                        $value = static::parseDateTimeJqx($value);
                        $filter['value'] = $value->format('Y-m-d H:i:s');
                    }
                }
        ]);
        // dump($data); die($qB->toSql());
        // Case: delete data
        if (isset($data['_delete']) && true === $data['_delete']) {
            // Limit by primary id?!
            $adIds = [];
            if (!empty($data['pid'])) {
                $cloneQB = clone $qB;
                $cloneQB
                    ->select($colAdsId = static::columnName('ads_id'))
                    ->groupBy($colAdsId)
                ;
                $adIds = $cloneQB->get()->keyBy($colAdsId)->keys()->toArray();
            }
            // Delete data
            $qbResult = $qB->delete();
            // Reset/Clear report stats
            if ($this->_ads) {
                static::updateAdsReportStats($this->_ads->id());
            } else {
                // Limit by primary id?!
                if (!empty($adIds)) {
                    foreach ($adIds as $adId) {
                        static::updateAdsReportStats($adId);
                    }
                // Case: delete all
                } else {
                    static::clearAdsReportStats();
                }
            }
            //
            return $qbResult;
        }
        // Format data
		// +++
        $rows = $qB->get()->map(function($row, $idx)
            use ($typeList, $typeListFlip) {
                $prop;
                $txt = '_text';
                $row->setColVal(($prop = 'type'), $typeList[$row->colVal($prop)]);
                //
                return $row;
            })
        ;
        //.end

        // Return
        return $rows;
    }

    /**
     * @param int|string $adsId Ads id
     * @param array $opts An array of options
     */
    public static function updateAdsReportStats($adsId, array $opts = [])
    {
        // Get, format options
        // +++

        // Build query
        $qB = static::whereRaw(1)
            ->select(
                // $colAdsId = static::columnName('ads_id'),
                $colType = static::columnName('type'),
                \DB::raw('COUNT(*) AS `cnt`')
            )
            ->where(static::columnName('ads_id'), $adsId)
            ->groupBy(/*$colAdsId, */$colType)
        ;
        // dd($qB->toSql());
        // Fetch
        $data = [];
        $qB->chunk(/* @TODO: */128, function($rptEnts) use (&$data, $colType) {
            foreach ($rptEnts as $rptEnt) {
                $data[$prop = $rptEnt->{$colType}] = $data[$prop] ?? 0;
                $data[$prop] += $rptEnt->cnt;
            }
            return false;
        });
        //.end

        // Build query for update
        return Ads::where(Ads::columnName('id'), $adsId)->update([
            Ads::columnName('uses') => $data[Rpt::TYPE_USES] ?? 0,
            Ads::columnName('viewed') => $data[Rpt::TYPE_VIEWED] ?? 0,
            Ads::columnName('clicked') => $data[Rpt::TYPE_CLICKED] ?? 0
        ]);
    }

    /**
     * @param array $opts An array of options
     */
    public static function clearAdsReportStats(array $opts = [])
    {
        // Get, format options
        // +++

        // Build query for update
        return Ads::whereRaw(1)->update([
            Ads::columnName('uses') => 0,
            Ads::columnName('viewed') => 0,
            Ads::columnName('clicked') => 0
        ]);
    }
}
