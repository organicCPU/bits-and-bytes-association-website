<?php

define("baseLoaded", 1);

require "../lib/config.php";
require_once $_SERVER['SERVER_PATH'] . "lib/auth.php";
require_once $_SERVER['SERVER_PATH'] . "lib/process.php";
require_once $_SERVER['SERVER_PATH'] . "vendor/ezyang/htmlpurifier/library/HTMLPurifier.auto.php";

$config = HTMLPurifier_Config::createDefault();
// configuration goes here:
$config->set('Core.Encoding', 'UTF-8'); // replace with your encoding
$config->set('HTML.Doctype', 'HTML 4.01 Transitional'); // replace with your doctype
$purifier = new HTMLPurifier($config);


if (!empty($_GET))
{
    global $id;
    $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

    $post = pullPost($id);
    $_SESSION["status_code"] = validatePostPermissions($post);
    
    if($_SESSION["status_code"] == POST_FOUND)
    {
        $postID = $post['ID'];
    }
}


if ($_SESSION['read'] != 1 && $_SESSION['admin'] != 1)
{
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="<?=$_SERVER['CLIENT_PATH']?>/assets/img/icons/favicon.ico" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?=$_SERVER['CLIENT_PATH']?>/assets/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="stylesheet" href="<?=$_SERVER['CLIENT_PATH']?>/assets/css/main.css">
    <title><?=$post['Title']?></title>
</head>
<body>

<?php
    include $_SERVER['SERVER_PATH'] . "/header.php";
?>

    <div class="container-fluid">
    <h1><?=$post['Title']?></h1>
    <hr/>
    <?=$purifier->purify($post['Content'])?>
</div>

    <?php if($post['OwnerID'] == getUsers($_SESSION['login_user'])[1][0]['UID'] || $_SESSION['admin']) : ?>
    <div class="container-fluid">
<a class="btn btn-primary" role="button" href="<?=$_SERVER['CLIENT_PATH'] . '/post/edit.php?id=' . $id?>">Edit</a>
<!--<a class="btn btn-danger" role="button" href="<?='delete/'.$id?>">Delete</a>-->
    </div>
<?php endif?>

<?php
    include $_SERVER['SERVER_PATH'] . "/footer.php";
?>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="<?=$_SERVER['CLIENT_PATH']?>/assets/js/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="<?=$_SERVER['CLIENT_PATH']?>/assets/js/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="<?=$_SERVER['CLIENT_PATH']?>/assets/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>