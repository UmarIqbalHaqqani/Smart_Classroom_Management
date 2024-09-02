<?php

namespace LoginPress_Social_Login\Microsoft\Handlers;
/**
 *
 */
class Session
{
    public static function set($key, $value)
    {
        $_SESSION['LoginPress/microsoft'][$key] = $value;
    }
    public static function unset($key)
    {
        if (Session::get($key)) {
            unset($_SESSION['LoginPress/microsoft'][$key]);
        }
    }
    public static function get($key)
    {
        return isset($_SESSION['LoginPress/microsoft'][$key])
            ? $_SESSION['LoginPress/microsoft'][$key]
            : null;
    }
}
