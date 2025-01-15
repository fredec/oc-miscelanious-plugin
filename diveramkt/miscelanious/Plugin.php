<?php namespace Diveramkt\Miscelanious;

use System\Classes\PluginBase;
use Str;
use Validator;
use Request;
use System\Models\PluginVersion;
use Event;
use RainLab\Translate\Classes\Translator;
use Diveramkt\Miscelanious\Classes\Functions;
use Diveramkt\Miscelanious\Classes\BackendHelpers;
use Diveramkt\Miscelanious\Models\ExtendBackendUsers;
use Db;
use Schema;
use Indikator\News\Models\Subscribers;

class Plugin extends PluginBase
{
    public $translator=false, $activeLocale=false, $translate_active=false;
    public function registerComponents()
    {
        return [
            'Diveramkt\Miscelanious\Components\Companies' => 'Companies',
            'Diveramkt\Miscelanious\Components\Phones' => 'Phones',
            'Diveramkt\Miscelanious\Components\Contacts' => 'Contacts',
            'Diveramkt\Miscelanious\Components\SocialProfiles' => 'SocialProfiles',
            'Diveramkt\Miscelanious\Components\Testimonials' => 'Testimonials',
            'Diveramkt\Miscelanious\Components\Parceiros' => 'Parceiros',
            'Diveramkt\Miscelanious\Components\Team' => 'Team',
            'Diveramkt\Miscelanious\Components\Teams' => 'Teams',
            'Diveramkt\Miscelanious\Components\Downloads' => 'Downloads',
            'Diveramkt\Miscelanious\Components\Usersbackend' => 'Usersbackend',
            'Diveramkt\Miscelanious\Components\Toposts'      => 'Toposts',
            'Diveramkt\Miscelanious\Components\GenericForm' => 'genericForm',
        ];
    }
    public function registerPageSnippets()
    {
        return [
            'Diveramkt\Miscelanious\Components\Companies' => 'Companies',
            'Diveramkt\Miscelanious\Components\Phones' => 'Phones',
            'Diveramkt\Miscelanious\Components\Contacts' => 'Contacts',
            'Diveramkt\Miscelanious\Components\SocialProfiles' => 'SocialProfiles',
            'Diveramkt\Miscelanious\Components\Testimonials' => 'Testimonials',
        ];
    }

    public function registerSettings()
    {
        return [
            'settings' => [
                'label'       => 'diveramkt.miscelanious::lang.config.miscelanious',
                'description' => 'diveramkt.miscelanious::lang.config.description',
                'category'    => 'DiveraMkt',
                'icon'        => 'icon-cog',
                'class'       => 'DiveraMkt\Miscelanious\Models\Settings',
                'order'       => 500,
                'keywords'    => 'diversos miscelanious diveramkt',
                // 'permissions' => ['Miscelanious.manage_upload'],
                'permissions' => ['manage_miscelanious_config'],
            ]
        ];
    }

