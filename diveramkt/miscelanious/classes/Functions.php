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

}
