<?php

define("baseLoaded", 1);

require_once "lib/auth.php";
require_once "lib/process.php";

if(isset($_SESSION['login_user']))
{
    header("Location: index.php");
}

if (!empty($_POST)) //sanitize
{
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
    $password2 = filter_input(INPUT_POST, "password2", FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $firstname = filter_input(INPUT_POST, "firstname", FILTER_SANITIZE_SPECIAL_CHARS);
    $lastname = filter_input(INPUT_POST, "lastname", FILTER_SANITIZE_SPECIAL_CHARS);
    
    register($username, $password, $password2, $email, $firstname, $lastname);
}

function register($username, $password, $password2, $email, $firstname, $lastname)
{
    $usergroup = 4;  //should I query the DB for default user group?
    global $db;

    $constraints = findRegistrationStatus($username, $password, $password2, $email, $firstname, $lastname); //validate

    try
    {
        $query = "INSERT INTO `users` (`Username`, `Password`, `Email`, `FirstName`, `LastName`, `Usergroup`) VALUES (:username, :password, :email, :FirstName, :LastName, $usergroup)";
        $statement = $db->prepare($query);
        $password = password_hash($password, PASSWORD_BCRYPT);
        $statement -> bindValue(':username', $username, PDO::PARAM_STR);
        $statement -> bindValue(':password', $password, PDO::PARAM_STR);
        $statement -> bindValue(':email', $email, PDO::PARAM_STR);
        $statement -> bindValue(':FirstName', $firstname, PDO::PARAM_STR);
        $statement -> bindValue(':LastName', $lastname, PDO::PARAM_STR);
        $statement -> execute();
    }
    catch (PDOException $e)
    {
        if ($e->errorInfo[1] == 1062) //if a UNIQUE constraint was violated
        {
            $constraints = substr($e->errorInfo[2], strpos($e->errorInfo[2], "for key", -18) + 9, -1); //might not be able to be attacked in email field

            if($constraints === "Username")
            {
                $constraints = UNIQUE_VIOLATION_USERNAME;
            }
            else if ($constraints === "Email")
            {
                $constraints = UNIQUE_VIOLATION_EMAIL;
            }
        }
    }  

    $_SESSION["status_code"] = $constraints;
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="./assets/img/icons/favicon.ico" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="./assets/css/main.css">
    <title>Registration</title>
</head>
<body>

<?php
    include $_SERVER['CLIENT_PATH'] . "/header.php";
?>

<div class="container">

<h1>Registration</h1>
<!--Serverside validation only atm-->
<form action="#" method="post">
    <div class="form-group">
        <label for="inputUser">Username:</label>
        <input type="text" class="form-control" name="username" id="inputUser" aria-describedby="user" placeholder="Username">
    </div>
    <div class="form-group">
    <label for="inputPassword">Password:</label>
    <input type="password" class="form-control" name="password" id="inputPassword" placeholder="Password">
  </div>
    <div class="form-group">
        <label for="inputFirstName">First Name:</label>
        <input type="text" class="form-control" name="firstname" id="inputFirstName" aria-describedby="FirstName" placeholder="First Name">
    </div>
    <div class="form-group">
        <label for="inputLastName">Last Name:</label>
        <input type="text" class="form-control" name="lastname" id="inputLastName" aria-describedby="LastName" placeholder="Last Name">
    </div>
  <div class="form-group">
    <label for="inputEmail">Email address:</label>
    <input type="email" class="form-control" name="email" id="inputEmail" aria-describedby="emailHelp" placeholder="Email">
    <small id="emailHelp" class="form-text text-muted">We'll never distribute your email.</small>
  </div>
  <div class="form-group">
    <label for="inputPasswordCheck">Verify Password:</label>
    <input type="password" class="form-control" name="password2" id="inputPasswordCheck" placeholder="Password">
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
</div>

<?php
    include $_SERVER['CLIENT_PATH'] . "/footer.php";
?>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="<?=$_SERVER['CLIENT_PATH']?>/assets/js/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="<?=$_SERVER['CLIENT_PATH']?>/assets/js/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="<?=$_SERVER['CLIENT_PATH']?>/assets/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>