<?php namespace Diveramkt\Miscelanious\Components;

use Cms\Classes\ComponentBase;

use Backend\Models\User;
use Backend\Models\UserRole;
use RainLab\Blog\Models\Post;
use Cms\Classes\Page;
use Diveramkt\Miscelanious\Models\ExtendBackendUsers;
use Diveramkt\Miscelanious\Classes\Functions;

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
				// 'validationPattern' => '^[0-9]+$',
			],

			'posts_enabled' => [
				'title' => 'Habilitar postagens',
				'description' => 'Habilitar a busca de postagens para cada usuário',
				"type" => "checkbox",
				"default" => 1,
				"group" => 'Postagens'
			],
			'have_post' => [
				'title' => 'Usuário com postagens',
				'description' => 'Usuários que tenham ao menos uma postagem',
				"type" => "checkbox",
				"default" => 0,
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
			'postPage' => [
				'title'       => 'rainlab.blog::lang.settings.posts_post',
				'description' => 'rainlab.blog::lang.settings.posts_post_description',
				'type'        => 'dropdown',
				'default'     => 'blog/post',
				'group'       => 'Postagens',
			],
			'categoryPage' => [
				'title'       => 'rainlab.blog::lang.settings.posts_category',
				'description' => 'rainlab.blog::lang.settings.posts_category_description',
				'type'        => 'dropdown',
				'default'     => 'blog/category',
				'group'       => 'Postagens',
			],
		];
	}

	public function getCategoryPageOptions()
	{
		return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
	}

	public function getPostPageOptions()
	{
		return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
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

	public $posts_enabled=0, $postPage, $categoryPage;
	protected function prepareVars()
	{
        /*
         * Page links
         */
        $this->posts_enabled=$this->property('posts_enabled');
        if($this->posts_enabled){
        	$this->postPage = $this->property('postPage');
        	$this->categoryPage = $this->property('categoryPage');
        }
    }

    public $users=[], $user=[];
    public function onRun(){
    	$this->prepareVars();

    	$limite_post=5; if(is_numeric($this->property('posts_limit'))) $limit_posts=$this->property('posts_limit');
    	$table_user='backend_users';
    	$table_join=new ExtendBackendUsers(); $table_join=$table_join->table;
    	if($this->property('id_user')){
    		$user=User::where($table_user.'.id',$this->property('id_user'))
    		->select($table_user.'.*')->join($table_join.' as join','join.user_id','=',$table_user.'.id')->where('join.infos','like','%"enabled":"1"%');
    		if($this->property('have_post')){
    			$table_post=new Post();
    			$user=$user->join($table_post->table.' as post','post.user_id','=',$table_user.'.id');
    		}
    		$user=$user->first();
    		if($this->posts_enabled && isset($user->id)){
    			$posts=Post::IsPublished()->where('user_id',$user->id)->take($limite_post)->orderBy('published_at','desc')->get();
    			$user->postagens=$this->urlPost($posts);
    			$user=$this->preparSocial($user);
    			$this->user=$user;
    		}
    	}else{

    		$users=User::where($table_user.'.role_id','>',0)
    		->select($table_user.'.*')->join($table_join.' as join','join.user_id','=',$table_user.'.id')->where('join.infos','like','%"enabled":"1"%');
    		if($this->property('limit') && is_numeric($this->property('limit'))) $users=$users->take($this->property('limit'));
			if($this->property('have_post')){
    			$table_post=new Post();
    			$users=$users->join($table_post->table.' as post','post.user_id','=',$table_user.'.id');
    		}
    		$roles=$this->property('userroles');
    		if(is_array($roles) && count($roles)){
    			$users->where(function ($query) use ($roles) {
    				foreach ($roles as $key => $value) {
    					if(!$key) $query->where('role_id','=',$value);
    					else $query->orWhere('role_id','=',$value);
    				}
    			});
    		}
    		$users=$users->get();

    		if($this->posts_enabled){
    			foreach ($users as $key => $value) {
    				$posts=Post::IsPublished()->where('user_id',$value->id)->take($limite_post)->orderBy('published_at','desc')->get();
    				$value->postagens=$this->urlPost($posts);
    				$users[$key]=$this->preparSocial($value);
    			}
    		}
    		$this->users=$users;

    	}
    }

    public function preparSocial($user=false){
    	if(isset($user->id)){
    		if(isset($user->social_profiles[0]['type'])){
    			$social_profiles=$user->social_profiles;
    			foreach ($social_profiles as $key => $value) {
    				$icon=$value['type'];
    				if($icon == 'email') $icon='envelope';
    				$value['icon_class']=Functions::getIconClass($icon);
    				$social_profiles[$key]=$value;
    			}
    			$user->social_profiles=$social_profiles;
    		}
    	}
    	return $user;
    }

    public function urlPost($posts){
    	if($this->postPage || $this->categoryPage){
    		$posts->each(function($post) {
    			if($this->postPage) $post->setUrl($this->postPage, $this->controller);
    			if($this->categoryPage){
    				$post->categories->each(function($category) {
    					$category->setUrl($this->categoryPage, $this->controller);
    				});
    			}
    		});
    	}
    	return $posts;
    }

}