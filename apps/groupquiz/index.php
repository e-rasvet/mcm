<?php

include_once "../../config.php";

if (isset($_SESSION['apps']['groupquiz'])) {
  $id         = optional_param('id', NULL, PARAM_INT);
  $appsfolder = browser_detect();
  
  $list = json_decode($_SESSION['apps']['groupquiz']);
  
  $idc = $list[$id];
  if (isset($list[$id + 1])) $idnext = $id + 1; else $idnext = 0;

  $data = get_record("apps_groupquiz", array("id" => $idc));
  //$score = get_user_score($_SESSION['userid'], 11);
  
  $quizs = get_records("apps_quizzes", array("apps_quizzes"=>$data->category));

  if (isset($_SESSION['currentcourse'])) 
    $course = get_record("course", array("id" => $_SESSION['currentcourse']));
  else 
    $course = get_record("course", array("id" => $_COOKIE['mcm_course']));


  mobilehtmlheader('Group Quiz');
  
  add_log($_SESSION['userid'], 11, time());
  
  if ($_SESSION['userid'] == 2){ //disable guest login
    echo 'Location: '.$CFG['wwwroot'].'/index.php/c/'.$course->id;
    die();
  }
  
  if (get_record("apps_groupquiz_list", array("uid1"=>$_SESSION['userid'])) || 
      get_record("apps_groupquiz_list", array("uid2"=>$_SESSION['userid'])) || 
      get_record("apps_groupquiz_list", array("uid3"=>$_SESSION['userid'])) || 
      get_record("apps_groupquiz_list", array("uid4"=>$_SESSION['userid'])) || 
      get_record("apps_groupquiz_list", array("uid5"=>$_SESSION['userid'])))
      include_once $CFG['dirroot']."/apps/groupquiz/".$appsfolder."/i2.php";
  else
      include_once $CFG['dirroot']."/apps/groupquiz/".$appsfolder."/i.php";
}




