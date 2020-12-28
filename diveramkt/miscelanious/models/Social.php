<?php namespace Diveramkt\Miscelanious\Models;

use Model;

/**
 * Model
 */
class Social extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sortable;
    
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

    public function getFormattedlink()
    {
        if ($this->name == 'whatsapp') {
            $search = [' ', '+', '(', ')', '-', '.'];
            $return = 'https://api.whatsapp.com/send?phone=55'.str_replace($search, '', $this->link);

            if ($this->description) {
                $return .= '&text='.urlencode($this->description);
            }

            return $return;
        } else
            return $this->link;
    }
}