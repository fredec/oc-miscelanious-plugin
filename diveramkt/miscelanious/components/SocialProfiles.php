<?php namespace Diveramkt\Miscelanious\Components;

use Cms\Classes\ComponentBase;

use Diveramkt\Miscelanious\Models\Social;
use Diveramkt\Miscelanious\Classes\Functions;

class SocialProfiles extends ComponentBase
{

	public function componentDetails(){
		return [
			'name' => 'Social Profiles',
			'description' => 'Get registered social profiles'
		];
	}

	public function onRun(){
		$social_profiles = Social::orderBy('sort_order','desc')->get();
		$this->profiles = Social::all();

		foreach ($social_profiles as $key => $value) {
			if(!$value->description) $social_profiles[$key]->description=$value->link;
			
			if($value->name == 'email'){
				$url='mailto:'.$value->link;
				$social_profiles[$key]->attributes['name']='envelope';
			}elseif($value->name == 'whatsapp') $url=Functions::whats_link($value->link);
			elseif($value->name == 'phone') $url=Functions::phone_link($value->link);
			else $url=Functions::prep_url($value->link);

			$social_profiles[$key]->attributes['url']=$url;
			$social_profiles[$key]->attributes['target']=Functions::target($url);
		}
		$this->social_profiles=$social_profiles;
	}

	public $social_profiles;
	public $profiles;
}