
<?php

include_once "../../config.php";

if (isset($_SESSION['apps']['vocabulary'])) {
  $id         = optional_param('id', NULL, PARAM_INT);
  $appsfolder = browser_detect();
  
  $list = json_decode($_SESSION['apps']['vocabulary']);
  
  $idc = $list[$id];
  if (isset($list[$id + 1])) $idnext = $id + 1; else $idnext = 0;

  $data = get_record("apps_vocabulary", array("id" => $idc));
  $score = get_user_score($_SESSION['userid'], 2);

  if (isset($_SESSION['currentcourse'])) 
    $course = get_record("course", array("id" => $_SESSION['currentcourse']));
  else 
    $course = get_record("course", array("id" => $_COOKIE['mcm_course']));
  
  add_log($_SESSION['userid'], 2, time());
  
  mobilehtmlheader('Vocabulary');
  
  include_once $CFG['dirroot']."/apps/vocabulary/".$appsfolder."/i.php";
}




