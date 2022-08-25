<?php namespace Diveramkt\Miscelanious\Classes;

use Cms\Classes\Theme;
use Cms\Classes\Controller;
use RainLab\Sitemap\Models\Definition;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFound;

// use Arcane\Seo\Classes\Robots;
use Arcane\Seo\Classes\Sitemap;
use Arcane\Seo\Models\Settings;
// use Cms\Classes\Controller;
// use October\Rain\Database\Attach\Resizer;
// use File as FileHelper;

use Diveramkt\Miscelanious\Classes\BackendHelpers;

class Sitemapload {

    public static function load(){
        \Route::get('sitemap.xml', function () {

            if(BackendHelpers::isSitemapRainlab()){
                $themeActive = Theme::getActiveTheme()->getDirName();
                try {
                    $definition = Definition::where('theme', $themeActive)->firstOrFail();
                }catch (ModelNotFound $e) {
                // \Log::info(trans('rainlab.sitemap::lang.definition.not_found'));
                // return \App::make(Controller::class)->setStatusCode(404)->run('/404');
                }
            }

            if(isset($definition) && isset($definition->items) && count($definition->items) > 0){
                return \Response::make($definition->generateSitemap())->header('Content-Type', 'application/xml');
            }elseif(BackendHelpers::isArcaneSeo()){
                $sitemap = new Sitemap;
                if (! Settings::get('enable_sitemap'))
                    return  \App::make(Controller::class)->setStatusCode(404)->run('/404');
                else
                    return \Response::make($sitemap->generate())->header('Content-Type', 'application/xml');
            }
        });
    }

}

?>