<?php
namespace App\Models;

class Holiday extends AbstractModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hr_holiday';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const CREATED_AT = 'holiday_created_at';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const UPDATED_AT = 'holiday_updated_at';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    // const DELETED_AT = 'holiday_deleted_at';
    /**
     * @var string Customize the names of the columns used to store the status.
     */
    const STATUS_COLUMN = 'holiday_status';

    /**
     * 
     */
    const DT_KEY_WKD = 'Wkd';

    /**
     * 
     */
    const DT_KEY_HOL = 'Hol';

     /**
     * Primary key
     * @var string
     */
    protected $primaryKey = 'holiday_id';

    /**
     * Column datafield prefix
     * @var string
     */
    public static $columnPrefix = 'holiday_';

    /**
     * jqx's grid columns & datafields!
     * @var array
     */
    protected static $jqxGridColumns = [
        [ 'datafield' => 'id' ],
        [
            'text' => 'Tên',
            'width' => 192,
            'datafield' => 'name',
            'pinned' => true,
        ],
        [ 'datafield' => 'day_fr' ],
        [ 'datafield' => 'month_fr' ],
        [
            'text' => 'Ngày áp dụng (từ)',
            'width' => 156,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
            'datafield' => [ ['date_fr'] ],
        ],
        [ 'datafield' => 'day_to' ],
        [ 'datafield' => 'month_to' ],
        [
            'text' => 'Ngày áp dụng (đến)',
            'width' => 156,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
            'datafield' => [ ['date_to'] ],
        ],
        [
            'text' => 'Năm giới hạn (từ)',
            'width' => 156,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
            'datafield' => 'year_fr',
        ],
        [
            'text' => 'Năm giới hạn (đến)',
            'width' => 156,
            'cellsalign' => 'right',
            'filterable' => false,
            'sortable' => false,
            'datafield' => 'year_to',
        ],
        [
            'text' => 'Ghi chú',
            'minwidth' => 128,
            'filterable' => false,
            'sortable' => false,
            'datafield' => 'note',
        ],
        [ 'datafield' => 'status' ],
        [
            'text' => 'Trạng thái',
            'datafield' => 'status_text',
            'width' => 128,
            'filtertype' => 'list',
        ]
    ];

    /**
     * Alter jqx grid column info
     * @param array $col Column data
     * @return array
     */
    public static function jqxGridCol($col)
    {
         $dfd = static::jqxGridDatafieldByCol($col);
        if ('status_text' === $dfd) {
            $col['filteritems'] = array_values(static::statusList());
            $col['filtertype'] = 'list';
        }
        return $col;
    }

    /**
     * 
     * @param \DateTime $dt
     * @return string
     */
    public static function makeDtKey(\DateTime $dt) 
    {
        return $dt->format(DATE_ATOM);
    }

    /**
     * Alter jqx grid column info
     * @param array $col Column data
     * @return array
     */
    public static function getHolidayInfoByTime(\DateTime $dtFr, \DateTime $dtTo, array $options = array())
    {
        // From
        $data = [];
        $data['year_fr'] = intval($dtFr->format('Y'));
        $data['month_fr'] =  intval($dtFr->format('m'));
        $data['day_fr'] = intval($dtFr->format('d'));
        // To
        $data['year_to'] = intval($dtTo->format('Y'));
        $data['month_to'] =  intval($dtTo->format('m'));
        $data['day_to']   = intval($dtTo->format('d'));
        //  ||
        $data['multiplier'] = 100;
        $data['md_number_fr'] = ($data['month_fr'] * $data['multiplier'] + $data['day_fr']);
        $data['md_number_to'] = ($data['month_to'] * $data['multiplier'] + $data['day_to']);

        // Create query builder
        $qB = static::whereRaw(1)
            ->where(static::STATUS_COLUMN, static::STATUS_1)
            // Check by year
            ->where(function($_qB) use (&$data) {
                return;
                // +++ from
                $_qB->where(function($_qB2) use (&$data) {
                    $_qB2->whereNull($colYearFr = static::columnName('year_fr'))
                        ->orWhere($colYearFr, '>=', $data['year_fr'])
                    ;
                // +++ to
                })->orWhere(function($_qB2) use (&$data) {
                    $_qB2->whereNull($colYearTo = static::columnName('year_to'))
                        ->orWhere($colYearTo, '<=', $data['year_to'])
                    ;
                });
            })
            // Check by month-day
            ->where(function($_qB) use (&$data) {
                return;
                // +++ from
                $_qB->whereRaw('(' . static::columnName('month_fr') . " * {$data['multiplier']} + " . static::columnName('day_fr') . ') BETWEEN ? AND ?', [
                    $data['md_number_fr'],
                    $data['md_number_to']
                ])
                // +++ to
                /* ->orWhereRaw('(' . static::columnName('month_to') . " * {$data['multiplier']} + " . static::columnName('day_to') . ') BETWEEN ? AND ?', [
                    $data['md_number_fr'],
                    $data['md_number_to']
                ]) */
                ->orWhere(function($_qB) use (&$data) {
                    $_qB->whereNull($colMonthTo = static::columnName('month_to'))
                        ->orWhereNull($colDayTo = static::columnName('day_to'))
                        ->orWhereRaw('(' . $colMonthTo . " * {$data['multiplier']} + " . $colDayTo . ') BETWEEN ? AND ?', [
                            $data['md_number_fr'],
                            $data['md_number_to']
                        ])
                    ;
                });
            })
        ;
        // die($qB->toSql());

        // Fetch data
        $result = [];
        $rows = $qB->get();
        if (!empty($rows)) {
            $interval = \DateInterval::createFromDateString('1 day');
            for ($year = $data['year_fr']; $year <= $data['year_to']; $year++) {
                foreach ($rows as $row) {
                    $yearFr = $row->colVal('year_fr');
                    $yearTo = $row->colVal('year_to');
                    if ((!is_null($yearFr) && $yearFr > $year)
                        || (!is_null($yearTo) && $yearTo < $year)
                    ) {
                        continue;
                    }
                    $monthFr = $row->colVal('month_fr');
                    $dayFr = $row->colVal('day_fr');
                    $monthTo = $row->colVal('month_to') ?: $monthFr;
                    $dayTo = $row->colVal('day_to') ?: $dayFr;
                    $period = new \DatePeriod(
                        new \DateTime("{$year}-{$monthFr}-{$dayFr} 00:00:00"),
                        $interval,
                        new \DateTime("{$year}-{$monthTo}-{$dayTo} 23:59:59")
                    );
                    foreach ($period as $dt) {
                        if (!($dt >= $dtFr && $dt <= $dtTo)) {
                            continue;
                        }
                        $key = static::makeDtKey($dt);
                        $result[$key] = $result[$key] ?: []; // Init
                        $result[$key][$row->id()] = $row;
                    }
                }
            }
        }
        // Return
        return $result;
    }

    /**
     * 
     * @param array $options An array of options
     * @return string
     */
    public function dateFr(array $options = array())
    {
        $dateFr = '';
        $dayFr = intval($this->colVal('day_fr'));
        $monthFr = intval($this->colVal('month_fr'));
        if ($dayFr && $monthFr) {
            $dayFr = ($dayFr < 10) ? "0{$dayFr}" : $dayFr;
            $monthFr = ($monthFr < 10) ? "0{$monthFr}" : $monthFr;
            $dateFr = "{$dayFr}-{$monthFr}";
        }
        return $dateFr;
    }

    /**
     * 
     * @param array $options An array of options
     * @return string
     */
    public function dateTo(array $options = array())
    {
        $dateTo = '';
        $dayTo = intval($this->colVal('day_to'));
        $monthTo = intval($this->colVal('month_to'));
        if ($dayTo && $monthTo) {
            $dayTo = ($dayTo < 10) ? "0{$dayTo}" : $dayTo;
            $monthTo = ($monthTo < 10) ? "0{$monthTo}" : $monthTo;
            $dateTo = "{$dayTo}/{$monthTo}";
        }
        return $dateTo;
    }

    /**
     * Make a list of day(s) in month
     * @param array $options An array of options
     * @return array
     */
    public static function makeListDay(array $options = array())
    {
        $return = [];
        for ($i = 1; $i <= 31; $i++) {
            $return[$i] = "Ngày {$i}";
        }
        return $return;
    }

    /**
     * Make a list of months in year
     * @param array $options An array of options
     * @return array
     */
    public static function makeListMonth(array $options = array())
    {
        $return = [];
        for ($i = 1; $i <= 12; $i++) {
            $return[$i] = "Tháng {$i}";
        }
        return $return;
    }

    /**
     * Make a list of years
     * @param array $options An array of options
     * @return array
     */
    public static function makeListYear(array $options = array())
    {
        $return = [];
        $curY = intval(date('Y')) + 5;
        for ($i = $curY; $i >= $curY - 15; $i--) {
            $return[$i] = "{$i}";
        }
        return $return;
    }

    /**
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
            'queryBuilder' => function(\Illuminate\Database\Eloquent\Builder $qB, $data) {
                // Set default query conditions
                /* $key = ($col = $this->columnName('status')) . '_text';
                if (!$data['filterGroups'][$key]) {
                    $qB->where($col, static::STATUS_1);
                }
                unset($col, $key); */
            },
            // Alter where conditions
            'where' => function(&$join, &$where, &$filter, &$condition, &$value)
                use ($statusListFlip) {
                    if (($prop = $this->columnName('status')) . '_text' === $filter['field']) {
                        $value = $filter['value'] = $statusListFlip[$value];
                    }
                }
            ,
        ]);
        // var_dump($data);die($qB->toSql());
        // Format data
        // +++
        $rows = $qB->get()->map(function($row, $idx)
            use ($statusList) {
                $prop;
                $row->{($prop = 'date_fr')} = $row->dateFr();
                $row->{($prop = 'date_to')} = $row->dateTo();
                $row->setColVal(($prop = 'status') . '_text', $statusList[$row->colVal($prop)]);
                //
                return $row;
            })
        ;
        //.end

        // Return
        return $rows;
    }
}