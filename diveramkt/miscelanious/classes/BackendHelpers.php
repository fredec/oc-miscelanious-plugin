<?php namespace Diveramkt\Miscelanious\Classes;

use Backend, BackendAuth;
use System\Models\PluginVersion;

class BackendHelpers {

    /**
     * Check if Rainlab Tranlate plugin is installed
     *
     * @return boolean
     */
    public static $getIsTranslate=null;
    public static function isTranslate() :bool {
        if(!Self::$getIsTranslate){
            $plugins=new PluginVersion();
            Self::$getIsTranslate=class_exists('\RainLab\Translate\Classes\Translator') && class_exists('\RainLab\Translate\Models\Message') && $plugins->where('code','RainLab.Translate')->ApplyEnabled()->count();
        }
        return Self::$getIsTranslate;
    }

    public static $getIsArcaneSeo=null;
    public static function isArcaneSeo() :bool {
        if(!Self::$getIsArcaneSeo){
            $plugins=new PluginVersion();
            Self::$getIsArcaneSeo=class_exists('\Arcane\Seo\Plugin') && class_exists('\Arcane\Seo\Models\Settings') && $plugins->where('code','Arcane.Seo')->ApplyEnabled()->count();
        }
        return Self::$getIsArcaneSeo;
    }

    public static $getIsIndikatorNews=null;
    public static function isIndikatorNews() :bool {
        if(!Self::$getIsIndikatorNews){
            $plugins=new PluginVersion();
            Self::$getIsIndikatorNews=class_exists('\Indikator\News\Plugin') && $plugins->where('code','indikator.News')->ApplyEnabled()->count();
        }
        return Self::$getIsIndikatorNews;
    }

}

?>