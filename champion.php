<?php

// require 'classes/Database.php';
// require 'classes/Article.php';

require 'includes/init.php';

// $db = new Database();

// $conn = $db->getConn();

$conn = require 'includes/db.php';

if (isset($_GET['id'])) {

    $champion = Champion::getWithCategories($conn, $_GET['id'], true);

} else {

    $champion = null;

}

?>

<?php require 'includes/header.php';?>

<div class="container">

<?php if ($champion): ?>
    <article>

        <div class="row row-header">
            <h2><?=htmlspecialchars($champion[0]['name']);?></h2>
        </div>

        <div class="row row-content">
            <time datetime="<?=$champion[0]['published_at']?>">
                <?php
$datatime = new DateTime($champion[0]['published_at']);
echo $datatime->format('j F, Y');
?>
            </time>
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

<?php else: ?>
    <p>No champions found.</p>
<?php endif;?>

</div>

<?php require 'includes/footer.php';?>
