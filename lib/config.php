<?php
if (isset($_SERVER['BBA_ROOT']))
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

if(!defined("baseLoaded"))
{
  header("Location: ../index.php");
}
?>