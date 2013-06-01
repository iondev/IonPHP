<?php if (!defined('ROOT')) die('No direct script access allowed');

class View
{
    private static $vars = array();

    public static function assign($key, $value)
    {
        self::$vars[$key] = $value;
    }

    public static function model($filename)
    {
        $path = APPPATH . DS . 'Models' . DS . $filename . '.php';

        if (file_exists($path)) {
            //Will replace how this works soon
            require_once $path;
            $obj = new $filename;
            return $obj;
        } else {
            return false;
        }
    }

    private static function load_controller($name)
    {
        $name = ucfirst($name);
        $controller_path = APPPATH . DS . 'Controllers' . DS . $name . '.php';
        if (!file_exists($controller_path)) {
            exit('Could not find ' . $name . ' Controller');
        }
        require_once $controller_path;
    }

    private static function get_layout($name, $layout_type = 'Default')
    {
        $name = ucfirst($name);
        $name = APPPATH . DS . 'Layouts' . DS . $layout_type . DS . "{$name}.php";
        return $name;
    }

    public static function render($path)
    {

        //Load the Base Controller
        self::load_controller('Base');

        //Store path into a variable
        $path = APPPATH . DS . 'Views' . DS . $path . '.php';

        //Get the contents of the view file
        if (!file_exists($path)) {
            //Need to change this to go to 404 page
            exit('Could not find requested page');
        } else {
            $layout = file_get_contents(self::get_layout('Master'));

            //Once loaded, replace {PAGE_CONTENT} with view file
            $contents = str_replace('{PAGE_CONTENT}', $contents = file_get_contents($path), $layout);

            //Template: Variables
            foreach (self::$vars as $key => $value) {
                $contents = preg_replace('/\[' . $key . '\]/', $value, $contents);
            }

            //Template: if else statements
            $contents = preg_replace('/\<\!\=\= if (.*) \=\=\>/', '<?php if ($1) : ?>', $contents);
            $contents = preg_replace('/\<\!\=\= else \=\=\>/', '<?php else : ?>', $contents);
            $contents = preg_replace('/\<\!\=\= endif \=\=\>/', '<?php endif; ?>', $contents);

            //Display page information out
            eval(' ?>' . $contents . '<?php if (!defined("ROOT")) die("No direct script access allowed");');
        }
    }
}