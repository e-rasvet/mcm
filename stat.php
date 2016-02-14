<?php

include_once "config.php";


$id          = optional_param('id', NULL, PARAM_INT);
$apps        = optional_param('apps', NULL, PARAM_TEXT);
$text        = optional_param('text', NULL, PARAM_TEXT);
$choice      = optional_param('choice', NULL, PARAM_TEXT);
$login       = optional_param('username', NULL, PARAM_TEXT);
$pass        = optional_param('password', NULL, PARAM_TEXT);
$coursemid   = optional_param('courseid', NULL, PARAM_INT);

if ($login && $pass) 
  if ($USER = get_record("user", array("username" => $login, "password" => $pass)))
    $_SESSION['userid'] = $USER->id;
    
if ($coursemid) $_SESSION['currentcourse'] = $coursemid;

  if (isset($_SESSION['currentcourse'])) 
    $courseid = $_SESSION['currentcourse'];
  else if (isset($_COOKIE['mcm_course']))
    $courseid = $_COOKIE['mcm_course'];
  else
    $courseid = 0;
    
if ($apps == "listening") {
  $datam              = array();
  $datam['userid']    = $_SESSION['userid'];
  $datam['apps']      = 4;
  $datam['appsid']    = $id;
  $datam['answer']    = $text;
  $datam['score']     = 0;
  $datam['courseid']  = $courseid;
  $datam['time']      = time();
  
  insert_record("score", $datam);
}

if ($apps == "choice") {
  $score = get_record("score", array("userid" => $_SESSION['userid'], "apps" => 1), 'score DESC');

  $datam              = array();
  $datam['userid']    = $_SESSION['userid'];
  $datam['apps']      = 1;
  $datam['appsid']    = $id;
  $datam['answer']    = $choice;
  $datam['courseid']  = $courseid;
  
  $data = get_record("apps_choice", array("id" => $id));
  $cor = "cor".$choice;
  if (!empty($data->$cor)) {
    /*** Check score ***/
    $incorects = $db->get_results("SELECT id FROM {$CFG['prefix']}score WHERE apps = 1 and appsid = '{$id}' and correct = 0 and time > '".(time()-30)."'");
    if (is_array($incorects) || is_object($incorects)) 
        $incount = count($incorects);
    else
        $incount = 0;
        
    $points = 5 - 2 * $incount;
    if ($points < 0) $points = 0;
    
    $datam['score'] = $score->score + $points;
    $datam['correct'] = 1;
  } else
    $datam['score'] = $score->score;
    
  $datam['time']   = time();
  
  insert_record("score", $datam);
  
  echo $datam['score'];
  die();
}

if ($apps == "quiz") {
  $score = get_record("score", array("userid" => $_SESSION['userid'], "apps" => 5), 'score DESC');

  $datam              = array();
  $datam['userid']    = $_SESSION['userid'];
  $datam['apps']      = 5;
  $datam['appsid']    = $id;
  $datam['answer']    = $choice;
  $datam['courseid']  = $courseid;
  
  if ($choice == 1) {
    /*** Check score ***/
    $incorects = $db->get_results("SELECT id FROM {$CFG['prefix']}score WHERE apps = 5 and appsid = '{$id}' and correct = 0 and time > '".(time()-30)."'");
    if (is_array($incorects) || is_object($incorects)) 
        $incount = count($incorects);
    else
        $incount = 0;
        
    $points = 5 - 2 * $incount;
    if ($points < 0) $points = 0;
        
    $datam['score'] = $score->score + $points;
    $datam['correct'] = 1;
  } else
    $datam['score'] = $score->score;
    
  $datam['time']   = time();
  
  insert_record("score", $datam);
  
  echo $datam['score'];
  die();
}
