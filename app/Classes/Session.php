<?php 

namespace App\Classes;

class Session
{
    public static function has($name) 
    {
        return isset($_SESSION[$name]);
    }

    public static function put($name, $value)
    {
        return $_SESSION[$name] = $value;
    }

    public static function get($name)
    {
        if (isset($_SESSION[$name])) {
            return $_SESSION[$name];
        }
        return null;
    }

    public static function flash($name, $value = '')
    {
        if (self::has($name)) {
            $session = self::get($name);
            self::delete($name);
            return $session;
        } else {
            self::put($name, $value);
        }
    }

    public function delete($name)
    {
        if (isset($_SESSION[$name])) {
            unset($_SESSION[$name]);
        }
        return false;
    }

    public function destroy()
    {
        return session_destroy();
    }
}