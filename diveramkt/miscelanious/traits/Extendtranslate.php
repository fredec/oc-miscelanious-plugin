<?php namespace Diveramkt\Miscelanious\Traits;

// use Input;
// use Request;
// use Response;
// use Validator;
// use ValidationException;
// use ApplicationException;
// use System\Models\File;
// use October\Rain\Support\Collection;
// use Exception;
// use October\Rain\Filesystem\Definitions;

trait Extendtranslate
{

	// use \Diveramkt\Miscelanious\Traits\Extendtranslate;
	// return $this->getValueJsonTranslate($this->infos, 'endereco');
	public function getValueJsonTranslateOld($parent, $name){
		if(isset($parent[$name])){
			if(strpos("[".\Request::url('/')."]", "/backend/")) return $parent[$name];

			$translator = \RainLab\Translate\Classes\Translator::instance();
			$locale = $translator->getLocale();
			$defaultLocale = $translator->getDefaultLocale();

			$translatedValue = $this->getAttributeTranslated($name, $locale);
         // if (!empty(strip_tags($translatedValue))) $translatedValue = $this->getAttributeTranslated($name, $defaultLocale);

			$parent_return=$parent[$name];
			if(is_array($parent_return)) $parent_return=json_encode($parent_return);
			if($locale != $defaultLocale && \Diveramkt\Miscelanious\Classes\Functions::isJson($parent_return)) $parent_return=json_decode($parent_return);

			if(is_array($translatedValue)){
				if(count($translatedValue)) return $translatedValue;
				else return $parent_return;
			}elseif(!empty(trim(strip_tags($translatedValue)))) return $translatedValue;
			else{
				return $parent_return;
			}
        // return $translatedValue ?: $parent_return;
		}
	}



	public function getValueJsonTranslate($parent, $name){
		$return=$this->getValueJsonTranslateReturn($parent, $name);
		if(is_object($return)){
			$return=(array)$return;
			return json_encode($return,true);
		}
		if(is_array($return)){
			$parent=json_decode($parent[$name],true);
			foreach ($parent as $key => $value) {
				if(!isset($return[$key])) $return[$key]=$value;
				else{
					if(is_array($return[$key]) && !count($return[$key])){
						$return[$key]=$value;
					}elseif(!is_array($return[$key]) && empty(strip_tags($return[$key]))){
						$return[$key]=$value;
					}
				}
			}
			return json_encode($return,true);
		}
		return $return;
	}
	public function getValueJsonTranslateReturn($parent, $name){
		if(isset($parent[$name])){
			if(strpos("[".\Request::url('/')."]", "/backend/")) return $parent[$name];

			$translator = \RainLab\Translate\Classes\Translator::instance();
			$locale = $translator->getLocale();
			$defaultLocale = $translator->getDefaultLocale();

			$translatedValue = $this->getAttributeTranslated($name, $locale);

			$parent_return=$parent[$name];
			if(is_array($parent_return)) $parent_return=json_encode($parent_return);
			if($locale != $defaultLocale && \Diveramkt\Miscelanious\Classes\Functions::isJson($parent_return)) $parent_return=json_decode($parent_return);

			if(is_array($translatedValue)){
				if(count($translatedValue)) return $translatedValue;
				else return $parent_return;
			}elseif(!empty(trim(strip_tags($translatedValue)))) return $translatedValue;
			else{
				return $parent_return;
			}
		}
	}

}