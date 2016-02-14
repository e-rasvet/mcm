<?php

  include_once "config.php";

  $login        = optional_param('username', NULL, PARAM_TEXT);
  $pass         = optional_param('password', NULL, PARAM_TEXT);
  $appid        = optional_param('appid', NULL, PARAM_INT);
 
  if (!$USER = get_record("user", array("username" => $login, "password" => $pass))) {
    $response = array(
      'logged'  => false,
      'message' => 'InValid login or password'
    );
    echo json_encode($response);
    
    die();
  }


  

  $score = get_record("score", array("userid" => $USER->id, "apps" => $appid), 'score DESC');
  
  echo $score->score;

