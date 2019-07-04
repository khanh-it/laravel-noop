<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @class SalaryRule
 */
class SalaryRule extends AbstractModel
{
    // use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hr_salary_rule';

    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const CREATED_AT = 'salary_rule_created_at';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const UPDATED_AT = 'salary_rule_updated_at';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    // const DELETED_AT = 'salary_rule_deleted_at';
    /**
     * @var string Customize the names of the columns used to store the status.
     */
    const STATUS_COLUMN = 'salary_rule_status';

     /**
     * Primary key
     * @var string
     */
    protected $primaryKey = 'salary_rule_id';

    /** @var string feature 0 (thu nhap) */
    const FEATURE_0 = 0;
    /** @var string feature 1 (khau tru) */
    const FEATURE_1 = 1;
    /** @var string feature 2 (giam tru) */
    const FEATURE_2 = 2;
    /**
     * Return feature list
     * @return void
     */
    public static function featureList() {
        $list = [
            static::FEATURE_0 => 'Thu nhập',
            static::FEATURE_1 => 'Khấu trừ',
            static::FEATURE_2 => 'Giảm trừ',
        ];
        return $list;
    }
    /**
     * set feature 0
     * @return void
     */
    public function featureIncome()
    {
        $this->salary_rule_feature = static::FEATURE_0;
        return $this;
    }
    /**
     * set feature 1
     * @return void
     */
    public function featureDeduct()
    {
        $this->salary_rule_feature = static::FEATURE_1;
        return $this;
    }
    /**
     * set feature 2
     * @return void
     */
    public function featureReduce()
    {
        $this->salary_rule_feature = static::FEATURE_2;
        return $this;
    }

    /** @var int taxable 0 (chiu thue theo bieu luy tien) */
    const TAXABLE_0 = 0;
    /** @var int taxable 1 (chiu thue toan phan) */
    const TAXABLE_1 = 1;
    /** @var int taxable 2 (khong chiu thue) */
    const TAXABLE_2 = 2;
    /**
     * Return taxable list
     * @return void
     */
    public static function taxableList() {
        $list = [
            static::TAXABLE_0 => 'Chịu thuế theo biểu lũy tiến',
            static::TAXABLE_1 => 'Chịu thuế toàn phần',
            static::TAXABLE_2 => 'Không chịu thuế',
        ];
        return $list;
    }
    /**
     * set taxable 0 (chiu thue theo bieu luy tien)
     * @return void
     */
    public function taxableProgressive()
    {
        $this->salary_rule_taxable = static::TAXABLE_0;
        return $this;
    }
    /**
     * set taxable 1 (chiu thue toan phan)
     * @return void
     */
    public function taxableFull()
    {
        $this->salary_rule_taxable = static::TAXABLE_1;
        return $this;
    }
    /**
     * set taxable 2 (khong chiu thue)
     * @return void
     */
    public function taxableNone()
    {
        $this->salary_rule_taxable = static::TAXABLE_2;
        return $this;
    }

    /**
     * @var array
     */
    protected static $_slrRuleData = null;

    /**
     * Get static salary config data
     * @return array
     */
    public static function slrRuleData()
    {
        if (empty(static::$_slrRuleData)) {
            // Relatives
            $models = [
                'slrRule' => app()->make(static::class),
                'slrRType' => app()->make(SalaryRuleType::class),
            ];
            $tblSlrRule = $models['slrRule']->getTable();
            $tblSlrRType = $models['slrRType']->getTable();
            //
            $collect = static::select([
                    "{$tblSlrRType}.salary_rule_type_code AS type_code",
                    "{$tblSlrRType}.salary_rule_type_name AS type_name",
                    "{$tblSlrRule}.salary_rule_code AS code",
                    "{$tblSlrRule}.salary_rule_name AS name",
                    "{$tblSlrRule}.salary_rule_feature AS feature",
                    "{$tblSlrRule}.salary_rule_taxable AS taxable",
                    "{$tblSlrRule}.salary_rule_quota AS quota",
                    "{$tblSlrRule}.salary_rule_money AS money"
                ])
                ->join(
                    ($tblSlrRType)
                    , ("{$tblSlrRType}." . ($pK = $models['slrRType']->getKeyName()))
                    , '=', "{$tblSlrRule}." . $models['slrRule']->columnName($pK)
                )
                ->where(static::columnName('status'), static::STATUS_1)
                ->get()
            ;
            static::$_slrRuleData = $collect->toArray();
        }
        // Return;
        return static::$_slrRuleData;
    }

