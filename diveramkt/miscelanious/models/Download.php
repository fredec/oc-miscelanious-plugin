<?php namespace Diveramkt\Miscelanious\Models;

use Model;
use Config;

/**
 * Model
 */
class Download extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sortable;
    
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'diveramkt_miscelanious_downloads';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'title' => 'required',
    ];

    public function scopeEnabled($query) {
        return $query->where('enabled', true);
    }

    public function getLink() {
        if ($this->file != '') {
            return url(Config::get('cms.storage.media.path')).$this->file;
        } else if ($this->url_externa != '') {
            return $this->url_externa;
        }
    }
}
