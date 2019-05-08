<?php namespace Diveramkt\Miscelanious\Models;

use Model;
use Detection\MobileDetect as Mobile_Detect;

/**
 * Model
 */
class Company extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /**
     * @var array Validation rules
     */
    public $rules = [
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
}
