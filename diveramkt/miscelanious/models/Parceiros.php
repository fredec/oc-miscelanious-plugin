<?php namespace Diveramkt\Miscelanious\Models;

use Model;

/**
 * Model
 */
class Parceiros extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sortable;
    
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'diveramkt_miscelanious_parceiros';

    public $attachOne = [
        'logo' => 'System\Models\File',
    ];

    public function scopeEnabled($query){
        $query->where('enabled',1);
    }

    /**
     * @var array Validation rules
     */
    public $rules = [
        'title' => 'required',
        'logo' => 'required',
    ];
}
