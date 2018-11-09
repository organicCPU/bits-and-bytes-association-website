<?php

if(!defined("baseLoaded"))
{
    header("Location: index.php");
}
    //In production, change the below three variables or work around this system entirely:
    define('DB_DSN','mysql:host=localhost;dbname=bba');
    define('DB_USER','p305'); 
    define('DB_PASS','HHMvSSZuRJ8SYCxz');
    
    try {
        $db = new PDO(DB_DSN, DB_USER, DB_PASS);
        $db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        print "Error: " . $e->getMessage();
        die(); // Force execution to stop on errors.
    }
?>