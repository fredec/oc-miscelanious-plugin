<?php namespace Diveramkt\Miscelanious\Components;

use Cms\Classes\ComponentBase;

use Diveramkt\Miscelanious\Models\Phone;
use Detection\MobileDetect as Mobile_Detect;

class Phones extends ComponentBase
{

	public function componentDetails(){
		return [
			'name' => 'Phones',
			'description' => 'Get registered phones'
		];
	}

	public function defineProperties(){
		return [
			'description' => [
				'title' => 'Descrição',
				'description' => 'Selecionar pela descrição',
				'type' => 'dropdown',
			],
		];
	}

	public function getDescriptionOptions(){
		$phones = Phone::where('description','!=','')->get();
		$retorno=array('' => 'Todos');
		foreach ($phones as $key => $value) {
			$retorno[$value->id]=$value->description;
		}
		// return array('phone' => 'phone', 'whatsapp' => 'whatsapp', 'email' => 'email', 'link' => 'link', 'all' => 'all');
		return $retorno;
	}

	public function onRun(){
		$detect = new Mobile_Detect;

		$this->device = 'desktop';
		if ($detect->isMobile())
			$this->device = 'mobile';

		if($this->property('description')) $this->phones = Phone::where('id',$this->property('description'))->first();
		else $this->phones = Phone::orderBy('sort_order','desc')->get();
		// else $this->phones = Phone::all();
	}

	public $phones;
	public $device;
}