    /**
     * Helper: calc PIT base on salary
     * @param int $type Type for calculation
     * @param double $salary Salary
     * @param array $options An array of options
     * @return double
     */
    public static function calcPITSumByRule($type, $salary, array $options = array())
    {
        // Get, format input(s)
        $slr = (1 * $salary);
        if ($slr > 0) {
            // Define vars
            $foundItem = null;
            // 
            $data = static::slrRuleData();
            if (!empty($data) && ($slr > 0)) {
                foreach ($data as $item) {
                    if ($type === $item['code']) {
                        $foundItem = $item;
                        break;
                    }
                }
                unset($item);
            }
            if ($foundItem) {
                // Define vars
                $multiplier = (static::FEATURE_0 == $foundItem['feature']) // Tinh chat "thu nhap"
                    ? 1
                    // nguoc lai, tinh chat "giam tru", "khau tru"
                    : -1
                ;
                // Case: khong chiu thue
                if (static::TAXABLE_2 == $foundItem['taxable']) {
                    $slr = 0;
                }

                //
                if ($slr > 0) {
                    // Dieu chinh gia tri luong theo 'ty le' ?!
                    if ($foundItem['money'] > 0) {
                        // Case: so tien co dinh
                        if ($foundItem['money'] >= 100) {
                            $slr = (1 * $foundItem['money']);
                        // Case: %
                        } else {
                            $slr = ($slr * (1 * $foundItem['money']) / 100);
                        }
                    }

                    // Tru di: gia tri "dinh muc" ?!
                    $slr -= (1 * $foundItem['quota']);
                    $slr = max(0, $slr);

                    // Case: chiu thue theo bieu luy tien
                    if (static::TAXABLE_0 == $foundItem['taxable']) {
                        if (true === $options['taxable_type_year']) {
                            $slr = PITConfig::calcTypeYear($slr);
                        } else {
                            $slr = PITConfig::calcTypeMonth($slr);
                        }
                    }
                    // Case: chiu thue toan phan
                    if (static::TAXABLE_1 == $foundItem['taxable']) {
                        // ...
                    }
                    $slr = max(0, $slr);
                }
                //
                $slr = $slr * $multiplier;
            }
            // Rounding values?
            // $slr = round($slr);
        }

        // Return;
        // dd(numberFormat($salary), numberFormat($slr), $foundItem);
        return $slr;
    }

    /**
     * Helper: calc PIT summary by salary rule type
     * @param string $type Rule type
     * @param array|\Closure $salaryArr An array of salary
     * @param array $options An array of options
     * @return double
     */
    public static function calcPITSumByRuleType($type, $salaryArr, array $options = array())
    {
        // Define var(s)
        $result = [];

        // Get, format input(s)
        $prefix = "{$type}::";

        // 
        $data = static::slrRuleData();
        if (!empty($data)) {
            foreach ($data as $idx => $item) {
                if (0 !== strpos($item['code'], $prefix)) {
                    unset($data[$idx]);
                }
            }
            unset($idx);
            //
            if (!empty($data)) {
                foreach ($data as $item) {
                    $key = str_replace($prefix, '', $item['code']);
                    $salary =\ is_callable($salaryArr) ? $salaryArr($key) : $salaryArr[$key];
                    $result[$key] = static::calcPITSumByRule($item['code'], $salary, $options);
                }
                unset($salary);
            }
        }
        // Debug?!
        if (true === $options['debug']) {
            echo '<pre>';
            var_dump($result);
			echo '</pre>';
        }
        //.end

        // Sum + rounding values...
        $result = round(array_sum($result));

        // Return
        return $result;
    }

    /**
     * Column datafield prefix
     * @var string
     */
    public static $columnPrefix = 'salary_rule_';

