<?php namespace Diveramkt\Miscelanious\Models;

use Model;

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

    public function getPhoneCompleteAttribute()
    {
        return '(' . $this->area_code . ') ' . $this->phone;
    }

    public function getMobileCompleteAttribute()
    {
        return '(' . $this->area_code_mobile . ') ' . $this->mobile;
    }

}
