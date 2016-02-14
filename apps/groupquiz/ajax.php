<?php

include_once "../../config.php";

$a         = optional_param('a', NULL, PARAM_TEXT);
$qid       = optional_param('qid', NULL, PARAM_INT);
$cid       = optional_param('cidm', NULL, PARAM_INT);
$aid       = optional_param('aid', NULL, PARAM_INT);

$ingroup   = $options['studentsinquizgame'];


if (isset($_SESSION['currentcourse'])) 
    $course = get_record("course", array("id" => $_SESSION['currentcourse']));
else 
    $course = get_record("course", array("id" => $_COOKIE['mcm_course']));
    

if($a == 'sign-up'){
  //Удалим ждущих более 20 мин
  $db->query("DELETE FROM {$CFG['prefix']}apps_groupquiz_online WHERE time < ".(time() - 20 * 60));
  $db->query("DELETE FROM {$CFG['prefix']}apps_groupquiz_list WHERE time < ".(time() - 20 * 60));
  
  //Формируем группу
  $usersinlist = get_records('apps_groupquiz_online', array());
  
  //Добавляем в лист ожидания
  if (!get_record('apps_groupquiz_online', array("uid"=>$_SESSION['userid']))){
    $add = array();
    $add['uid'] = $_SESSION['userid'];
    $add['time'] = time();
    
    if ($data = $db->get_row("SELECT * FROM {$CFG['prefix']}apps_groupquiz_list WHERE `uid1`={$add['uid']} 
                              OR `uid2`={$add['uid']} OR `uid3`={$add['uid']} 
                              OR `uid4`={$add['uid']} OR `uid5`={$add['uid']}",OBJECT)) {
      echo json_encode(array("resp"=>"success"));
      die();
    } else
      insert_record('apps_groupquiz_online', $add);
  }
  
  //Активируем группу
  if(count($usersinlist) >= $ingroup) {
    $list = json_decode($_SESSION['apps']['groupquiz']);
    $data = get_record("apps_groupquiz", array("id" => $list[0]));
    $quizs = get_records("apps_quizzes", array("category"=>$data->category));
    $quizzeslist = array();
    $resp = "noyet";
    
    foreach($quizs as $k => $v) {
      $quizzeslist[] = $v->id;
    }
    
    if ($data = get_records("apps_groupquiz_online", array(), 'time ASC')){
      $add = array();
      $c = 1;
      
      foreach($data as $k => $v){
        if ($c <= $ingroup) {
          $add['uid'.$c] = $v->uid;
          //$db->query("DELETE FROM {$CFG['prefix']}apps_groupquiz_online WHERE uid = ".($v->id));
          
          //if ((int)$v->uid == (int)$_SESSION['userid'])
          //  $resp = "success";
        }
        $c++;
      }
      
      
      $add['questions'] = json_encode($quizzeslist);
      $add['activequestion'] = 0;
      $add['compleate'] = 0;
      $add['time'] = time();

      if (!get_record("apps_groupquiz_list", array("questions"=>$add['questions'], "uid1"=>$add['uid1'], "uid2"=>$add['uid2']))) {
        insert_record("apps_groupquiz_list", $add);
        
        for($i=1;$i<=5;$i++) {
          if (!empty($add['uid'.$i]))
            $db->query("DELETE FROM {$CFG['prefix']}apps_groupquiz_online WHERE uid = ".($add['uid'.$i]));
        }
      }
      //echo json_encode(array("resp"=>$resp));
    }
    
  } 
  
  //else {
    //$users = array();
    $users = "";
    foreach ($usersinlist as $k => $v) {
      $d = get_record("user", array("id"=>$v->uid));
      //$users[$d->id] = $d->username;
      $users .= "<li>-- {$d->username}</li>";
    }
  
    echo json_encode(array("resp"=>"noyet", "users"=>$users));
 // }

}


if($a == "sign-up-close") {
  $db->query("DELETE FROM {$CFG['prefix']}apps_groupquiz_online WHERE uid = ".$_SESSION['userid']);
}


if($a == 'check-progress'){

    if (is_file($CFG['dirroot']."/datas/groupquiz/totalscore/".$qid."_".$cid.".txt")) {
      $users = file_get_contents($CFG['dirroot']."/datas/groupquiz/totalscore/".$qid."_".$cid.".txt");
      echo json_encode(array("resp"=>"end", "users"=>$users));
      die();
    }

    $data = get_record("apps_groupquiz_list", array("id"=>$qid));//apps_groupquiz_answers
    
    if (!$data) {
      echo json_encode(array("resp"=>"end", "users"=>""));
      die();
    }
    
    $questions = json_decode($data->questions);
    
    if (empty($questions[$cid])) {
      echo json_encode(array("resp"=>"end", "users"=>""));
      die();
    }
    
    //Goto next quiz
    $tuser = 0;
    for($i=1;$i<=5;$i++) {
      $name = 'uid'.$i;
      if (!empty($data->{$name}))
        $tuser++;
    }
    
    if ($firstuseransver = get_record("apps_groupquiz_answers", array("qid"=>$qid, "cid"=>$cid, "aid"=>1), "uid") || count(get_records("apps_groupquiz_answers", array("qid"=>$qid, "cid"=>$cid), "uid")) >= $tuser) {
      $users = "";

      $data->activequestion = $cid + 1;
      if(empty($questions[$data->activequestion])) {
        //close quiz
        $users = "";
        
        if (is_file($CFG['dirroot']."/datas/groupquiz/totalscore/".$qid."_".$cid.".txt")) {
          $users = file_get_contents($CFG['dirroot']."/datas/groupquiz/totalscore/".$qid."_".$cid.".txt");
        } else {
          for ($i=1;$i<=5;$i++) {
            $name = 'uid'.$i;
            $d = get_record("user", array("id"=>$data->{$name}), "id,username");
            
            if (!empty($data->{$name})) {
              if ($scores = get_records("score", array("userid"=>$d->id, "apps"=>11, "appsid"=>$qid))){
                $score = 0;
                while(list($k, $v)=each($scores)){
                  $score += $v->score;
                }
              } else 
                $score = 0;
                
              $users .= "<li>{$d->username} : {$score}</li>";
            }
          }
          
          //Close list
          $add = array();
          $add['id'] = $data->id;
          $add['compleate'] = 1;
          $add['activequestion'] = $data->activequestion;
          
          if(!$db->query("SELECT * FROM `apps_groupquiz_list` WHERE `activequestion` <= {$add['activequestion']} AND `id`={$add['id']}")) {
            insert_record("apps_groupquiz_list", $add);
            $db->query("DELETE FROM {$CFG['prefix']}apps_groupquiz_list WHERE id = ".($add['id']));
          }
          
          file_put_contents($CFG['dirroot']."/datas/groupquiz/totalscore/".$qid."_".$cid.".txt", $users);
        }
        
        echo json_encode(array("resp"=>"end", "users"=>$users));
        //die();
      } else {
        //Next quiz
        $add = array();
        $add['id'] = $data->id;
        $add['activequestion'] = $data->activequestion;
        
        if(!get_record("apps_groupquiz_list", array("activequestion"=>$add['activequestion'], "id"=>$add['id'])))
          insert_record("apps_groupquiz_list", $add);
        
        if ($firstuseransver = get_record("apps_groupquiz_answers", array("qid"=>$qid, "cid"=>$cid, "aid"=>1), "uid")) {
          $d = get_record("user", array("id"=>$firstuseransver->uid), "username");
          echo json_encode(array("resp"=>"answered", "users"=>"{$d->username}"));
        } else
          echo json_encode(array("resp"=>"answered", "users"=>"No winner"));
      }
    } else 
      echo json_encode(array("resp"=>"noyet", "users"=>""));
}


/*
if($a == 'check-first-answer'){
    //echo "$a, $qid, $cid, $aid";
    $data = get_record("apps_groupquiz_list", array("id"=>$qid));
    $questions = json_decode($data->questions);
    
    if ($data = get_record("apps_groupquiz_answers", array("qid"=>$qid, "cid"=>$cid, "aid"=>1), "uid")) {
      $d = get_record("user", array("id"=>$data->uid), "username");

      $data->activequestion = $cid + 1;
      if(empty($questions[$data->activequestion])) {
        $users = "";
        
        if (is_file($CFG['dirroot']."/datas/groupquiz/totalscore/".$qid."_".$cid.".txt")) 
          $users = file_get_contents($CFG['dirroot']."/datas/groupquiz/totalscore/".$qid."_".$cid.".txt");
          
        echo json_encode(array("resp"=>"success", "users"=>$users, "end"=>"true"));
      } else 
        echo json_encode(array("resp"=>"success", "users"=>"<li style=\"color:green\">{$d->username}</li>", "end"=>"false"));
      
      die();
    }
}
*/


if($a == 'item-answer'){
    $add              = array();
    $add['qid']       = $qid;
    $add['uid']       = $_SESSION['userid'];
    $add['aid']       = $aid;
    $add['cid']       = $cid;
    //$add['answer']    = 
    
    if ($aid == 1)
      $add['correct']   = 1;
    else
      $add['correct']   = 0;
      
    $add['time']      = time();
    
    
    if (!get_record("apps_groupquiz_answers", array("qid"=>$qid, "cid"=>$cid, "aid"=>1))) {
      $datam              = array();
      $datam['userid']    = $_SESSION['userid'];
      $datam['apps']      = 11;
      $datam['appsid']    = $qid;
      $datam['answer']    = $aid;
      $datam['score']     = $add['correct'];
      $datam['courseid']  = $course->id;
      $datam['time']      = time();
      
      insert_record("score", $datam);
      
      insert_record("apps_groupquiz_answers", $add);
      
      if ($add['correct'] == 1) 
        echo json_encode(array("resp"=>"success"));
      else
        echo json_encode(array("resp"=>"incorrect"));
    } else {
      echo json_encode(array("resp"=>"alreadyanswered"));
    }
}


if($a == 'logout-from-game') {
    $data = get_record("apps_groupquiz_list", array("id"=>$qid));//apps_groupquiz_answers
    
    $tuser = 0;
    $uname = 0;
    for($i=1;$i<=5;$i++) {
      $name = 'uid'.$i;
      if (!empty($data->{$name}))
        $tuser++;
        
      if ($data->{$name} == $_SESSION['userid'])
        $uname = $name;
    }
    
    if ($tuser <= 2) 
      $db->query("DELETE FROM {$CFG['prefix']}apps_groupquiz_list WHERE id = ".$qid);
    else 
      $db->query("UPDATE {$CFG['prefix']}apps_groupquiz_list SET {$uname}=0 WHERE id = ".$qid);
    
}
