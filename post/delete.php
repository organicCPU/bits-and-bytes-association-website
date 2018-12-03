<?php

define("baseLoaded", 1);

require "../lib/config.php";
require_once $_SERVER['SERVER_PATH'] . "lib/auth.php";
require_once $_SERVER['SERVER_PATH'] . "lib/process.php";
    
    if (!empty($_GET))
    {
        $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

        global $postID;

        $post = pullPost($id);
        $_SESSION["status_code"] = validatePostPermissions($post);
        
        if($_SESSION["status_code"] == POST_FOUND)
        {
            $postID = $post['ID'];
        }
        
    if ($_SESSION['delete'] == 1 || $post['OwnerID'] == getUsers($_SESSION['login_user'])[1][0]['UID'] || $_SESSION['admin'] == 1)
    {
        deletePost($id);
    }
    header("Location: " . $_SERVER['CLIENT_PATH'] . "/index.php"); 
    }

