<?php namespace Diveramkt\Miscelanious\Components;

use Cms\Classes\ComponentBase;

use Diveramkt\Miscelanious\Models\Parceiros as Records;

class Parceiros extends ComponentBase
{

	public function componentDetails(){
		return [
			'name' => 'Parceiros',
			'description' => 'Retornar os parceiros disponíveis',
		];
	}

	public function onRun(){
		$this->parceiros = Records::orderBy('sort_order','desc')->enabled()->get();
	}

	public $parceiros;
}