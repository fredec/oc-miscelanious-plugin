<?php namespace Diveramkt\Miscelanious\Models;

use Model;

/**
 * Model
 */
class Testmonial extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /*
     * Validation
     */
    public $rules = [
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'diveramkt_miscelanious_testmonials';
}