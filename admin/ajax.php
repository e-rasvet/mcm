<?php

if (!is_file("../config.php")) 
  header("Location: ../install.php"); 

include_once "../config.php";


$a      = optional_param('a');
$value  = optional_param('value');
$text   = optional_param('text');

if($a == 'quick-title-edit'){
  if (!empty($value) && !empty($text)) {
    list($oldtext,$apps) = explode("::", urldecode($value));
    $text = urldecode($text);
    $db->query("UPDATE {$CFG['prefix']}{$apps} SET `category` = '{$text}' WHERE `category` = '{$oldtext}'");
    
    switch ($apps) {
        case 'apps_choice':
            $i = 1;
            break;
        case 'apps_listening':
            $i = 4;
            break;
        case 'apps_quizzes':
            $i = 5;
            break;
        case 'apps_reading':
            $i = 3;
            break;
        case 'apps_vocabulary':
            $i = 2;
            break;
        case 'apps_resource':
            $i = 6;
            break;
    }
    
    $topicdata = get_records("course_topic", array(), '`order`');
    if (is_object($topicdata) || is_array($topicdata)) {
      while(list($key,$value)=each($topicdata)) {
        $activity = json_decode($value->data, true);

        foreach($activity as $k=>$v){
          $ik = key($v);
          $iv = current($v);
          if($ik == $i && $iv == $oldtext) {
            $activity[$k][$ik] = $text;
          }
        }
        
        $datam              = array();
        $datam['id']        = $value->id;
        $datam['data']      = json_encode($activity);
        
        $idt = insert_record("course_topic", $datam);
      }
    }
  }
}


