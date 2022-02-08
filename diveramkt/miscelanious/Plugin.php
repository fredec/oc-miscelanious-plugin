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
            'Diveramkt\Miscelanious\Components\Team' => 'Team',
            'Diveramkt\Miscelanious\Components\Teams' => 'Teams',
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
                return Functions::video_embed($url, $autoplay, $controls);
            },
            'youtube_thumb' => function($url, $tamanho=1) {
                return Functions::youtube_thumb($url, $tamanho);
            },
            'create_slug' => function($string) {
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
                $replace1=[]; $replace2=[];
                if($this->isTranslate()) $translator=\RainLab\Translate\Classes\Translator::instance();
                if(!isset($translator) || ($tranlsator->getLocale() == 'pb' || $translator->getLocale() == 'pt-br')){
                    setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
                // date_default_timezone_set('America/Sao_Paulo');

                    $replace1=array_merge($replace1, ['January','February','March','April','May','June','July','August','September','October','November','December']);
                    $replace2=array_merge($replace2, ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro']);
                }

                if(!$data) $data='today';
                else $data=date($data);
                $return=utf8_encode(strftime($for, strtotime($data)));

                return str_replace($replace1, $replace2, $return);
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

function HTMLToRGB($htmlCode)
{
    if($htmlCode[0] == '#')
        $htmlCode = substr($htmlCode, 1);

    if (strlen($htmlCode) == 3)
    {
        $htmlCode = $htmlCode[0] . $htmlCode[0] . $htmlCode[1] . $htmlCode[1] . $htmlCode[2] . $htmlCode[2];
    }

    $r = hexdec($htmlCode[0] . $htmlCode[1]);
    $g = hexdec($htmlCode[2] . $htmlCode[3]);
    $b = hexdec($htmlCode[4] . $htmlCode[5]);

    return $b + ($g << 0x8) + ($r << 0x10);
}

function RGBToHSL($RGB) {
    $r = 0xFF & ($RGB >> 0x10);
    $g = 0xFF & ($RGB >> 0x8);
    $b = 0xFF & $RGB;

    $r = ((float)$r) / 255.0;
    $g = ((float)$g) / 255.0;
    $b = ((float)$b) / 255.0;

    $maxC = max($r, $g, $b);
    $minC = min($r, $g, $b);

    $l = ($maxC + $minC) / 2.0;

    if($maxC == $minC)
    {
        $s = 0;
        $h = 0;
    }
    else
    {
        if($l < .5)
        {
            $s = ($maxC - $minC) / ($maxC + $minC);
        }
        else
        {
            $s = ($maxC - $minC) / (2.0 - $maxC - $minC);
        }
        if($r == $maxC)
            $h = ($g - $b) / ($maxC - $minC);
        if($g == $maxC)
            $h = 2.0 + ($b - $r) / ($maxC - $minC);
        if($b == $maxC)
            $h = 4.0 + ($r - $g) / ($maxC - $minC);

        $h = $h / 6.0; 
    }

    $h = (int)round(255.0 * $h);
    $s = (int)round(255.0 * $s);
    $l = (int)round(255.0 * $l);

    return (object) Array('hue' => $h, 'saturation' => $s, 'lightness' => $l);
}


}
