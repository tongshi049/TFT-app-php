<?php

/**
 * Authentication
 *
 * Login and logout
 */
class Auth
{

    /**
     * @Desc: Return the user authentication status
     *
     * @param
     *
     * @return boolean True if a use is logged in, false otherwise
     *
     * @Author: Tong
     * @Date: 2019-08-29 12:59:56
     *
     */
    public static function isLoggedIn()
    {
        return isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'];
    }

    /**
     * @Desc: Require the user to logged in, stopping with an unautoirised maeesage if not logged in.
     *
     * @return void
     *
     * @Author: Tong
     * @Date: 2019-09-03 12:50:50
     *
     */
    public static function requireLogin()
    {
        if (!Auth::isLoggedIn()) {

            die("unauthorised");

        }
    }

    /**
     * @Desc: Log in using the session
     *
     * @return void
     *
     * @Author: Tong
     * @Date: 2019-09-03 12:53:40
     *
     */
    public static function login()
    {
        // regenerate session id to prevent session fixation attacks!
        session_regenerate_id(true);

        $_SESSION['is_logged_in'] = true;
    }

    /**
     * @Desc: Log out using the session
     *
     * @return void
     *
     * @Author: Tong
     * @Date: 2019-09-03 12:55:54
     *
     */
    public static function logout()
    {
        // Unset all of the session variables.
        $_SESSION = array();

        // If it's desired to kill the session, also delete the session cookie.
        // Note: This will destroy the session, and not just the session data!
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Finally, destroy the session.
        session_destroy();
    }
}
