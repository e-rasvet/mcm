<?php

  include_once "config.php";

  $tid          = optional_param('tid', NULL, PARAM_INT);
  $login        = optional_param('username', NULL, PARAM_TEXT);
  $pass         = optional_param('password', NULL, PARAM_TEXT);
  $id           = optional_param('id', NULL, PARAM_INT);
  $cid          = optional_param('cid', 0, PARAM_INT);
 

  if (!$USER = get_record("user", array("username" => $login))) { //, "password" => $pass
      $response = array(
        'logged'  => false,
        'message' => 'InValid login or password'
      );
      
      echo json_encode($response);
      
      die();
  }
  
  if ($cid == 0)
    $courses = get_records("course");
  else
    $courses = get_records("course", array("id" => $cid));

  $C = 0;
  $rescourseslist = array();

  while(list($key,$value)=each($courses)) {
    if (!empty($value->guest) || get_record("course_student", array("userid" => $USER->id, "courseid" => $value->id))) {
		$courseid = $value->id;
		$topics = get_records("course_topic", array("courseid" => $courseid), '`order`');
		
		$restopicslistwithgroup = array();
		
		while(list($key2,$value2)=each($topics)) {
		  $topiccontent  = json_decode($value2->data);
		  $restopicslist = array();
		  $sectiontitle = $value2->title;
		  while(list($key3,$value3)=each($topiccontent)) {
			$actid = key($value3);
			$actcategory = $value3->$actid;
			//------LIST OF ITEMS--------------//
			if ($actid == 1) {
			  $listofquizzes = array();
			  $data = get_records("apps_choice", array("category" => $actcategory));
			  while(list($ke4,$value4)=each($data)) {
				$listofquizzes[] = $value4->id;
			  }
			  shuffle($listofquizzes); 
			  $items = implode(",",$listofquizzes);
			} else if ($actid == 2) {
			  //Vocabulary
			  $listofvocab = array();
			  $data = get_records("apps_vocabulary", array("category" => $actcategory));
			  while(list($key4,$value4)=each($data)) {
				$listofvocab[] = $value4->id;
			  }
			  shuffle($listofvocab); 
			  $items = implode(",",$listofvocab);
			} else if ($actid == 3) {
			  //Reading
			  $listofreading = array();
			  $data = get_records("apps_reading", array("category" => $actcategory));
			  while(list($key4,$value4)=each($data)) {
				$listofreading[] = $value4->id;
			  }
			  shuffle($listofreading); 
			  $items = implode(",",$listofreading);
			} else if ($actid == 4) {
			  //Listening
			  $listoflistening = array();
			  $data = get_records("apps_listening", array("category" => $actcategory));
			  while(list($key4,$value4)=each($data)) {
				$listoflistening[] = $value4->id;
			  }
			  shuffle($listoflistening); 
			  $items = implode(",",$listoflistening);
			} else if ($actid == 5) {
			  //Quiz
			  $listofquizzes = array();
			  $data = get_records("apps_quizzes", array("category" => $actcategory));
			  while(list($key4,$value4)=each($data)) {
				$listofquizzes[] = $value4->id;
			  }
			  shuffle($listofquizzes); 
			  $items = implode(",",$listofquizzes);
			}
			//---------------------------------//
			$items = explode(",", $items);
			$items = json_encode($items);
			
			$restopicslist[] = array('id' => $value3->id, 'idact' => $actid, 'name' => $actcategory, 'type' => $appstables2[$actid], 'link' => urlencode($actcategory), 'items' => $items);
		  }
		  $restopicslistwithgroup[] = array('name' => $sectiontitle, 'data' => $restopicslist);
		}
		
        $rescourseslist[] = array('id' => $value->id, 'name' => $value->fullname, 'sections' => $restopicslistwithgroup);
        $c++;
    }
  }
  reset($courses);
  
  echo json_encode($rescourseslist);
  
  