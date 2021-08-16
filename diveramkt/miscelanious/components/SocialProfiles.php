<?php namespace Diveramkt\Miscelanious\Components;

use Cms\Classes\ComponentBase;

use Diveramkt\Miscelanious\Models\Social;

class SocialProfiles extends ComponentBase
{

	public function componentDetails(){
		return [
			'name' => 'Social Profiles',
			'description' => 'Get registered social profiles'
		];
	}

	public function onRun(){
		$this->social_profiles = Social::orderBy('sort_order','desc')->get();
		$this->profiles = Social::all();
	}

	public $social_profiles;
	public $profiles;
}