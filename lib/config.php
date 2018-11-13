<?php
if (isset($_SERVER['BBA_ROOT']))
{
  exit();
}
else
{
  $_SERVER['BBA_ROOT']=__DIR__;
}

if(!defined("baseLoaded"))
{
  header("Location: index.php");
}
?>