 <?php

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
  unset($SESSION['login_user']); //in case they somehow get here while logged in

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
        $SESSION['login_user'] = $username; //is this best practice for login? should I store their permissions as well?

        echo "Authentication successful.";
    }
    else
    {
      echo "Authentication unsuccessful. Invalid credentials.";
    }
  }
  else
  {
    echo "Internal database error.";
  }
}

function changePassword($username, $password)
{

}

  //if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])  || ($_SERVER['PHP_AUTH_USER'] != ADMIN_LOGIN)|| ($_SERVER['PHP_AUTH_PW'] != ADMIN_PASSWORD))
  //{
    //header('HTTP/1.1 401 Unauthorized');

    //header('WWW-Authenticate: Basic realm="da blag"');

    //exit("Access Denied: Username and password required.");

  //}
?>