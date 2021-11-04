<?php namespace Diveramkt\Miscelanious\Models;

use Model;

/**
 * Model
 */
class Equipe extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sortable;
    
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    public $implement = [];
    public $translatable = [];

    public $attachOne = [
        'image' => 'System\Models\File',
    ];

    public $jsonable = ['links'];

    public function scopeEnabled($query){
        $query->where('enabled',1);
    }

    /**
     * @var string The database table used by the model.
     */
    public $table = 'diveramkt_miscelanious_equipe';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required',
    ];
}
