<?php namespace Diveramkt\Miscelanious\Models;

use Model;
use Diveramkt\Miscelanious\Classes\Functions;

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

    public function getIconclassAttribute(){
     $icon=$this->name;
     if($icon == 'email') $icon='envelope';
     return Functions::getIconClass($icon);
 }

 public function getUrlAttribute(){
    if($this->name == 'email') $url='mailto:'.$this->link;
    elseif($this->name == 'whatsapp') $url=Functions::whats_link($this->link);
    elseif($this->name == 'phone') $url=Functions::phone_link($this->link);
    else $url=Functions::prep_url($this->link);
    return $url;
}

public function getTargetAttribute(){
    return Functions::target($this->url);
}

}