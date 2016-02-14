<?php

include_once "../courses/config.php";

if(isset($USER) && is_object($USER)) {
  $id = file_get_contents("http://learn.core.kochi-tech.ac.jp/mcm/moodlelogin2.php?json=".urlencode(json_encode($USER)));
  header("Location: options.php?moodle=".$id);
} else
  header("Location: options.php?moodle=no");