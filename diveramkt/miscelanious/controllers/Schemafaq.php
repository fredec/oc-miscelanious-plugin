<?php namespace Diveramkt\Miscelanious\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Schemafaq extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController',        'Backend\Behaviors\ReorderController'    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $reorderConfig = 'config_reorder.yaml';

    public $requiredPermissions = [
        'manage_schema_faq' 
    ];

    public function __construct()
    {
        parent::__construct();
        // BackendMenu::setContext('Diveramkt.Miscelanious', 'miscelanious');
    }

    public function reorderExtendQuery($query)
    {

        if(isset($this->params[0])){
            $exp=explode('_', $this->params[0]);
            if($exp[0] == 'blog' && isset($exp[1]) && is_numeric($exp[1])){
                $query->where('post_id',$exp[1]); 
            }
        }

        $query->orderBy('sort_order', 'desc')->enabled();

        return $query;
    }

    public function listExtendQuery($query)
    {
        $query->orderBy('sort_order', 'desc');
        return $query;
    }

}
