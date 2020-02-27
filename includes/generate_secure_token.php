<?php

/* Defining verbose constants */
if (!defined('CSRF_TOKEN')) {
    define('CSRF_TOKEN', 'token');
    define('CSRF_TOKEN_EXPIRE', 'token-expire');
}

/* Class to set and unset CSRF token in session */
class CSRFToken
{
    // Sets CSRF token in session along with an expiry time
    public static function setTokenWithExpiry()
    {
        $length = 32;
        $_SESSION[CSRF_TOKEN] = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $length);
        $_SESSION[CSRF_TOKEN_EXPIRE] = time() + 3600;
    }

    // Unsets CSRF token after the validation is done
    public static function clearToken()
    {
        unset($_SESSION[CSRF_TOKEN]);
        unset($_SESSION[CSRF_TOKEN_EXPIRE]);
    }
}