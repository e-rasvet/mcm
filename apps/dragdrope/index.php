
<?php

include_once "../../config.php";

if (isset($_SESSION['apps']['dragdrope'])) {
  $id         = optional_param('id', NULL, PARAM_INT);
  $appsfolder = browser_detect();
  
  $list = json_decode($_SESSION['apps']['dragdrope']);
  
  $idc = $list[$id];
  if (isset($list[$id + 1])) $idnext = $id + 1; else $idnext = 0;

  $data = get_record("apps_dragdrope", array("id" => $idc));
  $score = get_user_score($_SESSION['userid'], 8);
  
  $c = 0;
  $questions = array();
  for($i=1;$i <= 5; $i++) {
    $var = "var".$i;
    
    if(!empty($data->{$var}))
      $questions[$i] = $data->{$var};
  }
  $questions = shuffle_assoc ($questions);
  
  $answers = array();
  for($i=1;$i <= 5; $i++) {
    $var = "a".$i;
    
    if(!empty($data->{$var}))
      $answers[$i] = $data->{$var};
  }
  $answers = shuffle_assoc ($answers);
  
  
  if (isset($_SESSION['currentcourse'])) 
    $course = get_record("course", array("id" => $_SESSION['currentcourse']));
  else 
    $course = get_record("course", array("id" => $_COOKIE['mcm_course']));
  
  add_log($_SESSION['userid'], 8, time());
  
  
  mobilehtmlheader('DragDrope activity');
  
  include_once $CFG['dirroot']."/apps/dragdrope/".$appsfolder."/i.php";
}




