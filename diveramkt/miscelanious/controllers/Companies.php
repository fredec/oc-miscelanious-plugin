<?php namespace Diveramkt\Miscelanious\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Companies extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController'    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = [
        'manage_company' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Diveramkt.Miscelanious', 'miscelanious');
    }
}
