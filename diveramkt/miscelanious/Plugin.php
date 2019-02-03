<?php namespace Diveramkt\Miscelanious;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
    }

    public function registerSettings()
    {
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