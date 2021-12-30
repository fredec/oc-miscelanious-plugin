<?php namespace Diveramkt\Miscelanious;

use System\Classes\PluginBase;
use Str;
use Validator;
use Request;
use System\Models\PluginVersion;
use Event;
use RainLab\Translate\Classes\Translator;
use Diveramkt\Miscelanious\Classes\Functions;

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
            'Diveramkt\Miscelanious\Components\Equipe' => 'Equipe',
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
            'canonical_url' => function($url=''){
                // $url=Request::url('/');
                if($this->isTranslate()){
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

                    
                    return $retorno.'?rel=0&controls='.$controls.'&amp;start=1&amp;autoplay='.$autoplay.'&amp;loop=1&amp;background=1';
                }elseif(strpos("[".$url."]", "vimeo.com")){
                    $par=explode('/', $url);
                    return 'https://player.vimeo.com/video/'.end($par).'?autoplay='.$autoplay.'&loop=1&background=1';
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
                if($this->isTranslate()) $translator=\RainLab\Translate\Classes\Translator::instance();
                if(!isset($translator) || ($tranlsator->getLocale() == 'pb' || $translator->getLocale() == 'pt-br')) setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
                // date_default_timezone_set('America/Sao_Paulo');

                if(!$data) $data='today';
                else $data=date($data);
                return utf8_encode(strftime($for, strtotime($data)));
            },

            'get_translate' => function($translate=false, $parent=false, $get=false){
                if(!$this->isTranslate()) return $translate;
                if(!$this->translator){
                    $this->translator = Translator::instance();
                    $this->activeLocale = $this->translator->getLocale();
                }

                if(!$translate) return;
                if(!$this->translate_active || (isset($translate->translations) && count($translate->translations) > 0)){
                    foreach ($translate->translations as $key => $value) {
                        if($value->locale!=$this->activeLocale) continue;
                        $this->translate_active=$value;
                    }
                }

                if(isset($this->translate_active['attribute_data']) && !empty($this->translate_active['attribute_data'])){
                    $trans=json_decode($this->translate_active['attribute_data']);
                    if(!$parent){
                        foreach ($trans as $key => $value) {
                            if(isset($translate->$key)) $translate->$key=$value; 
                        }
                        return $translate;
                    }elseif($get && isset($trans->$parent->$get)){
                        if($trans->$parent->$get) return $trans->$parent->$get;
                        elseif(isset($translate->$parent[$get])) return $translate->$parent[$get];
                    }elseif(!$get && isset($trans->$parent)){
                        $retorno=[];

                        foreach ($trans->$parent as $key => $value) {
                            if(!empty($value)) $retorno[$key]=$value;
                            else $retorno[$key]=$translate->$parent[$key];
                        }
                        return $retorno;
                        // if($trans->$parent) return $trans->$parent;
                        // elseif(isset($translate->$parent)) return $translate->$parent;
                    }
                }elseif($parent){
                    if(isset($translate->$parent[$get])) return $translate->$parent[$get];
                    elseif(isset($translate->$parent)) return $translate->$parent;
                }
                // return false;
                return $translate;
            },

            'formatValue' => function($value){
                return Functions::formatValue($value);
            },

            'limit_word' => function($string, $limit=10, $com='...'){
                $exp=array_filter(explode(' ', strip_tags($string)));
                $return=implode(' ', array_splice($exp, 0, $limit));
                if(count($exp)>$limit) $return.=$com;
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
            }

        ];
    }

    public function isTranslate(){
        $plugins=new PluginVersion();
        return class_exists('\RainLab\Translate\Classes\Translator') && class_exists('\RainLab\Translate\Models\Message') && $plugins->where('code','RainLab.Translate')->ApplyEnabled()->count();
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

    public function boot(){
        if($this->isTranslate()){
            Event::listen('cms.page.beforeDisplay', function($controller, $url, $page) {
                $translator=\RainLab\Translate\Classes\Translator::instance();
                $controller->vars['code_lang']=$translator->getLocale();
            });
            
            \Arcane\Seo\Models\Settings::extend(function($model) {
                $model->implement[] = 'RainLab.Translate.Behaviors.TranslatableModel';
                if (!$model->propertyExists('translatable')) $model->addDynamicProperty('translatable', []);
                $model->translatable = ['og_locale'];
            });
        }
        $this->validacoes();
        $class=get_declared_classes();

        $settings = \Diveramkt\Miscelanious\Models\Settings::instance();
        $config=[];
        // $config['base_url'] = str_replace('\/','/','http' . ( Request::server('HTTPS') == 'on' ? 's' : '') . '://' . Request::server('HTTP_HOST') . str_replace('//', '/', dirname(Request::server('SCRIPT_NAME')) . '/'));
        $config['base_url']=url('/');

        if(Request::server('HTTPS') == 'on' || $settings['redirect_https']){
            $pos = strpos($config['base_url'], 'https:');
            if ($pos === false) {
                header("HTTP/1.1 302 Moved Temporary");
                header("Location:".str_replace('http:','https:',Request::url()));
                exit();
            }
        }
        
        $veri='';
        if(Request::server('DOCUMENT_ROOT')) $veri.=' '.Request::server('DOCUMENT_ROOT').' ';
        if(Request::server('CONTEXT_DOCUMENT_ROOT')) $veri.=' '.Request::server('CONTEXT_DOCUMENT_ROOT').' ';

        if(isset($settings['redirect_www']) && $settings['redirect_www']
            && (!strpos("[".$veri."]", "C:/") || !strpos("[".$veri."]", "xampp/") || !strpos("[".$veri."]", ".october") || !strpos("[".$veri."]", "public_html"))
        ){

            $red=$settings['redirect_www'];


        $pos = strpos($config['base_url'], 'www');
        if ($pos === false) {

            $redirecionar=true;
            if(str_replace(' ','',$settings['sub_dominios']) != ''){

                $subs = preg_replace('/[\n|\r|\n\r|\r\n]{2,}/',',', $settings['sub_dominios']);
                $subs = preg_replace("/\r?\n/",',', $subs);
                $subs=explode(',', str_replace(';', ',', $subs));

                if(count($subs) > 0){
                    foreach ($subs as $key => $sub) {
                        if(strpos("[".$config['base_url']."]", "http://".$sub) || strpos("[".$config['base_url']."]", "https://".$sub)) $redirecionar=false; 
                    }
                }

            }

            if($redirecionar){
                // $url=(@Request::server('HTTPS') == 'on' ? 'https://' : 'http://').'www.'.Request::server('SERVER_NAME').Request::server('REQUEST_URI');
                $url='http://www.'.Request::server('SERVER_NAME').Request::server('REQUEST_URI');

                header("HTTP/1.1 ".$red." Moved Permanently");
                header("Location:".$url);
                exit();
            }

        }
    }




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

    Validator::extend('cnpj', function($attribute, $value, $parameters) {
        $cnpj = preg_replace('/[^0-9]/', '', (string) $value);

    // Valida tamanho
        if (strlen($cnpj) != 14)
            return false;

    // Verifica se todos os digitos são iguais
        if (preg_match('/(\d)\1{13}/', $cnpj))
            return false;   

    // Valida primeiro dígito verificador
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++)
        {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto))
            return false;

    // Valida segundo dígito verificador
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++)
        {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
    });

    Validator::extend('cpf', function($attribute, $value, $parameters) {
             // Extrai somente os números
        $cpf = preg_replace( '/[^0-9]/is', '', $value );

    // Verifica se foi informado todos os digitos corretamente
        if (strlen($cpf) != 11) {
            return false;
        }

    // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

    // Faz o calculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;
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
        $telefone= trim(str_replace('/', '', str_replace(' ', '', str_replace('-', '', str_replace(')', '', str_replace('(', '', $value))))));

        // $regexTelefone = "^[0-9]{11}$";
        $regexTelefone = "/[0-9]{11}/";

    $regexCel = '/[0-9]{2}[6789][0-9]{3,4}[0-9]{4}/'; // Regex para validar somente celular
    if (preg_match($regexTelefone, $telefone) or preg_match($regexCel, $telefone)) {
        return true;
    }else{
        return false;
    }
});
}


}
