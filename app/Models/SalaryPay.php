<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

/**
 * @class SalaryPay
 */
class SalaryPay extends AbstractModel
{
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        /* static::addGlobalScope('for_salary_id', function (Builder $builder) {
            $builder->where(static::columnName('for_salary_id'), null);
        }); */
    }
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tbl_salary_pay';

    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const CREATED_AT = 'salary_pay_created_at';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const UPDATED_AT = 'salary_pay_updated_at';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    // const DELETED_AT = 'salary_pay_deleted_at';
    /**
     * @var string Customize the names of the columns used to store the status.
     */
    // const STATUS_COLUMN = 'salary_pay_status';

     /**
     * Primary key
     *
     * @var string
     */
    protected $primaryKey = 'salary_pay_id';

    /**
     * Column datafield prefix
     * @var string
     */
    public static $columnPrefix = 'salary_pay_';

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
    // public static function jqxGridCol($col) { return $col; }

    /**
     * Get salary.
     * @return Salary|null
     */
    public function salary()
    {
        return $this->belongsTo(Salary::class, $this->columnName('salary_id'));
    }

    /**
     * Get salary detail.
     * @return SalaryDetail|null
     */
    public function salaryDetail()
    {
        return $this->belongsTo(SalaryDetail::class, $this->columnName('salary_detail_id'));
    }

    /**
     * Get Salary.
     * @return Salary|null
     */
    public function forSalary()
    {
        return $this->belongsTo(Salary::class, $this->columnName('for_salary_id'));
    }

    /**
     * Get the create account.
     * @return Account|null
     */
    public function createAccount()
    {
        return $this->belongsTo(Account::class, $this->columnName('created_by'));
    }

    /**
     * Fetch account's all salary pay info
     * @param int|string|array $accountId
     * @param array $options An array of options
     * @return array
     */
    public static function fetchAccountSalaryPay($accountId, array $options = array())
    {
        // Get, format input
        $isArr = is_array($accountId);
        $accountId = array_filter((array)$accountId);

        // Define vars
        $models = [
            'slr' => app()->make(Salary\SummaryStaff::class),
            'slrD' => app()->make(Salary\SummaryStaffDetail::class),
            'slrP' => app()->make(static::class),
        ];
        $cols = [
            // salary detail
            'slrd_account_id' => Salary\SummaryStaffDetail::columnName('account_id'),
            // salary pay
            'slrp_at' => SalaryPay::columnName('at'),
            'slrp_amount' => SalaryPay::columnName('amount'),
        ];
        $tbls = [
            'slr' => $models['slr']->getTable(),
            'slrD' => $models['slrD']->getTable(),
            'slrP' => $models['slrP']->getTable()
        ];

        // Create query builder
        // +++ 
        $qB = static::select("{$tbls['slrP']}.*")
            ->join(
                $tbls['slrD']
                , ("{$tbls['slrD']}." . ($pK = $models['slrD']->getKeyName()))
                , '=', "{$tbls['slrP']}." . SalaryPay::columnName($pK)
            )
            ->join(
                $tbls['slr']
                , ("{$tbls['slr']}." . ($pK = $models['slr']->getKeyName()))
                , '=', "{$tbls['slrD']}." . SalaryDetail::columnName($pK)
            )
            ->whereIn($cols['slrd_account_id'], $accountId)
        ;
        // +++ || add filters?
        if (isset($options['salary_type'])) {
            $qB->whereIn("{$tbls['slr']}.salary_type", (array)$options['salary_type']);
        }
        if (isset($options['timestamp_fr'])) {
            $qB->whereRaw(
                "`{$tbls['slrP']}`.`salary_pay_at` >= ?", date('Y-m-d 00:00:00', $options['timestamp_fr'])
            );
        }
        if (isset($options['timestamp_to'])) {
            $qB->whereRaw(
                "`{$tbls['slrP']}`.`salary_pay_at` <= ?", date('Y-m-d 23:59:59', $options['timestamp_to'])
            );
        }
        // +++
        $qB->orderBy("{$tbls['slrP']}.salary_pay_at", "ASC");
        // die($qB->toSql());

        // +++
        $collect = $qB->with('salaryDetail')->get();
        // dd($collect->toArray());

        // Return
        return $collect;
    }
}