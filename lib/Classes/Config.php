<?php if (!defined('ROOT')) die('No direct script access allowed');

class Config
{
    protected static $_config = array();

    private static $data;

    public static function get_param($section, $name = null) {
        if (self::$data === null) {
            self::$data = parse_ini_file(LIB.'Config'.DS.'source.ini', true);
            if (self::$data === false) {
                self::handleError('Configuration file missing/corrupt.');
            }
        }

        if (array_key_exists($section, self::$data)) {
            if ($name && array_key_exists($name, self::$data[$section])) {
                return self::$data[$section][$name];
            }else{
                return self::$data[$section];
            }
        }else{
            return false;
        }
    }

    private static function handleError($string) {
        echo '<h2 class="color:red">Fatal Error: ' . $string . '</h2>';
        exit();
    }

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