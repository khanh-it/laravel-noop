<?php
namespace App\Models;
class Country extends AbstractModel{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tbl_country';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

     /**
     * Primary key
     *
     * @var string
     */
    protected $primaryKey = 'country_id';

    protected $rules = [
        'country_code'              => 'required|max:55',
        'country_name'              => 'required|max:255',
        'country_note'              => 'string|max:255',
        'country_status'            => 'integer|between:0,1',
        'country_create_account_id' => 'integer|min:0', 
        'country_create_time'       => 'integer|max:11',
        'country_delete_account_id' => 'integer|min:0',
        'country_delete_time'       => 'integer|max:11',
        'country_is_language'       => 'integer|between:0,1',
        'country_phone_code'        => 'string|max:255',
        'country_flag'              => 'string',
        'country_not_deleted'       => 'integer|between:0,1'

    ];

    protected $editableFields = [
        'country_code',
        'country_name',
        'country_note',
        'country_status',
        'country_create_account_id',
        'country_create_time',
        'country_delete_account_id',
        'country_delete_time',
        'country_is_language',
        'country_phone_code',
        'country_flag',
        'country_not_deleted'
    ];

    public function createAccount(){
        //return $this->belongsTo('App\Models\Account', 'country_create_account_id');
    }

    public function deleteAccount(){
        //return $this->belongsTo('App\Models\Account', 'country_delete_account_id');
    }
}