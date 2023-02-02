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

    public static $getIsPolloZenVisits=null;
    public static function IsPolloZenVisits() :bool {
        if(!Self::$getIsPolloZenVisits){
            $plugins=new PluginVersion();
            Self::$getIsPolloZenVisits=class_exists('\PolloZen\MostVisited\Plugin') && $plugins->where('code','PolloZen.MostVisited')->ApplyEnabled()->count();
        }
        return Self::$getIsPolloZenVisits;
    }

    public static $getIsSitemapRainlab=null;
    public static function isSitemapRainlab() :bool {
        if(!Self::$getIsSitemapRainlab){
            $plugins=new PluginVersion();
            Self::$getIsSitemapRainlab=class_exists('\Rainlab\Sitemap\Plugin') && class_exists('\Rainlab\Sitemap\Models\Definition') && $plugins->where('code','Rainlab.Sitemap')->ApplyEnabled()->count();
        }
        return Self::$getIsSitemapRainlab;
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

    public static $getIsBlogRainlab=null;
    public static function isBlogRainlab() :bool {
        if(!Self::$getIsBlogRainlab){
            $plugins=new PluginVersion();
            Self::$getIsBlogRainlab=class_exists('\RainLab\Blog\Plugin') && $plugins->where('code','rainlab.Blog')->ApplyEnabled()->count();
        }
        return Self::$getIsBlogRainlab;
    }

    public static $getIsUpload=null;
    public static function isUpload() :bool {
        if(!Self::$getIsUpload){
            $plugins=new PluginVersion();
            Self::$getIsUpload=class_exists('\Diveramkt\Uploads\Plugin') && $plugins->where('code','diveramkt.Uploads')->ApplyEnabled()->count();
        }
        return Self::$getIsUpload;
    }

}

?>