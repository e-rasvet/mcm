<?php

include_once "../../config.php";
//include_once $CFG['dirroot'] . "/inc/f.php";

$id               = optional_param('id', NULL, PARAM_INT);
$app              = optional_param('app', NULL, PARAM_TEXT);
$end              = optional_param('end', NULL, PARAM_INT);


    $postdata = http_build_query(array('serverlogin' => $options['serverlogin'],'serverpassword' => $options['serverpassword'], 'siteurl' => $CFG['wwwroot'], 'id' => $id, 'type' => $app));
    $opts = array('http' => array(
      'method'  => 'POST',
      'header'  => 'Content-type: application/x-www-form-urlencoded',
      'content' => $postdata
     ));
    $context  = stream_context_create($opts);
    $result = @file_get_contents($mcm_server.'/apps/getbyid.php', false, $context);

    $data = json_decode($result);
    
if ($app == "apps_choice") {
    $newrecord = array();
    while(list($key,$value)=each($data->sql)) {
      if ($key != "id")
        $newrecord[$key] = $value;
    }
    if (!get_record("apps_choice", array("text" => $newrecord["text"], "var1" => $newrecord["var1"], "var2" => $newrecord["var2"], "var3" => $newrecord["var3"], "category" => $newrecord["category"]))) {
      $idnew = insert_record("apps_choice", $newrecord);
    
      if (!empty($newrecord['image'])) {
        if (strstr($data->image, ".jpg"))
          file_put_contents($CFG['dirroot']."/datas/choice/image/{$idnew}.jpg", file_get_contents($data->image));
        else
          file_put_contents($CFG['dirroot']."/datas/choice/image/{$idnew}.png", file_get_contents($data->image));
      }
    
      if (!empty($newrecord['soundtext'])) 
        file_put_contents($CFG['dirroot']."/datas/choice/soundtext/{$idnew}.mp3", file_get_contents($data->soundtext));
    
      $result = "<div class=\"logitem\">Choice item added (".stripslashes($newrecord['text']).")</div>";
    } else {
      $result = "<div class=\"logitem\">Choice item ".stripslashes($newrecord['text'])." - Already exist</div>";
    }
}

if ($app == "apps_listening") {
    $newrecord = array();
    while(list($key,$value)=each($data->sql)) {
      if ($key != "id")
        $newrecord[$key] = $value;
    }
    if (!get_record("apps_listening", array("feedback" => $newrecord["feedback"], "category" => $newrecord["category"]))) {
      $idnew = insert_record("apps_listening", $newrecord);
    
      if (!empty($newrecord['soundtext'])) 
        file_put_contents($CFG['dirroot']."/datas/listening/soundtext/{$idnew}.mp3", file_get_contents($data->soundtext));
    
      $result = "<div class=\"logitem\">Listening item added (".stripslashes($newrecord['feedback']).")</div>";
    } else {
      $result = "<div class=\"logitem\">Listening item ".stripslashes($newrecord['feedback'])." - Already exist</div>";
    }
}

if ($app == "apps_quizzes") {
    $newrecord = array();
    while(list($key,$value)=each($data->sql)) {
      if ($key != "id")
        $newrecord[$key] = $value;
    }
    if (!get_record("apps_quizzes", array("word" => $newrecord["word"], "category" => $newrecord["category"]))) {
      $idnew = insert_record("apps_quizzes", $newrecord);
    
      for ($j=1; $j <= 8; $j++) {
        $var = "var".$j;
        if (!empty($newrecord[$var])) {
          $imagename = "image".$j;
          if (strstr($data->$imagename, ".jpg"))
            file_put_contents($CFG['dirroot']."/datas/quiz/image/{$idnew}_".$j.".jpg", file_get_contents($data->$imagename));
          else
            file_put_contents($CFG['dirroot']."/datas/quiz/image/{$idnew}_".$j.".png", file_get_contents($data->$imagename));
        }
      }
    
      if (!empty($newrecord['soundword'])) 
        file_put_contents($CFG['dirroot']."/datas/quiz/soundword/{$idnew}.mp3", file_get_contents($data->soundword));
    
      $result = "<div class=\"logitem\">Quiz item added (".stripslashes($newrecord['word']).")</div>";
    } else {
      $result = "<div class=\"logitem\">Quiz item ".stripslashes($newrecord['word'])." - Already exist</div>";
    }
}

