<?php namespace Diveramkt\Miscelanious\Classes;

use Cache;
use Cms\Classes\Controller;

class PartialCache
{
    const INDEX_KEY = 'partial_cache_index'; // índice das chaves de partial

    public static function render(string $partialName, array $vars = [], ?string $key = null, int $segundos = 0): string
    {
        $settings=\Diveramkt\Miscelanious\Classes\Functions::getSettings();
        if(!$segundos) $segundos=$settings->partial_cache_time;
        if(!$settings->enabled_partial_cache || !$segundos){
            return Controller::getController()->renderPartial($partialName, $vars, false);
        }

        $cacheKey = $key ?: 'partial_' . md5($partialName . serialize($vars));
        self::rememberKey($cacheKey);

        return Cache::remember($cacheKey, $segundos, function () use ($partialName, $vars) {
            return Controller::getController()->renderPartial($partialName, $vars, false);
        });
    }

    protected static function rememberKey(string $cacheKey): void
    {
        $list = Cache::get(self::INDEX_KEY, []);
        if (!in_array($cacheKey, $list, true)) {
            $list[] = $cacheKey;
            Cache::forever(self::INDEX_KEY, $list); // persiste o índice
        }
    }

    public static function clear(): void
    {
        $list = Cache::get(self::INDEX_KEY, []);
        foreach ($list as $key) {
            Cache::forget($key);
        }
        Cache::forget(self::INDEX_KEY);
    }
}
