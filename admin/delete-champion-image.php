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

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $previous_image = $champion->image_file;

    //echo "File uploaded successfully.";
    if ($champion->setImageFile($conn, null)) {

        if ($previous_image) {
            unlink("../uploads/$previous_image");
        }

        Url::redirect("/admin/edit-champion-image.php?id={$champion->id}");

    }
}

?>

<?php require '../includes/header.php';?>

<h2>Delete champion image</h2>

<?php if ($champion->image_file): ?>
    <img src="/uploads/<?=$champion->image_file;?>" alt="">
<?php endif;?>

<form action="" method="post">

    <p>Are you sure?</p>

    <button>Delete</button>

    <a href="edit-champion-image.php?id=<?=$champion->id;?>" >Cancel</a>

</form>


<?php require '../includes/footer.php';?>
