<?php

require '../includes/init.php';

Auth::requireLogin();

$champion = new Champion();

$category_ids = [];

$conn = require '../includes/db.php';

$categories = Category::getAll($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // $db = new Database();
    // $conn = $db->getConn();

    $champion->name = $_POST['name'];
    $champion->description = $_POST['description'];
    $champion->published_at = $_POST['published_at'];

    $category_ids = $_POST['category'] ?? [];

    if ($champion->create($conn)) {

        $champion->setCategories($conn, $category_ids);

        Url::redirect("/admin/champion.php?id={$champion->id}");

    }
}

?>

<?php require '../includes/header.php';?>

<div class="container">
    <div class="row row-header">
        <h2>New champion</h2>
    </div>


    <?php require 'includes/champion-form.php';?>

</div>
<?php require '../includes/footer.php';?>
