<?php namespace Diveramkt\Miscelanious\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Downloads extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController',        'Backend\Behaviors\ReorderController'    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $reorderConfig = 'config_reorder.yaml';

    public $requiredPermissions = [
        'manage_downloads' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Diveramkt.Miscelanious', 'miscelanious', 'side-menu-downloads');
    }
}
