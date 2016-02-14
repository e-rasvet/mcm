<?php


if (!is_file("config.php")) 
  header("Location: install.php"); 

include_once "config.php";

if (isset($_SESSION['userid'])) 
  $USER = get_record("user", array("id" => $_SESSION['userid']));
else 
  if(isset($_COOKIE['mcm_username']) && isset($_COOKIE['mcm_password']))
    if ($USER = get_record("user", array("username" => $_COOKIE['mcm_username'], "password" => $_COOKIE['mcm_password']))) 
      $_SESSION['userid'] = $USER->id;


if (empty($_SESSION['userid'])) {
  header("Location: options.php");
  die();
} else {
  $link        = optional_param('link', NULL, PARAM_PATH);

  if (isset($link)) $_SERVER['PATH_INFO'] = $link;
  $la = explode("/",$_SERVER['PATH_INFO']);
  
  if ($la[1] == "a") {
    //Activitys
    $appsfolder = browser_detect();
    
    if (isset($la[3]))
      $la[3] = urldecode($la[3]);
    
    if ($la[2] == 1) {
      //Choice
      $data = get_records("apps_choice", array("category" => $la[3]));
      while(list($key,$value)=each($data)) {
        $listofquizzes[] = $value->id;
      }
      shuffle($listofquizzes); 
      $_SESSION['apps']['choice'] = json_encode($listofquizzes);
      header("Location: ".$CFG['wwwroot']."/apps/choice/index.php?id=0");
    } else if ($la[2] == 2) {
      //Vocabulary
      $data = get_records("apps_vocabulary", array("category" => $la[3]));
      while(list($key,$value)=each($data)) {
        $listofvocab[] = $value->id;
      }
      shuffle($listofvocab); 
      $_SESSION['apps']['vocabulary'] = json_encode($listofvocab);
      header("Location: ".$CFG['wwwroot']."/apps/vocabulary/index.php?id=0");
    } else if ($la[2] == 3) {
      //Reading
      $listofreading = array();
      $listofreading[] = $la[3];
      $_SESSION['apps']['reading'] = json_encode($listofreading);
      header("Location: ".$CFG['wwwroot']."/apps/reading/index.php?id=0");
      /*
      $data = get_records("apps_reading", array("category" => $la[3]));
      while(list($key,$value)=each($data)) {
        $listofreading[] = $value->id;
      }
      shuffle($listofreading); 
      $_SESSION['apps']['reading'] = json_encode($listofreading);
      header("Location: ".$CFG['wwwroot']."/apps/reading/index.php?id=0");
      */
    } else if ($la[2] == 4) {
      //Listening
      $data = get_records("apps_listening", array("category" => $la[3]));
      while(list($key,$value)=each($data)) {
        $listoflistening[] = $value->id;
      }
      shuffle($listoflistening); 
      $_SESSION['apps']['listening'] = json_encode($listoflistening);
      header("Location: ".$CFG['wwwroot']."/apps/listening/index.php?id=0");
    } else if ($la[2] == 5) {
      //Quiz
      $data = get_records("apps_quizzes", array("category" => $la[3]));
      while(list($key,$value)=each($data)) {
        $listofquizzes[] = $value->id;
      }
      shuffle($listofquizzes); 
      $_SESSION['apps']['quiz'] = json_encode($listofquizzes);
      header("Location: ".$CFG['wwwroot']."/apps/quiz/index.php?id=0");
    } else if ($la[2] == 6) {
      //resource
      $data = get_records("apps_resource", array("category" => $la[3]));
      while(list($key,$value)=each($data)) {
        $listofresource[] = $value->id;
      }
      shuffle($listofresource); 
      $_SESSION['apps']['resource'] = json_encode($listofresource);
      header("Location: ".$CFG['wwwroot']."/apps/resource/index.php?id=0");
    } else if ($la[2] == 7) {
      //resource
      $data = get_records("apps_clozeactivity", array("category" => $la[3]));
      while(list($key,$value)=each($data)) {
        $listofresource[] = $value->id;
      }
      shuffle($listofresource); 
      $_SESSION['apps']['clozeactivity'] = json_encode($listofresource);
      header("Location: ".$CFG['wwwroot']."/apps/clozeactivity/index.php?id=0");
    } else if ($la[2] == 8) {
      //resource
      $data = get_records("apps_dragdrope", array("category" => $la[3]));
      while(list($key,$value)=each($data)) {
        $listofresource[] = $value->id;
      }
      shuffle($listofresource); 
      $_SESSION['apps']['dragdrope'] = json_encode($listofresource);
      header("Location: ".$CFG['wwwroot']."/apps/dragdrope/index.php?id=0");
    } else if ($la[2] == 9) {
      //resource
      $data = get_records("apps_ordering", array("category" => $la[3]));
      while(list($key,$value)=each($data)) {
        $listofresource[] = $value->id;
      }
      shuffle($listofresource); 
      $_SESSION['apps']['ordering'] = json_encode($listofresource);
      header("Location: ".$CFG['wwwroot']."/apps/ordering/index.php?id=0");
    } else if ($la[2] == 10) {
      //resource
      $data = get_records("apps_listenspeak", array("category" => $la[3]));
      while(list($key,$value)=each($data)) {
        $listofresource[] = $value->id;
      }
      shuffle($listofresource); 
      $_SESSION['apps']['listenspeak'] = json_encode($listofresource);
      header("Location: ".$CFG['wwwroot']."/apps/listenspeak/index.php?id=0");
    } else if ($la[2] == 11) {
      //resource
      $data = get_records("apps_groupquiz", array("category" => $la[3]));
      while(list($key,$value)=each($data)) {
        $listofresource[] = $value->id;
      }
      shuffle($listofresource); 
      $_SESSION['apps']['groupquiz'] = json_encode($listofresource);
      header("Location: ".$CFG['wwwroot']."/apps/groupquiz/index.php?id=0");
    }
    
  } else {
    mobilehtmlheader($pagetitle);
    
    if ($la[1] == "c") {
      $selectors['courselist'] = "";
      $selectors['course-'.$la[2]] = 'selected="true"';
    } else {
      $selectors['courselist'] = 'selected="true"';
    }
?>
<script>
$(document).ready(function() {
  $('.coursewasselect').click(function() {
    var currentId = $(this).attr('href');
    currentId = currentId.replace("#courses-", "");
    $.post('<?php echo $CFG['wwwroot']."/save.php"; ?>', { act: "course", id: currentId});
  });
  
<?php 
  if(!empty($la[2]))
    echo "window.lastpage = 'courses-{$la[2]}';";
  
?>
});
</script>

<h1 id="pageTitle">Courses</h1>
<a id="homeButton" class="button" href="#home">Courses</a>

<?php
if(!empty($USER->id)) {
?>
<a href="<?php echo $CFG['wwwroot']; ?>/logout.php" class="button" id="options" target="_webapp">Logout (<?php echo $USER->username; ?>)</a>
<?php
} else {
?>
<a href="<?php echo $CFG['wwwroot']; ?>/options.php" class="button" id="options" target="_webapp">Options</a>
<?php
}
?>

<ul id="home" title="Courses" <?php echo $selectors['courselist']; ?>>
<?php
    //----------------COURSES LIST---------------//

    $courses = get_records("course");

    $C = 0;

    while(list($key,$value)=each($courses)) {
      if (!empty($value->guest) || get_record("course_student", array("userid" => $USER->id, "courseid" => $value->id))) {
        echo '<li><a href="#courses-'.$value->id.'" class="coursewasselect">'.$value->fullname.'</a></li>';
        $c++;
      }
    }
    reset($courses);
    
    if ($c == 0)
      echo '<li>No courses avaiable for you.</li>';

    echo '</ul>';
    
    ?>

<?php
    //----------------COURSES LIST---------------//
  while(list($coursekey,$course)=each($courses)) {
    $topics = get_records("course_topic", array("courseid" => $course->id), '`order`');

    echo '<div id="courses-'.$course->id.'" class="panel" title="'.$course->fullname.'" ';
    if (isset($selectors['course-'.$course->id])) echo $selectors['course-'.$course->id];
    echo '>';
    
    /****** ADD PLAY ALL LATER ******/
    
    //echo '<ul class="list"><li><a href="'.$CFG['wwwroot'].'/index.php/playall">Play all activity</a></li></ul>';

    $readingdivs = array();
    $c = 0;

    while(list($key,$value)=each($topics)) {
      $topiccontent = json_decode($value->data);
      echo '<h2>'.$value->title.'</h2>';
      echo '<ul class="list">';
      while(list($key2,$value2)=each($topiccontent)) {
        $actid = key($value2);
        $actcategory = $value2->$actid;
        
        if ($actid == 3)
          echo '<li><a href="#reading-'.$course->id.'-'.$c.'" target="_webapp">'.$actcategory.'</a> <div class="list-description">'.$appstables2[$actid].'</div></li>';
        else
          echo '<li><a href="'.$CFG['wwwroot'].'/index.php/a/'.$actid.'/'.urlencode($actcategory).'" target="_webapp">'.$actcategory.'</a> <div class="list-description">'.$appstables2[$actid].'</div></li>';
        
        if ($actid == 3) 
          $readingdivs[$c] = $actcategory;
        $c++;
      }
      echo '</ul>';
    }
    
    echo '</div>';
    
    if (count($readingdivs) > 0) {
      while(list($key,$value)=each($readingdivs)) {
       echo '<div id="reading-'.$course->id.'-'.$key.'" class="panel" title="'.$value.'">';
        echo '<h2>'.$value.'</h2>';
        echo '<ul class="list">';
        $reading = get_records("apps_reading", array("category" => $value), '`title`');
        while(list($key2,$value2)=each($reading)) {
          echo '<li><a href="'.$CFG['wwwroot'].'/index.php/a/3/'.$value2->id.'" target="_webapp">'.$value2->title.'</a></li>';
        }
        echo '</ul>';
        echo '</div>';
      }
    }
    
    
  }
  
    mobilehtmlfooter();
  }
}