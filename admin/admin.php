<?php

define("baseLoaded", 1);

require_once "../lib/config.php";
require_once $_SERVER['SERVER_PATH'] . "lib/auth.php";
require_once $_SERVER['SERVER_PATH'] . "lib/process.php";
//query DB if they are admin or not

if($_SESSION['admin'] != 1)
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
    <link rel="stylesheet" href="<?=$_SERVER['CLIENT_PATH']?>/assets/css/admin.css">
    <title>Administrative Control Panel</title>
</head>
<body>

<?php
    include $_SERVER['SERVER_PATH'] . "/header.php";
?>

<div class="container-fluid">
<h1>Administrative Control Panel</h1>
<hr/>
<div class="row">
    <div class="col-3">
      <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
        <a class="nav-link active" id="v-pills-overview-tab" data-toggle="pill" href="#v-pills-overview" role="tab" aria-controls="v-pills-overview" aria-selected="true">Overview</a>
        <a class="nav-link" id="v-pills-users-tab" data-toggle="pill" href="#v-pills-users" role="tab" aria-controls="v-pills-users" aria-selected="false">Users</a>
        <a class="nav-link" id="v-pills-usergroups-tab" data-toggle="pill" href="#v-pills-usergroups" role="tab" aria-controls="v-pills-usergroups" aria-selected="false">Usergroups</a>
        <a class="nav-link" id="v-pills-categories-tab" data-toggle="pill" href="#v-pills-categories" role="tab" aria-controls="v-pills-categories" aria-selected="false">Categories</a>
        <a class="nav-link" id="v-pills-posts-tab" data-toggle="pill" href="#v-pills-posts" role="tab" aria-controls="v-pills-posts" aria-selected="false">Posts</a>
      </div>
    </div>
    <div class="col-9">
      <div class="tab-content" id="v-pills-tabContent">
        <div class="tab-pane fade show active" id="v-pills-overview" role="tabpanel" aria-labelledby="v-pills-overview-tab">
          <p>text here</p>
        </div>
        <div class="tab-pane fade" id="v-pills-users" role="tabpanel" aria-labelledby="v-pills-users-tab">
            <?=printQueryPanel(getUsers(), true)?>
        </div>
        <div class="tab-pane fade" id="v-pills-usergroups" role="tabpanel" aria-labelledby="v-pills-usergroups-tab">
            <?=printQueryPanel(getUsergroups(), true)?>
        </div>
        <div class="tab-pane fade" id="v-pills-categories" role="tabpanel" aria-labelledby="v-pills-categories-tab"> 
          <?=printLinkPanel(getCategories(), false, $_SERVER['CLIENT_PATH'])?>
        </div>
        <div class="tab-pane fade" id="v-pills-posts" role="tabpanel" aria-labelledby="v-pills-posts-tab">
          <?=printLinkPanel(getPosts(), false, $_SERVER['CLIENT_PATH'] . "/post/view.php?id=")?>
        </div>
      </div>
    </div>
  </div>
  </div>
<?php
    include $_SERVER['SERVER_PATH'] . "/footer.php";
?>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="<?=$_SERVER['CLIENT_PATH']?>/assets/js/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="<?=$_SERVER['CLIENT_PATH']?>/assets/js/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="<?=$_SERVER['CLIENT_PATH']?>/assets/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="bootstable.js" ></script>
    <script src="admin.js"></script>

</body>
</html>