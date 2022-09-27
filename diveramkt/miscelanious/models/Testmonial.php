<?php namespace Diveramkt\Miscelanious\Models;

use Model;

/**
 * Model
 */
class Testmonial extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sortable;

    public $implement = array();
    public $translatable = array();
    public $jsonable = ['infos'];

    // \Diveramkt\Miscelanious\Models\Testmonial::extend(function($model) {
    //     $model->implement = ['RainLab.Translate.Behaviors.TranslatableModel'];
    //     $model->translatable = ['name','position','testmonial','image'];
    // });
    
    /*
     * Validation
     */
    public $rules = [
        'name' => 'required',
        'testmonial' => 'required',
    ];

    public $attachOne = [
        'foto' => 'System\Models\File',
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'diveramkt_miscelanious_testmonials';

    public function scopeActive($query)
    {
        return $query->where('enabled', true);
    }
}