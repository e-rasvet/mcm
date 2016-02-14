<script type="text/javascript" language="javascript" src="<?php echo $CFG['wwwroot']; ?>/js/niftyplayer.js"></script>

<style type="text/css">
#container {
    position: relative;
    height: 600px;
    width: 500px;
    margin: 50px auto;
    overflow: hidden;
    border: 1px solid #fff;
}

/* Defines the position and dimensions of the leafContainer div */
#leafContainer 
{
    position: absolute;
    width: 100%;
    height: 100%;
}

p {
  margin: 15px;
}

a
{
  color: #5C090A;
  text-decoration: none;
}

/* Sets the color of the "Dino's Gardening Service" message */
em 
{
    font-weight: bold;
    font-style: normal;
}

.phone {
  font-size: 150%;
  vertical-align: middle;
}

/* This CSS rule is applied to all div elements in the leafContainer div.
   It styles and animates each leafDiv.
*/
#leafContainer > div 
{
    position: absolute;
    width: 100px;
    height: 100px;
    font-size: 20px;
    
    /* We use the following properties to apply the fade and drop animations to each leaf.
       Each of these properties takes two values. These values respectively match a setting
       for fade and drop.
    */
    -webkit-animation-iteration-count: infinite, infinite;
    -webkit-animation-direction: normal, normal;
    -webkit-animation-timing-function: linear, ease-in;
}

/* This CSS rule is applied to all img elements directly inside div elements which are
   directly inside the leafContainer div. In other words, it matches the 'img' elements
   inside the leafDivs which are created in the createALeaf() function.
*/
#leafContainer > div > img {
     position: absolute;
     width: 100px;
     height: 100px;

    /* We use the following properties to adjust the clockwiseSpin or counterclockwiseSpinAndFlip
       animations on each leaf.
       The createALeaf function in the Leaves.js file determines whether a leaf has the 
       clockwiseSpin or counterclockwiseSpinAndFlip animation.
    */
     -webkit-animation-iteration-count: infinite;
     -webkit-animation-direction: alternate;
     -webkit-animation-timing-function: ease-in-out;
     -webkit-transform-origin: 50% -100%;
}


/* Hides a leaf towards the very end of the animation */
@-webkit-keyframes fade
{
    /* Show a leaf while into or below 95 percent of the animation and hide it, otherwise */
    0%   { opacity: 1; }
    95%  { opacity: 1; }
    100% { opacity: 0; }
}


/* Makes a leaf fall from -300 to 600 pixels in the y-axis */
@-webkit-keyframes drop
{
    /* Move a leaf to -300 pixels in the y-axis at the start of the animation */
    0%   { -webkit-transform: translate(0px, -50px); }
    /* Move a leaf to 600 pixels in the y-axis at the end of the animation */
    100% { -webkit-transform: translate(0px, 650px); }
}

/* Rotates a leaf from -50 to 50 degrees in 2D space */
@-webkit-keyframes clockwiseSpin
{
    /* Rotate a leaf by -50 degrees in 2D space at the start of the animation */
    0%   { -webkit-transform: rotate(-50deg); }
    /*  Rotate a leaf by 50 degrees in 2D space at the end of the animation */
    100% { -webkit-transform: rotate(50deg); }
}


/* Flips a leaf and rotates it from 50 to -50 degrees in 2D space */
@-webkit-keyframes counterclockwiseSpinAndFlip 
{
    /* Flip a leaf and rotate it by 50 degrees in 2D space at the start of the animation */
    0%   { -webkit-transform: scale(-1, 1) rotate(50deg); }
    /* Flip a leaf and rotate it by -50 degrees in 2D space at the end of the animation */
    100% { -webkit-transform: scale(-1, 1) rotate(-50deg); }
}

</style>
<script>

<?php if(!empty($data->soundtext)) { ?>
window.playmark = 0;
$(document).ready(function(){
  $('#playbutton').text("Playing");
  
  $('#playbutton').click(function() {
    if (window.playmark == 0) {
      niftyplayer('audiotext').play();
      window.playmark = 1;
      $('#playbutton').text("Playing");
    }
    return false;
  });
  
  $('#questionbox').click(function() {
    if (window.playmark == 0) {
      niftyplayer('audiotext').play();
      window.playmark = 1;
      $('#playbutton').text("Playing");
    }
    return false;
  });
});
<?php
}
?>

