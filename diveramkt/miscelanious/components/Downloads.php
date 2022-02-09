<?php namespace Diveramkt\Miscelanious\Components;

use Cms\Classes\ComponentBase;

use Diveramkt\Miscelanious\Models\Download;


class Downloads extends ComponentBase
{

	public function componentDetails(){
		return [
			'name' => 'Downloads',
			'description' => 'Get all files',
		];
	}

    public function onRun(){
		$this->files = Download::orderBy('sort_order','asc')->enabled()->get();
	}

	public $files;
}