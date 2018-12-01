<?php

require_once "config.php";

is_Library_File();

define("USERNAME_MIN_LENGTH", 1);
define("USERNAME_MAX_LENGTH", 20);
define("PASSWORD_MIN_LENGTH", 6);
define("NAME_MIN_LENGTH", 2);

$post = 
[
	"id" => filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT),
	"title" => filter_input(INPUT_POST, "title", FILTER_SANITIZE_SPECIAL_CHARS),
	"content" => filter_input(INPUT_POST, "content", FILTER_SANITIZE_SPECIAL_CHARS)
];

//class StatusCodes extends SplEnum
//{
    //const __default = self::GENERAL_ERROR;
    const OK_PREFIX = 2;
    const CLIENT_ERROR_PREFIX = 4;
    const SERVER_ERROR_PREFIX = 5;

    const OK = 200;
    const POST_OK = 211;
    const LOGIN_OK = 281;
    const REGISTRATION_OK = 291;

    const UNAUTHORIZED = 401;
    const BAD_LOGIN = 481;
    const BAD_USERNAME = 490;
    const USERNAME_TAKEN = 491;
    const BAD_PASSWORD = 492;
    const PASSWORDS_UNMATCH = 493;
    const BAD_FIRST_NAME = 494;
    const BAD_LAST_NAME = 495;
    const BAD_EMAIL = 496;
    const EMAIL_TAKEN = 497;

    const GENERAL_ERROR = 500;
    const UNIQUE_VIOLATION_USERNAME = 592;
    const UNIQUE_VIOLATION_EMAIL = 596;

    const INTERNAL_SERVER_ERROR = 600;
//}

function sanitizePOST()
{
        //sanitize any potential useful inputs
        $id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT);
        $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
        $password2 = filter_input(INPUT_POST, "password2", FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
        $firstname = filter_input(INPUT_POST, "firstname", FILTER_SANITIZE_SPECIAL_CHARS);
        $lastname = filter_input(INPUT_POST, "lastname", FILTER_SANITIZE_SPECIAL_CHARS);
        $content = filter_input(INPUT_POST, "content", FILTER_SANITIZE_SPECIAL_CHARS);

        //return useful inputs
}

function findRegistrationStatus($username, $password, $password2, $email, $firstname, $lastname)
{
    $result = REGISTRATION_OK;

    if ((strlen($username) < USERNAME_MIN_LENGTH) || (strlen($username) > USERNAME_MAX_LENGTH))
    {
        $result = BAD_USERNAME;
    }
    else if (strlen($password) < PASSWORD_MIN_LENGTH)
    {
        $result = BAD_PASSWORD;
    }
    else if(strlen($firstname) < NAME_MIN_LENGTH)
    {
        $result = BAD_FIRST_NAME;
    }
    else if(strlen($lastname) < NAME_MIN_LENGTH)
    {
        $result = BAD_LAST_NAME;
    }
    else if((filter_var($email, FILTER_VALIDATE_EMAIL)) == false)
    {
        $result = BAD_EMAIL;
    }
    else if (!($password === $password2))
    {
        $result = PASSWORDS_UNMATCH;
    }

    return $result;
}

function statusCodeToText($status)
{
    $string;

    switch($status)
    {
        case OK:
            break;
        case BAD_USERNAME:
            $string = "Username needs to be between " . USERNAME_MIN_LENGTH . " and " . USERNAME_MAX_LENGTH . " characters.";
            break;
        case BAD_PASSWORD:
            $string = "Password is too weak. Please enter a stronger password.";
            break;
        case PASSWORDS_UNMATCH:
            $string = "The passwords provided do not match. Please re-enter your password.";
            break;
        case BAD_FIRST_NAME:
            $string = "First Name must be at least " . NAME_MIN_LENGTH . " characters long.";
            break;
        case BAD_LAST_NAME:
            $string = "Last Name must be at least " . NAME_MIN_LENGTH . " characters long.";
            break;
        case BAD_EMAIL:
            $string = "The syntax of the provided email address is malformed. Please enter a new email.";
            break;
        case GENERAL_ERROR:
            $string = "An unknown error has occurred.";
            break;
        case BAD_LOGIN:
            $string = "Authentication unsuccessful. Invalid credentials.";
            break;
        case LOGIN_OK:
            $string = "Welcome back, " . $_SESSION['login_user'] . ".";
            break;
        case REGISTRATION_OK:
            $string = "Registration successful.";
            break;
        case UNIQUE_VIOLATION_USERNAME:
            $string = "That username has already been taken. Please enter a new username.";
            break;
        case UNIQUE_VIOLATION_EMAIL:
            $string = "That email has already been taken. Please enter a new email.";
            break;
        case INTERNAL_SERVER_ERROR:
        default:
            $string = "Internal server error. Please try again later.";
            break;
    }

    return $string;
}

function statusCodeToAlert($status)
{
    $alertType;

    switch (substr($status, 0, 1))
    {
        case OK_PREFIX:
        $alertType = "alert-success";
        break;
        case CLIENT_ERROR_PREFIX:
        case SERVER_ERROR_PREFIX:
        $alertType = "alert-danger";
        break;
        default:
        $alertType = "alert-warning";
        break;
    }

    return $alertType;
}

function printAlertFromStatus($status)
{
    $alertType = statusCodeToAlert($status);
    $statusText = statusCodetoText($status);

    ?>
    <div class="alert <?=$alertType?> alert-dismissible fade show" role="alert">
     <?=$statusText?>
     <button type="button" class="close" data-dismiss="alert" aria-label="Close">
       <span aria-hidden="true">&times;</span>
     </button>
    </div>
  <?php

}

