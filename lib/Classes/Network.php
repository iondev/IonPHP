<?php

/** @noinspection PhpInconsistentReturnPointsInspection */
class Network
{

    public static $controller;
    public static $action;
    protected static $default_controller = 'Main';
    protected static $default_error_controller = 'Error';
    protected static $default_controller_method = 'index';
    protected static $url = '';

    public static function route()
    {
        $uri = self::uri_structure();
        $controller = (isset($uri['controller']) ? $uri['controller'] : self::$default_controller);
        $action = (isset($uri['action']) ? $uri['action'] : self::$default_controller_method);
        //get the controller file
        if (self::detect_controller($controller) == true) {
            if (!method_exists($controller, $action)) {
                $controller = self::$default_error_controller;
                require_once APPPATH . DS . 'Controllers/Error.php';
                $action = 'index';
            }
        }

        Config::rw('Controller', $controller);
        $obj = $controller;
        exit(call_user_func_array(array($obj, $action), array_slice($uri['segments'], 2)));
    }

    public static function full_url()
    {
        if (!defined('FULL_URL')) {
            $s = null;
            if (env('HTTPS')) {
                $s = 's';
            }
            $httpHost = env('HTTP_HOST');
            if (isset($httpHost)) {
                return 'http' . $s . '://' . $httpHost;
            }
            unset($httpHost, $s);
        }
        return false;
    }

    private static function uri_structure()
    {
        $request_url = (isset($_SERVER['REQUEST_URI'])) ? $_SERVER['REQUEST_URI'] : '';
        $script_url = (isset($_SERVER['PHP_SELF'])) ? $_SERVER['PHP_SELF'] : '';
        if ($request_url != $script_url)
            self::$url = trim(preg_replace('/' . str_replace('/', '\/', str_replace('index.php', '', $script_url)) . '/', '', $request_url, 1), '/');
        $segments = explode('/', self::$url);
        if (isset($segments[0]) && $segments[0] != '')
            self::$controller = $segments[0];
        if (isset($segments[1]) && $segments[1] != '')
            self::$action = $segments[1];
        return array('controller' => self::$controller, 'action' => self::$action, 'segments' => $segments);
    }

    private static function detect_controller($name)
    {
		$name = ucfirst($name);
        if (file_exists(APPPATH . DS . 'Controllers' . DS . $name . '.php')) {
            require_once APPPATH . DS . 'Controllers' . DS . $name . '.php';
        } else {
            show_error("Could not find Controller: {$name}");
        }
    }

}