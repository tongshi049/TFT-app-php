<?php

/**
 * @Desc: Redirect to another URL on the same site
 *
 * @param string $path The path to redirct to
 *
 * @return void
 *
 * @Author: Tong
 * @Date: 2019-08-29 10:45:57
 *
 */
function redirect($path)
{
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
        $protocol = 'https';
    } else {
        $protocl = 'http';
    }

    header("Location: $protocl://" . $_SERVER['HTTP_HOST'] . $path);
    exit;
}
