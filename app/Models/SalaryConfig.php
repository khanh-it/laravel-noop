<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

/**
 * @class SalaryConfig
 */
class SalaryConfig extends AbstractModel
{
    /**
     * Local scope.
     * @param \Illuminate\Database\Eloquent\Builder $qB
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExcludeRelatives($qB)
    {
        $qB->whereNull(static::columnName('salary_id'));
    }
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hr_salary_config';

    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const CREATED_AT = 'salary_config_created_at';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const UPDATED_AT = 'salary_config_updated_at';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    // const DELETED_AT = 'salary_config_deleted_at';
    /**
     * @var string Customize the names of the columns used to store the status.
     */
    // const STATUS_COLUMN = 'salary_status';

    /**
     * Salary fixed (luong khoan) type (day - tinh theo ngay) 0
     * @var int 0
     */
    const SLR_FIX_TYPE_0 = 0;
    /**
     * Salary fixed (luong khoan) type (month - tinh theo thang) 1
     * @var int 1
     */
    const SLR_FIX_TYPE_1 = 1;
    /**
     * 
     * @return array
     */
    public static function getSlrFixedTypeList()
    {
        return [
            static::SLR_FIX_TYPE_0 => 'Ngày',
            static::SLR_FIX_TYPE_1 => 'Tháng'
        ];
    }
    /**
     * Helper: encode salary fixed data
     * @param array $data
     * @return string
     */
    public static function salaryFixedEncode(array $data)
    {
        return @\json_encode($data);
    }
    /**
     * Helper: decode salary fixed data
     * @param string $data
     * @return mixed
     */
    public static function salaryFixedDecode($data)
    {
        if (is_string($data)) {
            return @\json_decode($data, true);
        }
        return $data;
    }

    /**
     * Primary key
     * @var string
     */
    protected $primaryKey = 'salary_config_id';

    /**
     * Column datafield prefix
     * @var string
     */
    public static $columnPrefix = 'salary_config_';

    /**
     * jqx's grid columns & datafields!
     * @var array
     */
    protected static $jqxGridColumns = [];

    /**
     * Alter jqx grid column info
     * @param array $col Column data
     * @return array
     */
    // public static function jqxGridCol($col) {}

    /**
     * Get the create account.
     */
    public function createAccount()
    {
        return $this->belongsTo(Account::class, $this->columnName('created_by'));
    }

    /**
     * Cache static
     * @var array
     */
    protected static $_find1stDefault = [];

    /**
     * Find first default record
     * @param array $options An array of options
     * @return mixed
     */
    public static function find1stDefault(array $options = array())
    {
        // Cached?!
        $cKey = @unserialize($options);
        if (static::$_find1stDefault[$cKey]) {
            return static::$_find1stDefault[$cKey];
        }
        //.end
        // Get, format input(s)
        $depIdArr = [];
        if ($options['department_id']) {
            $depModel = Department::find($options['department_id']);
            if ($depModel) {
                $depIdArr = array_keys((array)$depModel->findAllAncestor());
            }
            unset($depModel);
        }
        // Create query builder
        $qB = static::excludeRelatives()
            ->where(function($_qB) use ($depIdArr) {
                $_qB->whereNull($colDepartmentId = static::columnName('department_id'));
                if (!empty($depIdArr)) {
                    $_qB->orWhereIn($colDepartmentId, $depIdArr);
                }
            })
        ;
        // die($qB->toSql());
        // Fetch data
        $records = $qB->get();
        $finalRecord = $records->filter(function($row) {
            return is_null($row->colVal('department_id'));
        })->first();
        // ||Loop recursive find record by department
        if ($records->count() && !empty($depIdArr)) {
            $recordWithDep = null;
            foreach ($depIdArr as $depId) {
                foreach ($records as $record) {
                    if (is_null($recordWithDep) && $depId && ($depId == $record->colVal('department_id'))) {
                        $recordWithDep = $record;
                        break;
                    }
                }
            }
        }
        // Return
        // +++ Merge data?!
        if ((true === $options['merge']) && ($recordWithDep && $finalRecord)) {
            $attrs = $recordWithDep->attributes;
            foreach ($recordWithDep->attributes as $attr => $value) {
                if ((is_null($value) || (0 === $value)) && is_numeric($finalRecord->{$attr})) {
                    $recordWithDep->{$attr} = $finalRecord->{$attr};
                }
            } unset($attr, $value);
        }
        // dd($depIdArr, $records, $recordWithDep ? $recordWithDep->toArray() : null, $finalRecord->toArray());
        $return = ($recordWithDep ?: $finalRecord);
        // Cache?!
        static::$_find1stDefault[$cKey] = $return;
        //.end
        return $return;
    }

