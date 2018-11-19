<?php

if(!defined("baseLoaded"))
{
  header("Location: ../index.php");
}
else
{
  session_start();
}

require_once "connect.php";
require_once "process.php";

function login($username, $password)
{
  global $db;

  $statusCode = GENERAL_ERROR;

  $username = filter_var($username, FILTER_SANITIZE_SPECIAL_CHARS);
  $password = filter_var($password, FILTER_SANITIZE_SPECIAL_CHARS);
  logout(); //in case they somehow get here while logged in

  $query = "SELECT Username, Password FROM users WHERE Username = :username";
  $statement = $db->prepare($query);
  $statement->bindValue(':username', $username, PDO::PARAM_STR);
  $statement -> execute();
  $rows = $statement->fetchAll();


  //if find SINGLE row in db
  if(count($rows) == 1)
  {
    //possibly insecure, fix later
    $result = password_verify($password, $rows[0]['Password']);
  
    //clean up with ? op
    if ($result == 1)
    {
      $statusCode = LOGIN_OK;
      $_SESSION['login_user'] = $rows[0]['Username'];
    }
    else
    {
      $statusCode = BAD_LOGIN;
    }
  }
  else if(count($rows) > 1)
  {
    $statusCode = GENERAL_ERROR;
  }
  else
  {
    $statusCode = BAD_LOGIN;
  }

  $_SESSION["status_code"] = $statusCode;
}

function logout()
{
  unset($_SESSION['login_user']);
  unset($_SESSION["status_code"]);
}

function changePassword($username, $password)
{

}

?>