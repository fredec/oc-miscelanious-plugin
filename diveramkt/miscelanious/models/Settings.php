<?php namespace Diveramkt\Miscelanious\Models;

use Model;
use Db;

class Settings extends Model
{
	public $implement = ['System.Behaviors.SettingsModel'];

    // A unique code
	public $settingsCode = 'miscelanious_settings';

    // Reference to field configuration
	public $settingsFields = 'fields.yaml';
}