    /**
     * Returns plain PHP functions.
     *
     * @return array
     */
    private function getPhpFunctions()
    {
        return [
            'phone_number' => function ($string) {
                $search = [' ', '+', '(', ')', '-', '.'];
                return str_replace($search, '', $string);
            },
            'phone_link' => function ($string, $cod='') {
                return Functions::phone_link($string, $cod);
            },
            'only_numbers' => function ($string) {
                // $search = [' ', '+', '(', ')', '-', '.'];
                // return str_replace($search, '', $string);
                return preg_replace("/[^0-9]/", "", $string);
            },
            'whats_link' => function ($tel, $msg=false) {
                return Functions::whats_link($tel, $msg);
            },
            'whats_share' => function ($text) {
                return Functions::whats_share($text);
            },
            'prep_url' => function($url) {
                return Functions::prep_url($url);
            },
            'icon_settings' => function($icon) {
                return Functions::getIconClass($icon);
            },
            'canonical_url' => function($url=''){
                // $url=Request::url('/');
                if(BackendHelpers::isTranslate()){
                    $translator=\RainLab\Translate\Classes\Translator::instance();
                    $lang=$translator->getLocale();
                    if((strstr($url.' ', '/'.$lang.' '))) $url=str_replace('/'.$lang.' ', '', $url.' ');
                    elseif((strstr($url.'/', '/'.$lang.'/'))) $url=str_replace('/'.$lang.'/', '/', $url.'/');
                }
                return $url;
            },
            'target' => function($link){
                return Functions::target($link);
            },
            'video_embed' => function($url, $autoplay=0, $controls=1) {
                if(strpos("[".$url."]", "youtu.be/") || strpos("[".$url."]", "youtube")){
                    if(strpos("[".$url."]", "&feature")){
                        preg_match_all("#&feature(.*?)&#s", $url, $result);
                        if(isset($result[0][0])) $url=str_replace($result[0][0], '&', $url);
                        else{
                            $url=explode('&feature', $url);
                            $url=$url[0];
                        }
                    }
                    $retorno='';

                    if(strpos("[".$url."]", "&")){
                        $exp=explode('&', $url);
                        foreach ($exp as $key => $value) {
                            if($key > 0) $url=str_replace('&'.$value,'', $url);
                        }
                    }

                    if(strpos("[".$url."]", "watch?v=")) $retorno=str_replace('/watch?v=', '/embed/', str_replace('&feature=youtu.be','',$url));
                    elseif(strpos("[".$url."]", "youtu.be/")){
                        $exp=explode('youtu.be/', $url);
                        if(isset($exp[1])){
                            $retorno='https://www.youtube.com/embed/'.$exp[1];
                        }else $retorno=$url;
                    }else $retorno=$url;

                    if(strpos("[".$url."]", "youtube.com/shorts/")){
                        $exp=explode("youtube.com/shorts/", $retorno);
                        $exp=end($exp);
                        $exp=explode("?", $exp);
                        $retorno='https://www.youtube.com/embed/'.$exp[0];
                    }

                    $muted=0; if($autoplay) $muted=1;
                    return $retorno.'?rel=0&controls='.$controls.'&mute='.$muted.'&amp;start=1&amp;autoplay='.$autoplay.'&amp;loop=1&amp;background=1';
                }elseif(strpos("[".$url."]", "vimeo.com")){
                    $par=explode('/', $url);
                    return 'https://player.vimeo.com/video/'.end($par).'?autoplay='.$autoplay.'&mute='.$muted.'&loop=1&background=1';
                }
                return $url;
            },
            'youtube_thumb' => function($url, $tamanho=1) {
                $numero = 0;
                if(strpos("[".$url."]", "embed")){
                    $exp=explode('/', $url);
                    $exp=explode('?',end($exp));
                    $url=array();
                    $url[0]='v='.$exp[0];
                }elseif(strpos("[".$url."]", "youtu.be/")){
                    // https://youtu.be/ftFSKcSubKQ
                    $url_=explode('/', $url);
                    $url_=end($url_);

                    $url=array();
                    $url[0]='v='.$url_;
                }else{
                    $url = str_replace('&', '&amp;', $url);
                    $url = explode('&amp;', $url);
                }
                if(isset($url[0])){
                    if($tamanho == 1){
                        return 'https://i1.ytimg.com/vi/' . substr(stristr($url[0], 'v='), 2) . '/' . $numero . '.jpg';
                    }elseif($tamanho == 2){
                        return 'https://i1.ytimg.com/vi/' . substr(stristr($url[0], 'v='), 2) . '/hqdefault.jpg';
                    }elseif($tamanho == 3){
                        return 'https://img.youtube.com/vi/' . substr(stristr($url[0], 'v='), 2) . '/mqdefault.jpg';
                    }elseif($tamanho == 4){
                        return 'https://img.youtube.com/vi/' . substr(stristr($url[0], 'v='), 2) . '/maxresdefault.jpg';
                    }
                }
                return false;
            },
            'create_slug' => function($string) {
                // $table = array(
                //     'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
                //     'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                //     'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
                //     'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
                //     'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
                //     'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
                //     'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
                //     'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r', '/' => '-', ' ' => '-', ',' => '', ':' => '-'
                // );
                // $stripped = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $string);
                // return strtolower(strtr($string, $table));
                return Str::slug(strip_tags($string));
            },
            'file_exists_media' => function($string){
                if(str_replace(' ','',$string) == '' || is_numeric($string)) return false;
                $base='storage/app/media';
                if(file_exists($base.$string)) return true;
                else return false;
            },
            'verificar_video_youtube' => function($url){
                if ((strstr($url, 'youtube') || strstr($url, 'youtu.be'))) return true;
                else return false;
            },
            'str_replace' => function($string, $busca, $subistituir){
                return str_replace($busca, $subistituir, $string);
            },
            'urllinkstring' => function( $text ) {
                $text = ' ' . html_entity_decode( $text );
    // Full-formed links
                $text = preg_replace(
                    '#(((f|ht){1}tps?://)[-a-zA-Z0-9@:%_\+.~\#?&//=]+)#i',
                    '<a style="text-decoration: underline;" href="\\1" target=_blank>\\1</a>',
                    $text
                );
    // Links without scheme prefix (i.e. http://)
                $text = preg_replace(
                    '#([[:space:]()[{}])(www.[-a-zA-Z0-9@:%_\+.~\#?&//=]+)#i',
                    '\\1<a style="text-decoration: underline;" href="http://\\2" target=_blank>\\2</a>',
                    $text
                );
    // E-mail links (mailto)
                $text = preg_replace(
                    '#([_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3})#i',
                    '<a style="text-decoration: underline;" href="mailto:\\1" target=_blank>\\1</a>',
                    $text
                );

                return $text;
            },
            'strpos' => function($string, $procura){
                return strpos("[".$string."]", "$procura");
            },
            'data_formato' => function($data, $for='%A, %d de %B de %Y'){
                return Functions::data_formato($data, $for);
            },

            'get_translate' => function($translate=false, $parent=false, $get=false){
                // return Functions::get_translate($translate, $parent, $get);
                if(!BackendHelpers::isTranslate()) return $translate;
                if(!$this->translator or !$this->activeLocale){
                    $this->translator = Translator::instance();
                    $this->activeLocale = $this->translator->getLocale();
                }

                if(!$translate) return;
                // echo 'teste: '.$translate->id;
                // if($this->translate_active_infos){
                //     if(isset($this->translate_active_infos[$translate->id][$this->activeLocale])) echo json_encode($this->translate_active_infos[$translate->id][$this->activeLocale]);
                // }
                // if(!$this->translate_active && isset($translate->id) && isset($this->translate_active_infos[$translate->id][$this->activeLocale])){
                //     $this->translate_active=$this->translate_active_infos[$translate->id][$this->activeLocale];
                // }

                if(!$this->translate_active || (isset($translate->translations) && count($translate->translations) > 0)){
                    foreach ($translate->translations as $key => $value) {
                        // $this->translate_active_infos[$translate->id][$value->locale]=$value;
                        if($value->locale!=$this->activeLocale) continue;
                        $this->translate_active=$value;
                    }
                }
                if(isset($this->translate_active['attribute_data']) && !empty($this->translate_active['attribute_data'])){
                    $trans=json_decode($this->translate_active['attribute_data']);

                    if($get && isset($trans->$parent[$get]) && !empty(trim(strip_tags($trans->$parent[$get])))) return $trans->$parent[$get];
                    elseif(isset($trans->$parent) && !is_array($trans->$parent) && !empty(trim(strip_tags($trans->$parent)))) return $trans->$parent;
                    elseif(isset($trans->$parent) && is_array($trans->$parent) && count($trans->$parent)) return $trans->$parent;
                }

                $return_parent=$translate->$parent;
                if(!is_array($return_parent)){
                    if(Functions::isJson($return_parent)) $return_parent=json_decode($return_parent);
                }
                if($get && $parent && isset($return_parent[$get])) return $return_parent[$get];
                elseif($parent && isset($return_parent)) return $return_parent;
            },

            'formatValue' => function($value){
                return Functions::formatValue($value);
            },

            'limit_word' => function($string, $limit=10, $com='...'){
                $exp=array_filter(explode(' ', strip_tags($string))); $total=$exp;
                $return=implode(' ', array_splice($exp, 0, $limit));
                if(count($total)>$limit) $return.=$com;
                return $return;
            },

            'empty_text' => function($text_html){
                $text=str_replace(' ','',strip_tags($text_html));
                $conter='a-zA-Z0-9';
                $text= preg_replace("/[^".$conter."]/", "", $text);
                if(empty($text)) return 1;
                else return 0;
            },

            'dark_or_light_color' => function($rgb_color) {
                $rgb = $this->HTMLToRGB($rgb_color);
                $hsl = $this->RGBToHSL($rgb);
                if($hsl->lightness > 200) {
                    return 'light';
                } else {
                    return 'dark';
                }
            },

            'hex_to_rgba' => function($hex, $opacity = 0.5) {
                list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
                $rgba = 'rgba('.$r.', '.$g.', '.$g.', '.$opacity.')';
                return $rgba;
            },

            'add_tag_image' => function($text, $tag_search='loading="lazy"') {
                $tags=explode(' ', $tag_search);
                preg_match_all("#<img (.*?)>#s", $text, $result);
                if(isset($result[0][0])){
                    foreach ($result[0] as $key => $value) {
                        foreach ($tags as $key => $tag) {
                            if(!strpos("[".$value."]", trim($tag))){
                                $text=str_replace($result[1][$key], $result[1][$key].' '.$tag, $text);
                            }
                        }
                    }
                }
                return $text;
            },

            'getHeightimage' => function($image=false, $width=false){
                if(!$image) return;
                $image=trim(str_replace(' /', '', ' '.$image));
                $image=str_replace('%20', ' ', $image);
                if(!file_exists($image)) return;
                $image=getimagesize($image);
                $height=$image[1];
                if(!isset($image[0]) || !isset($image[1])) return;
                if($width) $height=($width*$image[1])/$image[0];
                return floor($height);
            },
            'getWidthimage' => function($image=false, $height=false){
                if(!$image) return;
                $image=trim(str_replace(' /', '', ' '.$image));
                $image=str_replace('%20', ' ', $image);
                if(!file_exists($image)) return;
                $image=getimagesize($image);
                if(!isset($image[0]) || !isset($image[1])) return;
                $width=$image[0];
                if($height) $width=($height*$image[0])/$image[1];
                return floor($width);
            },
            'webspeed' => function(){
                return Functions::webspeed();
            },

            'logo_site' => function($width=false){
                $image=false;
                if(BackendHelpers::isArcaneSeo()) $arcane=\Arcane\Seo\Models\Settings::instance();
                if(isset($arcane->logo->path)) $image=$arcane->logo->path;
                else{
                    $thema=new \Cms\Classes\Theme();
                    $thema=$thema->getActiveTheme();
                    if(isset($thema->site_logo->path)) $image=$thema->site_logo->path;
                }
                if($width && BackendHelpers::isUpload()){
                    $image_resize=new \Diveramkt\Uploads\Classes\Image($image);
                    return $image_resize->resize($width, auto, []);
                }else return $image;
            },

            'logo_email' => function($width=false){
                $image=false;
                if(BackendHelpers::isArcaneSeo()) $arcane=\Arcane\Seo\Models\Settings::instance();
                if(isset($arcane->logo_email->path)) $image=$arcane->logo_email->path;
                elseif(isset($arcane->logo->path)) $image=$arcane->logo->path;
                else{
                    $thema=new \Cms\Classes\Theme();
                    $thema=$thema->getActiveTheme();
                    if(isset($thema->site_logo->path)) $image=$thema->site_logo->path;
                }
                if($width && BackendHelpers::isUpload()){
                    $image_resize=new \Diveramkt\Uploads\Classes\Image($image);
                    return $image_resize->resize($width, auto, []);
                }else return $image;
            },

            'bottom_email' => function($width=false){
                $image=false;
                if(BackendHelpers::isArcaneSeo()) $arcane=\Arcane\Seo\Models\Settings::instance();
                if(isset($arcane->bottom_email->path)) $image=$arcane->bottom_email->path;
                return $image;
            },

            'logo_site_white' => function($width=false){
                $image=false;
                if(BackendHelpers::isArcaneSeo()) $arcane=\Arcane\Seo\Models\Settings::instance();
                if(isset($arcane->logo_white->path)) $image=$arcane->logo_white->path;
                else{
                    $thema=new \Cms\Classes\Theme();
                    $thema=$thema->getActiveTheme();
                    if(isset($thema->site_logo_white->path)) $image=$thema->site_logo_white->path;
                }
                if($width && BackendHelpers::isUpload()){
                    $image_resize=new \Diveramkt\Uploads\Classes\Image($image);
                    return $image_resize->resize($width, auto, []);
                }else return $image;
            },

            'name_site' => function(){
                if(BackendHelpers::isArcaneSeo()){
                    $arcane=\Arcane\Seo\Models\Settings::instance();
                    if(isset($arcane->site_name)) return $arcane->site_name;
                }
            },

            'is_mobile' => function(){
                return Functions::is_mobile();
            },

        ];
    }

