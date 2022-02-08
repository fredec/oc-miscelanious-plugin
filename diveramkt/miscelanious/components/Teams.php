<?php namespace Diveramkt\Miscelanious\Components;

use Cms\Classes\ComponentBase;

use Diveramkt\Miscelanious\Models\Equipecategorias;

class Teams extends ComponentBase
{

	public function componentDetails(){
		return [
			'name' => 'Teams',
			'description' => 'Get all teams and its members',
		];
	}

    public function onRun(){
		$this->records = Equipecategorias::orderBy('sort_order','desc')->enabled()->get(); // Just to keep compatibility with old version
	}

	public $records;
}