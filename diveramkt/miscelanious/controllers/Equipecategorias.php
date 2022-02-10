<?php namespace Diveramkt\Miscelanious\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Equipecategorias extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController',        'Backend\Behaviors\ReorderController', 'Backend\Behaviors\RelationController'    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $reorderConfig = 'config_reorder.yaml';
    public $relationConfig = 'config_relation.yaml';    

    public $requiredPermissions = [
        'manage_equipe' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Diveramkt.Miscelanious', 'miscelanious', 'miscelanious');
    }

    public function reorderExtendQuery($query)
    {
        $query->orderBy('sort_order', 'desc')->where('enabled',1);
        return $query;
    }

    public function listExtendQuery($query)
    {
        $query->orderBy('sort_order', 'desc');
        return $query;
    }
    
}
