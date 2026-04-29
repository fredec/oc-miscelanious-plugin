<?php namespace Diveramkt\Miscelanious\Models;

use Model;

/**
 * Model
 */
class Schemafaq extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\SoftDelete;
    protected $dates = ['deleted_at'];

    use \October\Rain\Database\Traits\Sortable;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'diveramkt_miscelanious_schema_faq';
    public $implement = ['@RainLab.Translate.Behaviors.TranslatableModel'];

    public $translatable = [
        'title',
        'text',
    ];

    /**
     * @var array Validation rules
     */
    public $rules = [
        'title' => 'required',
        'text' => 'required',
    ];

    public function scopeEnabled($query){
        $query->where($this->table.'.enabled',1)->orderBy($this->table.'.sort_order','desc');
    }
}
