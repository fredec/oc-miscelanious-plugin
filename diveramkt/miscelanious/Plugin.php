<?php namespace Diveramkt\Miscelanious;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            'Diveramkt\Miscelanious\Components\Companies' => 'Companies',
            'Diveramkt\Miscelanious\Components\Phones' => 'Phones',
            'Diveramkt\Miscelanious\Components\Contacts' => 'Contacts',
            'Diveramkt\Miscelanious\Components\SocialProfiles' => 'SocialProfiles',
            'Diveramkt\Miscelanious\Components\Testimonials' => 'Testimonials',
        ];
    }
    public function registerPageSnippets()
    {
        return [
            'Diveramkt\Miscelanious\Components\Companies' => 'Companies',
            'Diveramkt\Miscelanious\Components\Phones' => 'Phones',
            'Diveramkt\Miscelanious\Components\Contacts' => 'Contacts',
            'Diveramkt\Miscelanious\Components\SocialProfiles' => 'SocialProfiles',
            'Diveramkt\Miscelanious\Components\Testimonials' => 'Testimonials',
        ];
    }

    /**
     * Returns plain PHP functions.
     *
     * @return array
     */
    private function getPhpFunctions()
    {
        return [
            'phone_number' => function ($string) {
                $search = [' ', '+', '(', ')', '-', '.'];
                return str_replace($search, '', $string);
            },
            'phone_link' => function ($string) {
                $search = [' ', '+', '(', ')', '-', '.'];
                return 'tel:+55'.str_replace($search, '', $string);
            },
            'only_numbers' => function ($string) {
                $search = [' ', '+', '(', ')', '-', '.'];
                return str_replace($search, '', $string);
            },
            'whats_link' => function ($tel) {
                $iphone = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
                $android = strpos($_SERVER['HTTP_USER_AGENT'],"Android");
                $palmpre = strpos($_SERVER['HTTP_USER_AGENT'],"webOS");
                $berry = strpos($_SERVER['HTTP_USER_AGENT'],"BlackBerry");
                $ipod = strpos($_SERVER['HTTP_USER_AGENT'],"iPod");

                if ($iphone || $android || $palmpre || $ipod || $berry == true) {
                    $link='https://api.whatsapp.com/send?phone=55';
                } else {
                    $link='https://web.whatsapp.com/send?phone=55';
                }
                
                return $link.preg_replace("/[^0-9]/", "", $tel);
            },
        ];
    }

    /**
     * Add Twig extensions.
     *
     * @see Text extensions http://twig.sensiolabs.org/doc/extensions/text.html
     * @see Intl extensions http://twig.sensiolabs.org/doc/extensions/intl.html
     * @see Array extension http://twig.sensiolabs.org/doc/extensions/array.html
     * @see Time extension http://twig.sensiolabs.org/doc/extensions/date.html
     *
     * @return array
     */
    public function registerMarkupTags()
    {
        $filters = [];
        // add PHP functions
        $filters += $this->getPhpFunctions();

        return [
            'filters'   => $filters,
        ];
    }
}
