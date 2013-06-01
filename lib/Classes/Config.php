<?php if (!defined('ROOT')) die('No direct script access allowed');

class Config
{
    protected static $_config = array();

    public static function rw($key, $value = null)
    {
        if ($key === 'source' && file_exists($value))
            self::$_config = array_merge(self::$_config, parse_ini_file($value, true));
        else if ($value == null)
            return (isset(self::$_config[$key]) ? self::$_config[$key] : null);
        else
            return (self::$_config[$key] = $value);

        return $value;
    }
}