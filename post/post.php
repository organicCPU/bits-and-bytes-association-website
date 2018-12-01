<?php

define("baseLoaded", 1);

require "../lib/config.php";
require_once $_SERVER['SERVER_PATH'] . "lib/auth.php";

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
    <link rel="stylesheet" href="<?=$_SERVER['CLIENT_PATH']?>/assets/css/main.css">
    <title>Create New Post</title>
</head>
<body>

<?php
    include $_SERVER['SERVER_PATH'] . "/header.php";
?>

    <div class="container-fluid">
    <h1>Create New Post</h1>
    <hr/>
<!--Serverside validation only atm-->
<form action="#" method="post">
    <div class="form-group">
        <label for="inputUser">Title:</label>
        <input type="text" class="form-control" name="title" id="inputTitle" aria-describedby="Title" placeholder="Title">
    </div>
    <div class="form-group">
        <label for="inputContent">Content:</label>
        <textarea class="form-control" name="content" id="inputContent" rows="5"></textarea>
    </div>
    <div class="form-group">
        <label for="selectCategory">Category:</label>
        <select class="form-control" name="category" id="selectCategory">
        <?php foreach (getCategories()[1] as $category) : ?>
            <option><?=$category[0]?></option>
        <?php endforeach ?>
        </select>
    </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
</div>


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