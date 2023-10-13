<?php namespace Diveramkt\Miscelanious\Classes;

use Request;
use Diveramkt\Miscelanious\Classes\BackendHelpers;
use Diveramkt\Miscelanious\Models\Settings;

class Functions
{

  public static $getSettingsCache=null;
  public static function getSettings(){
    if(strpos("[".Request::url('/')."]",'diveramkt/miscelanious/settings')) return;
    if(!Self::$getSettingsCache){
      Self::$getSettingsCache=Settings::instance();

      if(!isset(Self::$getSettingsCache['replace_icons'])) Self::$getSettingsCache['replace_icons']=[];
      $replace=Self::$getSettingsCache['replace_icons'];
      $replace_new=[];
      if(isset($replace[0])){
        foreach ($replace as $key => $value) {
          if(!isset($value['new']) || !isset($value['origin'])) continue;
          $replace_new[$value['origin']]=$value['new'];
        }
      }
      Self::$getSettingsCache['replace_icons']=$replace_new;

    }

    // echo '<pre>';
    // // print_r(Self::$getSettingsCache->enabled_types_testimonials);
    // print_r(array_flip(Self::$getSettingsCache->enabled_types_testimonials));
    // echo '</pre>';
    return Self::$getSettingsCache;
  }

  public static function getBaseurl(){
    $base=Request::server('SERVER_NAME');
    if(!$base) $base=str_replace(['https://','http://','www.','/'], ['','','',''], url(''));
    return $base;
  }
  public static function redirectPlugin($settings=false){
    if(!$settings) $settings = Self::getSettings();
    if(!isset($settings['redirect_type']) || !$settings['redirect_type']) $settings['redirect_type']=0;
    if(!isset($settings['redirect_subdomains_ssl'])) $settings['redirect_subdomains_ssl']=1;
    if(!$settings['redirect_type']) return;
    if(!isset($settings['redirect_www']) || !is_numeric($settings['redirect_www'])) $settings['redirect_www']=0;

    $url=Request::url();
    $sub_active=false;
    if(isset($settings['redirect_subdomains'])){
      $subs = array_filter(explode("\n", trim($settings['redirect_subdomains'])));
      if(isset($subs[0])){
        foreach ($subs as $key => $sub) {
          $sub=str_replace('\n', '', trim($sub));
          if(strpos("[".$url."]", 'https://'.$sub.'.') || strpos("[".$url."]", 'http://'.$sub.'.')) $sub_active=true;
        }
      }
    }

    // !Request::is('https://')
    // echo Request::secure();
    // Request::server('HTTPS') == 'on' && 
    $url_current=Request::url();
    // if($settings['redirect_https'] && !strpos($url, 'https://') && ($settings['redirect_subdomains_ssl'] || !$sub_active)){
    if($settings['redirect_https'] && !strpos($url, 'https://') && !$sub_active){
      $url=str_replace('http://', 'https://', $url);
      // $url_current=str_replace('http://', 'https://', $url_current);
    }

    if($settings['redirect_www'] && !strpos($url, 'www.') && !$sub_active){
      $url=str_replace(['http://','https://'], ['http://www.','https://www.'], $url);
    }

    if($url != $url_current){
      if(!isset($settings['redirect_base_saved'])){
        $settings['redirect_base_saved']=Self::getBaseurl();
        $settings->save();
        $settings['redirect_base_saved']=Self::getBaseurl();
      }

      // if(isset($settings['redirect_base_saved']) && !strpos("[".$url."/]", $settings['redirect_base_saved'])){
      //   $settings['redirect_base_saved']=Self::getBaseurl();
      //   $settings['redirect_type']=0;
      //   $settings->save();
      //   return;
      // }

      header("HTTP/1.1 ".$settings['redirect_type']." Moved Temporary");
      header("Location:".$url);
      exit();
    }
  }

  // public static function prep_url($url) {
  //   if(!strpos("[".$url."]", "http://") && !strpos("[".$url."]", "https://")){
  //     $veri=Request::server('HTTP_HOST'). str_replace('//', '/', dirname(Request::server('SCRIPT_NAME')));
  //     if(!strpos("[".$url."]", ".") && !strpos("[".$veri."]", "https://")){
  //                       // $url='http' . ((Request::server('HTTPS') == 'on') ? 's' : '') . '://www.'.str_replace(array('//','\/'),array('/','/'),$veri.'/'.$url);
  //       $url='http' . ((Request::server('HTTPS') == 'on') ? 's' : '') . '://'.str_replace(array('//','\/'),array('/','/'),$veri.'/'.$url);
  //     }else $url='http://'.$url;
  //   }
  //   return $url;
  // }

//   public static function prep_url($url) {
//     if(!strpos("[".$url."]", "http://") && !strpos("[".$url."]", "https://")){
//      if(!strpos("[".$url."]", ".") && !strpos("[".url('/')."]", "https://")){
//       $url=url($url);
//       if(Request::server('HTTPS') == 'on') $url=str_replace('http://', 'https://', $url);
//     }else $url='http://'.$url;
//   }
//   return $url;
// }

