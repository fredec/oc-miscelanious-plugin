<?php namespace Diveramkt\Miscelanious\Models;

use Model;
use Diveramkt\Miscelanious\Classes\Functions;
use System\Classes\MediaLibrary;

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
        'testmonial' => 'required_if:type,text|required_without:type',
        'video' => 'required_if:type,video',
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

    public function beforeSave($model=false){
        $infos=$this->infos;
        if(!is_array($infos)) $infos=[];
        foreach ($this->attributes as $key => $value) {
            if(!\Schema::hasColumn($this->table, $key)){
                $infos[$key]=$value;
                unset($this->$key);
            }
        }
        $this->infos=$infos;
    }

    public function getTypeAttribute(){
        if(isset($this->infos['type'])) return $this->infos['type'];
    }

    public function getCoverAttribute(){
        $settings=Functions::getSettings();
        if(!$settings->enabled_testimonials_imagemedia and isset($this->foto->path)) return $this->foto->path;
        elseif($this->image) return url(MediaLibrary::url($this->image));
    }

}