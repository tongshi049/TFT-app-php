<?php

require 'includes/init.php';

$conn = require 'includes/db.php';

$paginator = new Paginator($_GET['page'] ?? 1, 5, Champion::getTotal($conn, true));
$champions = Champion::getPage($conn, $paginator->limit, $paginator->offset, true);

?>

<?php require 'includes/header.php';?>

  <div class="container">

    <div class="row row-header">
      <div class="col-12 row ml-2">
        <span class="col-1 align-self-center"><h3>Champions</h3></span>
        <span class="offset-1 align-self-center"><img class="resize" src="/img/little-champs.jpg" alt=""></span>
        <hr>
      </div>
    </div>

    <?php if (empty($champions)): ?>
      <div class="row row-content">
        <p>No champions found.</p>
      </div>
    <?php else: ?>

      <div class="col-10">
        <ul id="index" class="list-group list-group-flush">
          <?php foreach ($champions as $champion): ?>

            <li class="list-group-item">
              <article>
                <h2>
                  <a href="champion.php?id=<?=$champion['id']?>">
                    <?=htmlspecialchars($champion["name"]);?>
                  </a>
                </h2>

                <time datetime="<?=$champion['published_at']?>">
                  <?php $datatime = new DateTime($champion['published_at']);
echo $datatime->format('j F, Y');?>
                </time>

                <?php if ($champion['category_names']): ?>
                  <p>Categories:
                    <?php foreach ($champion['category_names'] as $name): ?>
                      <span class="badge badge-pill badge-info">
                        <?=htmlspecialchars($name);?>
                      </span>
                    <?php endforeach;?>
                  </p>
                <?php endif;?>

                <p class="f-large">
                  <?=htmlspecialchars($champion["description"]);?>
                </p>
              </article>
            </li>

          <?php endforeach;?>
        </ul>
      </div>

      <div class="col-8 mx-auto">
        <?php require 'includes/pagination.php';?>
      </div>
  <?php endif;?>
</div>

<?php require 'includes/footer.php';?>
