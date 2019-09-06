<?php

require '../includes/init.php';

Auth::requireLogin();

$conn = require '../includes/db.php';

$champion = Champion::getByID($conn, $_POST['id']);

$published_at = $champion->publish($conn);

?><time><?=$published_at?></time>