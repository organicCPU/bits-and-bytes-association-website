 <?php


  if(!defined("baseLoaded"))
  {
    header("Location: index.php");
  }

  define('ADMIN_LOGIN','dank');

  define('ADMIN_PASSWORD','memes');


  if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])

      || ($_SERVER['PHP_AUTH_USER'] != ADMIN_LOGIN)

      || ($_SERVER['PHP_AUTH_PW'] != ADMIN_PASSWORD)) {

    header('HTTP/1.1 401 Unauthorized');

    header('WWW-Authenticate: Basic realm="da blag"');

    exit("Access Denied: Username and password required.");

  }

   

?>