    /**
     * jqx's grid columns & datafields!
     * @var array
     */
    protected static $jqxGridColumns = [
        [ 'datafield' => 'id' ],
        [ 'datafield' => 'code' ],
        [ 'datafield' => 'salary_rule_type_id' ],
        [
            'text' => 'Loại khoản lương',
            'datafield' => [ ['salary_rule_type_name'] ],
            'width' => 0,
            'filtertype' => 'list',
            'pinned' => true,
            'hidden' => true,
        ],
        [
            'text' => 'Tiêu chí',
            'datafield' => 'name',
            'width' => 196,
            'pinned' => true,
        ],
        [ 'datafield' => 'order' ],
        /* [
            'text' => 'Số thứ tự',
            'datafield' => ['order', [
                'type' => 'number'
            ]],
            'width' => 128,
            // 'columntype' => 'number',
            'filtertype' => 'number',
            'cellsalign' => 'right',
        ], */
        [ 'datafield' => 'feature' ],
        [
            'text' => 'Tính chất',
            'datafield' => 'feature_text',
            'width' => 128,
            'filtertype' => 'list',
        ],
        [ 'datafield' => 'taxable' ],
        [
            'text' => 'Chịu thuế',
            'datafield' => 'taxable_text',
            'width' => 164,
            'filtertype' => 'list',
        ],
        [ 'datafield' => 'quota' ],
        [
            'text' => 'Định mức',
            'datafield' => ['quota', [
                'type' => 'number'
            ]],
            'width' => 128,
            // 'columntype' => 'number',
            'filtertype' => 'number',
            'cellsalign' => 'right',
            'cellsrenderer' => '{!!window.jqxGridCellsRenderer!!}'
        ],
        [ 'datafield' => 'money' ],
        [
            'text' => 'Tỷ lệ (số tiền hoặc %)',
            'datafield' => ['money', [
                'type' => 'number'
            ]],
            'width' => 128,
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
        // [ 'datafield' => 'create_account_id' ],
        // [ 'datafield' => 'created_at' ],
        // [ 'datafield' => 'updated_at' ],
        // [ 'datafield' => 'delete_account_id' ],
        // [ 'datafield' => 'deleted_at' ],
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
        if ('feature_text' === $col['datafield']) {
            $col['filteritems'] = static::featureList();
        }
        if ('taxable_text' === $col['datafield']) {
            $col['filteritems'] = static::taxableList();
        }
        return $col;
    }

    /**
     * Get the create account.
     */
    public function createAccount()
    {
        return $this->hasOne(Account::class, 'salary_rule_create_account_id');
    }

    /**
     * Get the create account.
     */
    public function deleteAccount()
    {
        return $this->hasOne(Account::class, 'salary_rule_delete_account_id');
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
        $featureList = static::featureList();
        $featureListFlip = array_flip($featureList);
        // +++
        $taxableList = static::taxableList();
        $taxableListFlip = array_flip($taxableList);
        // +++
        $statusList = static::statusList();
        $statusListFlip = array_flip($statusList);
		// Prepare the data
        $qB = $this->qBFromJqxRequestPayload($data, $totalRowsQB, [
            // Alter query builder
            'queryBuilder' => function(\Illuminate\Database\Eloquent\Builder $qB, &$data) {
                $models = [
                    'srtype' => app()->make(SalaryRuleType::class),
                ];
                $modelTable = $this->getTable();
                // Join SalaryRuleType
                $qB->leftJoin(
                    ($tableSrtype = $models['srtype']->getTable())
                    , ("{$tableSrtype}." . ($pK = $models['srtype']->getKeyName()))
                    , '=', "{$modelTable}." . $this->columnName($pK)
                );
                // Select
                $qB->select([
                    "{$modelTable}.*",
                    "{$tableSrtype}.salary_rule_type_name",
                ]);
                // Order by
                $data['sortorder'] = 'asc';
                // die($qB->toSql());
            },
            // Alter where conditions
            'where' => function(&$join, &$where, &$filter, &$condition, &$value)
                use ($featureListFlip, $taxableListFlip, $statusListFlip) 
                {
                    if (($prop = 'salary_rule_feature'). '_text' === $filter['field']) {
                        $filter['field'] = $prop;
                        $value = $filter['value'] = $featureListFlip[$value];
                    }
                    if (($prop = 'salary_rule_taxable'). '_text' === $filter['field']) {
                        $filter['field'] = $prop;
                        $value = $filter['value'] = $taxableListFlip[$value];
                    }
                    if (($prop = 'salary_rule_status'). '_text' === $filter['field']) {
                        $filter['field'] = $prop;
                        $value = $filter['value'] = $statusListFlip[$value];
                    }
                }
        ]);
        // var_dump($data);die($qB->toSql());
		// Format data
		// +++
        $rows = $qB->get()->map(function($row, $idx)
            use ($statusList, $featureList, $taxableList) {
                $prop;
                $txt = '_text';
                $row->setColVal(($prop = 'feature') . $txt, $featureList[$row->colVal($prop)]);
                $row->setColVal(($prop = 'taxable') . $txt, $taxableList[$row->colVal($prop)]);
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