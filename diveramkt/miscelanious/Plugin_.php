<?php namespace Diveramkt\Miscelanious;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            'Diveramkt\Miscelanious\Components\Companies' => 'Companies',
            'Diveramkt\Miscelanious\Components\Phones' => 'Phones',
            'Diveramkt\Miscelanious\Components\Contacts' => 'Contacts',
            'Diveramkt\Miscelanious\Components\SocialProfiles' => 'SocialProfiles',
            'Diveramkt\Miscelanious\Components\Testimonials' => 'Testimonials',
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
            'phone_link' => function ($string) {
                // $search = [' ', '+', '(', ')', '-', '.'];
                // return 'tel:+55'.str_replace($search, '', $string);
                return 'tel:+55'.preg_replace("/[^0-9]/", "", $string);
            },
            'only_numbers' => function ($string) {
                // $search = [' ', '+', '(', ')', '-', '.'];
                // return str_replace($search, '', $string);
                return preg_replace("/[^0-9]/", "", $string);
            },
            'whats_link' => function ($tel) {
                $iphone = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
                $android = strpos($_SERVER['HTTP_USER_AGENT'],"Android");
                $palmpre = strpos($_SERVER['HTTP_USER_AGENT'],"webOS");
                $berry = strpos($_SERVER['HTTP_USER_AGENT'],"BlackBerry");
                $ipod = strpos($_SERVER['HTTP_USER_AGENT'],"iPod");

                if ($iphone || $android || $palmpre || $ipod || $berry == true) {
                    $link='https://api.whatsapp.com/send?phone=55';
                } else {
                    $link='https://web.whatsapp.com/send?phone=55';
                }
                return $link.preg_replace("/[^0-9]/", "", $tel);
            },
            'whats_share' => function ($text) {
                $iphone = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
                $android = strpos($_SERVER['HTTP_USER_AGENT'],"Android");
                $palmpre = strpos($_SERVER['HTTP_USER_AGENT'],"webOS");
                $berry = strpos($_SERVER['HTTP_USER_AGENT'],"BlackBerry");
                $ipod = strpos($_SERVER['HTTP_USER_AGENT'],"iPod");
                if ($iphone || $android || $palmpre || $ipod || $berry == true) {
                    $link='https://api.whatsapp.com/send';
                } else {
                    $link='https://web.whatsapp.com/send';
                }
                
                return $link.'/?text='.$text;
            },
            // 'prep_url' => function($url) {
            //     if(!strpos("[".$url."]", "http://") && !strpos("[".$url."]", "https://")) $url='http://'.$url;
            //     return $url;
            // },
            'prep_url' => function($url) {

                $base = 'http' . ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . str_replace('//', '/', dirname($_SERVER['SCRIPT_NAME']) . '/');
                
                if(!strpos("[".$url."]", "http://") && !strpos("[".$url."]", "https://")){
                    $veri=str_replace('www.','',$_SERVER['HTTP_HOST']. str_replace('//', '/', dirname($_SERVER['SCRIPT_NAME'])));
                    if(!strpos("[".$url."]", ".") && !strpos("[".$veri."]", "https://")){
                        $url='http' . ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 's' : '') . '://www.'.str_replace(array('//','//'),array('/','/'),$veri.'/'.$url);
                    }else $url='http://'.$url;
                }
                return str_replace('//www.','//',$url);
            },
            'target' => function($link){
                if(!strpos("[".$link."]", $_SERVER['HTTP_HOST'])) return 'target="_blank"';
                else return 'target="_parent"';
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
                $table = array(
                    'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
                    'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                    'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
                    'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
                    'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
                    'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
                    'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
                    'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r', '/' => '-', ' ' => '-'
                );
                $stripped = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $string);
                return strtolower(strtr($string, $table));
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
}