  public static function prep_url($url) {
    $url=trim($url);
    if(strpos("[".$url."]", "#") && !strpos("[".$url."]", "/#")) $url=str_replace('#', '/#', $url);
    if(!strpos("[".$url."]", ".") && (!strpos("[".$url."]", "http://") && !strpos("[".$url."]", "https://"))) $url=url($url);
    if(!strpos("[".$url."]", "http://") && !strpos("[".$url."]", "https://")){
    // if(Request::server('HTTPS') == 'on'){
    //   $url='https://'.$url;
    // }else{
      $url='http://'.$url;
    // }
    }
    return $url;
  }

  public static function target($link){
    // $url = 'http' . ((Request::server('HTTPS') == 'on') ? 's' : '') . '://' . Request::server('HTTP_HOST');
    $link=str_replace('//www.','//',$link); $url=str_replace('//www.','//',url('/'));
    if(!strpos("[".$link."/]", $url)) return 'target=_blank';
    else return 'target=_parent';
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
    if(!strpos("[".$string."]", "+") && strncmp($string, "0800", 4) !== 0){
      $link='+55'.$link;
    }
    return 'tel:'.$link;
  }

  public static function formatValue($number=false){
    $number=preg_replace("/[^0-9.,]/", "", $number);
    $number=floatval(str_replace(',', '.', $number));
    return number_format($number, 2, ',', '.');
  }

  public static function data_formato($data, $for='%A, %d de %B de %Y'){
    $replace1=[]; $replace2=[];
    if(BackendHelpers::isTranslate()) $translator=\RainLab\Translate\Classes\Translator::instance();
    if(!isset($translator) || ($translator->getLocale() == 'pb' || $translator->getLocale() == 'pt-br')){
      setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
                // date_default_timezone_set('America/Sao_Paulo');

      $replace1=array_merge($replace1, ['January','February','March','April','May','June','July','August','September','October','November','December']);
      $replace2=array_merge($replace2, ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro']);
    }

    if(!$data) $data='today';
    else $data=date($data);
    $return=utf8_encode(strftime($for, strtotime($data)));

    return str_replace($replace1, $replace2, $return);
  }

  // 'data_formato' => function($data, $for='%A, %d de %B de %Y'){

  //   if($this->isTranslate()) $translator=\RainLab\Translate\Classes\Translator::instance();
  //   if(!isset($translator) || ($translator->getLocale() == 'pb' || $translator->getLocale() == 'pt-br')) setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
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

  public static function getIconClass($icon=false){
    $settings = Self::getSettings();
    if(!$icon) return;
    if(!isset($settings['version_icons'])) return 'fa fa-'.$icon;
    if(isset($settings['replace_icons'][$icon])) $icon=$settings['replace_icons'][$icon];

    if($settings['version_icons'] == 'others'){
      $sub=$settings['others_icons'];
      if(empty($sub)) $sub='fa fa-';
      return $sub.$icon;
    }

    if($settings['version_icons'] == '5'){
      if($icon == 'phone' || $icon == 'envelope' || $icon == 'link'){
        return 'fa fa-'.$icon;
      }
      return 'fab fa-'.$icon;
    }

    return 'fa fa-'.$icon;
  }

// //////////////////PARA SALVAR UM VALOR COLUNA DOUBLE
  public static function savePriceDouble($val){
    // if(empty($val) || (!is_float($val) && !is_numeric($val))) return 0;
    if(empty($val)) return 0;
    $conter='0-9.,'; $val=preg_replace("/[^".$conter."]/", "", $val);
    $val=str_replace(',', '.', $val); $val=explode('.',$val);
    if(count($val) > 1){
      $cents = array_pop($val);
      return implode('',$val).'.'.$cents;
    }else{
      if(empty($val[0])) return 0;
      else return $val[0];
    }
  }
  public static function getPriceDouble($val){
    $conter='0-9.'; $val=preg_replace("/[^".$conter."]/", "", $val);
    return number_format($val, 2, ',', '.');
  }
// //////////////////PARA SALVAR UM VALOR COLUNA DOUBLE
// ////////////////////////VALIDAÇÕES
  public static function validCnpj($value){
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
  }
  public static function validCpf($value){
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
  }
  public static function validPhone($value){
  //  $telefone= trim(str_replace('/', '', str_replace(' ', '', str_replace('-', '', str_replace(')', '', str_replace('(', '', $value))))));
  $conter='0-9';
  $telefone=preg_replace("/[^".$conter."]/", "", $value);

        // $regexTelefone = "^[0-9]{11}$";
  //  $regexTelefone = "/[0-9]{11}/";
   $regexTelefone = "/[0-9]{10}/";

    $regexCel = '/[0-9]{2}[6789][0-9]{3,4}[0-9]{4}/'; // Regex para validar somente celular
    if (preg_match($regexTelefone, $telefone) or preg_match($regexCel, $telefone)) {
      return true;
    }else{
      return false;
    }
  }
  // ////////////////////////VALIDAÇÕES

  public static $is_mobile_cache=null;
  public static function is_mobile(){
    if(Self::$is_mobile_cache != null) return Self::$is_mobile_cache;
    else{
      $detect = new \Mobile_Detect;
      if ($detect->isMobile()) Self::$is_mobile_cache=true;
      else Self::$is_mobile_cache=false;
    }
    return Self::$is_mobile_cache;
  }

}
