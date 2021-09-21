<?php

class Flash
{
    function set_flash_message(string $session_key, string $message)
    {
        $_SESSION[$session_key] = $message;
    }

    function display_flash_message(string $session_key)
    {
        if (isset($_SESSION[$session_key])) {
            echo "<div class=\"alert alert-{$session_key} text-dark\" role=\"alert\">{$_SESSION[$session_key]}</div>";
            unset($_SESSION[$session_key]);
        }
    }

}

