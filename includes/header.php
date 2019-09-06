<!DOCTYPE html>
<html>
<head>
  <title>TFT GAME</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet"
  href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
  integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
  crossorigin="anonymous">
  <link rel="stylesheet" href="/css/styles.css">
  <link rel="stylesheet" href="/css/jquery.datetimepicker.min.css">
</head>
<body>



  <nav class="navbar navbar-dark navbar-expand-sm fixed-top nav-large nav-white">
    <div class="container bg-image">
      <ul class="nav">

        <li class="nav-item">
          <a class="nav-link" href="/"><font color="white">Home</font></a>
        </li>

        <?php if (Auth::isLoggedIn()): ?>

          <li class="nav-item">
            <a class="nav-link" href="/admin/" ><font color="white">Admin</font></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/logout.php" ><font color="white">Log out</font></a>
          </li>

        <?php else: ?>

          <li class="nav-item">
            <a class="nav-link" href="/login.php" ><font color="white">Log in</font></a>
          </li>

        <?php endif;?>

        <li class="nav-item">
          <a class="nav-link" href="/contact.php"><font color="white">Contact</font></a>
        </li>

      </ul>
    </div>
  </nav>

  <header class="jumbotron">
      <div class="container">
          <div class="row row-header">
              <div class="col-12 col-sm-6">
                  <h1>TeamFight Tactics</h1>
                  <p>In this app, we introduce polular champions in this game.</p>
              </div>
              <div class="col-12 col-sm-3 align-self-center">
                  <img src="/img/TFT.jpg" class="img-fluid">
              </div>
          </div>
      </div>
  </header>

  <main>