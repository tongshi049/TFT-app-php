<?php

require '../includes/init.php';
Auth::requireLogin();

$conn = require '../includes/db.php';

$paginator = new Paginator($_GET['page'] ?? 1, 5, Champion::getTotal($conn)); // null coalescing operator
$champions = Champion::getPage($conn, $paginator->limit, $paginator->offset);

?>

<?php require '../includes/header.php';?>

<div class="container">

<div class="row row-header">
  <div class="col-12 row">
    <span class="col-2 align-self-center"><h3>Administration</h3></span>
    <span class="offset-1 align-self-center"><img class="resize-l" src="/img/image.jpg" alt=""></span>
    <hr>
  </div>
</div>

  <div class="row row-content ml-3">
    <p><a href="new-champion.php">New Champion</a></p>
  </div>

  <?php if (empty($champions)): ?>
    <div class="row row-content">
      <p>No champions found.</p>
    </div>
  <?php else: ?>

    <div class="col-8">
      <table class="table table-striped">
        <thead class="thead-dark">
          <th style="width: 60%">Name</th>
          <th style="width: 40%">Published</th>
        </thead>
        <tbody>
        <?php foreach ($champions as $champion): ?>

          <tr style="height: 60px">
            <td>
              <a href="champion.php?id=<?=$champion["id"]?>">
                <?=htmlspecialchars($champion["name"]);?>
              </a>
              <?php foreach ($champion["category_names"] as $category_name): ?>
              <span class="badge badge-pill badge-info"><?=htmlspecialchars($category_name)?></span>
              <?php endforeach;?>
            </td>
            <td>
              <?php if ($champion['published_at']): ?>
                <time><?=$champion['published_at'];?></time>
              <?php else: ?>
                Unpublished
                <button class="publish btn btn-primary btn-sm" data-id="<?=$champion['id']?>">Publish</button>
              <?php endif;?>
            </td>
          </tr>

        <?php endforeach;?>
        </tbody>
      </table>
    </div>

    <div class="col-8 mx-auto">
      <?php require '../includes/pagination.php';?>
    </div>
  <?php endif;?>

</div>

<?php require '../includes/footer.php';?>
