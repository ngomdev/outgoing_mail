<?php

if (!function_exists('nestedLang')) {
    function nestedLang($key, $replace = [], $locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        $segments = explode('.', $key);

        $jsonFile = resource_path("lang/{$locale}.json");
        $jsonContent = file_get_contents($jsonFile);
        $translations = json_decode($jsonContent, true);

        $nestedArray = $translations[$segments[0]] ?? null;

        if (count($segments) > 1) {
            for ($i = 1; $i < count($segments); $i++) {
                $nestedArray = $nestedArray[$segments[$i]] ?? null;
            }
        }

        if (is_null($nestedArray)) {
            return $key;
        }

        return str_replace(array_keys($replace), array_values($replace), $nestedArray);
    }
}
