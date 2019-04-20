<?php namespace Diveramkt\Miscelanious\Components;

use Cms\Classes\ComponentBase;

use Diveramkt\Miscelanious\Models\Testmonial;

class Testimonials extends ComponentBase
{

	public function componentDetails(){
		return [
			'name' => 'Testimonials',
			'description' => 'Get all testimonials registered'
		];
	}

	public function onRun(){
		$this->testimonials = Testmonial::all();
	}

	public $testimonials;
}