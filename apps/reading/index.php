
<?php

include_once "../../config.php";

if (isset($_SESSION['apps']['reading'])) {
  $id         = optional_param('id', NULL, PARAM_INT);
  $appsfolder = browser_detect();
  
  $list = json_decode($_SESSION['apps']['reading']);
  
  $idc = $list[$id];
  if (isset($list[$id + 1])) $idnext = $id + 1; else $idnext = 0;

  $data = get_record("apps_reading", array("id" => $idc));
  $score = get_user_score($_SESSION['userid'], 3);
  
  if (isset($_SESSION['currentcourse'])) 
    $course = get_record("course", array("id" => $_SESSION['currentcourse']));
  else 
    $course = get_record("course", array("id" => $_COOKIE['mcm_course']));
  
  add_log($_SESSION['userid'], 3, time());
  
  mobilehtmlheader('Reading');
  
  include_once $CFG['dirroot']."/apps/reading/".$appsfolder."/i.php";
}




