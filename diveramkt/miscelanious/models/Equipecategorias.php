<?php namespace Diveramkt\Miscelanious\Models;

use Model;

/**
 * Model
 */
class Equipecategorias extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sortable;

    public $implement = [];
    public $translatable = [];
    
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    public $hasMany = [
        'members' => [
            'Diveramkt\Miscelanious\Models\Equipe',
            'order'      => 'sort_order desc',
            'conditions' => 'enabled = 1',
        ],
        'equipe' => [
            'Diveramkt\Miscelanious\Models\Equipe',
            'order'      => 'sort_order desc',
            'conditions' => 'enabled = 1',
        ],
        'equipe_backend' => [
            'Diveramkt\Miscelanious\Models\Equipe',
            'order'      => 'sort_order desc',
        ],
    ];

    public function scopeEnabled($query){
        $query->where('enabled',1);
    }

    /**
     * @var string The database table used by the model.
     */
    public $table = 'diveramkt_miscelanious_equipe_categorias';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'title' => 'required',
    ];
}
