<?php

//phpinfo();

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
    //var_dump($_FILES);
    try {

        // if the uploaded file is too large (exceeds post_maximum) then this file
        // will not be uploaded, i.e. the uploaded file is empty
        if (empty($_FILES)) {
            throw new Exception('Invalid upload');
        }

        switch ($_FILES['file']['error']) {
            case UPLOAD_ERR_OK:
                # code...
                break;

            case UPLOAD_ERR_NO_FILE:
                throw new Exception('No file uploaded');
                break;

            case UPLOAD_ERR_INI_SIZE:
                throw new Exception('File is too large (from the server settings)');
                break;

            default:
                throw new Exception('An error occurred');
                break;
        }

        if ($_FILES['file']['size'] > 1000000) {
            throw new Exception('File is too large');
        }

        $mime_types = ['image/gif', 'image/png', 'image/jpg', 'image/jpeg'];

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $_FILES['file']['tmp_name']);

        //if (!in_array($_FILES['file']['type'], $mime_types)) {
        if (!in_array($mime_type, $mime_types)) {

            throw new Exception('Invalid file type');

        }

        // Move the uploaded file
        $pathinfo = pathinfo($_FILES["file"]["name"]);

        $base = $pathinfo['filename'];

        $base = preg_replace('/[^a-zA-Z0-9_-]/', '_', $base);

        $base = mb_substr($base, 0, 200);

        $filename = $base . "." . $pathinfo['extension'];

        $destination = "../uploads/$filename";

        $i = 1;

        while (file_exists($destination)) {

            $filename = $base . "-$i." . $pathinfo['extension'];
            $destination = "../uploads/$filename";

            $i++;
        }

        if (move_uploaded_file($_FILES['file']['tmp_name'], $destination)) {

            $previous_image = $champion->image_file;

            //echo "File uploaded successfully.";
            if ($champion->setImageFile($conn, $filename)) {

                if ($previous_image) {
                    unlink("../uploads/$previous_image");
                }

                Url::redirect("/admin/champion.php?id={$champion->id}");

            }

        } else {

            throw new Exception('Unable to move uploaded file');

        }

    } catch (Exception $e) {
        echo $e->getMessage();
    }

}

?>

<?php require '../includes/header.php';?>

<div class="container">

    <div class="row row-header">
    <h2>Edit champion image</h2>
    </div>

    <div class="row row-content">
        <div class="col-10">
            <?php if ($champion->image_file): ?>
            <img class="image-fluid img-thumbnail" src="/uploads/<?=$champion->image_file;?>" alt="">
        </div>
        <div class="col-1 align-self-center">
            <a class="delete" href="delete-champion-image.php?id=<?=$champion->id;?>">Delete</a>
            <?php endif;?>
        </div>
    </div>

    <div class="row row-content mt-2">
        <form action="" method="post" enctype="multipart/form-data">

            <div>
                <label for="file">Image file</label>
                <input type="file" name="file" id="file">
            </div>
            <div class="mt-3"><button class="btn btn-primary">Upload</button></div>
        </form>
    </div>

</div>

<?php require '../includes/footer.php';?>
