<?php namespace Diveramkt\Miscelanious\Models;

use Model;
use Diveramkt\Miscelanious\Classes\Functions;

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

    public function afterFetch(){
        if(isset($this->links[0])){
            $links=$this->links;
            foreach ($links as $key => $value) {

                if($links[$key]['type'] == 'email') $links[$key]['url']='mailto:'.$links[$key]['link'];
                elseif($links[$key]['type'] == 'phone') $links[$key]['url']=Functions::phone_link($links[$key]['link']);
                elseif($links[$key]['type'] == 'whatsapp') $links[$key]['url']=Functions::whats_link($links[$key]['link']);
                else{
                    $links[$key]['url']=Functions::prep_url($links[$key]['link']);
                    $links[$key]['target']=Functions::target($links[$key]['url']);
                }

                if($links[$key]['type'] == 'email') $links[$key]['icon']=Functions::getIconClass('envelope');
                else $links[$key]['icon']=Functions::getIconClass($links[$key]['type']);
            }
            $this->links=$links;
        }
    }

}
