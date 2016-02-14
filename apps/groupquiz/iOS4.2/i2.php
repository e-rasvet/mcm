<?php

if ($qg = $db->get_row("SELECT * FROM {$CFG['prefix']}apps_groupquiz_list WHERE `uid1`={$_SESSION['userid']} 
                              OR `uid2`={$_SESSION['userid']} OR `uid3`={$_SESSION['userid']} 
                              OR `uid4`={$_SESSION['userid']} OR `uid5`={$_SESSION['userid']} LIMIT 1",OBJECT)) {
  $questions = json_decode($qg->questions);
  $activeq = $questions[$qg->activequestion];
  
  $data = get_record("apps_quizzes", array("id"=>$activeq));
  
  //Loading scores list
  if ($scores = get_records("score", array("userid"=>$_SESSION['userid'], "apps"=>11, "appsid"=>$qg->id))){
    $score = 0;
    while(list($k, $v)=each($scores)){
      $score += $v->score;
    }
  } else 
    $score = 0;
}
?>
<script type="application/x-javascript">
var playmark = 0;
var answered = 0;
var nocheck  = 1;
$(document).ready(function(){

<?php
if(is_file($CFG['dirroot']."/datas/quiz/soundword/".$data->id.".mp3")) {
?>
  $('#playbutton').text("Play");
  
  var word = document.getElementById("audiotext");
  word.load();
  word.addEventListener("ended", function() { $('#playbutton').text("Play"); playmark = 0; }, true);
  //word.play(); 
  //$('#playbutton').text("Pause"); 
  //playmark = 1;
  
  $('#playbutton').click(function() {
    if (playmark == 1) {
      word.pause();
      playmark = 0;
      $('#playbutton').text("Play");
    }
    else
    {
      word.play();
      playmark = 1;
      $('#playbutton').text("Pause");
    }
    return false;
  });
  
<?php
}
?>
  
<?php
$a = array(1,2,3,4,5,6,7,8);
shuffle($a); 
?>
$('.item-box').click(function() {
  if (window.answered == 0) {
    window.answered = 1;
    var i = $(this).attr("data-id");
    //console.log('item '+i+' selected');
    $('#item_ansfer_'+i).show();
    $('.item-box').not(this).hide();
    $('#box-data').show();
    
    $.post("ajax.php", { 'a': 'item-answer', 'qid': <?php echo $qg->id ;?>, 'cidm': <?php echo $qg->activequestion ;?>, 'aid': i}, function (resp){
      var obj = jQuery.parseJSON( resp );
      if(obj.resp == 'success') {
        var newscore = parseInt($('#yourscore').text()) + 1;
        $('#yourscore').html(newscore);
      } else if(obj.resp == 'alreadyanswered') {
        
      }
    });
  }
});


function checkgroupprogress(){
  $.post("ajax.php", { 'a': 'check-progress', 'qid': <?php echo $qg->id ;?>, 'cidm': <?php echo $qg->activequestion ;?>}, function (resp){
    var obj = jQuery.parseJSON( resp );
    
    if(obj.resp == 'noyet') {
      
    } else if(obj.resp == 'answered'){
      console.log('ready to start');
      clearTimeout(myTimer);
      window.answered = 1;
      var i = 1;
      $('#item_ansfer_'+i).show();
      $('.item-box').not("#item_"+i).hide();
      $('#box-data').show();
      $('#activeusers').html(obj.users);
      //$('#nexttimer-box').show();
      setTimeout(function(){nextquestion(2)},1000);
    } else if(obj.resp == 'end') {
      console.log('end');
      clearTimeout(myTimer);
      $('.item-box').hide();
      //$('#nexttimer-box').hide();
      $('.list-header').text("Total score");
      $('#list1').show();
      $('#box-data').show();
      $('#return-box').show();
      
      if (obj.users == '' || obj.users == null) {
        $.post("ajax.php", { 'a': 'check-progress', 'qid': <?php echo $qg->id ;?>, 'cidm': <?php echo $qg->activequestion ;?>}, function (resp){
          var obj = jQuery.parseJSON( resp );
          $('#activeusers').html(obj.users);
        });
      } else 
        $('#activeusers').html(obj.users);
    }
  });
}


function nextquestion(i){
  i = i -1;
  if (i > 0) {
    $('#nexttimer').text(i);
    setTimeout(function(){nextquestion(i)},1000);
  } else {
    console.log('reload');
    window.location.replace("index.php?id=0");
  }
}


var myTimer = setInterval(function(){checkgroupprogress()},1000);
checkgroupprogress();

});


window.onbeforeunload = function (e) {
  if(window.nocheck == 0) {
    $.post("ajax.php", { 'a': 'logout-from-game', 'qid': <?php echo $qg->id ;?>, 'cidm': <?php echo $qg->activequestion ;?>});
    var message = "you was deleted from this game";
    if (typeof evt == "undefined") {
        evt = window.event;
    }
    if (evt) {
        evt.returnValue = message;
    }
    return message;
  }
};


</script>
<style>
#list1 { }
#list1 ul { list-style:none; text-align:center; border-top:1px solid #eee; border-bottom:1px solid #eee; padding:10px 0; }
#list1 ul li { margin: 10px 0; padding:0 10px; }
#list1 ul li a { text-decoration:none; color:#eee; }
#list1 ul li a:hover { text-decoration:underline; }
.list-header{text-align: center;font-size: 20px;}
</style>
</head>
<body>

<h1 id="pageTitle">Quiz</h1>
<a href="<?php echo $CFG['wwwroot']; ?>/index.php/c/<?php echo $course->id; ?>" class="button" id="homeButton" target="_webapp" onclick="window.nocheck=0;">Back</a>

<?php
if(is_file($CFG['dirroot']."/datas/quiz/soundword/".$data->id.".mp3")) {
?>
<a href="#" class="button" id="playbutton" target="_webapp"><img width="16" height="11" src="<?php echo $CFG['wwwroot'].'/images/loader-small.gif'; ?>" alt="" /></a>
<?php } ?>

<div id="home" title="Vocabulary" selected="true">
<div style="text-align:center;padding-top:10px;"><strong><?php echo $data->word; ?></strong></div>
<div style="padding-top:10px;padding-bottom:10px;">
<?php
$c = 0;
while(list($key,$i)=each($a)) {
  $var = "var".$i;
  $cor = "cor".$i;
  
  if (!empty($data->$var)) {
  
    echo '<div style="';
    echo 'padding-left:30px;border: solid 1px #666;height:100px;background-color:#f7f7f7';
    echo '" id="item_'.$i.'" class="item-box" data-id="'.$i.'"><div style="float:left;padding-right:20px;"><img src="'.printimage('/datas/quiz/image/'.$data->id.'_'.$i).'" alt="" width="100px" height="100px" /></div>';
    echo '<div style="float:left;display:none;position: absolute;" id="item_ansfer_'.$i.'"><img src="'.$CFG['wwwroot'].'/images/';
    if ($i == 1) echo 'correct'; else echo 'incorrect';
    echo '.png" alt="" width="100px" height="100px"/></div>';
    echo '<div style="padding-top: 40px;">'.$data->$var.'</div></div>';
    echo '<div style="clear:both;"></div>';
    
    if ($c == 0)
      $c = 1;
    else
      $c = 0;
  }
}
?>
</div>

<div id="box-data" style="display:none">
  <div class="list-header">This question winner: <span id="activeusers"></span></div>
  <div id="list1" style="display:none;"><ul id="activeusers"></ul></div>
  
  <div style="text-align:center;">Your score: <span id="yourscore"><?php echo $score; ?></span></div>
  
  <div style="text-align:center;display:none;" id="nexttimer-box"><span id="nexttimer">2</span></div>
  <div style="text-align:center;display:none;" id="return-box"><a href="<?php echo $CFG['wwwroot']; ?>/index.php/c/<?php echo $course->id; ?>" target="_webapp">Return to Course</a></span></div>
</div>


<div style="width:1px;height:1px;text-indent: -9999px;">
<?php
if(is_file($CFG['dirroot']."/datas/quiz/soundword/".$data->id.".mp3")) {
?>
		<audio src="<?php echo $CFG['wwwroot']."/datas/quiz/soundword/".$data->id.".mp3" ?>" id="audiotext" autobuffer="autobuffer"></audio>
<?php
}
?>
</div>

</div>
<div id="nextword"></div>
<div>

</div>
</body>
</html>