
<?php

include_once "../../config.php";

if (isset($_SESSION['apps']['resource'])) {
  $id         = optional_param('id', NULL, PARAM_INT);
  $appsfolder = browser_detect();
  
  $list = json_decode($_SESSION['apps']['resource']);
  
  $idc = $list[$id];
  if (isset($list[$id + 1])) $idnext = $id + 1; else $idnext = 0;

  $data = get_record("apps_resource", array("id" => $idc));
  $score = get_user_score($_SESSION['userid'], 6);
  
  if (isset($_SESSION['currentcourse'])) 
    $course = get_record("course", array("id" => $_SESSION['currentcourse']));
  else 
    $course = get_record("course", array("id" => $_COOKIE['mcm_course']));
  
  add_log($_SESSION['userid'], 6, time());
  
  mobilehtmlheader('resource');
  
  include_once $CFG['dirroot']."/apps/resource/".$appsfolder."/i.php";
}




