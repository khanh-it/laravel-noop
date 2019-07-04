<?php

namespace App\Models;

/**
 * @class TimeSheetItem
 */
class TimeSheetItem extends AbstractModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hr_time_sheet_item';

    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const CREATED_AT = 'time_sheet_item_created_at';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const UPDATED_AT = 'time_sheet_item_updated_at';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    // const DELETED_AT = 'time_sheet_item_deleted_at';
    /**
     * @var string Customize the names of the columns used to store the status.
     */
    // const STATUS_COLUMN = 'time_sheet_item_status';

    /**
     * Primary key
     * @var string
     */
    protected $primaryKey = 'time_sheet_item_id';

    /**
     * Column datafield prefix
     * @var string
     */
    public static $columnPrefix = 'time_sheet_item_';

    /**
     * jqx's grid columns & datafields!
     * @var array
     */
    protected static $jqxGridColumns = [
        [ 'datafield' => 'created_by' ],
        [ 'datafield' => 'created_at' ],
        [ 'datafield' => 'updated_at' ],
    ];

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
        return $this->belongsTo(Account::class, static::columnName('create_by'));
    }

    /**
     * Get time sheet detail.
     * @return mixed
     */
    public function tsDetail()
    {
        return $this->belongsTo(TimeSheetDetail::class, static::columnName('time_sheet_detail_id'));
    }

    /**
     * Get time sheet sign.
     * @return mixed
     */
    public function tsSign()
    {
        return $this->belongsTo(TimeSheetSign::class, static::columnName('time_sheet_sign_id'));
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
        // ...
		// Prepare the data
        $qB = $this->qBFromJqxRequestPayload($data, $totalRowsQB, [
            //
            // 'queryBuilder' => function(\Illuminate\Database\Eloquent\Builder $qB, $data) {},
            //
            // 'where' => function(&$join, &$where, &$filter, &$condition, &$value) {}
        ]);
        // var_dump($data);die($qB->toSql());
		// Format data
		// +++
        $rows = $qB->get()->map(function($row, $idx)
            use ($typeList, $defaultList, $statusList) {
                $prop;
                // $row->{($prop = $this->columnName('')) . ''} = $typeList[$row->{$prop}];
                //
                return $row;
            })
        ;
        //.end
        
        // Return
        return $rows;
	}
    
    /**
     * Find records match
     * @param int|string|array $id Record ids
     * @param int|string $tsdId Time sheet detail id
     * @return mixed
     */
    public static function findMatchTSDetail($id, $tsdId)
    {
        $model = app()->make(static::class);
        $isArr = \is_array($id);
        $id = (array)$id;
        $query = static
            ::whereIn($model->getKeyName(), $id)
            ->where(static::columnName('time_sheet_detail_id'), $tsdId)
        ;
        $return = $isArr ? $query->get() : $query->first();
        return $return;
    }
    
    /**
     * Find records match
     * @param int|string $tsdId
     * @param int|string $tsSignId
     * @param int|string $dateIndex
     * @param array $options An array of options
     * @return mixed
     */
    public static function findMatchTSDetailTSSignAndDateIndex($tsdId, $tsSignId, $dateIndex, array &$options = array())
    {
        $model = $query = static::whereRaw(1)
            ->where(static::columnName('time_sheet_detail_id'), $tsdId)
            ->where(static::columnName('time_sheet_sign_id'), $tsSignId)
            ->where(static::columnName('date_index'), $dateIndex)
            ->first()
        ;
        // die($query->toSql());
        if (is_array($options['update_data']))
        {
            $uData = $options['update_data'];
            if (is_null($model))
            {
                $model = app()->make(static::class);
                $model->{$model->columnName($prop = 'time_sheet_detail_id')} = $tsdId;
                $model->{$model->columnName($prop = 'time_sheet_sign_id')} = $tsSignId;
                $model->{$model->columnName($prop = 'date_index')} = $dateIndex;
            }
            $model->{$model->columnName($prop = 'time_hours')} = $uData[$prop];
            $model->{$model->columnName($prop = 'time_from')} = $uData[$prop];
            $model->{$model->columnName($prop = 'time_to')} = $uData[$prop];
            if (is_null($options['update_result']))
            {
                $options['update_result'] = true;
            }
            
            $options['update_result'] = $options['update_result'] && $model->save();
        }
        return $model;
    }
    
    /**
     * Truncate records match
     * @param int|string $tsdId
     * @param int|string $dateIndex
     * @param array $options An array of options
     * @return mixed
     */
    public static function truncateCell($tsdId, $dateIndex, array &$options = array())
    {
        $isArr = \is_array($tsdId);
        $tsdId = (array)$tsdId;
        $result = static::whereRaw(1)
            ->whereIn(static::columnName('time_sheet_detail_id'), $tsdId)
            ->where(static::columnName('date_index'), $dateIndex)
            ->delete()
        ;
        return $result;
    }
    
    /**
     * Cal hours by info from - to
     * @return float
     */
    public function calTimeByFromTo(array $options = array())
    {
        $hours = 0;
        $timeFrom = strtotime($this->colVal('time_from'));
        $timeTo = strtotime($this->colVal('time_to'));
        if ($timeFrom && $timeTo) {
            // Limit min, max?!
            $min = $options['min'];
            $max = $options['max'];
            if ($min) {
                $timeFrom = min($min, $timeFrom);
            }
            if ($max) {
                $timeTo = max($timeTo, $max);
            }
            //
            $arr = [
                [ $timeFrom, $timeTo ]
            ];
            // exclude range?!
            $excludeFr = $options['exclude_fr'];
            $excludeTo = $options['exclude_to'];
            if ($excludeFr && $excludeTo) {
                $arr = [
                    [ $timeFrom, max($timeFrom, min($excludeFr, $timeTo)) ],
                    [ min(max($timeFrom, $excludeTo), $timeTo), $timeTo ],
                ];
            }
            // echo '<pre>'; var_dump($arr); echo '</pre>';
            foreach ($arr as $item) {
                list($timeFrom, $timeTo) = $item;
                if ($timeFrom && $timeTo) {
                    $hours += (abs($timeTo - $timeFrom) / 3600);
                }
            }

            // ob_start();
            // var_dump([
            //     'date_time_from' => date('H:i:s',$timeFrom),
            //     'date_time_to' => date('H:i:s',$timeTo),
            //     'date_excludeFr'=> date('H:i:s',$options['exclude_fr']),
            //     'date_excludeTo'=> date('H:i:s',$options['exclude_to']),
            //     'time_from' => $timeFrom,
            //     'time_to' => $timeTo,
            //     'excludeFr' => $options['exclude_fr'],
            //     'excludeTo' => $options['exclude_to'],
            //     'arr' => $arr,
            //     'hours' => $hours,
            // ]);
            // $dump = ob_get_clean();
            // echo '<pre>' . preg_replace('/\]\=\>
            // (\s+)/m', '] => ', $dump) . '</pre>';

            // var_dump('$hours:', $hours);
        }
        return $hours;
    }

    /**
     * Get detail time sheet
     * @param int|string $month
     * @param int|string $year
     * @param int|string $departmentid
     * @param array $options
     * @return array
     */
    public static function getTimeSheetItem( array $options = array() )
    {
        // Get, format input()
        // +++ 
        
        if( $options['date'] ){

            $sat = [];
            $sun = [];
            $wkd = [];
            $query = [];
            foreach ($options['date'] as $dt){
                if($dt->format("D") == 'Sat' || $dt->format("D") == 'Sun'){
                    ($dt->format("D") == 'Sat') ? array_push($sat,$dt->format("d") - 1)  : array_push($sun,$dt->format("d") - 1);
                }else{
                    array_push($wkd,$dt->format("d") - 1);
                }
            }
            
            //ngày nghỉ thường
            if($wkd){
                $params=[
                    'tsdetail_id' => $options['tsdetail_id'],
                    'date' => $wkd,
                    'riceShiftOT'=> $options['riceShiftOT']
                ];
                $query_wkd = static::queryTimeWorkTime($params);
                $query['wkd']=$query_wkd;
            }

            //ngày nghỉ t7
            if($sat){
                $params=[
                    'tsdetail_id' => $options['tsdetail_id'],
                    'date' => $sat,
                    'riceShiftOT'=> $options['riceShiftOT']
                ];
                $query_sat = static::queryTimeWorkTime($params);
                $query['sat']=$query_sat;
            }

            //ngày nghỉ cn
            if($sun){
                $params=[
                    'tsdetail_id' => $options['tsdetail_id'],
                    'date' => $sun,
                    'riceShiftOT'=> $options['riceShiftOT']
                ];
                $query_sun = static::queryTimeWorkTime($params);
                $query['sun']=$query_sun;
            }

        }else{
            $params=[
                'tsdetail_id' => $options['tsdetail_id'],
            ];
            $query = static::queryTimeWorkTime($params);
        }

        return $query;

    }

    /**
     * query ngày nghỉ,ngày ot
     * @param int|string $departmentid
     * @param array $options
     * @return array
     */
    public static function queryTimeWorkTime(array $options = array())
    {
        $query = static::whereRaw(1)
            ->where(static::columnName('time_sheet_detail_id'), $options['tsdetail_id']);
            if($options['riceShiftOT'] == true){
                $query = $query->where(static::columnName('time_sheet_sign_id'),17);
            }else{
                $query = $query->whereNotIn(static::columnName('time_sheet_sign_id'),[2,17]);
            }
            
            //ngày 
            if($options['date']){
                $query = $query->whereIn(static::columnName('date_index'),$options['date']);
            }
            
        $query = $query->select('time_sheet_item_time_sheet_sign_id', 'time_sheet_item_time_hours',
        'time_sheet_item_time_from', 'time_sheet_item_time_to')->get();
        ob_start();
        if($query){
            $toltal = 0;
            foreach($query as $key){
                if($key['time_sheet_item_time_hours']){
                    if($key['time_sheet_item_time_hours'] == 8){
                        $toltal+=1;
                    }else{
                        $toltal+=$key['time_sheet_item_time_hours']/8;
                    }
                }
                if($key['time_sheet_item_time_from'] && $key['time_sheet_item_time_to']){
                    $from  = new \DateTime($key['time_sheet_item_time_from']);
                    $to = new \DateTime($key['time_sheet_item_time_to']);
                    $diff = $from->diff( $to );
                    $off = $diff->format('%h');
                    $toltal+=$off/8;
                }
            }
        }
        return $toltal;
    }
}