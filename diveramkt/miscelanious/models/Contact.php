<?php namespace Diveramkt\Miscelanious\Models;

use Model;
use Detection\MobileDetect as Mobile_Detect;

/**
 * Model
 */
class Contact extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    public $implement = [];
    public $translatable = [];
    
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;


/* ==== FUNCTION NOT USED ANYMORE, JUST TO KEEP COMPATIBILITY == */
    public function beforeSave()
    {
        // Autogenerate link according to the type of contact
        if ($this->type == 'phone') {
            $this->link = 'tel:+55' . $this->only_numbers($this->value);
        }else if ($this->type == 'whatsapp') {
            $this->link = 'https://web.whatsapp.com/send?phone=55' . $this->only_numbers($this->value);
        }else if ($this->type == 'email') {
            $this->link = 'mailto:' . $this->value;
        }else if ($this->type == 'link') {
            $this->link = $this->value;
        }
    }
/* ==== END FUNCTION NOT USED ANYMORE == */


    /**
     * @var string The database table used by the model.
     */
    public $table = 'diveramkt_miscelanious_contacs';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    private function only_numbers($string) {
        $search = [' ', '+', '(', ')', '-', '.'];
        return str_replace($search, '', $string);
    }

    public function getWhatsapplinkAttribute()
    {
        if ($this->type == 'whatsapp') {
            $search = [' ', '+', '(', ')', '-', '.'];
            if ($this->detectMobile)
                return 'https://api.whatsapp.com/send?phone=55'.str_replace($search, '', $this->value);
            else
                return 'https://web.whatsapp.com/send?phone=55'.str_replace($search, '', $this->value);
        } else
            return null;
    }

    // public function getIconOptions(){
    //     return [];
    // }

}