    /**
     * Add Twig extensions.
     *
     * @see Text extensions http://twig.sensiolabs.org/doc/extensions/text.html
     * @see Intl extensions http://twig.sensiolabs.org/doc/extensions/intl.html
     * @see Array extension http://twig.sensiolabs.org/doc/extensions/array.html
     * @see Time extension http://twig.sensiolabs.org/doc/extensions/date.html
     *
     * @return array
     */
    public function registerMarkupTags()
    {
        $filters = [];
        // add PHP functions
        $filters += $this->getPhpFunctions();

        return [
            'filters'   => $filters,
        ];
    }

    public function registerMailTemplates()
    {
        return [
            'diveramkt.miscelanious::mail.message_default',
        ];
    }

    public function boot(){

        // \Event::listen('mailer.beforeSend', function ($view,$data,$callback) {
        //     // $texto=json_encode($view).' - '.json_encode($data).' - '.json_encode($callback).' - ';
        //     // $arquivo = "meu_arquivo.txt";
        //     // $fp = fopen($arquivo, "a+");
        //     // fwrite($fp, $texto);
        //     // fclose($fp);
        //     // $callback->replyTo('rh@memorialpaxdeminas.com.br');
        //     $view->replyTo('rh@memorialpaxdeminas.com.br');
        // });

        Event::listen('mailer.prepareSend', function ($mailerInstance,$view,$message) {
            $settings=Functions::getSettings();
            if($view == 'diveramkt.goldsystem::mail.message_default') return;
            if($settings->enabled_sender_email_replyTo){
                $configs=\System\Models\MailSetting::instance();
                if($view != 'martin.forms::mail.autoresponse') return;
                // throw new ApplicationException(json_encode($view));
                if($configs->sender_email) $message->replyTo($configs->sender_email);
            }
        });

        \Event::listen('pages.menuitem.listTypes', function() {
            if(!BackendHelpers::isBlogRainlab() || !BackendHelpers::isBlogTagsBedard()) return;
            return [
                'list-bedard-blogtags'      => 'Lista tags do Blog',
            ];
        });
        \Event::listen('pages.menuitem.resolveItem', function($type, $item, $url, $theme) {
            $return=[];
            if($item->cmsPage) $page = \Cms\Classes\Page::loadCached($theme, $item->cmsPage);
            if ($type == 'list-bedard-blogtags' && $item->cmsPage) {
                $tags=\RainLab\Blog\Models\Post::isPublished()
                ->join('bedard_blogtags_post_tag as join','join.post_id','=','rainlab_blog_posts.id')
                ->join('bedard_blogtags_tags as tags','tags.id','=','join.tag_id')
                ->select('tags.*')->distinct()->orderBy('tags.name','asc')
                ->get();
                if(isset($tags[0]->id)){
                    $items=[];
                    foreach ($tags as $key => $value) {
                        $url = \Cms\Classes\Page::url($page->getBaseFileName(), ['tag' => $value->slug]);
                        $items[] = [
                            'title' => $value->name,
                            'url'   => str_replace('/default','',$url),
                            // 'mtime' => $category->updated_at
                        ];
                    }
                    $return['items']=$items;
                }
            }
            return $return;
        });
        \Event::listen('pages.menuitem.getTypeInfo', function ($type) {
            if ($type === 'list-bedard-blogtags') {
                $theme = \Cms\Classes\Theme::getActiveTheme();
                $pages = \Cms\Classes\Page::listInTheme($theme, true);
                return [
                    'dynamicItems' => true,
                    'cmsPages' => $pages,
                ];
            }
        });

        Event::listen('backend.page.beforeDisplay', function ($backendController,$action,$params) {
            $backendController->addDynamicMethod('onGetBlocksContent', function($query) use ($backendController) {
                $blocos=\Diveramkt\Miscelanious\Models\Contentblocks::get();
                $return=[];
                if(isset($blocos[0]->id)){
                    foreach ($blocos as $key => $value) {
                        $return[$value->slug]=$value->title;
                    }
                }
                return [
                    'blocks' => $return,
                    'count' => count($blocos),
                ];
            });
            $backendController->addJs('/plugins/diveramkt/miscelanious/assets/js/blocks_content.js', 'Diveramkt.Miscelanious');
            $backendController->addCss('/plugins/diveramkt/miscelanious/assets/css/blocks_content.css', 'Diveramkt.Miscelanious');

            if(!$backendController instanceof \Diveramkt\Miscelanious\Controllers\Contentblocks) {
                \Backend\FormWidgets\RichEditor::extend(function($widget) {
            // $widget->addCss('/plugins/diveramkt/lotofacil/assets/style_editor.css','0.0.0');
                    $widget->addJs('/plugins/diveramkt/miscelanious/assets/js/addblockcontent.js','0.0.2');
                });
            }

        });

        Event::listen('cms.page.render', function ($controller,$pageContents) {
            if(\Diveramkt\Miscelanious\Models\Contentblocks::count()){
                // $blocks=\Diveramkt\Miscelanious\Models\Contentblocks::get();
                $replace1=[]; $replace2=[];

                foreach (\Diveramkt\Miscelanious\Models\Contentblocks::get() as $key => $value) {
                    if(isset($value->type) && $value->type == 1) $blocks[$value->slug]=$value->content_code;
                    else $blocks[$value->slug]=$value->content;
                }

                $inicio='<figure '; $fim='</figure>';
                preg_match_all("#".$inicio."(.*?)".$fim."#s", $pageContents, $figures);

                if(isset($figures[0][0])){
                    foreach ($figures[0] as $key => $value) {
                        if(!strpos("[".$figures[1][$key]."]",'data-block-content="true"')) continue;

                        $inicio='data-snippet="'; $fim='"';
                        preg_match_all("#".$inicio."(.*?)".$fim."#s", $value, $code);
                        if(!isset($code[1][0])) continue;

                        array_push($replace1, $value);
                        if(isset($blocks[$code[1][0]])) array_push($replace2, $blocks[$code[1][0]]);
                        else array_push($replace2, '');
                    }
                }

                // foreach ($blocks as $key => $value) {
                //     array_push($replace1, '{{'.$value->slug.'}}');
                //     array_push($replace2, $value->content);
                // }
                return str_replace($replace1, $replace2, $pageContents);
            }
        });

        \Diveramkt\Miscelanious\Classes\Sitemapload::load();
        \Event::listen('backend.page.beforeDisplay', function($controller, $action, $params) {
            $settings=Functions::getSettings();
            if(isset($settings->disabled_trash_backend) && $settings->disabled_trash_backend){
            // plugins_path()
                $controller->addCss(url('plugins/diveramkt/miscelanious/assets/css/styles_custom.css?v=0.0.1'));
            }
        });

        if(BackendHelpers::isIndikatorNews()){
            Subscribers::extend(function($model) {
                $model->bindEvent('model.afterCreate', function() use ($model) {
                    if(!post('email') || strpos("[".Request::url('/')."]",'indikator/news/subscribers')) return;

                    $settings=Functions::getSettings();
                    $emails=$settings->indikatornews_newletter_notifications;
                    $emails=str_replace(['\r\n','\r','\n',';',' '],[',',',',',',',',''],$emails);
                    $emails=array_filter(explode(',', $emails));

                    if(count($emails)){
                        $template='diveramkt.miscelanious::mail.message_default';
                        $data=[
                            'infos' => [],
                        ];
                        $data['infos'][0]=[ 'text' => $settings->indikatornews_newletter_notifications_message];
                        foreach (post() as $key => $value) {
                            if($key == 'name') $key='Nome';
                            $data['infos'][0]['data'][ucfirst($key)]=$value;
                        }
                        \Mail::sendTo($emails, $template, $data, function ($message) {
                            $message->subject('Novo cadastro na newsletter');
                        });
                    }

                });
            });
        }

        \Event::listen('backend.menu.extendItems', function($navigationManager) {
            $settings=Functions::getSettings();
            if(BackendHelpers::isIndikatorNews() && isset($settings->indikatornews_newletter) && $settings->indikatornews_newletter){
                $navigationManager->removeMainMenuItem('Indikator.News', 'news');
                $menu_custom=[
                    'news' => [
                        'label'       => 'diveramkt.miscelanious::lang.menu.newsletter',
                        'url'         => \Backend::url('indikator/news/subscribers'),
                        'icon'        => 'icon-newspaper-o',
                        'iconSvg'     => 'plugins/indikator/news/assets/images/news-icon.svg',
                        'permissions' => ['indikator.news.subscribers'],
                        'order'       => 320,

                        'sideMenu' => [
                            'subscribers' => [
                                'label'       => 'indikator.news::lang.menu.subscribers',
                                'url'         => \Backend::url('indikator/news/subscribers'),
                                'icon'        => 'icon-user',
                                'permissions' => ['indikator.news.subscribers'],
                                'order'       => 300
                            ],
                        ]
                    ]
                ];
                $navigationManager->addMainMenuItems('Indikator.News',$menu_custom);
            }
        });

        if(BackendHelpers::isTranslate()){
            Event::listen('cms.page.beforeDisplay', function($controller, $url, $page) {
                $translator=\RainLab\Translate\Classes\Translator::instance();
                $controller->vars['code_lang']=$translator->getLocale();
            });
            \RainLab\Translate\Models\Locale::extend(function($model){
                $model->bindEvent('model.afterFetch', function () use ($model) {
                    $settings=Functions::getSettings();
                    if(isset($settings->flag_translate[$model->id])) $model->flag=$settings->flag_translate[$model->id];
                });
                $model->bindEvent('model.beforeSave', function() use ($model) {
                    if($model->flag){
                        $settings=Functions::getSettings();
                        if(!isset($settings->flag_translate)) $settings->flag_translate=[];
                        else $flag_translate=$settings->flag_translate;
                        $flag_translate[$model->id]=$model->flag;
                        $settings->flag_translate=$flag_translate;
                        $settings->save();
                    }
                    unset($model->flag);
                });
            });
            if(BackendHelpers::isArcaneSeo()){
                \Arcane\Seo\Models\Settings::extend(function($model) {
                    if (!$model->propertyExists('jsonable')) $model->addDynamicProperty('jsonable', []);
                    if (!$model->propertyExists('implement')) $model->addDynamicProperty('implement', []);
                    // if (!$model->propertyExists('translatable')) $model->addDynamicProperty('translatable', []);

                    $model->implement[] = 'RainLab.Translate.Behaviors.TranslatableModel';

                    // $jsonable=$model->jsonable;
                    // $jsonable[]='translatable';
                    // $model->jsonable=$jsonable;

                    // if(isset($model->translatable)) $model->translatable = ['og_locale'];
                });
            }
            if(BackendHelpers::isTranslateExtended()){
                $confg=\Excodus\TranslateExtended\Models\Settings::instance();
                if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && $confg->browser_language_detection){
                 $translator = Translator::instance();
                 $accepted = BrowserMatching::parseLanguageList($_SERVER['HTTP_ACCEPT_LANGUAGE']);
                 $available = Locale::listEnabled();
                 $matches = BrowserMatching::findMatches($accepted, $available);
                 if (!empty($matches)) {
                    $match = array_keys($matches)[0];
                    $translator->setLocale($match);
                }
            }
        }
    }

