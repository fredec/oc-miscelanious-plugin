<?php namespace Diveramkt\Miscelanious\Models;

use Model;

/**
 * Model
 */
class Social extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sortable;
    
    public $implement = array();
    public $translatable = array();
    
    /*
     * Validation
     */
    public $rules = [
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'diveramkt_miscelanious_social';

    public $attachOne = [ 'icon' => 'System\Models\File' ];
}