<?php

/**
 * Category
 *
 * Grouping for articles
 *
 * @Author: Tong
 * @Date: 2019-09-04 10:03:04
 *
 */
class Category
{

    /**
     * @Desc: Get all the categories
     *
     * @param object $conn Connection to the db
     *
     * @return array An associative array of all the article records
     *
     * @Author: Tong
     * @Date: 2019-09-04 10:05:02
     *
     */
    public static function getAll($conn)
    {
        $sql = "SELECT *
                FROM category
                ORDER BY name";

        $result = $conn->query($sql);

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
}
