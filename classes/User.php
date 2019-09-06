<?php

/**
 * @Desc: User
 *
 * A person or entity that can log in the site
 * @Author: Tong
 * @Date: 2019-08-30 16:52:23
 *
 */
class User
{

    /**
     * Unique identifier
     * @var integer
     */
    public $id;

    /**
     * Unique string
     * @var string
     */
    public $username;

    /**
     *
     * @var string
     */
    public $password;

    /**
     * @Desc: Authenticate a user by username and password
     *
     * @param string $username Username
     * @param string $password Password
     *
     * @return boolean True if the credentials are correct, false otherwise
     *
     * @Author: Tong
     * @Date: 2019-08-30 16:56:13
     *
     */
    public static function authenticate($conn, $username, $password)
    {
        $sql = "SELECT *
                FROM user
                WHERE username = :username";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':username', $username, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');

        $stmt->execute();

        if ($user = $stmt->fetch()) {
            return password_verify($password, $user->password);
        }
    }

}
