<?php namespace Diveramkt\Miscelanious\Components;

use Cms\Classes\ComponentBase;

use Backend\Models\User;
use Backend\Models\UserRole;
use RainLab\Blog\Models\Post;

class Usersbackend extends ComponentBase
{

	public function componentDetails(){
		return [
			'name' => 'Usuários backend',
			'description' => 'Buscar os usuário do backend'
		];
	}

	public function defineProperties(){
		return [
			'userroles' => [
				'title' => 'Funções',
				'description' => 'Selecione as função dos usuários',
				"type" => "set",
				// "items" => [
				// 	"create" => "Create",
				// 	"update" => "Update",
				// 	"preview" => "Preview"
				// ],
				// "default": ["create", "update"]
			],

			'limit' => [
				'title' => 'Limite',
				'description' => 'Limite de usuário por vez',
				'type'              => 'string',
				'validationPattern' => '^[0-9]+$',
			],

			'id_user' => [
				'title' => 'Id do usuário',
				'description' => 'Consultar usuário pelo id',
				'type'              => 'string',
				'validationPattern' => '^[0-9]+$',
			],

			'posts_enabled' => [
				'title' => 'Habilitar postagens',
				'description' => 'Habilitar a busca de postagens para cada usuário',
				"type" => "checkbox",
				"default" => 1,
				"group" => 'Postagens'
			],
			'posts_limit' => [
				'title' => 'Limite de postagens',
				'description' => 'Limite de postagens para cada usuário',
				'type'              => 'string',
				'validationPattern' => '^[0-9]+$',
				"default" => 5,
				"group" => 'Postagens'
			],
		];
	}

	public function getUserrolesOptions() {
		$roles=UserRole::get();

		$return=[];
		if(count($roles)){
			foreach ($roles as $key => $value) {
				$return[$value->id]=$value->name;
			}
		}
		return $return;
	}

	// public function getSortOrderOptions() {
	// 	// return [
	// 	// 	'created_at asc' => 'Created at (ascending)',
	// 	// 	'created_at desc' => 'Created at (descending)',
	// 	// ];
	// 	return [
	// 		'asc' => 'Ordem (ascending)',
	// 		'desc' => 'Ordem (descending)',
	// 	];
	// }

	public $users=[], $user=[];
	public function onRun(){

		$limite_post=5; if(is_numeric($this->property('posts_limit'))) $limit_posts=$this->property('posts_limit');
		if($this->property('id_user')){
			$this->user=User::where('id',$this->property('id_user'))->first();
			if($this->property('posts_enabled')){
				$this->user->postagens=Post::IsPublished()->where('user_id',$user->id)->take($limite_post)->orderBy('published_at','desc')->get();
			}
		}else{

			$users=User::where('role_id','>',0);
			if($this->property('limit') && is_numeric($this->property('limit'))) $users=$users->take($this->property('limit'));
			$roles=$this->property('userroles');
			if(is_array($roles) && count($roles)){
				$users->where(function ($query) use ($roles) {
					foreach ($roles as $key => $value) {
						if(!$key) $query->where('role_id','=',$value);
						else $query->orWhere('role_id','=',$value);
					}
				});
			}
			$this->users=$users->get();

			if($this->property('posts_enabled')){
				foreach ($this->users as $key => $value) {
					$this->users[$key]->postagens=Post::IsPublished()->where('user_id',$value->id)->take($limite_post)->orderBy('published_at','desc')->get();
				}
			}

		}
	}

}