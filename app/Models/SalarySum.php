<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

/**
 * @class SalarySum
 */
class SalarySum extends AbstractModel
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
    protected $table = 'tbl_salary_sum';

    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const CREATED_AT = 'salary_sum_created_at';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    const UPDATED_AT = 'salary_sum_updated_at';
    /**
     * @var string Customize the names of the columns used to store the timestamps.
     */
    // const DELETED_AT = 'salary_sum_deleted_at';
    /**
     * @var string Customize the names of the columns used to store the status.
     */
    // const STATUS_COLUMN = 'salary_sum_status';

     /**
     * Primary key
     *
     * @var string
     */
    protected $primaryKey = 'salary_sum_id';

    /**
     * Column datafield prefix
     * @var string
     */
    public static $columnPrefix = 'salary_sum_';

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
}