if ($app == "apps_reading") {
    $newrecord = array();
    while(list($key,$value)=each($data->sql)) {
      if ($key != "id")
        $newrecord[$key] = $value;
    }
    $rtitle = $newrecord["title"];
    if (!get_record("apps_reading", array("title" => $newrecord["title"], "category" => $newrecord["category"]))) {
      $idnew = insert_record("apps_reading", $newrecord);
    
      if (strstr($data->imagemain, ".jpg"))
        file_put_contents($CFG['dirroot']."/datas/reading/image/{$idnew}.jpg", file_get_contents($data->imagemain));
      else
        file_put_contents($CFG['dirroot']."/datas/reading/image/{$idnew}.png", file_get_contents($data->imagemain));
    
      if (!empty($newrecord['soundtext'])) 
        file_put_contents($CFG['dirroot']."/datas/reading/soundtext/{$idnew}.mp3", file_get_contents($data->soundtext));
    
    
      //---SQL2---//
      while(list($key,$value)=each($data->sql2)) {
        $newrecord = array();
        while(list($key2,$value2)=each($value->sql)) {
          if ($key2 != "id")
            $newrecord[$key2] = $value2;
        }
        $newrecord['readerid'] = $idnew;
        $idnewt = insert_record("apps_reading_texts", $newrecord);
        if ($value->image) {
		  if (strstr($value->image, ".jpg"))
			file_put_contents($CFG['dirroot']."/datas/reading/image/{$idnew}_{$idnewt}.jpg", file_get_contents($value->image));
		  else
			file_put_contents($CFG['dirroot']."/datas/reading/image/{$idnew}_{$idnewt}.png", file_get_contents($value->image));
        }
      }
      
      $result = "<div class=\"logitem\">Reading item added (".stripslashes($rtitle).")</div>";
    } else {
      $result = "<div class=\"logitem\">Reading item ".stripslashes($rtitle)." - Already exist</div>";
    }
}

if ($app == "apps_vocabulary") {
    $newrecord = array();
    while(list($key,$value)=each($data->sql)) {
      if ($key != "id")
        $newrecord[$key] = $value;
    }
    if (!get_record("apps_vocabulary", array("word" => $newrecord["word"], "category" => $newrecord["category"]))) {
      $idnew = insert_record("apps_vocabulary", $newrecord);
    
      if (!empty($newrecord['image'])) {
        if (strstr($data->image, ".jpg"))
          file_put_contents($CFG['dirroot']."/datas/vocab/image/{$idnew}.jpg", file_get_contents($data->image));
        else
          file_put_contents($CFG['dirroot']."/datas/vocab/image/{$idnew}.png", file_get_contents($data->image));
      }
    
      if (!empty($newrecord['soundtext'])) 
        file_put_contents($CFG['dirroot']."/datas/vocab/soundtext/{$idnew}.mp3", file_get_contents($data->soundtext));

      if (!empty($newrecord['soundword'])) 
        file_put_contents($CFG['dirroot']."/datas/vocab/soundword/{$idnew}.mp3", file_get_contents($data->soundword));

      if (!empty($newrecord['video'])) 
        file_put_contents($CFG['dirroot']."/datas/vocab/video/{$idnew}.m4v", file_get_contents($data->video));
    
      $result = "<div class=\"logitem\">Vocabulary item added (".stripslashes($newrecord['word']).")</div>";
    } else {
      $result = "<div class=\"logitem\">Vocabulary item ".stripslashes($newrecord['word'])." - Already exist</div>";
    }
}


if ($app == "apps_resource") {
/*
    $newrecord = array();
    while(list($key,$value)=each($data->sql)) {
      if ($key != "id")
        $newrecord[$key] = $value;
    }
    if (!get_record("apps_resource", array("word" => $newrecord["word"], "category" => $newrecord["category"]))) {
      $idnew = insert_record("apps_vocabulary", $newrecord);
    
      if (!empty($newrecord['image'])) {
        if (strstr($data->image, ".jpg"))
          file_put_contents($CFG['dirroot']."/datas/vocab/image/{$idnew}.jpg", file_get_contents($data->image));
        else
          file_put_contents($CFG['dirroot']."/datas/vocab/image/{$idnew}.png", file_get_contents($data->image));
      }
    
      if (!empty($newrecord['soundtext'])) 
        file_put_contents($CFG['dirroot']."/datas/vocab/soundtext/{$idnew}.mp3", file_get_contents($data->soundtext));

      if (!empty($newrecord['soundword'])) 
        file_put_contents($CFG['dirroot']."/datas/vocab/soundword/{$idnew}.mp3", file_get_contents($data->soundword));

      if (!empty($newrecord['video'])) 
        file_put_contents($CFG['dirroot']."/datas/vocab/video/{$idnew}.m4v", file_get_contents($data->video));
    
      $result = "<div class=\"logitem\">Vocabulary item added (".stripslashes($newrecord['word']).")</div>";
    } else {
      $result = "<div class=\"logitem\">Vocabulary item ".stripslashes($newrecord['word'])." - Already exist</div>";
    }
    */
}

echo '<div class="logitem-conteiner">'.$result.'</div>';

if ($end == 1) {
  echo '<div style="padding:30px;"><h2>Installation completed!</h2> <a href="'.$CFG['wwwroot'].'/admin/index.php?type=importcontents">Import other content</a> or <a href="'.$CFG['wwwroot'].'/admin/index.php?type=contents">Check current content</a></div>';
}