/* Define the number of leaves to be used in the animation */
const NUMBER_OF_LEAVES = <?php echo count($words); ?>;

/* 
    Called when the "Falling Leaves" page is completely loaded.
*/
function init()
{
    /* Get a reference to the element that will contain the leaves */
    var container = document.getElementById('leafContainer');
    /* Fill the empty container with new leaves */
    for (var i = 0; i < NUMBER_OF_LEAVES; i++) 
    {
        container.appendChild(createALeaf());
    }
}


/*
    Receives the lowest and highest values of a range and
    returns a random integer that falls within that range.
*/
function randomInteger(low, high)
{
    return low + Math.floor(Math.random() * (high - low));
}


/*
   Receives the lowest and highest values of a range and
   returns a random float that falls within that range.
*/
function randomFloat(low, high)
{
    return low + Math.random() * (high - low);
}


/*
    Receives a number and returns its CSS pixel value.
*/
function pixelValue(value)
{
    return value + 'px';
}


/*
    Returns a duration value for the falling animation.
*/

function durationValue(value)
{
    return value + 's';
}


function createrandomposition(counter)
{
    return positionsmatrix[counter];
}


function shuffle(o)
{ 
    for(var j, x, i = o.length; i; j = Math.floor(Math.random() * i), x = o[--i], o[i] = o[j], o[j] = x);
    return o;
};


/*
    Uses an img element to create each leaf. "Leaves.css" implements two spin 
    animations for the leaves: clockwiseSpin and counterclockwiseSpinAndFlip. This
    function determines which of these spin animations should be applied to each leaf.
*/

counter     = 1;
currentitem = 1;

var phrase = new Array();
<?php
foreach($words as $k => $v) {
echo 'phrase['.$k.'] = "'.$v.'";'."\n";
}
?>

var positionsmatrix = ['0','1','2','3','4','5','6','7','8','9'];
positionsmatrix = shuffle(positionsmatrix);

var speedposition = ['11','11.5','12','12.5','13'];
speedposition = shuffle(speedposition);

var starttop   = [];
var startspeed = [];

function createALeaf()
{
    /* Start by creating a wrapper div, and an empty img element */
    var leafDiv = document.createElement('div');
    var image = document.createElement('div');
    
    /* Randomly choose a leaf image and assign it to the newly created element */
    image.innerHTML = phrase[counter];
    image.style.padding = "40px 0 0 20px";
    
    leafDiv.style.backgroundColor = "#fff";
    leafDiv.setAttribute('id', 'leaf_'+counter);
    leafDiv.setAttribute('data-url', counter);
    leafDiv.setAttribute('data-text', phrase[counter]);
     
    leafDiv.addEventListener('mousedown', function() {
        //console.log($(this).attr('data-text'));
        setanswer($(this).attr('data-url'), $(this).attr('data-text'));
    });
   
    var top = "-60px";
    var position = createrandomposition(counter);

    /* Position the leaf at a random location along the screen */
    if (position > 4) {
        leafDiv.style.left = ((position - 5) * 100) + "px";
        top = "-160px";
        var speed = speedposition[(position - 5)];
    } else {
        leafDiv.style.left = (position * 100) + "px";
        var speed = speedposition[position];
    }
    
    leafDiv.style.top = top;
    
    starttop[counter] = top;
    startspeed[counter] = speed * 1000;
    
    leafDiv.appendChild(image);
    
<?php
    foreach($words as $k => $v) {
?>
    if (counter == <?php echo $k; ?>) {
      function startagain<?php echo $k; ?>(){
        $('#leaf_<?php echo $k; ?>').css( "top", starttop[<?php echo $k; ?>]);
        setTimeout(function(){$('#leaf_<?php echo $k; ?>').animate({ "top": "+=700px" },{queue: true,duration: startspeed[<?php echo $k; ?>],specialEasing: {top: 'linear'},complete: function(){startagain<?php echo $k; ?>()}});},100);
      }
      startagain<?php echo $k; ?>();
    }<?php if ($k < count($words)) echo ' else '; ?>
<?php } ?>
    
    counter++;

    /* Return this img element so it can be added to the document */
    return leafDiv;
}

