
<?php

include_once "../../config.php";

if (isset($_SESSION['apps']['choice'])) {
  $id         = optional_param('id', NULL, PARAM_INT);
  $appsfolder = browser_detect();
  
  $list = json_decode($_SESSION['apps']['choice']);
  
  $idc = $list[$id];
  if (isset($list[$id + 1])) $idnext = $id + 1; else $idnext = 0;

  $data = get_record("apps_choice", array("id" => $idc));
  $score = get_user_score($_SESSION['userid'], 1);
  
  $c = 0;
  $correctchoices = "";
  $correctchoicesstring = "";
  for($i=1;$i <= 12; $i++) {
    $cor = "cor".$i;
    $var = "var".$i;
    if ($data->$cor == 1) {
      $c++;
      $correctchoices .= $i.",";
      $correctchoicesstring .= $data->$var.",";
    }
  }
  $correctchoices = substr($correctchoices, 0, -1);
  $correctchoicesstring = substr($correctchoicesstring, 0, -1);
  
  if ($c > 1) $multichoice = true; else $multichoice = false;
  
  
  if (isset($_SESSION['currentcourse'])) 
    $course = get_record("course", array("id" => $_SESSION['currentcourse']));
  else 
    $course = get_record("course", array("id" => $_COOKIE['mcm_course']));
  
  add_log($_SESSION['userid'], 1, time());
  
  
  mobilehtmlheader('Choice');
  
  include_once $CFG['dirroot']."/apps/choice/".$appsfolder."/i.php";
}




