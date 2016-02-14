<?php

  include_once "config.php";

  $tid          = optional_param('tid', NULL, PARAM_INT);
  $login        = optional_param('username', NULL, PARAM_TEXT);
  $pass         = optional_param('password', NULL, PARAM_TEXT);
  $id           = optional_param('id', NULL, PARAM_INT);
 

  if (!$USER = get_record("user", array("username" => $login, "password" => $pass))) {
      $response = array(
        'logged'  => false,
        'message' => 'InValid login or password'
      );
      
      echo json_encode($response);
      
      die();
  }
  
  
  if ($tid == 1) {
    $data = get_record("apps_choice", array("id" => $id));    
    if ($data->image) {
      if (is_file($CFG['dirroot']."/datas/choice/image/{$id}.jpg"))
        $data->imagetype = "jpg";
      else
        $data->imagetype = "png";
    }
  } else if ($tid == 2) {
    $data = get_record("apps_vocabulary", array("id" => $id));
    if ($data->image) {
      if (is_file($CFG['dirroot']."/datas/vocab/image/{$id}.jpg"))
        $data->imagetype = "jpg";
      else
        $data->imagetype = "png";
    }
  } else if ($tid == 3) {
    $data = get_record("apps_reading", array("id" => $id));
    if (is_file($CFG['dirroot']."/datas/reading/image/{$id}.jpg"))
      $data->imagetype = "jpg";
    else
      $data->imagetype = "png";
    $data2 = get_records("apps_reading_texts", array("readerid" => $id));
    while(list($key, $value)=each($data2)) {
      if (!empty($value->image)) {
        if (is_file($CFG['dirroot']."/datas/reading/image/{$id}_{$value->id}.jpg"))
          $data2[$key]->imagetype = "jpg";
        else
          $data2[$key]->imagetype = "png";
      }
    }
    
    reset($data2);
    
    echo json_encode(array('main' => $data, 'items' => $data2));
    die();
  } else if ($tid == 4) {
    $data = get_record("apps_listening", array("id" => $id));
  } else if ($tid == 5) {
    $data = get_record("apps_quizzes", array("id" => $id));    
    for ($i=1; $i <= 8; $i++) {
        if (is_file($CFG['dirroot']."/datas/quiz/image/{$id}_{$i}.jpg"))
          $data->imagetype[$i] = "jpg";
        else if (is_file($CFG['dirroot']."/datas/quiz/image/{$id}_{$i}.png"))
          $data->imagetype[$i] = "png";
        else
          $data->imagetype[$i] = "null";
    }
    $keys = range(1,8);
    shuffle($keys);
    $c = 0;
    foreach($keys as $key) {
      $c++;
      $var = 'var'.$key;
      $data->vars[$c]->text = $data->$var;
      $data->vars[$c]->id   = $key;
      $data->vars[$c]->imagetype = $data->imagetype[$key];
    }
    unset($data->imagetype);
  } 
  
  echo json_encode($data);