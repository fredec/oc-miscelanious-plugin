<?php namespace Diveramkt\Miscelanious\Models;

use Model;

/**
 * Model
 */
class ExtendBackendUsers extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    public $jsonable = ['infos'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'diveramkt_miscelanious_extend_backend_users';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
}
