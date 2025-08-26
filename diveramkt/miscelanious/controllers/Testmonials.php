<?php namespace Diveramkt\Miscelanious\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Testmonials extends Controller
{
    public $implement = ['Backend\Behaviors\ListController','Backend\Behaviors\FormController','Backend\Behaviors\ReorderController'];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $reorderConfig = 'config_reorder.yaml';

    public $requiredPermissions = [
        'manage_testmonials' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Diveramkt.Miscelanious', 'miscelanious', 'menu-testmonials');
    }

    public function reorderExtendQuery($query)
    {
        $query->orderBy('sort_order', 'desc');
        return $query;
    }

    public function listExtendQuery($query)
    {
        $query->orderBy('sort_order', 'desc');
        return $query;
    }

    public function formExtendFields($form)
    {
        $settings=\Diveramkt\Miscelanious\Classes\Functions::getSettings();
        if(!$settings->enabled_testimonials_name_html) $form->getField('name')->type = 'text';
        if(!$settings->enabled_testimonials_testmonial_html) $form->getField('testmonial')->type = 'textarea';
    }

}