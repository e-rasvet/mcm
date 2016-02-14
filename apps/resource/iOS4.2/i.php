<script type="application/x-javascript">

$(document).ready(function(){
  var v = document.getElementById("audiotext");
  var playmark = 0;
  var hidemark = 0;
  var page = 1;
  
  v.load();
  v.play(); $('#playbutton').text("Playing"); playmark = 1;
  
  v.addEventListener("loadedmetadata", function() { audioprocess(); }, true);
  
  v.addEventListener("ended", function() { $('#playbutton').text("Play"); playmark = 0; $("#faceimg").attr("src","<?php echo printimage('/datas/resource/image/'.$data->id); ?>"); $('#shownextbutton').toggle(); }, true);
  
  $('#playbutton').click(function() {
    if (playmark == 0) {
      v.play();
      playmark = 1;
      $('#playbutton').text("Playing");
      audioprocess();
    }
    return false;
  });
  
});

</script>
</head>
<body>

<h1 id="pageTitle">resource</h1>

<a href="<?php echo $CFG['wwwroot']; ?>/index.php/c/<?php echo $course->id; ?>" class="button" id="homeButton" target="_webapp">Back</a>


<?php if(!empty($data->soundtext)) { ?>
<a href="#" class="button" id="playbutton" target="_webapp"><img width="16" height="11" src="<?php echo $CFG['wwwroot'].'/images/loader-small.gif'; ?>" alt="" /></a>
<?php } ?>

<div id="home" title="resource" selected="true">
<?php
if($data->image == 1) {
?>
<div id="hideclick" style="text-align:center;padding-top:5px;padding-bottom:10px;"><img src="<?php echo printimage('/datas/resource/image/'.$data->id); ?>" alt="" id="faceimg" /></div>
<?php
}
?>

<div style="text-align:center;padding-bottom:10px;" id="text-of-reader"><?php echo $data->text ?>
<?php

if(!empty($data->url)) {
  echo '<br /><br /><a href="'.$data->url.'" target="_webapp">'.$data->urlname.'</a><br /><br />';
}

?>
</div>
<div style="text-align:center;" id="text-hard-word"></div>

<div style="text-align:center;" id="shownextbutton"><a class="whiteButton" type="button" href="<?php
if (!empty($idnext)) {
  echo $CFG['wwwroot'].'/apps/resource/index.php?id='.$idnext;
} else {
  echo $CFG['wwwroot'] .'/index.php/c/'.$course->id;
}
?>" target="_webapp"><?php if (!empty($idnext)) echo 'Next ->'; else echo 'Back'; ?></a></div>

</div>

<div id="nextpage" title="Vocabulary">
<?php if(!empty($data->soundtext)) { ?>
<audio src="<?php echo $CFG['wwwroot']."/datas/resource/soundtext/".$data->id.".mp3" ?>" id="audiotext" autobuffer="autobuffer"></audio>
<?php } ?>
</div>


</body>
</html>