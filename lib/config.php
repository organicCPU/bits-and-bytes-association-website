<?php
//In production, this should be placed in the root directory for easy navigation. This should also be called by every single .php file that is not disjointed.
if (isset($_SERVER['SERVER_PATH']))
{
  exit();
}
else
{
  //$root = substr(__DIR__, 0, -3);
  //$root = 

  $_SERVER['SERVER_PATH']=substr(__DIR__, 0, -3); //primitive but whatever, lib should never be renamed
  $_SERVER['CLIENT_PATH']='/webdev2/project/repo'; //fix me later, should be smart-pathed $_SERVER['HTTP_HOST'] . 
}

function is_Library_File()
{
  if(!defined("baseLoaded"))
  {
    header("Location: " . $_SERVER['CLIENT_PATH'] . "/index.php");
  }
}

?>