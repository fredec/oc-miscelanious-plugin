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

	public function onRun(){
		$detect = new Mobile_Detect;

		$this->device = 'desktop';
		if ($detect->isMobile())
			$this->device = 'mobile';

		$this->phones = Phone::all();
	}

	public $phones;
	public $device;
}