<?php

include_once "../../config.php";

$id               = optional_param('id', NULL, PARAM_INT);
$act              = optional_param('act', NULL, PARAM_TEXT);
$name             = optional_param('name', NULL, PARAM_TEXT);
$apps             = optional_param('apps', NULL, PARAM_INT);
$topic_box        = optional_param('topic_box', NULL, PARAM_INT);


if ($act == "loadtopicact") {
  echo '<select name="topicactivitys_'.$name.'_'.$apps.'">';
  if ($apps == 1) {
    $data = $db->get_results("SELECT DISTINCT `category` FROM {$CFG['prefix']}apps_choice ORDER BY `category`",OBJECT);
    while (list($key,$value)=each($data)) {
      echo '<option value="'.$value->category.'">'.$value->category.'</option>';
    }
  } else if ($apps == 2) {
    $data = $db->get_results("SELECT DISTINCT `category` FROM {$CFG['prefix']}apps_vocabulary ORDER BY `category`",OBJECT);
    while (list($key,$value)=each($data)) {
      echo '<option value="'.$value->category.'">'.$value->category.'</option>';
    }
  } else if ($apps == 3) {
    $data = $db->get_results("SELECT DISTINCT `category` FROM {$CFG['prefix']}apps_reading ORDER BY `category`",OBJECT);
    while (list($key,$value)=each($data)) {
      echo '<option value="'.$value->category.'">'.$value->category.'</option>';
    }
  } else if ($apps == 4) {
    $data = $db->get_results("SELECT DISTINCT `category` FROM {$CFG['prefix']}apps_listening ORDER BY `category`",OBJECT);
    while (list($key,$value)=each($data)) {
      echo '<option value="'.$value->category.'">'.$value->category.'</option>';
    }
  } else if ($apps == 5) {
    $data = $db->get_results("SELECT DISTINCT `category` FROM {$CFG['prefix']}apps_quizzes ORDER BY `category`",OBJECT);
    while (list($key,$value)=each($data)) {
      echo '<option value="'.$value->category.'">'.$value->category.'</option>';
    }
  } else if ($apps == 6) {
    $data = $db->get_results("SELECT DISTINCT `category` FROM {$CFG['prefix']}apps_resource ORDER BY `category`",OBJECT);
    while (list($key,$value)=each($data)) {
      echo '<option value="'.$value->category.'">'.$value->category.'</option>';
    }
  } else if ($apps == 7) {
    $data = $db->get_results("SELECT DISTINCT `category` FROM {$CFG['prefix']}apps_clozeactivity ORDER BY `category`",OBJECT);
    while (list($key,$value)=each($data)) {
      echo '<option value="'.$value->category.'">'.$value->category.'</option>';
    }
  } else if ($apps == 8) {
    $data = $db->get_results("SELECT DISTINCT `category` FROM {$CFG['prefix']}apps_dragdrope ORDER BY `category`",OBJECT);
    while (list($key,$value)=each($data)) {
      echo '<option value="'.$value->category.'">'.$value->category.'</option>';
    }
  } else if ($apps == 9) {
    $data = $db->get_results("SELECT DISTINCT `category` FROM {$CFG['prefix']}apps_ordering ORDER BY `category`",OBJECT);
    while (list($key,$value)=each($data)) {
      echo '<option value="'.$value->category.'">'.$value->category.'</option>';
    }
  } else if ($apps == 10) {
    $data = $db->get_results("SELECT DISTINCT `category` FROM {$CFG['prefix']}apps_listenspeak ORDER BY `category`",OBJECT);
    while (list($key,$value)=each($data)) {
      echo '<option value="'.$value->category.'">'.$value->category.'</option>';
    }
  } else if ($apps == 11) {
    $data = $db->get_results("SELECT DISTINCT `category` FROM {$CFG['prefix']}apps_groupquiz ORDER BY `category`",OBJECT);
    while (list($key,$value)=each($data)) {
      echo '<option value="'.$value->category.'">'.$value->category.'</option>';
    }
  }
  
  echo '</select>';
}

if ($act == "updatetopiclist") {
  //print_r ($topic_box); Array ( [0] => 1 [1] => 3 [2] => 2 )
  $topicdata = get_records("course_topic", array("courseid" => $id), '`order`');

  $c = 0;
  while(list($key,$value)=each($topicdata)) {
    $c++;
    $nowdata[$c] = $value->id;
  }
  $j = 0;
  while(list($key,$value)=each($topic_box)) {
    $j++;
    $datam              = array();
    $datam['order']     = $j;
    $datam['id']        = $nowdata[$value];
    
    if ($j <= $c)
      $id = insert_record("course_topic", $datam);
  }
}
