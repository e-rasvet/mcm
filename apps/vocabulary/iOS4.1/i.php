<script type="application/x-javascript">
$(document).ready(function(){
  var v  = document.getElementById("audioword");
  var v2 = document.getElementById("audiotext");
  var playmark = 0;
  var page = 1;
  
  v.load();
  v2.load();
  
  v.play(); $('#playbutton').text("Pause"); playmark = 1;
  
  v.addEventListener("ended", function() { $('#playbutton').text("Play"); playmark = 0;}, true);
  $('#playbutton').click(function() {
    if (playmark == 1) {
      if (page == 1) { v.pause(); } else { v2.pause(); }
      playmark = 0;
      $('#playbutton').text("Play");
    }
    else
    {
      if (page == 1) { v.play(); } else { v2.play(); }
      playmark = 1;
      $('#playbutton').text("Pause");
    }
    return false;
  });
  $('#definition').click(function() {
    v.pause();
    v.src = "";
    page = 2;
    v2.play();
    v2.addEventListener("ended", function() { $('#playbutton').text("Play"); playmark = 0; }, true);
  });
  
  $('#nextpage').click(function() {
<?php
if (!empty($idnext)) {
?>
window.location = "<?php echo $CFG['wwwroot']; ?>/apps/vocabulary/index.php?id=<?php echo $idnext; ?>";
<?php
} else {
?>
window.location = "<?php echo $CFG['wwwroot']; ?>/index.php/c/<?php echo $course->id; ?>";
<?php
}
?>
  });
  
});
</script>
<style>
a.bgnext
{
position: absolute;
top: 0;
left: 0;
width: 100%;
height:  100%;
}
</style>
</head>
<body>

<h1 id="pageTitle">Vocabulary</h1>
<a href="<?php echo $CFG['wwwroot']; ?>/index.php/c/<?php echo $course->id; ?>" class="button" id="homeButton" target="_webapp">Back</a>

<?php if(!empty($data->soundtext)) { ?>
<a href="#" class="button" id="playbutton" target="_webapp"><img width="16" height="11" src="<?php echo $CFG['wwwroot'].'/images/loader-small.gif'; ?>" alt="" /></a>
<?php } ?>

<div id="home" title="Vocabulary" selected="true">
<div style="text-align:center;padding-top:40px;padding-bottom:40px;"><h2><?php echo $data->word; ?></h2></div>
<div style="text-align:center;padding-top:40px;padding-bottom:40px;"><small>click on screen for next frame</small></div>
<div style="text-align:center;"><a class="bgnext" href="#nextpage" id="definition"> </a></div>
</div>

<div id="nextpage" title="Vocabulary" style="min-height: 320px;">
<div style="text-align:center;padding-top:10px;"><?php if(!empty($data->image)) { ?><img src="<?php echo printimage('/datas/vocab/image/'.$data->id); ?>" alt="" /><?php } ?></div>
<div style="text-align:center;padding-top:20px;padding-bottom:20px;"><?php echo $data->translate; ?></div>
<div style="text-align:center;padding-bottom:20px;"><?php if (strstr($data->word, "/")) list($data->word) = explode("/", $data->word); echo preg_replace("/({$data->word})/i","<span style=\"color:red;\">$1</span>",$data->text); ?></div>


<div style="text-align:center;padding-top:40px;padding-bottom:40px;"><small><?php if (!empty($idnext)) echo "click on screen for next item"; else echo "click on screen for returning to course"; ?></small></div>

<audio src="<?php if(!empty($data->soundword)) echo $CFG['wwwroot'].'/datas/vocab/soundword/'.$data->id.'.mp3'; ?>" id="audioword" autobuffer="autobuffer"></audio>
<audio src="<?php if(!empty($data->soundtext)) echo $CFG['wwwroot'].'/datas/vocab/soundtext/'.$data->id.'.mp3'; ?>" id="audiotext" autobuffer="autobuffer"></audio>
</div>
</body>
</html>