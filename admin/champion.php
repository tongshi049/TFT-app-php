<?php

require '../includes/init.php';

Auth::requireLogin();

$conn = require '../includes/db.php';

if (isset($_GET['id'])) {

    $champion = Champion::getWithCategories($conn, $_GET['id']);

} else {

    $champion = null;

}

?>

<?php require '../includes/header.php';?>

<div class="container">
<?php if ($champion): ?>

    <article>
    <div class="row row-header">
        <h2><?=htmlspecialchars($champion[0]['name']);?></h2>
    </div>

    <div class="row row-content">
        <?php if ($champion[0]['published_at']): ?>
            <time><?=$champion[0]['published_at'];?></time>
        <?php else: ?>
            Unpublished
        <?php endif;?>
    </div>

    <div class="row row-content">
        <?php if ($champion[0]['category_name']): ?>
            <p>Categories:
                <?php foreach ($champion as $a): ?>
                    <span class="badge badge-pill badge-info">
                        <?=htmlspecialchars($a['category_name']);?>
                    </span>
            <?php endforeach;?>
            </p>
        <?php endif;?>
    </div>

    <div class="row row-content">
        <div class="col-10">
        <?php if ($champion[0]['image_file']): ?>
            <img class="image-fluid img-thumbnail" src="/uploads/<?=$champion[0]['image_file'];?>" alt="">
        <?php endif;?>
        </div>
    </div>

    <div class="row row-content f-large">
        <p><?=htmlspecialchars($champion[0]['description']);?></p>
    </div>

    </article>

    <div class="row row-content col-5">
        <ul class="nav">
            <li class="nav-item"><a class="nav-link" href="edit-champion.php?id=<?=$champion[0]['id'];?>">Edit</a></li>
            <li class="nav-item"><a class="delete nav-link" href="delete-champion.php?id=<?=$champion[0]['id'];?>">Delete</a></li>
            <li class="nav-item"><a class="nav-link" href="edit-champion-image.php?id=<?=$champion[0]['id'];?>">Edit image</a></li>
        </ul>
    </div>

<?php else: ?>
    <p>No champions found.</p>
<?php endif;?>

</div>



<?php require '../includes/footer.php';?>
