<?php

include_once "../../config.php";

if (isset($_SESSION['apps']['ordering'])) {
  $id         = optional_param('id', NULL, PARAM_INT);
  $appsfolder = browser_detect();
  
  $list = json_decode($_SESSION['apps']['ordering']);
  
  $idc = $list[$id];
  if (isset($list[$id + 1])) $idnext = $id + 1; else $idnext = 0;

  $data = get_record("apps_ordering", array("id" => $idc));
  $score = get_user_score($_SESSION['userid'], 9);
  
  $c = 0;
  $words = array();
  for($i=1;$i <= 10; $i++) {
    $var = "var".$i;
    
    if(!empty($data->{$var}))
      $words[$i] = $data->{$var};
  }
  //$words = shuffle_assoc ($words);
  
  
  if (isset($_SESSION['currentcourse'])) 
    $course = get_record("course", array("id" => $_SESSION['currentcourse']));
  else 
    $course = get_record("course", array("id" => $_COOKIE['mcm_course']));
  
  add_log($_SESSION['userid'], 9, time());
  
  
  mobilehtmlheader('ordering activity');
  
  include_once $CFG['dirroot']."/apps/ordering/".$appsfolder."/i.php";
}




