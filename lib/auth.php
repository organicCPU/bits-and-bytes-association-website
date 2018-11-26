<?php

require_once "config.php";

if(!defined("baseLoaded"))
{
  header("Location: " . $_SERVER['CLIENT_PATH'] . " index.php");
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

  $query = "SELECT Username, Password, Usergroup FROM users WHERE Username = :username"; 
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
      $_SESSION['global_permissions'] = $rows[0]['Usergroup'];
      getPermissions($_SESSION['global_permissions']); //assuming that this pseudo-subquery is better than a join
      //$_SESSION['isAdmin'] = $rows[0]
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

function getPermissions($string)
{
  global $db;

  $query = "SELECT IsAdmin, CanCreate, CanUpdate, CanRead, CanDelete FROM usergroups WHERE Usergroup = $string"; 
  $statement = $db->prepare($query);
  $statement -> execute();
  $rows = $statement->fetch();

  //should save this as an array but meh this is a prototype anyway
  $_SESSION['admin'] = $rows[0]['IsAdmin'];
  $_SESSION['read'] = $rows[0]['CanRead'];
  $_SESSION['create'] = $rows[0]['CanCreate'];
  $_SESSION['update'] = $rows[0]['CanUpdate'];
  $_SESSION['delete'] = $rows[0]['CanDelete'];

}

function logout()
{
  unset($_SESSION['login_user']);
  unset($_SESSION['status_code']);
  unset($_SESSION['admin']);
  unset($_SESSION[['create']]);
  unset($_SESSION[['read']]);
  unset($_SESSION[['update']]);
  unset($_SESSION[['delete']]);
}

function changePassword($username, $password)
{

}

?>