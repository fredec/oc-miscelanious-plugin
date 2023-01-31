<?php namespace Diveramkt\Miscelanious\Models;

use Model;

/**
 * Model
 */
class Contentblocks extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sortable;
    use \October\Rain\Database\Traits\Sluggable;
    
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    protected $slugs = ['slug' => 'title'];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'diveramkt_miscelanious_content_blocks';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'title' => 'required',
    ];
}
