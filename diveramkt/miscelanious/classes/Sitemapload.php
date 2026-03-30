<?php namespace Diveramkt\Miscelanious\Classes;

use Cms\Classes\Theme;
use Cms\Classes\Controller;
use RainLab\Sitemap\Models\Definition;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFound;
use Arcane\Seo\Classes\Sitemap;
use Arcane\Seo\Models\Settings;
use RainLab\Translate\Models\Locale;

use Diveramkt\Miscelanious\Classes\BackendHelpers;

class Sitemapload {

    public static function load(){
        \Route::get('sitemap.xml', function () {

            if(BackendHelpers::isSitemapRainlab()){
                $themeActive = Theme::getActiveTheme()->getDirName();

                try {
                    $definition = Definition::where('theme', $themeActive)->firstOrFail();
                } catch (ModelNotFound $e) {}
            }

            if(isset($definition) && isset($definition->items) && count($definition->items) > 0){

                $xmlBase = $definition->generateSitemap();

                if(!BackendHelpers::isTranslate()){
                    return \Response::make($xmlBase)->header('Content-Type', 'application/xml');
                }

                // 🔹 Carrega idiomas
                $locales = Locale::listAvailable(); // ['pt' => 'Português', ...]
                $locale_get=Locale::where('is_enabled',1)->get();

                $defaultLocale='en';
                foreach ($locale_get as $key => $value) {
                    if($value->is_default){
                        $defaultLocale=$value->code;
                    }
                }


                $dom = new \DOMDocument('1.0', 'UTF-8');
                $dom->formatOutput = true;

// urlset com namespace
                $urlset = $dom->createElement('urlset');
                $urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
                $urlset->setAttribute('xmlns:xhtml', 'http://www.w3.org/1999/xhtml');

                $dom->appendChild($urlset);

                libxml_use_internal_errors(true);
// parse XML original
                $xml = simplexml_load_string($xmlBase);

                if ($xml === false) {
                    dd(libxml_get_errors());
                }

                $namespaces = $xml->getNamespaces(true);
                $urls = $xml->children($namespaces[''])->url;

                foreach ($urls as $url) {

                    $urlChildren = $url->children($namespaces['']);

                    $loc = (string) $urlChildren->loc;
                    $path = parse_url($loc, PHP_URL_PATH);

                    $urlNode = $dom->createElement('url');

    // loc

                    // $defaultLocale = array_key_first($locales);
                    $defaultUrl = url('/' . $defaultLocale . $path);


                    $locNode = $dom->createElement('loc', $defaultUrl);
                    $urlNode->appendChild($locNode);

    // hreflang alternates
                    foreach ($locale_get as $key => $value) {

                        $code=$value->code;

                        $translatedUrl = url('/' . $code . $path);
                        $hreflang=$value->code_locale;
                        // $hreflang = match($code) {
                        //     'pt' => 'pt-BR',
                        //     'en' => 'en-US',
                        //     'es' => 'es-ES',
                        //     default => $code
                        // };

                        // $link = $dom->createElementNS(
                        //     'http://www.w3.org/1999/xhtml',
                        //     'xhtml:link'
                        // );
                        $link = $dom->createElement('xhtml:link');
                        $link->setAttribute('rel', 'alternate');
                        $link->setAttribute('hreflang', $hreflang);
                        $link->setAttribute('href', $translatedUrl);
                        $urlNode->appendChild($link);
                    }

    // x-default
                    // $xDefault = $dom->createElementNS(
                    //     'http://www.w3.org/1999/xhtml',
                    //     'xhtml:link'
                    // );
                    $xDefault = $dom->createElement('xhtml:link');

                    $xDefault->setAttribute('rel', 'alternate');
                    $xDefault->setAttribute('hreflang', 'x-default');
                    $xDefault->setAttribute('href', url('/'));

                    $urlNode->appendChild($xDefault);

    // lastmod
                    if (isset($urlChildren->lastmod)) {
                        $urlNode->appendChild(
                            $dom->createElement('lastmod', (string)$urlChildren->lastmod)
                        );
                    }

    // changefreq
                    if (isset($urlChildren->changefreq)) {
                        $urlNode->appendChild(
                            $dom->createElement('changefreq', (string)$urlChildren->changefreq)
                        );
                    }

    // priority
                    if (isset($urlChildren->priority)) {
                        $urlNode->appendChild(
                            $dom->createElement('priority', (string)$urlChildren->priority)
                        );
                    }

                    $urlset->appendChild($urlNode);
                }

                // dd($dom->saveXML());
                return response($dom->saveXML(), 200)->header('Content-Type', 'application/xml');






            }

            elseif(BackendHelpers::isArcaneSeo()){
                $sitemap = new Sitemap;

                if (! Settings::get('enable_sitemap'))
                    return \App::make(Controller::class)->setStatusCode(404)->run('/404');
                else
                    return \Response::make($sitemap->generate())->header('Content-Type', 'application/xml');
            }
        });
}
}