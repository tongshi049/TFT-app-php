<?php

/**
 * Champion
 *
 * @Author: Tong
 * @Date: 2019-08-30 10:14:38
 *
 */
class Champion
{
    /**
     * Unique identifier
     * @var integer
     */
    public $id;

    /**
     * The champion name
     * @var name
     */
    public $name;

    /**
     * The champion description
     * @var string
     */
    public $description;

    /**
     * Validation errors
     * @var array
     */
    public $errors = [];

    /**
     * The publication date and time
     * @var date
     */
    public $published_at;

    /**
     * Path to the image
     * @var string
     */
    public $image_file;

    /**
     * @Desc: Get all the champions
     *
     * @param object $conn Connection to the database
     *
     * @return array An associative array of all the champion records
     *
     * @Author: Tong
     * @Date: 2019-08-30 10:13:29
     *
     */
    public static function getAll($conn)
    {
        $sql = "SELECT *
        FROM champion
        ORDER BY published_at;";

        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);

    }

    /**
     * @Desc: Get a page of champions
     *
     * @param object $conn Connection to the database
     * @param integer $limit Number of records to return
     * @param integer $offset Number of records to skip
     *
     * @return array An associative array of the page of champion records
     *
     * @Author: Tong
     * @Date: 2019-09-03 14:43:34
     *
     */
    public static function getPage($conn, $limit, $offset, $only_published = false)
    {

        $condition = $only_published ? ' WHERE published_at IS NOT NULL' : '';

        $sql = "SELECT c.*, category.name AS category_name
                FROM (SELECT *
                FROM champion
                $condition
                ORDER BY published_at
                LIMIT :limit
                OFFSET :offset) AS c
                LEFT JOIN champion_category
                ON c.id = champion_category.champion_id
                LEFT JOIN category
                ON champion_category.category_id = category.id";
        // if we add only_published constraint this will break pagination
        // so we add it before LIMIT and OFFSET

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $champions = [];

        $previous_id = null;

        foreach ($results as $row) {

            $champion_id = $row['id'];

            if ($champion_id != $previous_id) {

                $row['category_names'] = [];

                $champions[$champion_id] = $row;

            }

            if ($row['category_name'] !== null) {

                $champions[$champion_id]['category_names'][] = $row['category_name'];

            }

            $previous_id = $champion_id;

        }

        return $champions;
    }

    /**
     * @Desc: Get the champion record based on the ID
     *
     * @param object $conn Connection to the database
     * @param integer $id the champion ID
     * @param string $columns Optional list of columns for the select, defuat all
     *
     * @return mixed An object of this class, or null if not found
     *
     * @Author: Tong
     * @Date: 2019-08-30 10:09:35
     *
     */
    public static function getByID($conn, $id, $columns = '*')
    {
        $sql = "SELECT $columns
            FROM champion
            WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Champion');

        if ($stmt->execute()) {

            return $stmt->fetch();
        }

    }

    /**
     * @Desc: Update the champion with its current property values
     *
     * @param object $conn Connection to the database
     *
     * @return boolean True if the update was successful, false otherwise.
     *
     * @Author: Tong
     * @Date: 2019-08-30 10:44:52
     *
     */
    public function update($conn)
    {

        if ($this->validate()) {

            $sql = "UPDATE champion
                    SET name = :name,
                        description = :description,
                        published_at = :published_at
                    WHERE id = :id";

            $stmt = $conn->prepare($sql);

            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
            $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
            $stmt->bindValue(':description', $this->description, PDO::PARAM_STR);

            if ($this->published_at == '') {
                $stmt->bindValue(':published_at', null, PDO::PARAM_NULL);
            } else {
                $stmt->bindValue(':published_at', $this->published_at, PDO::PARAM_STR);
            }

            return $stmt->execute();
        } else {
            return false;
        }
    }

    /**
     * @Desc: Set the champion categories
     *
     * @param object $conn Connection to the database
     * @param array $ids Category IDs
     *
     * @return void
     *
     * @Author: Tong
     * @Date: 2019-09-04 10:41:09
     *
     */
    public function setCategories($conn, $ids)
    {
        if ($ids) {

            $sql = "INSERT IGNORE INTO champion_category (champion_id, category_id)
                    VALUES ";

            $values = [];

            foreach ($ids as $id) {
                $values[] = "({$this->id}, ?)";
            }

            $sql .= implode(", ", $values);

            //var_dump($sql);exit;

            $stmt = $conn->prepare($sql);

            foreach ($ids as $i => $id) {
                $stmt->bindValue($i + 1, $id, PDO::PARAM_INT);
            }

            $stmt->execute();
        }

        $sql = "DELETE FROM champion_category
                WHERE champion_id = {$this->id}";

        if ($ids) {

            $placeholders = array_fill(0, count($ids), '?');

            $sql .= " AND category_id NOT IN (" . implode(", ", $placeholders) . ")";

        }

        //var_dump($sql);exit;

        $stmt = $conn->prepare($sql);

        foreach ($ids as $i => $id) {
            $stmt->bindValue($i + 1, $id, PDO::PARAM_INT);
        }

        $stmt->execute();

    }

    /**
     * @Desc: Validate the properties, putting any validation error messages in the $errors
     *
     * @return boolean True if the current properties are valid, false otherwise
     *
     * @Author: Tong
     * @Date: 2019-08-29 09:49:03
     *
     */
    protected function validate()
    {

        // Validate the form data
        if ($this->name == '') {
            //die('Title is required');
            $this->errors[] = 'Name is required';
        }
        if ($this->description == '') {
            $this->errors[] = 'Description is required';
        }

        if ($this->published_at != '') {
            //????????
            $date_time = true; //date_create_from_format('m-d-Y', $published_at);

            if ($date_time === false) {
                $this->errors[] = 'Invalid date and time';
            } else {
                $date_errors = date_get_last_errors();

                if ($date_errors['warning_count'] > 0) {
                    $this->errors[] = 'Invalid date and time';
                }
            }
        }

        return empty($this->errors);

    }

    /**
     * @Desc: Delete the current champion
     *
     * @param object $conn Connection to the databse
     *
     * @return boolean True if the delete was successful, false otherwise
     *
     * @Author: Tong
     * @Date: 2019-08-30 12:23:13
     *
     */
    public function delete($conn)
    {
        $sql = "DELETE FROM champion
                WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * @Desc: Insert a new champion with its current property values
     *
     * @param object $conn Connection to the database
     *
     * @return boolean True if the insert was successful, false otherwise
     *
     * @Author: Tong
     * @Date: 2019-08-30 12:36:23
     *
     */
    public function create($conn)
    {
        if ($this->validate()) {

            $sql = "INSERT INTO champion (name, description, published_at)
                    VALUES (:name, :description, :published_at)";

            $stmt = $conn->prepare($sql);

            $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
            $stmt->bindValue(':description', $this->description, PDO::PARAM_STR);

            if ($this->published_at == '') {
                $stmt->bindValue(':published_at', null, PDO::PARAM_NULL);
            } else {
                $stmt->bindValue(':published_at', $this->published_at, PDO::PARAM_STR);
            }

            if ($stmt->execute()) {
                $this->id = $conn->lastInsertId();
                return true;
            }

        } else {
            return false;
        }
    }

    /**
     * @Desc: Get a count of the total number of records
     *
     * @param object $conn Counnection to the database
     *
     * @return integer The total number of records
     *
     * @Author: Tong
     * @Date: 2019-09-03 15:33:05
     *
     */
    public static function getTotal($conn, $only_published = false)
    {
        $condition = $only_published ? " WHERE published_at IS NOT NULL" : '';
        return $conn->query("SELECT COUNT(*) FROM champion $condition")->fetchColumn();
    }

    /**
     * @Desc: Update the image file property
     *
     * @param object $conn Conncection to the database
     * @param string $filename The filename of the image file
     *
     * @return boolean True if it was successful, false otherwise
     *
     * @Author: Tong
     * @Date: 2019-09-03 20:33:01
     *
     */
    public function setImageFile($conn, $filename)
    {
        $sql = "UPDATE champion
                SET image_file = :image_file
                WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':image_file', $filename, $filename == null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * @Desc: Get the champion record based on the ID along with associated categoris, if any
     *
     * @param object $conn Connection to the database
     * @param integer $id the champion ID
     *
     * @return array The champion data with categories
     *
     * @Author: Tong
     * @Date: 2019-09-04 09:33:55
     *
     */
    public static function getWithCategories($conn, $id, $only_published = false)
    {
        $sql = "SELECT champion.*, category.name AS category_name
                FROM champion
                LEFT JOIN champion_category
                ON champion.id = champion_category.champion_id
                LEFT JOIN category
                ON champion_category.category_id = category.id
                WHERE champion.id = :id";

        if ($only_published) {
            $sql .= ' AND champion.published_at IS NOT NULL'; // don't forget space before AND
        }

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @Desc: Get the champion's categories
     *
     * @param object $conn Connection to the database
     *
     * @return array The category data
     *
     * @Author: Tong
     * @Date: 2019-09-04 09:52:51
     *
     */
    public function getCategories($conn)
    {
        $sql = "SELECT category.*
                FROM category
                JOIN champion_category
                ON category.id = champion_category.category_id
                WHERE champion_category.champion_id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @Desc: Publish the champion, setting the published_at field to the current date and time
     *
     * @param object $conn Connection to the database
     *
     * @return mixed The published at date and time if successful, null otherwise
     *
     * @Author: Tong
     * @Date: 2019-09-04 14:51:49
     *
     */
    public function publish($conn)
    {
        $sql = "UPDATE champion
                SET published_at = :published_at
                WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

        $published_at = date("Y-m-d");

        $stmt->bindValue(':published_at', $published_at, PDO::PARAM_STR);

        if ($stmt->execute()) {
            return $published_at;
        }
    }
}
