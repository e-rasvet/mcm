<script type="text/javascript" language="javascript" src="<?php echo $CFG['wwwroot']; ?>/js/niftyplayer.js"></script>
<script type="application/x-javascript">
var playmark = 0;
$(document).ready(function(){
  $('#playbutton').text("Playing");
  
  $('#playbutton').click(function() {
    if (window.playmark == 0) {
      niftyplayer('audioword').play();
      window.playmark = 1;
      $('#playbutton').text("Playing");
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
setInterval("playerstatuscheck()",200);
  
  function playerstatuscheck() {
    if (niftyplayer('audioword').getState() == "empty") {
      niftyplayer('audioword').play();
    } else if (niftyplayer('audioword').getState() == "playing") {
      if (window.playmark == 0) {
        window.playmark = 1;
      }
    } else if (niftyplayer('audioword').getState() == "finished") {
      if (window.playmark == 1) {
        $('#playbutton').text('Play'); 
        window.playmark = 0; 
      }
    }
  }
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

		<div style="width:1px;height:1px;text-indent: -9999px;"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="1" height="1" id="audioword" align="">
		 <param name=movie value="<?php echo $CFG['wwwroot']; ?>/images/niftyplayer.swf?file=<?php echo $CFG['wwwroot']."/datas/quiz/soundword/".$data->id.".mp3" ?>&as=0">
		 <param name=quality value=high>
		 <param name=bgcolor value=#FFFFFF>
		 <embed src="<?php echo $CFG['wwwroot']; ?>/images/niftyplayer.swf?file=<?php echo $CFG['wwwroot']."/datas/quiz/soundword/".$data->id.".mp3" ?>&as=0" quality=high bgcolor=#FFFFFF width="1" height="1" name="audioword" align="" type="application/x-shockwave-flash" swLiveConnect="true" pluginspage="http://www.macromedia.com/go/getflashplayer">
		</embed>
		</object></div>
		
</div>
<div id="nextword"></div>
<div>

</div>
</body>
</html>