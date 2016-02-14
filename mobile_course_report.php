<?php

  include_once "config.php";

  $act          = optional_param('act', 'courses', PARAM_TEXT);
  $login        = optional_param('username', NULL, PARAM_TEXT);
  $pass         = optional_param('password', NULL, PARAM_TEXT);
  $courseid     = optional_param('courseid', NULL, PARAM_INT);
 

    if (!$USER = get_record("user", array("username" => $login, "password" => $pass))) {
      $response = array(
        'logged'  => false,
        'message' => 'InValid login or password'
      );
      
      echo json_encode($response);
      
      die();
    }
    
    
 
  if ($act == "courses") {
    $courses = get_records("course");

    $C = 0;
    $rescourseslist = array();

    while(list($key,$value)=each($courses)) {
      if (!empty($value->guest) || get_record("course_student", array("userid" => $USER->id, "courseid" => $value->id))) {
        $rescourseslist[] = array('id' => $value->id, 'name' => $value->fullname);
        $c++;
      }
    }
    reset($courses);
    
    if ($c == 0)
      $response = array(
        'logged'  => false,
        'message' => 'No courses avaiable for you'
      );
      
    if (is_array($response)) 
      echo json_encode($response);
    else
      echo json_encode($rescourseslist);
  }
  
  if ($act == "topics") {
    $topics = get_records("course_topic", array("courseid" => $courseid), '`order`');
    
    $restopicslistwithgroup = array();
    
    while(list($key,$value)=each($topics)) {
      $topiccontent  = json_decode($value->data);
      $restopicslist = array();
      $sectiontitle = $value->title;
      while(list($key2,$value2)=each($topiccontent)) {
        $actid = key($value2);
        $actcategory = $value2->$actid;
        //------LIST OF ITEMS--------------//
        if ($actid == 1) {
          $listofquizzes = array();
          $data = get_records("apps_choice", array("category" => $actcategory));
          while(list($key3,$value3)=each($data)) {
            $listofquizzes[] = $value3->id;
          }
          shuffle($listofquizzes); 
          $items = json_encode($listofquizzes);
        } else if ($actid == 2) {
          //Vocabulary
          $listofvocab = array();
          $data = get_records("apps_vocabulary", array("category" => $actcategory));
          while(list($ke3y,$value3)=each($data)) {
            $listofvocab[] = $value3->id;
          }
          shuffle($listofvocab); 
          $items = json_encode($listofvocab);
        } else if ($actid == 3) {
          //Reading
          $listofreading = array();
          $data = get_records("apps_reading", array("category" => $actcategory));
          while(list($key3,$value3)=each($data)) {
            $listofreading[] = $value3->id;
          }
          shuffle($listofreading); 
          $items = json_encode($listofreading);
        } else if ($actid == 4) {
          //Listening
          $listoflistening = array();
          $data = get_records("apps_listening", array("category" => $actcategory));
          while(list($key3,$value3)=each($data)) {
            $listoflistening[] = $value3->id;
          }
          shuffle($listoflistening); 
          $items = json_encode($listoflistening);
        } else if ($actid == 5) {
          //Quiz
          $listofquizzes = array();
          $data = get_records("apps_quizzes", array("category" => $actcategory));
          while(list($key3,$value3)=each($data)) {
            $listofquizzes[] = $value3->id;
          }
          shuffle($listofquizzes); 
          $items = json_encode($listofquizzes);
        }
        //---------------------------------//
        $restopicslist[] = array('id' => $value2->id, 'idact' => $actid, 'name' => $actcategory, 'type' => $appstables2[$actid], 'link' => urlencode($actcategory), 'items' => $items);
      }
      $restopicslistwithgroup[] = array('name' => $sectiontitle, 'data' => $restopicslist);
    }
    
    echo json_encode($restopicslistwithgroup);
  }
 