function setanswer(i,e){
    if (i == currentitem) {
      $('#word_'+i).html(e);
      //$('#leaf_'+i).css("background-color", "#99ff99");
      //setTimeout(function(){$('#leaf_'+i).remove();},500);
      $('#leaf_'+i).remove();
      
      if (currentitem == NUMBER_OF_LEAVES){
        $('#container').remove();
        $('#nextbtn').toggle();
      } else 
        currentitem++;
    } else {
      $('#leaf_'+i).css("background-color", "#ffcccc");
      setTimeout(function(){$('#leaf_'+i).css("background-color", "#fff");},1000);
    }
}

/* Calls the init function when the "Falling Leaves" page is full loaded */
window.addEventListener('load', init, false);

<?php if(!empty($data->soundtext)) { ?>
$(document).ready(function() {
  setTimeout("niftyplayer('audiotext').play();", 600);
  setTimeout("niftyplayer('audiotext').registerEvent('onSongOver', '$(\"#playbutton\").text(\"Play\");window.playmark=0;');", 500);
});
<?php } ?>
</script>

<body>
<h1 id="pageTitle">Ordering activity</h1>
<a href="<?php echo $CFG['wwwroot']; ?>/index.php/c/<?php echo $course->id; ?>" class="button" id="homeButton" target="_webapp">Back</a>
<?php if(!empty($data->soundtext)) { ?>
<a href="#" class="button" id="playbutton" target="_webapp"><img width="16" height="11" src="<?php echo $CFG['wwwroot'].'/images/loader-small.gif'; ?>" alt="" /></a>
<?php } ?>

<div id="home" title="Question" selected="true">

    <div id="container">
      <div id="leafContainer"></div>
    </div>
    <div style="clear:both"></div>
    <div style="border: 2px solid #AAA;width: 500px;margin: 0 auto;padding: 10px;background-color: #CCCCFF;" id="questionbox">
      <?php
      echo $data->question;
      ?>
      <div>
        <?php
        foreach($words as $k => $v) {
          echo '<span id="word_'.$k.'">_</span> ';
        }
        ?>
      </div>
    </div>

<div>

</div>

<div style="clear:both;"></div>

<div><div style="width:120px;font-weight:bold;margin-left:5px;" id="user-score">Score <?php echo $score; ?></div>

<div style="display:none;margin-right:10px;" id="nextbtn">
<?php
if (!empty($idnext)) {
?>
<a class="whiteButton" type="button" href="<?php echo $CFG['wwwroot']; ?>/apps/ordering/index.php?id=<?php echo $idnext; ?>" target="_webapp">Next -></a>
<?php
} else {
?>
<a class="whiteButton" type="button" href="<?php echo $CFG['wwwroot']; ?>/index.php/c/<?php echo $course->id; ?>" target="_webapp">Return to course</a>
<?php
}
?>
</div></div>
  
  <div style="clear:both;"></div>
  
<?php if(!empty($data->soundtext)) { ?>
		<div style="width:1px;height:1px;text-indent: -9999px;"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="1" height="1" id="audiotext" align="">
		 <param name=movie value="<?php echo $CFG['wwwroot']; ?>/images/niftyplayer.swf?file=<?php echo $CFG['wwwroot']."/datas/ordering/soundtext/".$data->id.".mp3" ?>&as=0">
		 <param name=quality value=high>
		 <param name=bgcolor value=#FFFFFF>
		 <embed src="<?php echo $CFG['wwwroot']; ?>/images/niftyplayer.swf?file=<?php echo $CFG['wwwroot']."/datas/ordering/soundtext/".$data->id.".mp3" ?>&as=0" quality=high bgcolor=#FFFFFF width="1" height="1" name="audiotext" align="" type="application/x-shockwave-flash" swLiveConnect="true" pluginspage="http://www.macromedia.com/go/getflashplayer">
		</embed>
		</object></div>
<?php } ?>
  
<div>

</div>
  
</div>

</body>
</html>