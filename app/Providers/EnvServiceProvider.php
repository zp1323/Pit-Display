<?php
/**
 * 
 */
namespace Providers;

class EnvServiceProvider
{
    /**
     * Get the value of a key in the .env file
     * @param  String $key key in file
     * @return [type]      [description]
     */
    public static function get($key)
    {
        $dictionary = include "env.php";

        if(array_key_exists($key, $dictionary))
        {
            return $dictionary[$key];
        }

        throw new \Exception("Environment key missing: " . $key);
    }
}