    if(BackendHelpers::isArcaneSeo()){
        \Arcane\Seo\Models\Settings::extend(function($model) {
            $array=[
                'logo' => 'System\Models\File',
                'logo_white' => 'System\Models\File',
                'logo_email' => 'System\Models\File',
                'bottom_email' => 'System\Models\File',
            ];
            if(isset($model->attachOne)) $model->attachOne=array_merge($model->attachOne,$array);
            else $model->addDynamicProperty('attachOne', $array);
        });
    }

    Event::listen('backend.form.extendFields', function($widget) {
        if($widget->isNested === false){
            if (
                $widget->model instanceof \Arcane\Seo\Models\Settings
                && BackendHelpers::isArcaneSeo()
            ) {
                $widget->addFields([
                    'logo' => [
                        'label'   => 'Logo do site',
                        'span' => 'auto',
                        'type' => 'fileupload',
                    ],
                    'logo_white' => [
                        'label'   => 'Logo do site - Branca',
                        'span' => 'auto',
                        'type' => 'fileupload',
                    ],
                    'section_images_email' => [
                        'label'   => 'Imagens no email',
                        'span' => 'full',
                        'type' => 'section',
                    ],
                    'logo_email' => [
                        'label'   => 'Header no email',
                        'commentAbove' => 'Necessário ser jpg/png',
                        'span' => 'auto',
                        'type' => 'fileupload',
                    ],
                    'bottom_email' => [
                        'label'   => 'Rodapé no email',
                        'commentAbove' => 'Necessário ser jpg/png',
                        'span' => 'auto',
                        'type' => 'fileupload',
                    ],
                ]);
            }elseif($widget->model instanceof \RainLab\Translate\Models\Locale) {
                $widget->addFields([
                    'flag' => [
                        'label'   => 'Bandeira',
                        'span' => 'auto',
                        'type' => 'mediafinder',
                        'mode' => 'image',
                    ],
                ]);
            }elseif($widget->model instanceof \Diveramkt\Miscelanious\Models\Company) {
                $settings=Functions::getSettings();
                if(!$settings->enabled_images_companies) $widget->removeField('images');
                if(!$settings->enabled_subtitle_companies) $widget->removeField('subtitle');

                if(!$settings->enabled_companies_phone){
                    $widget->removeField('phone'); $widget->removeField('area_code');
                }
                if(!$settings->enabled_companies_telefones) $widget->removeField('phones');
                if(!$settings->enabled_companies_phone && !$settings->enabled_companies_telefones) $widget->removeField('section_phone');
                
                if(!$settings->enabled_companies_skype) $widget->removeField('skype');
                if(!$settings->enabled_companies_opening_hours){
                    $widget->removeField('section_hours');
                    $widget->removeField('opening_hours');
                }
                if(!$settings->enabled_companies_social) $widget->removeField('social');

                if(isset($settings->enabled_companies_cnpj) && !$settings->enabled_companies_cnpj) $widget->removeField('cnpj');
                if(isset($settings->enabled_companies_textabout) && !$settings->enabled_companies_textabout){
                    $widget->removeField('text_about');
                    $widget->removeField('section_textabout');
                }
                if(!$settings->enabled_companies_email) $widget->removeField('email');
                if(!$settings->enabled_companies_emails) $widget->removeField('emails');
                if(!$settings->enabled_companies_email && !$settings->enabled_companies_emails) $widget->removeField('section_email');
                
                if(!$settings->enabled_companies_mobiles) $widget->removeField('mobiles');
                if(!$settings->enabled_companies_mobile) $widget->removeField('mobile'); $widget->removeField('area_code_mobile');
                if(!$settings->enabled_companies_mobiles && !$settings->enabled_companies_mobile) $widget->removeField('section_mobile');
            }elseif($widget->model instanceof \Diveramkt\Miscelanious\Models\Phone) {
                $settings=Functions::getSettings();
                if(!$settings->enabled_phones_number){
                    $widget->removeField('number');
                    $widget->removeField('area_code');
                }
                if(!$settings->enabled_phones_icon) $widget->removeField('icon');
                if(!$settings->enabled_phones_infos) $widget->removeField('info');
                if(isset($settings->enabled_phones_numbers) && !$settings->enabled_phones_numbers) $widget->removeField('numbers');
            }elseif($widget->model instanceof \Diveramkt\Miscelanious\Models\Testmonial) {
                $settings=Functions::getSettings();
                   //  if(!$settings->enabled_video_testimonials){
                   //     $widget->removeField('video');
                   //     $widget->removeField('type');
                   // }
                if(!is_array($settings->enabled_types_testimonials) || !count($settings->enabled_types_testimonials)){
                 $widget->removeField('type');
             }
             if(!$settings->enabled_testimonials_business) $widget->removeField('business');
             if(!$settings->enabled_testimonials_position) $widget->removeField('position');
             if(!$settings->enabled_testimonials_link) $widget->removeField('link');
             if(!$settings->enabled_testimonials_imagemedia) $widget->removeField('image');
             else $widget->removeField('foto');
         }
     }
 });

Event::listen('backend.list.extendColumns', function ($listWidget) {
        // if (!$listWidget->getController() instanceof \Backend\Controllers\Users) {
        //     return;
        // }
    if($listWidget->model instanceof \Diveramkt\Miscelanious\Models\Testmonial) {
        $settings=Functions::getSettings();
        if(!$settings->enabled_testimonials_position) $listWidget->removeColumn('position');
    }
});

$this->validacoes();
$class=get_declared_classes();

$settings=Functions::getSettings();
Functions::redirectPlugin($settings);

// ///////////////////EXTEND BACKEND USERS
Event::listen('backend.form.extendFields', function($widget) {
    if (!Schema::hasTable('diveramkt_miscelanious_extend_backend_users')) return;
    if (
        $widget->model instanceof \Backend\Models\User
        and $widget->isNested === false
    ) {

            // $model=$widget->model;
            // if(isset($model->jsonable)) $model->jsonable[]='social_profiles';
            // else $model->addDynamicProperty('jsonable', ['social_profiles']);

        $widget->addFields([
            'enabled' => [
                'label'   => 'Habilitado',
                'span' => 'full',
                'type' => 'switch',
                'default' => 1,
            ],
        ]);

        $widget->addTabFields([
            'description' => [
                'label'   => 'Pequena descrição',
                'span' => 'full',
                'size' => 'small',
                'type' => 'textarea',
                'tab' => 'Texto',
            ],
            'text' => [
                'label'   => 'Texto sobre',
                'span' => 'full',
                'size' => 'large',
                'type' => 'richeditor',
                'tab' => 'Texto',
            ],
            'social_profiles' => [
                'label'   => 'Redes Sociais',
                'span' => 'full',
                'prompt' => "Adicionar novo link",
                'type' => 'repeater',
                'form' => [
                    'fields' => [
                        'type' => [
                            'label' => 'Tipo',
                            'span' => 'auto',
                            'type' => 'dropdown',
                            'options' => [
                                '' => 'Selecionar',
                                'facebook' => "Facebook",
                                'twitter' => 'Twitter',
                                'instagram' => 'Instagram',
                                'linkedin' => 'Linkedin',
                                'pinterest' => 'Pinterest',
                                'tiktok' => 'Tiktok',
                                'youtube' => 'Youtube',
                                'whatsapp' => 'WhatsApp',
                                'phone' => 'Telefone',
                                'skype' => 'Skype',
                                'flickr' => 'Flickr',
                                'spotify' => 'Spotify',
                                'email' => 'Email',
                            ],
                        ],
                        'link' => [
                            'label' => 'Link',
                            'span' => 'auto',
                            'type' => 'text',
                        ],
                    ],
                ],
                'tab' => 'Redes Sociais',
            ],
        ]);
    }
});

if(BackendHelpers::IsPolloZenVisits() && BackendHelpers::isBlogRainlab()){
    \RainLab\Blog\Models\Post::extend(function($model) {
        $model->addDynamicMethod('getVisitsTotalAttribute', function($query) use ($model) {
            $visits=\PolloZen\MostVisited\Models\Visits::
            select( \Db::raw('SUM(visits) as total_visits'))
            ->distinct()->where('post_id',$model->id)->first();
            if($visits->total_visits) return $visits->total_visits;
            else return 0;
        });
    });
}

if(BackendHelpers::isBlogRainlab()){
    \RainLab\Blog\Models\Post::extend(function($model){
        if(BackendHelpers::isArcaneSeo()){
            $model->bindEvent('model.beforeSave', function() use ($model) {
                $arcane_seo_options=$model->arcane_seo_options;
                if(isset($arcane_seo_options['og_title'])) $arcane_seo_options['og_title']=str_replace('Defaults to SEO if left blank', '', $arcane_seo_options['og_title']);
                if(isset($arcane_seo_options['og_description'])) $arcane_seo_options['og_description']=str_replace('Defaults to SEO if left blank', '', $arcane_seo_options['og_description']);
                if(isset($arcane_seo_options['og_type'])) $arcane_seo_options['og_type']=str_replace('website article video etc...', 'article', $arcane_seo_options['og_type']);
                if(isset($arcane_seo_options['og_ref_image'])) $arcane_seo_options['og_ref_image']=str_replace('{{ example.image }}', '', $arcane_seo_options['og_ref_image']);
                $model->arcane_seo_options=$arcane_seo_options;
            });
        }
        // //////////////////////CHECK SLUG POST ÚNICOS AUTOMÁTICO
        $model->bindEvent('model.beforeValidate', function() use ($model) {
            if(!$model->slug || empty($model->slug)){
                $model->slug=\Str::slug($model->title);
            }
            $stop=1;
            for ($i=0; $i < $stop; $i++) { 
                $slug=$model->slug;
                if($i) $slug.='-'.$i;
                $veri=\RainLab\Blog\Models\Post::where('slug',$slug);
                if(isset($model->id)) $veri=$veri->where('id','!=',$model->id);
                $veri=$veri->first();
                if(isset($veri->id)) $stop++;
            }
            $model->slug=$slug;
        });
        // //////////////////////CHECK SLUG POST ÚNICOS AUTOMÁTICO
    });
}

\Backend\Models\User::extend(function($model) {
    if (!Schema::hasTable('diveramkt_miscelanious_extend_backend_users')) return;
    $model->hasOne=[
        'getExtendInfos' => [
            'Diveramkt\Miscelanious\Models\ExtendBackendUsers',
            'key' => 'user_id',
        ],
    ];
            // $model->hasMany=[
            //     'postagens' => [
            //         'RainLab\Blog\Models\Post',
            //         // 'limit' => 1,
            //         // 'conditions' => ' limit = "1" ',
            //         'scope' => 'IsPublished',
            //     ],
            // ];
    $model->addJsonable('social_profiles');

    $model->addDynamicMethod('getEnabledAttribute', function($query) use ($model) {
        $infos=$model->getExtendInfos;
        if(isset($infos->infos['enabled'])) return $infos->infos['enabled'];
    });
    $model->addDynamicMethod('getTextAttribute', function($query) use ($model) {
        $infos=$model->getExtendInfos;
        if(isset($infos->infos['text'])) return $infos->infos['text'];
    });
    $model->addDynamicMethod('getDescriptionAttribute', function($query) use ($model) {
        $infos=$model->getExtendInfos;
        if(isset($infos->infos['description'])) return $infos->infos['description'];
    });
    $model->addDynamicMethod('getSocialProfilesAttribute', function($query) use ($model) {
        $infos=$model->getExtendInfos;
        if(isset($infos->infos['social_profiles'])){
            if(strpos("[".Request::url('/')."]", '/backend/users')) return $infos->infos['social_profiles'];
            else{
                $social_profiles=json_decode($infos->infos['social_profiles']);
                foreach ($social_profiles as $key => $value) {
                    $type=$social_profiles[$key]->type;
                    if($type == 'email') $type='envelope';
                    $social_profiles[$key]->icon=Functions::getIconClass($type);
                }
                return json_encode($social_profiles);
            }
        }
    });

    $model->bindEvent('model.beforeSave', function () use ($model) {
        $infos=$model->infos;
        $table='backend_users';

        foreach ($model->attributes as $key => $value) {
            if(!\Schema::hasColumn($table, $key)){
                if($key == 'social_profiles'){
                    $value=json_decode($value);
                    if(count($value)){
                        foreach ($value as $key2 => $vet) {
                            if(!$vet->link || !$vet->type){
                                unset($value[$key2]);
                                continue;
                            }
                            if($vet->type == 'email'){
                                $value[$key2]->url='mailto:'.$vet->link;
                                $value[$key2]->target='';
                            }else{
                                $value[$key2]->url=Functions::prep_url($vet->link);
                                $value[$key2]->target=Functions::target($value[$key2]->url);
                            }
                        }
                    }
                    $value=array_filter($value);
                    $value=json_encode($value);
                }

                $infos[$key]=$value;
                unset($model->$key);
            }
        }

        $get_infos=ExtendBackendUsers::where('user_id',$model->id)->first();
        if(!isset($get_infos->id)){
            $set_infos=new ExtendBackendUsers();
            $set_infos->user_id=$model->id;
            if(isset($infos['text'])) $set_infos->text=$infos['text'];
            $set_infos->infos=$infos;
            $set_infos->save();
        }else{
            if(isset($infos['text'])) $get_infos->text=$infos['text'];
            $get_infos->infos=$infos;
            $get_infos->save();
        }
    });
            // $model->bindEvent('model.afterFetch', function () use ($model) {
            //     if(!isset($model->id)) return;
            //     $get_infos=$model->getExtendInfos;
            //     if(!isset($get_infos->id)) return;
            //     $attributes=$model->attributes;
            //     if(isset($get_infos->infos) && count($get_infos->infos)){
            //         foreach ($get_infos->infos as $key => $value) {
            //             if($key == 'description') continue;
            //             if(is_array($value)) $attributes[$key]=json_encode($value);
            //             else $attributes[$key]=$value;
            //         }
            //     }
            //     if(isset($get_infos->text)) $attributes['text']=$get_infos->text;
            //     $model->attributes=$attributes;
            // });
});

