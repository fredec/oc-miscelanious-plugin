<?php namespace Diveramkt\Miscelanious\Components;

use Cms\Classes\ComponentBase;

use Diveramkt\Miscelanious\Models\Company;
use System\Classes\PluginManager;
use Diveramkt\Locais\Models\Settings as Locais;

class Companies extends ComponentBase
{

	public function componentDetails(){
		return [
			'name' => 'Companies',
			'description' => 'Get registered companies'
		];
	}

	public function defineProperties(){
		return [
			'company' => [
				'title' => 'Company',
				'description' => 'The company to show the data',
				'type' => 'dropdown',
			],
		];
	}

	public function onRun(){
		$this->company = $this->getCompany();
	}

	public function getCompanyOptions(){
		return $this->getAllCompany();
	}

	public function checkLocais(){
		if(PluginManager::instance()->hasPlugin('Diveramkt.Locais') && Locais::instance()->enabled_diversos) return true;
		return false;
	}

	protected function getCompany(){
		$return=[];

		if ($this->property('company') == "") {
			$return=Company::orderBy('sort_order', 'desc')->get();
		}elseif ($this->property('company') == "sim" || $this->property('company') == "nao") {
			if($this->property('company') == 'sim') $return=Company::where('main',1)->orderBy('sort_order', 'desc')->get();
			else $return=Company::where('main',0)->orderBy('sort_order', 'desc')->get();
		}else{
			$return=Company::where('id',$this->property('company')+1)->first();
			if($this->checkLocais()){
				if(isset($return->stateid)) $return->state=$return->stateid->sigla;
				if(isset($return->cityid)) $return->city=$return->cityid->titulo;
			}
			return $return;
		}

		if(count($return) && $this->checkLocais()){
			foreach ($return as $key => $value) {
				if(isset($value->stateid)) $return[$key]->state=$value->stateid->sigla;
				if(isset($value->cityid)) $return[$key]->city=$value->cityid->titulo;
			}
		}

		return $return;
	}

	protected function getAllCompany(){
		$query = Company::all();

		$result[''] = 'Todos';
		$result['sim'] = 'Principais';
		$result['nao'] = 'Secundários';
		foreach ($query as $id=> $c){
			$result[$id] = $c->name;
		}

		if(count($result) && $this->checkLocais()){
			foreach ($result as $key => $value) {
				if(isset($value->stateid)) $result[$key]->state=$value->stateid->sigla;
				if(isset($value->cityid)) $result[$key]->city=$value->cityid->titulo;
			}
		}
		
		return $result;
	}

	public $company;
}