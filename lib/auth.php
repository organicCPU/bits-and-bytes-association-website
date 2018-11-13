<?php

require("connect.php");

if(!defined("baseLoaded"))
{
  header("Location: index.php");
}
else
{
  session_start();
}

function login($username, $password)
{
  global $db;

  $username = filter_var($username, FILTER_SANITIZE_SPECIAL_CHARS);
  $password = filter_var($password, FILTER_SANITIZE_SPECIAL_CHARS);
  logout(); //in case they somehow get here while logged in

  $query = "SELECT Password FROM users WHERE Username = :username";
  $statement = $db->prepare($query);
  $statement->bindValue(':username', $username, PDO::PARAM_STR);
  $statement -> execute();
  $rows = $statement->fetchAll();

  if(count($rows) == 1)
  {
    //possibly insecure, fix later
    $result = password_verify($password, $rows[0]['Password']);
  
    //clean up with ? op
    if ($result == 1)
    {
        $_SESSION['login_user'] = $username; //is this best practice for login? should I store their permissions as well?
        ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
           Welcome back, <?=$_SESSION['login_user']?>.
           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
             <span aria-hidden="true">&times;</span>
           </button>
          </div>
        <?php
    }
    else
    {
      ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          Authentication unsuccessful. Invalid credentials.
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      <?php
    }
  }
  else
  {
    ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        Internal database error.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
         <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <?php
  }
}

function logout()
{
  unset($_SESSION['login_user']);
}

function changePassword($username, $password)
{

}
?>