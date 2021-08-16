<?php namespace Diveramkt\Miscelanious\Components;

use Cms\Classes\ComponentBase;

use Diveramkt\Miscelanious\Models\Company;

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

	protected function getCompany(){
		if ($this->property('company') == "") {
			return Company::orderBy('sort_order', 'desc')->get();
		}elseif ($this->property('company') == "sim" || $this->property('company') == "nao") {
			if($this->property('company') == 'sim') return Company::where('main',1)->orderBy('sort_order', 'desc')->get();
			else return Company::where('main',0)->orderBy('sort_order', 'desc')->get();
		}else{
			return Company::where('id',$this->property('company')+1)->first();
		}

	}

	protected function getAllCompany(){
		$query = Company::all();

		$result[''] = 'Todos';
		$result['sim'] = 'Principais';
		$result['nao'] = 'Secundários';
		foreach ($query as $id=>$c)
			$result[$id] = $c->name;

		return $result;
	}

	public $company;
}