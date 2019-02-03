<?php namespace Diveramkt\Miscelanious\Models;

use Model;

/**
 * Model
 */
class Phone extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /*
     * Validation
     */
    public $rules = [
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'diveramkt_miscelanious_phones';

    public function scopeFirst($query)
    {
        return $query->first();
    }
}