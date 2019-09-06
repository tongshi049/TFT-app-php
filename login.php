<?php

// require 'classes/Database.php';
// require 'classes/Url.php';
// require 'classes/User.php';

// session_start();

require 'includes/init.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $conn = require 'includes/db.php';

    if (User::authenticate($conn, $_POST['username'], $_POST['password'])) {

        // // regenerate session id to prevent session fixation attacks!
        // session_regenerate_id(true);

        // $_SESSION['is_logged_in'] = true;
        Auth::login();

        Url::redirect("/");

    } else {

        //die("login incorrect");
        $error = "Login incorrect";

    }
}

?>

<?php require 'includes/header.php';?>

<div class="container">

    <div class="row row-header">
        <div class="ml-3"><h2>Login</h2></div>
    </div>

    <?php if (!empty($error)): ?>
        <div class="row row-content">
            <p><?=$error?></p>
        </div>
    <?php endif;?>

    <form action="" method="post">

        <div class="form-group col-5">
            <label for="username"></label>
            <input type="text" class="form-control" name="username" id="username">
        </div>

        <div class="form-group col-5">
            <label for="password"></label>
            <input type="password" class="form-control" name="password" id="password">
        </div>

        <div class="ml-3"><button class="btn btn-primary">Log in</button></div>

    </form>

</div>

<?php require 'includes/footer.php';?>




