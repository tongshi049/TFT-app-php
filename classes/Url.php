<?php

/**
 * URL
 *
 * Response methods
 *
 * @Author: Tong
 * @Date: 2019-09-03 12:19:29
 *
 */
class Url
{
    /**
     * @Desc: Redirect to another URL on the same site
     *
     * @param string $path The path to redirct to
     *
     * @return void
     */
    public static function redirect($path)
    {
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
            $protocol = 'https';
        } else {
            $protocl = 'http';
        }

        header("Location: $protocl://" . $_SERVER['HTTP_HOST'] . $path);
        exit;
    }
}
