<?php namespace Diveramkt\Miscelanious\Components;

use Cms\Classes\ComponentBase;

use Diveramkt\Miscelanious\Models\Equipecategorias;

class Team extends ComponentBase
{

	public function componentDetails(){
		return [
			'name' => 'Team',
			'description' => 'Get all members from a selected team',
		];
	}

	public function defineProperties(){
		return [
			'team' => [
				'title' => 'Team',
				'description' => 'Choose the Team Category',
				'type' => 'dropdown',
			],
		];
	}

	public function onRun(){
		$this->teams = Equipecategorias::orderBy('sort_order','desc')->enabled()->get(); // Just to keep compatibility with old version
		$this->team = $this->getCategory();
		$this->records = $this->getAllMembers();
	}

	public function getTeamOptions(){
		return $this->getAllCategories();
	}

	protected function getAllCategories(){
		$result=array();
		$query = Equipecategorias::all();

		foreach ($query as $id=>$c)
			$result[$c->id] = $c->title;

		return $result;
	}

	protected function getCategory(){
		if ($this->property('team') == "") {
			return Equipecategorias::first();
		}else{
			return Equipecategorias::where('id',$this->property('team'))->first();
		}
	}

	protected function getAllMembers(){
		if ($this->property('team') == "") {
			$cat = Equipecategorias::enabled();
			if (@$cat->first())
				return $cat->first()->members;
			else
				return false;
		}else{
			$cat = Equipecategorias::where('id',$this->property('team'))->enabled();
			if (@$cat->first())
				return $cat->first()->members;
			else
				return false;
		}

	}

	public function scopeEnabled($query) {
		return $query->where('enabled', true);
	}

	public $team, $teams, $records;
}