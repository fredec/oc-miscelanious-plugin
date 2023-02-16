<?php namespace Diveramkt\Miscelanious\Components;

use Cms\Classes\ComponentBase;
use RainLab\Blog\Models\Post as BlogPost;
use Db;

class Toposts extends ComponentBase
{
    /**
     * A collection of posts to display
     *
     * @var Collection
     */
    public $posts, $postPage;

    public function componentDetails()
    {
        return [
            'name'        => 'Top posts',
            'description' => 'Postagens mais acessadas'
        ];
    }

        /**
     * Definition of propertys
     * @return [array]
     */
        public function defineProperties()
        {
            return [
                'postPage' => [
                    'title'         => 'Post page',
                    'description'   => 'Page to show linked posts',
                    'type'          => 'dropdown',
                    'default'       => 'blog/post',
                    'group'         => 'Links',
                ]
            ];
        }

        public function onRun()
        {

            $data7=date('Y-m-d', strtotime("-7 days",strtotime(date('Y-m-d'))));
            $posts=BlogPost::take(3)
            ->select('rainlab_blog_posts.title', Db::raw('SUM(visits.visits) as total_visits'),'visits.post_id as id', 'rainlab_blog_posts.content_html', 'rainlab_blog_posts.excerpt', 'rainlab_blog_posts.slug','rainlab_blog_posts.published_at')
            ->distinct()
            ->join('pollozen_mostvisited_visits as visits','visits.post_id','=','rainlab_blog_posts.id')
            ->groupBy('visits.post_id')->groupBy('rainlab_blog_posts.title')->groupBy('rainlab_blog_posts.content_html')->groupBy('rainlab_blog_posts.excerpt')->groupBy('rainlab_blog_posts.slug')
            ->orderBy('total_visits','desc')->groupBy('rainlab_blog_posts.published_at')
            ->where('visits.date','>=',$data7)
            ->where('visits.date','<=',date('Y-m-d'))
            ->get();

        // print_r($data7);
        // $posts=Db::table('pollozen_mostvisited_visits as visits')
        // ->select('visits.post_id as id', Db::raw('SUM(visits.visits) as total_visits'), 'post.title', 'post.content_html', 'post.excerpt', 'post.slug')
        // ->join('rainlab_blog_posts as post','post.id','=','visits.post_id')
        // ->groupBy('visits.post_id')->groupBy('post.title')->groupBy('post.content_html')->groupBy('post.excerpt')->groupBy('post.slug')
        // ->distinct()
        // ->orderBy('total_visits','desc')
        // ->where('date','>=',$data7)
        // ->where('date','<=',date('Y-m-d'))
        // ->get();

            if($this->property('postPage')) $this->postPage=$this->property('postPage');
            foreach ($posts as $key => $post) {
                if(empty($post->excerpt)) $post->summary=$post->content_html;
                else $post->summary=$post->excerpt;

                if($this->postPage) $post->setUrl($this->postPage,$this->controller);
                // $post->url=url('noticia/'.$post->slug);
            }
            $this->posts=$posts;
        }

    }
