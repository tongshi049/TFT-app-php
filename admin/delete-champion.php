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

// make sure we can only delete the champion by submitting the form!
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    if ($champion->delete($conn)) {

        Url::redirect("/admin/index.php");

    }
}
?>

<?php require '../includes/header.php';?>

<h2>Delete champion</h2>

<!-- Use post to update, delete resources -->
<form method="post">
    <p>Are you sure?</p>
    <button>Delete</button>
    <a href="champion.php?id=<?=$champion->id;?>">Cancel</a>
</form>

<?php require '../includes/footer.php';?>