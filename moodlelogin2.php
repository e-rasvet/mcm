<?php

include_once "config.php";

  $USER = json_decode(urldecode($_GET['json']));

  if(!$us = get_record("user", array("username" => $USER->username, "password" => $USER->password))) {
    $add = array();
    $add['username'] = $USER->username;
    $add['password'] = $USER->password;
    $add['firstname'] = $USER->firstname;
    $add['lastname'] = $USER->lastname;
    $add['email'] = $USER->email;
    
    if(!empty($add['username']))
      $id = insert_record("user", $add);
    else
      $id = 'no';
  } else 
    $id = $us->id;
  
 echo $id;
  
  //header("Location: index.php");