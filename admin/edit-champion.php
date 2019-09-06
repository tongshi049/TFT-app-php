<?php

require '../includes/init.php';
Auth::requireLogin();

$conn = require '../includes/db.php';

if (isset($_GET['id'])) {

    $champion = Champion::getByID($conn, $_GET['id']);

    if (!$champion) {
        die("champion not found");
    }

} else {

    die("id not supplied, champion not found");

}

$category_ids = array_column($champion->getCategories($conn), 'id');

$categories = Category::getAll($conn);
//var_dump($categories);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $champion->name = $_POST['name'];
    $champion->description = $_POST['description'];
    $champion->published_at = $_POST['published_at'];

    $category_ids = $_POST['category'] ?? [];

    if ($champion->update($conn)) {

        $champion->setCategories($conn, $category_ids);

        Url::redirect("/admin/champion.php?id={$champion->id}");

    }

}

?>

<?php require '../includes/header.php';?>

<div class="container">
    <div class="row row-header">
        <h2>Edit champion</h2>
    </div>


    <?php require 'includes/champion-form.php';?>
</div>

<?php require '../includes/footer.php';?>
