
<?php

include_once "../../config.php";

if (isset($_SESSION['apps']['listening'])) {
  $id         = optional_param('id', NULL, PARAM_INT);
  $appsfolder = browser_detect();
  
  $list = json_decode($_SESSION['apps']['listening']);
  
  $idc = $list[$id];
  if (isset($list[$id + 1])) $idnext = $id + 1; else $idnext = 0;

  $data = get_record("apps_listening", array("id" => $idc));
  $score = get_user_score($_SESSION['userid'], 4);
   
  if (isset($_SESSION['currentcourse'])) 
    $course = get_record("course", array("id" => $_SESSION['currentcourse']));
  else 
    $course = get_record("course", array("id" => $_COOKIE['mcm_course']));  
  
  mobilehtmlheader('Listening');
  
  add_log($_SESSION['userid'], 4, time());
  
  include_once $CFG['dirroot']."/apps/listening/".$appsfolder."/i.php";
}




