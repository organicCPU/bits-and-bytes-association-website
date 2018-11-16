<?php

define("baseLoaded", 1);

require("lib/auth.php");

$action = filter_input(INPUT_GET, "action", FILTER_SANITIZE_SPECIAL_CHARS);

if ($action == "login")
{
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, "field", FILTER_SANITIZE_SPECIAL_CHARS); //I don't like this. Hash before sending the packet in future implementations. Too easy to packet sniff.

    login($username, $password);
}
else if($action == "logout")
{
    logout();
}

header("Location: index.php");

?>