    /**
     * Clone first default record
     * @see https://stackoverflow.com/questions/23895126/clone-an-eloquent-object-including-all-relationships
     * @param array $options An array of options
     * @return mixed
     */
    public static function clone1stDefault(array $options = array())
    {
        // Get, format options
        $options = array_replace($options, [
            'merge' => true
        ]);

        //
        $record2nd = null;
        $record = static::find1stDefault($options);
        if ($record) {
            $record2nd = $record->replicate();
            // Reset data?!
            //...
            //
            $record2nd->push();
        }
        return $record2nd;
    }

    /**
	 * Get data list
	 * @param array $options An array of options
     * @return array
	 */
    public static function fetchAll(array $options = array())
    {
        // Get, +format options
        // +++
        $limit = $options['limit'];

        // Create query builder
		$qB = static::excludeRelatives();
        //  ||Filter
        if (array_key_exists('department_id', $options)) {
            // +++
            $departmentId = $options['department_id'];
            $qB->where(static::columnName('department_id'), $departmentId);
        }
        if ($limit) {
            $qB->limit($limit);
        }
        // die($qB->toSql());
        // Return
		return $qB->get()->all();
	}

    /**
	 * Count records by
	 * @param array $options An array of options
     * @return array
	 */
    public static function countAllByDepartmentId(array $options = array())
    {
        // Get, +format options
        // +++

        // Create query builder
        $qB = static::excludeRelatives()
            ->select([
                $colDepartmentId = static::columnName('department_id'),
                \DB::raw('COUNT(*) AS `cnt`')
            ])
            ->groupBy([
                $colDepartmentId
            ])
        ;
        //  ||Filter
        // die($qB->toSql());
        // Return
        $return = $qB->get()->mapWithKeys(function($row) use ($colDepartmentId) {
            return [$row->{$colDepartmentId} => $row->cnt];
        });
		return $return->all();
	}

    /**
	 * Calculate insurance pays per employees
	 * @param array $options An array of options
     * @return double
	 */
    public function calPerEmployeesPays(array $options = array())
    {
        $result = (1 * $this->colVal('per_employees_pay_social'))
            + (1 * $this->colVal('per_employees_pay_medical'))
            + (1 * $this->colVal('per_employees_pay_unemployment'))
        ;
        return $result;
    }

    /**
	 * Calculate salary fixed unit price
	 * @param array $options An array of options
     * @return double
	 */
    public function calSalaryFixedUnitPriceDay(array $options = array())
    {
        $result = 0;
        $data = (array)$this->__get('salary_config_salary_fixed');
        // Don gia tinh theo ngay!
        if (static::SLR_FIX_TYPE_0 == $data['type']) {
            $result = $data['unit_price'];
        }
        // Don gia tinh theo thang!
        if (static::SLR_FIX_TYPE_1 == $data['type']) {
            if (!isset($options['working_days_of_month'])) {
                throw new \Exception('Option `working_days_of_month` is required!');
            }
            $result = ($data['unit_price'] / (1 * $options['working_days_of_month']));
        }
        return $result;
    }

    /**
     * @Overloading magic __get
     * @param string $prop Object's property name
     * @return mixed
     */
    public function __get($prop)
    {
        $return = parent::__get($prop);
        if (('salary_config_salary_fixed' === $prop)
            && is_string($return)
        ) {
            $return = ($this->{$prop} = static::salaryFixedDecode($return));
        }
        return $return;
    }
}