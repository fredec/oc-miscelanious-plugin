<?php namespace Diveramkt\Miscelanious\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Redirect;
use Backend;

class Homeplugin extends Controller
{
	public $implement = [
	];

	public function __construct()
	{
		parent::__construct();
	}

	public function index(){
		BackendMenu::setContext('Diveramkt.Miscelanious', 'miscelanious');
		$items=BackendMenu::listSideMenuItems();
		$redirect = array_shift($items);
		if(isset($redirect->url)) return Redirect::to($redirect->url);
		else Redirect::to(Backend::url('/'));
	}
}
