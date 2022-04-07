<?php namespace Diveramkt\Miscelanious\Classes;

use Request;

class Functions
{

  public static function getBaseurl(){
    $base=Request::server('SERVER_NAME');
    if(!$base) $base=str_replace(['https://','http://','www.','/'], ['','','',''], url(''));
    return $base;
  }
  public static function redirectPlugin($settings=false){
    if(!$settings) $settings = \Diveramkt\Miscelanious\Models\Settings::instance();
    if(!isset($settings['redirect_type']) || !$settings['redirect_type']) $settings['redirect_type']=0;
    if(!$settings['redirect_type']) return;
    if(!isset($settings['redirect_www']) || !is_numeric($settings['redirect_www'])) $settings['redirect_www']=0;

    // !Request::is('https://')
    // echo Request::secure();
    // Request::server('HTTPS') == 'on' && 
    $url=Request::url();
    if($settings['redirect_https'] && !strpos($url, 'https://')){
      $url=str_replace('http://', 'https://', $url);
    }

    if($settings['redirect_www'] && !strpos($url, 'www.')){
      $url=str_replace(['http://','https://'], ['http://www.','https://www.'], $url);
    }

    if($url != Request::url()){
      if(!isset($settings['redirect_base_saved'])){
        $settings['redirect_base_saved']=Self::getBaseurl();
        $settings->save();
        $settings['redirect_base_saved']=Self::getBaseurl();
      }

      if(isset($settings['redirect_base_saved']) && !strpos("[".$url."/]", $settings['redirect_base_saved'])){
        $settings['redirect_base_saved']=Self::getBaseurl();
        $settings['redirect_type']=0;
        $settings->save();
        return;
      }

      header("HTTP/1.1 ".$settings['redirect_type']." Moved Temporary");
      header("Location:".$url);
      exit();
    }
  }

  public static function prep_url($url) {
    if(!strpos("[".$url."]", "http://")){
      if(!strpos("[".$url."]", ".") && !strpos("[".$url."]", "https://")){
        $url=url($url);
      }else $url='http://'.$url;
    }
    return $url;
  }

  public static function target($link){
    // $url = 'http' . ((Request::server('HTTPS') == 'on') ? 's' : '') . '://' . Request::server('HTTP_HOST');
    $link=str_replace('//www.','//',$link); $url=str_replace('//www.','//',url('/'));
    if(!strpos("[".$link."/]", $url)) return 'target="_blank"';
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
