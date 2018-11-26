<?php

require_once "config.php";

is_Library_File();

define("USERNAME_MIN_LENGTH", 1);
define("USERNAME_MAX_LENGTH", 20);
define("PASSWORD_MIN_LENGTH", 6);
define("NAME_MIN_LENGTH", 2);

//class StatusCodes extends SplEnum
//{
    //const __default = self::GENERAL_ERROR;
    const OK_PREFIX = 2;
    const CLIENT_ERROR_PREFIX = 4;
    const SERVER_ERROR_PREFIX = 5;

    const OK = 200;
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

function printQuery($cols, $rows)
{
    //oh god #YOLO
    ?>
    <div class="table-responsive">
        <table class="table">
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
    <?php
}

function getUsers($search = "all")
{
    global $db;

    $cols = ['UID', 'Username', 'Email', 'FirstName', 'LastName', 'BoardStartDate', 'BoardEndDate', 'Usergroup'];

    $query = "SELECT UID, Username, Email, FirstName, LastName, BoardStartDate, BoardEndDate, Usergroup FROM Users"; 
    $statement = $db->prepare($query);
    $statement -> execute();
    $rows = $statement->fetchAll();
    printQuery($cols, $rows);
}

function updateUser($username)
{
    global $db;
}

function deleteUser()
{
    global $db;
}

function createUser()
{
    global $db;
}

function createCategory()
{
    global $db;
}

function getCategories()
{
    global $db;

    $cols = ['Name', 'ShowInHeader'];

    $query = "SELECT Name, ShowInHeader FROM Categories"; 
    $statement = $db->prepare($query);
    $statement -> execute();
    $rows = $statement->fetchAll();
    printQuery($cols, $rows);
}

function updateCategory()
{
    global $db;
}

function deleteCategory()
{
    global $db;
}

function createPost()
{
    global $db;
}

function getPosts()
{
    global $db;

    $cols = ['Title', 'Date', 'OwnerID', 'CategoryID'];

    $query = "SELECT Title, Date, OwnerID, CategoryID FROM posts"; 
    $statement = $db->prepare($query);
    $statement -> execute();
    $rows = $statement->fetchAll();
    printQuery($cols, $rows);
}

function updatePost()
{
    global $db;
}

function deletePost()
{
    global $db;
}

function createUsergroup()
{
    global $db;
}

function getUsergroups()
{
    global $db;

    $cols = ['Name', 'IsAdmin', 'CanCreate', 'CanUpdate', 'CanDelete'];

    $query = "SELECT Name, IsAdmin, CanCreate, CanUpdate, CanDelete FROM Usergroups"; 
    $statement = $db->prepare($query);
    $statement -> execute();
    $rows = $statement->fetchAll();
    printQuery($cols, $rows);
}

function updateUsergroup()
{
    global $db;
}

function deleteUsergroup()
{
    global $db;
}

?>