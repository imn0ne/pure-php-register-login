<?php

namespace App\Classes;

class Config
{
    /**
     * functon for get data from 'config' array
     * @param string $path the 'key.value' format
     * @return string
     */
    public static function get($path)
    {
        $config = $GLOBALS['config'];
        $path = explode('.', $path);
        
        foreach($path as $item) {
            if (array_key_exists($item, $config)) {
                $config = $config[$item];
            }
        }
        return $config;
    }
}