<?php

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
function isLoggedIn() 
{
    return isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'];
}