<?php namespace Diveramkt\Miscelanious\Classes;

use Request;

class Functions
{

  public static function prep_url($url) {
    if(!strpos("[".$url."]", "http://") && !strpos("[".$url."]", "https://")){
      $veri=Request::server('HTTP_HOST'). str_replace('//', '/', dirname(Request::server('SCRIPT_NAME')));
      if(!strpos("[".$url."]", ".") && !strpos("[".$veri."]", "https://")){
                        // $url='http' . ((Request::server('HTTPS') == 'on') ? 's' : '') . '://www.'.str_replace(array('//','\/'),array('/','/'),$veri.'/'.$url);
        $url='http' . ((Request::server('HTTPS') == 'on') ? 's' : '') . '://'.str_replace(array('//','\/'),array('/','/'),$veri.'/'.$url);
      }else $url='http://'.$url;
    }
    return $url;
  }

  public static function target($link){
    $url = 'http' . ((Request::server('HTTPS') == 'on') ? 's' : '') . '://' . Request::server('HTTP_HOST');
    $link=str_replace('//www.','//',$link); $url=str_replace('//www.','//',$url);
    if(!strpos("[".$link."]", $url)) return 'target="_blank"';
    else return 'target="_parent"';
  }

  public static function whats_link($tel, $msg=false){
    if(isset($_SERVER['HTTP_USER_AGENT'])){
      $iphone = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
      $android = strpos($_SERVER['HTTP_USER_AGENT'],"Android");
      $palmpre = strpos($_SERVER['HTTP_USER_AGENT'],"webOS");
      $berry = strpos($_SERVER['HTTP_USER_AGENT'],"BlackBerry");
      $ipod = strpos($_SERVER['HTTP_USER_AGENT'],"iPod");

      $extra=''; if(!strpos("[".$tel."]", "+")) $extra='55';

      if ($iphone || $android || $palmpre || $ipod || $berry == true) {
        $link='https://api.whatsapp.com/send?phone='.$extra;
      } else {
        $link='https://web.whatsapp.com/send?phone='.$extra;
      }
      $link=$link.preg_replace("/[^0-9]/", "", $tel);
      if($msg) $link.='&text='.$msg;
      return $link;
    }else return $tel;
  }

  public static function whats_share($text){
    if(isset($_SERVER['HTTP_USER_AGENT'])){
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
    }else return $text;
  }

  public static function phone_link($string, $cod=''){
    $link='';
    $link.=$cod.preg_replace("/[^0-9]/", "", $string);
    if(!strpos("[".$string."]", "+")) $link='+55'.$link;
    else $link='+'.$link;
    return 'tel:'.$link;
  }

  public static function formatValue($number=false){
    $number=preg_replace("/[^0-9.,]/", "", $number);
    $number=floatval(str_replace(',', '.', $number));
    return number_format($number, 2, ',', '.');
  }

  public static function video_embed($url, $autoplay=0, $controls=1){
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
  }

  public static function youtube_thumb($url, $tamanho=1){
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
  }

  // 'data_formato' => function($data, $for='%A, %d de %B de %Y'){

  //   if($this->isTranslate()) $translator=\RainLab\Translate\Classes\Translator::instance();
  //   if(!isset($translator) || ($tranlsator->getLocale() == 'pb' || $translator->getLocale() == 'pt-br')) setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
  //               // date_default_timezone_set('America/Sao_Paulo');

  //   if(!$data) $data='today';
  //   else $data=date($data);
  //   return utf8_encode(strftime($for, strtotime($data)));

  //           // return strftime($info, strtotime('today'));
  //           // return strftime($info, strtotime($date));
  //           // return date($for, mktime($data));
  //           // return $data;

  //           // return end($date2);
  //           // return $date->format('Y-m-d H:i:s');
  //           // return strftime('%A, %d de %B de %Y', $data);
  //   if($for == 'hora_minuto'){
  //     $exp=explode(' ', $data);
  //     $exp=end($exp);
  //     $exp=explode(':', $exp);

  //     $retorno='';
  //     if(isset($exp[0])) $retorno.=$exp[0];
  //     if(isset($exp[1])) $retorno.=':'.$exp[1];
  //     if($retorno != '') $retorno=' ás '.$retorno;
  //   }else{
  //     $date = new \DateTime($data);
  //     $retorno=$date->format($for);
  //   }

  //   $array1=array(''); $array2=array('');
  //   if($for == 'F'){
  //     $array1=array('January','February','March','April','May','June','July','August','September','October','November','December');
  //     $array2=array('Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro');
  //   }elseif($for == 'M'){
  //     $array1=array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
  //     $array2=array('Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez');
  //   }
  //   return str_replace($array1, $array2, $retorno);

  //           // https://docs.microsoft.com/pt-br/dotnet/standard/base-types/standard-date-and-time-format-strings
  //           // $date = new \DateTime($data);
  //           // $idioma = new \CultureInfo("pt-BR");
  //           // $retorno=$date.ToString($for, $idioma);
  //               // return $retorno;
  // },

}
