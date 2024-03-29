<?php namespace Diveramkt\Miscelanious\Models;

use Model;
use Detection\MobileDetect as Mobile_Detect;

/**
 * Model
 */
class Company extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sortable;
    
    public $implement = [];
    public $translatable = [];

    // \Diveramkt\Miscelanious\Models\Company::extend(function($model) {
    //     $model->implement = ['RainLab.Translate.Behaviors.TranslatableModel'];
    //     $model->translatable = ['name','city'];
    // });

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required',
    ];

    public $jsonable = ['mobiles','phones','emails'];

    public $attachMany = [
        'images' => 'System\Models\File',
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'diveramkt_miscelanious_companies';

    public function getAddressAttribute()
    {
        $address = $this->street;

        if(!empty($address)){
            if(!empty($this->number)){
                $address.= ', ' . $this->number;
            }

            if(!empty($this->addon)){
                $address.= ', ' . $this->addon;
            }

            if(!empty($this->neighborhood)){
                $address.= ' - ' . $this->neighborhood;
            }

            if(!empty($this->city)){
                $address.= "<br/>" . $this->city;
            }

            if(!empty($this->state)){
                $address.= ' - ' . $this->state;
            }

            if(!empty($this->zipcode)){
                $address.= ' - ' . $this->zipcode;
            }
        }
        
        return $address;
    }

    public function getDetectMobileAttribute()
    {
        $detect = new Mobile_Detect;

        $this->device = 'desktop';
        if ($detect->isMobile()) {
            $this->device = 'mobile';
            return true;
        }else
        return false;
    }

    public function getPhonelinkAttribute()
    {
        $search = [' ', '+', '(', ')', '-', '.'];
        return 'tel:55'.str_replace($search, '', $this->phone);
    }

    public function getWhatsapplinkAttribute()
    {
        $search = [' ', '+', '(', ')', '-', '.'];
        if ($this->detectMobile)
            return 'https://api.whatsapp.com/send?phone=55'.str_replace($search, '', $this->mobile);
        else
            return 'https://web.whatsapp.com/send?phone=55'.str_replace($search, '', $this->mobile);
    }

    public function getEmaillinkAttribute()
    {
        return 'mailto:'.$this->email;
    }

}
