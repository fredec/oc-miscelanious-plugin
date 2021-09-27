<?php namespace Diveramkt\Miscelanious\Components;

use Cms\Classes\ComponentBase;

use Diveramkt\Miscelanious\Models\Equipecategorias;

class Equipe extends ComponentBase
{

	public function componentDetails(){
		return [
			'name' => 'Equipe',
			'description' => 'Retornar os integrantes da equipe',
		];
	}

	public function onRun(){
		$this->equipe = Equipecategorias::orderBy('sort_order','desc')->enabled()->get();
	}

	public $equipe;
}