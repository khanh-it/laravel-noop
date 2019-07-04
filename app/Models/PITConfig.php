<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @class PITConfig
 */
class PITConfig extends AbstractModel
{
    // use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hr_pit_config';

    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const CREATED_AT = 'pit_config_created_at';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const UPDATED_AT = 'pit_config_updated_at';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    // const DELETED_AT = 'pit_config_deleted_at';
    /**
     * @var string Customize the names of the columns used to store the status.
     */
    // const STATUS_COLUMN = 'pit_config_status';

    /**
     * Primary key
     *
     * @var string
     */
    protected $primaryKey = 'pit_config_id';

    /**
     * Column datafield prefix
     * @var string
     */
    public static $columnPrefix = 'pit_config_';

    /**
     * jqx's grid columns & datafields!
     * @var array
     */
    protected static $jqxGridColumns = [
        [ 'datafield' => 'id' ],
        [
            'text' => 'Bậc',
            'datafield' => 'level',
            'cellsalign' => 'center',
            'width' => 96,
        ],
        [ 'datafield' => 'month_from' ],
        [
            'text' => 'Trên',
            'datafield' => 'month_from_text',
            'cellsalign' => 'right',
            'columngroup' => ['month', [
                'text' => 'Biểu tính thuế theo tháng',
            ]]
        ],
        [ 'datafield' => 'month_to' ],
        [
            'text' => 'Đến',
            'datafield' => 'month_to_text',
            'cellsalign' => 'right',
            'columngroup' => 'month'
        ],
        [ 'datafield' => 'year_from' ],
        [
            'text' => 'Trên',
            'datafield' => 'year_from_text',
            'cellsalign' => 'right',
            'columngroup' => ['year', [
                'text' => 'Biểu tính thuế theo năm',
            ]]
        ],
        [ 'datafield' => 'year_to' ],
        [
            'text' => 'Đến',
            'datafield' => 'year_to_text',
            'cellsalign' => 'right',
            'columngroup' => 'year'
        ],
        [ 'datafield' => 'tax' ],
        [
            'text' => 'Thuế suất (%)',
            'datafield' => 'tax_text',
            'width' => 256,
            'cellsalign' => 'right',
        ],
        // [ 'datafield' => 'created_by' ],
        // [ 'datafield' => 'created_at' ],
        // [ 'datafield' => 'updated_at' ],
        // [ 'datafield' => 'deleted_by' ],
        // [ 'datafield' => 'deleted_at' ],
    ];

    /**
     * Cach tinh thue ly tien theo thang (type = 0)
     * @var int
     */
    const CALC_TYPE_MONTH = 0;

    /**
     * Cach tinh thue ly tien theo nam (type = 1)
     * @var int
     */
    const CALC_TYPE_YEAR = 1;

    /**
     * @var array
     */
    protected static $_PITConfigData = null;

    /**
     * Get static PIT config data
     * @return array
     */
    public static function PITConfigData()
    {
        if (empty(static::$_PITConfigData)) {
            //
            $pitcnfTypeMonth = [];
            $pitcnfTypeYear = [];
            //
            $data = static::whereRaw(1)->get();
            if ($data) {
                $data->map(function($var) use (&$pitcnfTypeMonth, &$pitcnfTypeYear) {
                    $pitcnfTypeMonth[] = [
                        ($prop = 'from') => 1 * $var->colVal('month_' . $prop),
                        ($prop = 'to') => 1 * $var->colVal('month_' . $prop),
                        ($prop = 'tax') => 1 * $var->colVal($prop)
                    ];
                    $pitcnfTypeYear[] = [
                        ($prop = 'from') => 1 * $var->colVal('year_' . $prop),
                        ($prop = 'to') => 1 * $var->colVal('year_' . $prop),
                        ($prop = 'tax') => 1 * $var->colVal($prop)
                    ];
                });
            }
            // Sort data to make sure return data is correct
            usort($pitcnfTypeMonth, function($a, $b) {
                return $a['from'] > $b['to'];
            });
            usort($pitcnfTypeYear, function($a, $b) {
                return $a['from'] > $b['to'];
            });
            static::$_PITConfigData = [
                static::CALC_TYPE_MONTH => $pitcnfTypeMonth,
                static::CALC_TYPE_YEAR => $pitcnfTypeYear
            ];

        }
        // Return;
        return static::$_PITConfigData;
    }

    /**
     * Helper: calc PIT base on salary
     * @param int $type Type for calculation
     * @param double $salary Salary
     * @param array $options An array of options
     * @return double
     */
    protected static function _calc($type, $salary, array $options = array())
    {
        // Define vars
        $result = [];
        $debug = [];
        // Get, format input(s)
        $slr = (1 * $salary);
        //
        $data = static::PITConfigData();
        $data = $data[$type];
        if (!empty($data) && ($slr > 0)) {
            foreach ($data as $item) {
                $base = min($slr, 1 * ($item['to'] - $item['from']));
                $pit = ($base * $item['tax'] / 100);
                $debug[] = numberFormat($base) . ' | ' . numberFormat($pit) . "({$item['tax']}%).";
                $result[] = $pit;
                $slr -= $base;
                if ($slr <= 0) {
                    break;
                }
            }
        }
        // Rounding values
        $result = round(array_sum($result));
        // Return
        if (true === $options['debug']) {
            return $debug;
        }
        return $result;
    }

    /**
     * Helper: calc PIT base on salary for type: month
     * @see static::_calc
     */
    public static function calcTypeMonth($salary, array $options = array())
    {
        return static::_calc(static::CALC_TYPE_MONTH, $salary, $options);
    }

    /**
     * Helper: calc PIT base on salary for type: year
     * @see static::_calc
     */
    public static function calcTypeYear($salary, array $options = array())
    {
        return static::_calc(static::CALC_TYPE_YEAR, $salary, $options);
    }

    /**
     * Alter jqx grid column info
     * @param array $col Column data
     * @return array
     */
    // public static function jqxGridCol($col) { return $col; }

    /**
     * Get the create account.
     */
    public function createAccount()
    {
        return $this->belongsTo(Account::class, $this->columnName('created_by'));
    }

    /**
     * Get the delete account.
     */
    public function deleteAccount()
    {
        return $this->belongsTo(Account::class, $this->columnName('deleted_by'));
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
		// Prepare the data
        $qB = $this->qBFromJqxRequestPayload($data, $totalRowsQB, []);
        // var_dump($data);die($qB->toSql());
		// Format data
		// +++
        $rows = $qB->get()->map(function($row, $idx) {
                $prop;
                $row->setColVal(($prop = 'month_from') . '_text', numberFormat($row->colVal($prop)));
                $row->setColVal(($prop = 'month_to') . '_text', numberFormat($row->colVal($prop)));
                $row->setColVal(($prop = 'year_from') . '_text', numberFormat($row->colVal($prop)));
                $row->setColVal(($prop = 'year_to') . '_text', numberFormat($row->colVal($prop)));
                $row->setColVal(($prop = 'tax') . '_text', numberFormatTax($row->colVal($prop)));
                //
                return $row;
            })
        ;
        //.end
        
        // Return
        return $rows;
	}
}