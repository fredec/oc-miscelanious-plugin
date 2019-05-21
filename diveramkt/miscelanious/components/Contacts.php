<?php namespace Diveramkt\Miscelanious\Components;

use Cms\Classes\ComponentBase;

use Diveramkt\Miscelanious\Models\Contact;
use Detection\MobileDetect as Mobile_Detect;

class Contacts extends ComponentBase
{

	public function componentDetails(){
		return [
			'name' => 'Contacts',
			'description' => 'Get registered contacts'
		];
	}

	public function onRun(){
		$detect = new Mobile_Detect;

		$this->device = 'desktop';
		if ($detect->isMobile())
			$this->device = 'mobile';

		$this->contacts = Contact::all();
	}

	public $contacts;
	public $device;
}