function printCustomAlert($alertType, $content)
{
    ?>
    <div class="alert <?=$alertType?> alert-dismissible fade show" role="alert">
     <?=$content?>
     <button type="button" class="close" data-dismiss="alert" aria-label="Close">
       <span aria-hidden="true">&times;</span>
     </button>
    </div>
  <?php
}

function printQueryPanel($args, $edit = null)
{
    $rows = $args[1];
    $cols = $args[0];
    //oh god #YOLO
    ?>
    <div class="table-responsive">
        <table class="table" id="makeEditable">
            <thead>
                <tr>
    <?php foreach($cols as $col) : ?>
        <th scope="col"><?=$col?></th>
    <?php endforeach?>
                </tr>
            </thead>
            <tbody>
    <?php foreach($rows as $row => $field) : ?>
    <tr>
        <?php foreach($cols as $col) : ?>
            <td><?=$field[$col]?></td>
        <?php endforeach ?>
    </tr>
    <?php endforeach?>
            </tbody>
        </table>
    </div>
    <?php if ($edit) : ?>
    <span>
  <button class="fas fa-plus" id="but_add"></button>
</span>
    <?php endif?>

    <?php
}


function getUsers($username = null)
{
    global $db;

    $query;
    $statement;

    $cols = ['UID', 'Username', 'Email', 'FirstName', 'LastName', 'BoardStartDate', 'BoardEndDate', 'Usergroup'];

    if ($username == null)
    {
        $query = "SELECT UID, Username, Email, FirstName, LastName, BoardStartDate, BoardEndDate, Usergroup FROM Users"; 
    }
    else
    {
        $query = "SELECT UID, Username, Email, FirstName, LastName, BoardStartDate, BoardEndDate, Usergroup FROM Users WHERE Username = :Username"; 
    }
    $statement = $db->prepare($query);
    $statement -> bindValue(':Username', $username, PDO::PARAM_STR);
    $statement -> execute();
    $rows = $statement->fetchAll();

    return [$cols, $rows];
    //printQuery($cols, $rows);
}

function updateUser($username)
{
    global $db;
}

function deleteUser($username)
{
    global $db;
}

function createUser($username, $password, $password2, $email, $firstname, $lastname, $usergroup)
{
    global $db;
    $constraints = findRegistrationStatus($username, $password, $password2, $email, $firstname, $lastname);
    try
    {
        $query = "INSERT INTO `users` (`Username`, `Password`, `Email`, `FirstName`, `LastName`, `Usergroup`) VALUES (:username, :password, :email, :FirstName, :LastName, :usergroup)";
        $statement = $db->prepare($query);
        $password = password_hash($password, PASSWORD_BCRYPT);
        $statement -> bindValue(':username', $username, PDO::PARAM_STR);
        $statement -> bindValue(':password', $password, PDO::PARAM_STR);
        $statement -> bindValue(':email', $email, PDO::PARAM_STR);
        $statement -> bindValue(':FirstName', $firstname, PDO::PARAM_STR);
        $statement -> bindValue(':LastName', $lastname, PDO::PARAM_STR);
        $statement -> bindValue(':usergroup', $usergroup, PDO::PARAM_INT);
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
    return $constraints;
}

function createCategory($name)
{
    global $db;
}

function getCategories($name = null)
{
    global $db;

    $cols = ['Name', 'ShowInHeader'];

    $query = "SELECT Name, ShowInHeader FROM Categories"; 
    $statement = $db->prepare($query);
    $statement -> execute();
    $rows = $statement->fetchAll();
    return [$cols, $rows];
}

function updateCategory($name)
{
    global $db;
}

function deleteCategory($name)
{
    global $db;
}

function createPost()
{
    global $db;

	$query = "UPDATE blog SET content = :content, title = :title, time = NOW() WHERE id = :id";
	$statement = $db->prepare($query);
	$statement->bindValue(':id', $post['id']);
	$statement->bindValue(':content', $post['content']);
	$statement->bindValue(':title', $post['title']);
	$statement -> execute();
}

function getPosts($OwnerID = null)
{
    global $db;

    $cols = ['Title', 'Date', 'OwnerID', 'CategoryID'];

    if($OwnerID == null)
    {
        $query = "SELECT Title, Date, OwnerID, CategoryID FROM Posts";
        $statement = $db->prepare($query);
    }
    else
    {
        $query = "SELECT Title, Date, OwnerID, CategoryID FROM Posts WHERE OwnerID = :OwnerID";
        $statement = $db->prepare($query);
        $statement -> bindValue(':OwnerID', $OwnerID, PDO::PARAM_STR);
    }
    $statement -> execute();
    $rows = $statement->fetchAll();
    return [$cols, $rows];
}

function updatePost($post)
{
	global $db;

	$query = "INSERT INTO blog (content, title) VALUES (:content, :title)";
	$statement = $db->prepare($query);
	$statement->bindValue(':content', $post['content']);
	$statement->bindValue(':title', $post['title']);
	$statement -> execute();
	header("Location: index.php");
	die();
}

function deletePost($post)
{
    global $db;

	$query = "DELETE FROM blog WHERE id = :id";
	$statement = $db->prepare($query);
	$statement->bindValue(':id', $post);
	$statement -> execute();
}

function createUsergroup()
{
    global $db;
}

function getUsergroups()
{
    global $db;

    $cols = ['Name', 'IsAdmin', 'CanCreate', 'CanRead', 'CanUpdate', 'CanDelete'];

    $query = "SELECT Name, IsAdmin, CanCreate, CanRead, CanUpdate, CanDelete FROM Usergroups"; 
    $statement = $db->prepare($query);
    $statement -> execute();
    $rows = $statement->fetchAll();

    return [$cols, $rows];
}

function updateUsergroup($usergroup)
{
    global $db;
}

function deleteUsergroup($usergroup)
{
    global $db;
}

?>