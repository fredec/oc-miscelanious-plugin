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
                $urls = [];
                $xml = $definition->generateSitemap();
                if(!BackendHelpers::isTranslate()) return \Response::make($xml)->header('Content-Type', 'application/xml');
                $locales = Locale::listAvailable();
                foreach ($locales as $code => $name) {
                    app()->setLocale($code);
                    $xml = preg_replace('/<\?xml.*?\?>/', '', $xml);
                    preg_match('/<urlset[^>]*>(.*?)<\/urlset>/s', $xml, $matches);
                    if (!empty($matches[1])) $urls[] = trim(str_replace('default/', $code.'/', $matches[1]));
                }
                $cont = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
                $cont .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" ';
                $cont .= 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ';
                $cont .= 'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 ';
                $cont .= 'http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';
                $cont .= implode("\n", $urls);
                $cont .= '</urlset>';
                return \Response::make($cont)->header('Content-Type', 'application/xml');
                // return \Response::make($definition->generateSitemap())->header('Content-Type', 'application/xml');
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