    // \Backend\Models\User::extend(function($model) {
        // if(!in_array('RainLab.Translate.Behaviors.TranslatableModel',$model->implement)) $model->implement[] = 'RainLab.Translate.Behaviors.TranslatableModel';
        // $model->translatable = ['name','description','position'];
    // });
    // ///////////////////EXTEND BACKEND USERS

if(in_array('RainLab\Translate\Plugin', $class) || in_array('RainLab\Translate\Classes\Translator', $class)){

    \Diveramkt\Miscelanious\Models\Equipe::extend(function($model) {
        if(!in_array('RainLab.Translate.Behaviors.TranslatableModel',$model->implement)) $model->implement[] = 'RainLab.Translate.Behaviors.TranslatableModel';
        $model->translatable = ['name','description','position'];
    });

    \Diveramkt\Miscelanious\Models\Equipecategorias::extend(function($model) {
        if(!in_array('RainLab.Translate.Behaviors.TranslatableModel',$model->implement)) $model->implement[] = 'RainLab.Translate.Behaviors.TranslatableModel';
        $model->translatable = ['title','description'];
    });

    \Diveramkt\Miscelanious\Models\Company::extend(function($model) {
        if(!in_array('RainLab.Translate.Behaviors.TranslatableModel',$model->implement)) $model->implement[] = 'RainLab.Translate.Behaviors.TranslatableModel';
        $model->translatable = ['name','city','neighborhood','street','addon','number','state','opening_hours','mobiles','phones'];
    });

    \Diveramkt\Miscelanious\Models\Testmonial::extend(function($model) {
        if(!in_array('RainLab.Translate.Behaviors.TranslatableModel',$model->implement)) $model->implement[] = 'RainLab.Translate.Behaviors.TranslatableModel';
        $model->translatable = ['name','position','testmonial','image'];
    });

    \Diveramkt\Miscelanious\Models\Contact::extend(function($model) {
        if(!in_array('RainLab.Translate.Behaviors.TranslatableModel',$model->implement)) $model->implement[] = 'RainLab.Translate.Behaviors.TranslatableModel';
        $model->translatable = ['description','value'];
    });

    \Diveramkt\Miscelanious\Models\Phone::extend(function($model) {
        if(!in_array('RainLab.Translate.Behaviors.TranslatableModel',$model->implement)) $model->implement[] = 'RainLab.Translate.Behaviors.TranslatableModel';
        $model->translatable = ['area_code','description','info'];
    });

    \Diveramkt\Miscelanious\Models\Social::extend(function($model) {
        if(!in_array('RainLab.Translate.Behaviors.TranslatableModel',$model->implement)) $model->implement[] = 'RainLab.Translate.Behaviors.TranslatableModel';
        $model->translatable = ['description'];
    });

}
}


public function validacoes(){

    Validator::extend('cpf_cnpj', function($attribute, $value, $parameters) {
        if(Functions::validCnpj($value)) return true;
        elseif(Functions::validCpf($value)) return true;
        else return false;
    });
    Validator::extend('cnpj', function($attribute, $value, $parameters) {
        return Functions::validCnpj($value);
    });
    Validator::extend('cpf', function($attribute, $value, $parameters) {
        return Functions::validCpf($value);
    });
    Validator::extend('data', function($attribute, $value, $parameters) {
             $data = explode("/","$value"); // fatia a string $dat em pedados, usando / como referência
             $d = $data[0];
             $m = $data[1];
             $y = $data[2];

    // verifica se a data é válida!
    // 1 = true (válida)
    // 0 = false (inválida)
             $res = checkdate($m,$d,$y);
             return $res;
             if ($res == 1){
                 echo "data ok!";
             } else {
                 echo "data inválida!";
             }
         });
    Validator::extend('phone', function($attribute, $value, $parameters) {
        return Functions::validPhone($value);
    });
    Validator::extend('cep', function($attribute, $value, $parameters) {
        return Functions::ValidCep($value);
    });

}


}
