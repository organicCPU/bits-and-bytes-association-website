<?php

if(!defined("baseLoaded"))
{
    header("Location: index.php");
}
    //In production, change the below three variables:
    define('DB_DSN','mysql:host=localhost;dbname=bba');
    define('DB_USER','serveruser'); 
    define('DB_PASS','gorgonzola7!');
    
    try {
        $db = new PDO(DB_DSN, DB_USER, DB_PASS);
    } catch (PDOException $e) {
        print "Error: " . $e->getMessage();
        die(); // Force execution to stop on errors.
    }
?>