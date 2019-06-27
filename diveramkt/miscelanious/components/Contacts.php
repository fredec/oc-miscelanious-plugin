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

	public function defineProperties(){
		return [
			'type' => [
				'title' => 'Type',
				'description' => 'The type of the contact',
				'type' => 'dropdown',
			],
		];
	}

	public function onRun(){
		$detect = new Mobile_Detect;

		$this->device = 'desktop';
		if ($detect->isMobile())
			$this->device = 'mobile';

		$this->contacts = $this->getContacts();
	}

	protected function getContacts(){
		if ($this->property('type') == "" || $this->property('type') == "all") {
			return Contact::all();
		}else{
			return Contact::where('type',$this->property('type'))->get();
		}

	}

	public function getTypeOptions(){
		return array('phone' => 'phone', 'whatsapp' => 'whatsapp', 'email' => 'email', 'link' => 'link', 'all' => 'all');
	}

	public $contacts;
	public $device;
}