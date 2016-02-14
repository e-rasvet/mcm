<?php

include_once "config.php";

session_destroy();

$urlinfo = parse_url($CFG['wwwroot']);
setcookie("mcm_username", '', time()+3600*24*7, "/", $urlinfo['host']);
setcookie("mcm_password", '', time()+3600*24*7, "/", $urlinfo['host']);

header("Location: index.php");
