<?php

define("baseLoaded", 1);

require('blog/connect.php');

function passwordTest()
{
    $result = password_hash("common_password", PASSWORD_BCRYPT);
    echo $result;
    echo "\n";
    echo password_verify("common_password", $result);

    //global $db;

    //$query = "INSERT INTO `users` (`Username`, `Password`, `Email`, `FirstName`, `LastName`, `Biography`, `BoardStartDate`, `BoardEndDate`, `Usergroup`) VALUES ('Mika', '$result', 'test@test.test', 'Michael', 'memes', NULL, NULL, NULL, '1')";
    //$statement = $db->prepare($query);
    //$statement -> execute();
    //$rows = $statement->fetchAll();
}

function register($username, $password, $email, $firstname, $lastname)
{
    $usergroup = 4;  //should I query the DB for default user group?
    global $db;

    //Additional sanitization required.

    $username = filter_var($username, FILTER_SANITIZE_SPECIAL_CHARS);
    $password = filter_var($password, FILTER_SANITIZE_SPECIAL_CHARS); //heh
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $firstname = filter_var($firstname, FILTER_SANITIZE_SPECIAL_CHARS);
    $lastname = filter_var($lastname, FILTER_SANITIZE_SPECIAL_CHARS);

    //Validation required.

    $constraints = (checkConstraints($username, $password, $email, $firstname, $lastname));

    if($constraints == "1")
    {
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

            echo "Registration successful.";
        }
        catch (PDOException $e)
        {
            //echo 'Caught exception: ',  $e->getMessage(), "\n";
    
            //Find violated constraint:
            if ($e->errorInfo[1] == 1062) //if a UNIQUE constraint was violated
            {
                $errorString = $e->errorInfo[2];
                $errorString = substr($errorString, strpos($errorString, "for key", -18) + 9, -1); //might not be able to be attacked in email field
                echo $errorString;
            }
            else
            {
                echo "Undefined error occurred.";
            }
        }  
    }
    else
    {
        echo $constraints;
    }
}

function checkConstraints($username, $password, $email, $firstname, $lastname)
{
    define("USERNAME_MIN_LENGTH", 1);
    define("USERNAME_MAX_LENGTH", 20);
    define("PASSWORD_MIN_LENGTH", 6);
    define("NAME_MIN_LENGTH", 2);

    $result = "1";

    if ((strlen($username) < USERNAME_MIN_LENGTH) || (strlen($username) > USERNAME_MAX_LENGTH))
    {
        $result = "Username needs to be between " . USERNAME_MIN_LENGTH . " and " . USERNAME_MAX_LENGTH . " characters.";
    }
    else if (strlen($password) < PASSWORD_MIN_LENGTH)
    {
        $result = "Password";
    }
    else if((filter_var($email, FILTER_VALIDATE_EMAIL)) == false)
    {
        $result = "Email";
    }
    else if(strlen($firstname) < NAME_MIN_LENGTH)
    {
        $result = "First Name";
    }
    else if(strlen($lastname) < NAME_MIN_LENGTH)
    {
        $result = "Last Name";
    }

    return $result;
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
    <title>Hello, world!</title>
</head>
<body>

<?php
    include("header.php");
?>

    <h1>Hello, world!</h1>

<?php
    include("footer.php");
?>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="./assets/js/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="./assets/js/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="./assets/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>