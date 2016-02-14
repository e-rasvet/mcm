<script type="application/x-javascript">
$(document).ready(function(){
  var word           = document.getElementById("audioword");
  word.load();
  word.play(); $('#playbutton').text("Pause"); playmark = 1;
  word.addEventListener("ended", function() { $('#playbutton').text("Play"); playmark = 0; }, true);
  
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
$a = array(1,2,3,4,5,6,7,8);
shuffle($a); 
while(list($key,$i)=each($a)) {
  $var = "var".$i;
  $cor = "cor".$i;
  if (!empty($data->$var)) {
?>
  $('#item_<?php echo $i; ?>').click(function() {
    $.post('<?php echo $CFG['wwwroot']; ?>/stat.php', { apps: "quiz", id: <?php echo $data->id; ?>, choice: <?php echo $i; ?> });
    $('#item_ansfer_<?php echo $i; ?>').toggle();
    <?php if ($i == 1) { ?>
    window.setTimeout("window.location.href='<?php
if (!empty($idnext)) {
  echo $CFG['wwwroot'].'/apps/quiz/index.php?id='.$idnext;
} else {
  echo $CFG['wwwroot'] .'/index.php/c/'.$course->id;
}
?>';", 1000);
    <?php } ?>
  });
<?php
  }
} 
reset($a);
?>
});
</script>
</head>
<body>

<h1 id="pageTitle">Quiz</h1>
<a href="<?php echo $CFG['wwwroot']; ?>/index.php/c/<?php echo $course->id; ?>" class="button" id="homeButton" target="_webapp">Back</a>

<?php if(!empty($data->soundword)) { ?>
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
    echo '" id="item_'.$i.'"><div style="float:left;padding-right:20px;"><img src="'.printimage('/datas/quiz/image/'.$data->id.'_'.$i).'" alt="" width="100px" height="100px" /></div><div style="float:left;display:none;position: absolute;" id="item_ansfer_'.$i.'"><img src="'.$CFG['wwwroot'].'/images/';
    if ($i == 1) echo 'correct'; else echo 'incorrect';
    echo '.png" alt="" width="100px" height="100px"/></div><div style="padding-top: 40px;">'.$data->$var.'</div></div>';
    echo '<div style="clear:both;"></div>';
    
    if ($c == 0)
      $c = 1;
    else
      $c = 0;
  }
}
?>
</div>
<div style="text-align:center;">Current score: <?php echo $score; ?></div>
</div>
<div id="nextword"></div>
<div>
<audio src="<?php echo $CFG['wwwroot'].'/datas/quiz/soundword/'.$data->id.".mp3"; ?>" id="audioword" autobuffer="autobuffer"></audio>

</div>
</body>
</html>