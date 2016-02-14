<?php

if (!function_exists('json_encode')) {
  include_once 'JSON.php';

  function json_encode($data) {
    $json = new Services_JSON();
    return( $json->encode($data) );
  }

  function json_decode($data, $bool) {
    if ($bool) 
      $json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
    else 
      $json = new Services_JSON();
    
    return( $json->decode($data) );
  }
}




$mcm_server = "http://webapp.netcourse.org";


$admin_menu = array(
"Courses" => array("createcourse"=>"Create courses", 
                   "listofcourses"=>"List of courses", 
                   "managecourses"=>"Manage courses"),
"Users"   => array("addusers"=>"Add users", 
                   "listofusers"=>"List of users", 
                   "importusers"=>"Import users"),
"Applications" => array("contents"=>"Current content", 
                        "importcontents"=>"Import content", 
                        "apps_vocabulary"=>"Vocabulary Flashcard", 
                        "apps_quizzes"=>"Vocabulary quiz", 
                        "apps_reading"=>"Reading & listening", 
                        "apps_listening"=>"Listen & dictation", 
                        "apps_choice"=>"Multiple choice", 
                        "apps_resource"=>"Resource", 
                        "apps_clozeactivity"=>"Cloze activity",
                        "apps_dragdrope"=>"Drag & Drop",
                        "apps_ordering"=>"Ordering",
                        "apps_listenspeak"=>"Listen & Speak",
                        "apps_groupquiz"=>"Group quiz"),
"Administration" => array("options"=>"Options")
);

$roles = array(1 => "Admin", 
               2 => "Teacher", 
               3 => "Student");

$appstables = array("vocabulary" => "apps_vocabulary", 
                    "reading" => "apps_reading", 
                    "quiz" => "apps_quizzes", 
                    "listening" => "apps_listening", 
                    "choice" => "apps_choice", 
                    "resource"=>"apps_resource", 
                    "clozeactivity" => "apps_clozeactivity",
                    "dragdrope" => "apps_dragdrope",
                    "ordering" => "apps_ordering",
                    "listenspeak" => "apps_listenspeak",
                    "groupquiz" => "apps_groupquiz"
                    );

$appsnewnames = array("vocabulary" => "Vocabulary Flashcard", 
                      "reading" => "Reading & listening", 
                      "quiz" => "Vocabulary quiz", 
                      "listening" => "Listen & dictation", 
                      "choice" => "Multiple choice", 
                      "resource" => "Resource", 
                      "clozeactivity" => "Cloze activity",
                      "dragdrope" => "Drag & Drop",
                      "ordering" => "Ordering", 
                      "listenspeak" => "Listen & Speak",
                      "groupquiz" => "Group quiz");

$appstables2  = array(1 => "Multiple choice", 
                      2 => "Vocabulary Flashcard", 
                      3 => "Reading & listening", 
                      4 => "Listen & dictation", 
                      5 => "Vocabulary quiz", 
                      6 => "Resource", 
                      7 => "Cloze activity",
                      8 => "Drag & Drop",
                      9 => "Ordering",
                      10 => "Listen & Speak",
                      11 => "Group quiz");

$appsmainfield = array("apps_vocabulary" => "word", 
                       "apps_reading" => "title", 
                       "apps_quizzes" => "word", 
                       "apps_listening" => "feedback", 
                       "apps_choice" => "text", 
                       "apps_resource"=>"text", 
                       "apps_clozeactivity" => "text",
                       "apps_dragdrope" => "text",
                       "apps_ordering" => "text",
                       "apps_listenspeak" => "text",
                       "apps_groupquiz" => "title"
                       );


function _e($text, $lang = '') {
  echo $text;
}

function __($text, $lang = '') {
  return $text;
}


$db = new ezSQL_mysql($CFG['dbuser'],$CFG['dbpass'],$CFG['dbname'],$CFG['host']);


//check_server_status();


$optionsarray = get_records("options");
$options = array();
while(list($key,$value)=each($optionsarray)) {
  $options[$value->name] = $value->value;
}


function get_records($table, $data = array(), $sort='', $fields='*', $limitfrom='', $limitnum='') {
  global $db, $CFG;
  
  $wq = "";
  $ws = "";
  $wl = "";
  
  while (list($key,$value)=each($data)) {
    $value = antihack($value);
    if (is_int($value))
      $wq .= " `{$key}`={$value} AND";
    else
      $wq .= " `{$key}`='{$value}' AND";
  }
  
  if (!empty($wq)) {
    $wq = substr($wq, 0, -3);
    $wq = " WHERE ".$wq;
  }
  
  if (!empty($sort)) {
    $ws = " ORDER BY {$sort} ";
  }
  
  if (!empty($limitfrom) && !empty($limitnum)) {
    $wl = " LIMIT {$limitfrom},{$limitnum} ";
  }
  
  
  $res = $db->get_results("SELECT {$fields} FROM {$CFG['prefix']}{$table} {$wq} {$ws} {$wl}",OBJECT);

  if (is_object($res) || is_array($res))
    while (list($key,$value)=each($res)) {
      while (list($key2,$value2)=each($value)) {
        $resnew[$key]->$key2 = stripslashes($value2);
      }
    }
  else
    return false;
    
  return $resnew;
}

function get_record($table, $data = array(), $sort='', $fields='*') {
  global $db, $CFG;
  
  $wq = "";
  $ws = "";
  
  while (list($key,$value)=each($data)) {
    $value = antihack($value);
    if (is_int($value))
      $wq .= " `{$key}`={$value} AND";
    else
      $wq .= " `{$key}`='{$value}' AND";
  }
  
  if (!empty($wq)) {
    $wq = substr($wq, 0, -3);
    $wq = " WHERE ".$wq;
  }
  
  if (!empty($sort)) {
    $ws = " ORDER BY {$sort} ";
  }
  
  $res = $db->get_row("SELECT {$fields} FROM {$CFG['prefix']}{$table} {$wq} {$ws} LIMIT 1",OBJECT);
  
  if (is_object($res) || is_array($res))
    while(list($key,$value)=each($res)) {
      $resnew->$key = stripslashes($value);
    }
  else
    return false;
  
  return $resnew;
}

function insert_record($table, $data = array()) {
  global $db, $CFG;


  if (!isset($data['id'])) {
    $wc = "";
    $wv = "";
  
    while (list($key,$value)=each($data)) {
      $value = antihack($value);
    
      $wc .= "`$key`,";
    
      if (is_int($value))
        $wv .= "{$value},";
      else
        $wv .= "'{$value}',";
   }
  
    $wv = substr($wv, 0, -1);
    $wc = substr($wc, 0, -1);
  
    $db->query("INSERT INTO {$CFG['prefix']}{$table} ({$wc}) VALUES ({$wv}) ");
    
    return mysql_insert_id();
  } else {
    $wv = "";
  
    while (list($key,$value)=each($data)) {
      if ($key != "id") {
        $value = antihack($value);
        if (is_int($value))
          $wv .= " `{$key}`={$value},";
        else
          $wv .= " `{$key}`='{$value}',";
      }
    }
    
    $wv = substr($wv, 0, -1);
    
    $db->query("UPDATE {$CFG['prefix']}{$table} SET {$wv} WHERE `id`={$data['id']}");
    
    return $data['id'];
  }
}

//function update_record($table, $data = array()) {
//  global $db, $CFG;
//}

function antihack($var) {
    $var = addslashes($var);
    $var = str_replace("+", " ", $var);
    $var = str_replace("UNION", " ", $var);
    $var = str_replace("null", " ", $var);
    $var = str_replace("`", " ", $var);
    $var = str_replace("#", " ", $var);
    return $var;
}


function optional_param($parname, $default=NULL, $type=PARAM_CLEAN) {

    // detect_unchecked_vars addition
    global $CFG;

    if (isset($_POST[$parname])) {       // POST has precedence
        $param = $_POST[$parname];
    } else if (isset($_GET[$parname])) {
        $param = $_GET[$parname];
    } else {
        return $default;
    }

    return clean_param($param, $type);
}

function clean_param($param, $type=PARAM_CLEAN) {

    global $CFG;

    if (is_array($param)) {              // Let's loop
        $newparam = array();
        foreach ($param as $key => $value) {
            $newparam[$key] = clean_param($value, $type);
        }
        return $newparam;
    }

    switch ($type) {
        case PARAM_RAW:          // no cleaning at all
            return $param;

        case PARAM_CLEAN:        // General HTML cleaning, try to use more specific type if possible
            if (is_numeric($param)) {
                return $param;
            }
            $param = stripslashes($param);   // Needed for kses to work fine
            $param = clean_text($param);     // Sweep for scripts, etc
            return addslashes($param);       // Restore original request parameter slashes

        case PARAM_CLEANHTML:    // prepare html fragment for display, do not store it into db!!
            $param = stripslashes($param);   // Remove any slashes
            $param = clean_text($param);     // Sweep for scripts, etc
            return trim($param);

        case PARAM_INT:
            return (int)$param;  // Convert to integer

        case PARAM_NUMBER:
            return (float)$param;  // Convert to integer

        case PARAM_BOOL:         // Convert to 1 or 0
            $tempstr = strtolower($param);
            if ($tempstr == 'on' or $tempstr == 'yes' ) {
                $param = 1;
            } else if ($tempstr == 'off' or $tempstr == 'no') {
                $param = 0;
            } else {
                $param = empty($param) ? 0 : 1;
            }
            return $param;

        case PARAM_NOTAGS:       // Strip all tags
            return strip_tags($param);

        case PARAM_TEXT:    // leave only tags needed for multilang
            return clean_param(strip_tags($param, '<lang><span>'), PARAM_CLEAN);

        case PARAM_SAFEDIR:      // Remove everything not a-zA-Z0-9_-
            return eregi_replace('[^a-zA-Z0-9_-]', '', $param);

        case PARAM_CLEANFILE:    // allow only safe characters
            return clean_filename($param);

        case PARAM_FILE:         // Strip all suspicious characters from filename
            $param = ereg_replace('[[:cntrl:]]|[<>"`\|\':\\/]', '', $param);
            $param = ereg_replace('\.\.+', '', $param);
            if($param == '.') {
                $param = '';
            }
            return $param;

        case PARAM_PATH:         // Strip all suspicious characters from file path
            $param = str_replace('\\\'', '\'', $param);
            $param = str_replace('\\"', '"', $param);
            $param = str_replace('\\', '/', $param);
            $param = ereg_replace('[[:cntrl:]]|[<>"`\|\':]', '', $param);
            $param = ereg_replace('\.\.+', '', $param);
            $param = ereg_replace('//+', '/', $param);
            return ereg_replace('/(\./)+', '/', $param);

        case PARAM_HOST:         // allow FQDN or IPv4 dotted quad
            $param = preg_replace('/[^\.\d\w-]/','', $param ); // only allowed chars
            // match ipv4 dotted quad
            if (preg_match('/(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})/',$param, $match)){
                // confirm values are ok
                if ( $match[0] > 255
                     || $match[1] > 255
                     || $match[3] > 255
                     || $match[4] > 255 ) {
                    // hmmm, what kind of dotted quad is this?
                    $param = '';
                }
            } elseif ( preg_match('/^[\w\d\.-]+$/', $param) // dots, hyphens, numbers
                       && !preg_match('/^[\.-]/',  $param) // no leading dots/hyphens
                       && !preg_match('/[\.-]$/',  $param) // no trailing dots/hyphens
                       ) {
                // all is ok - $param is respected
            } else {
                // all is not ok...
                $param='';
            }
            return $param;

        case PARAM_URL:          // allow safe ftp, http, mailto urls
            include_once($CFG['dirroot'] . '/inc/validateurlsyntax.php');
            if (!empty($param) && validateUrlSyntax($param, 's?H?S?F?E?u-P-a?I?p?f?q?r?')) {
                // all is ok, param is respected
            } else {
                $param =''; // not really ok
            }
            return $param;
    }
}


function clean_filename($string) {
    $textlib = textlib_get_instance();
    $string = $textlib->specialtoascii($string);
    $string = preg_replace('/[^\.a-zA-Z\d\_-]/','_', $string ); // only allowed chars
    $string = preg_replace("/_+/", '_', $string);
    $string = preg_replace("/\.\.+/", '.', $string);
    return $string;
}

function clean_text($text) {
    global $CFG;
    
    $ALLOWED_TAGS = "br,strong,div,a,font,span,i,u,b";
    $text = strip_tags($text, $ALLOWED_TAGS);
    $text = eregi_replace("([^a-z])language([[:space:]]*)=", "\\1Xlanguage=", $text);
    $text = eregi_replace("([^a-z])on([a-z]+)([[:space:]]*)=", "\\1Xon\\2=", $text);

    return $text;
} 


function check_server_status() {
    global $CFG, $mcm_server, $db, $options;
    
    $trampampam        = optional_param('trampampam', NULL, PARAM_TEXT);
    
    $lastchecking = get_record("options", array("name" => "_lastcheking"));
    $blockingsite = get_record("options", array("name" => "_errorreporting"));
    
    $checktime = time() - 24 * 3600;
    
    if (!$lastchecking || (int)$lastchecking->value < $checktime || !empty($trampampam)) {
      $postdata = http_build_query(array('serverlogin' => $options['serverlogin'],'serverpassword' => $options['serverpassword'], 'siteurl' => $CFG['wwwroot']));
      $opts = array('http' => array(
        'method'  => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'content' => $postdata
       ));
      $context  = stream_context_create($opts);
      $result = @file_get_contents($mcm_server.'/report/index.php', false, $context);
      
      $data = json_decode($result);
      
      if ($data->status == 'block') {
        $datam              = array();
        $datam['name']      = '_errorreporting';
        $datam['value']     = 1;
        insert_record("options", $datam);
      }
      
      if ($data->status == 'enable') 
        $db->query("DELETE FROM {$CFG['prefix']}options WHERE `name` = '_errorreporting'");

      
      $datam              = array();
      $datam['name']      = '_lastcheking';
      $datam['value']     = time();
      $datam['id']        = $lastchecking->id;
      insert_record("options", $datam);
      
      $latestversion = get_record("options", array("name" => "_latestversion"));
  
      $datam              = array();
      $datam['name']      = '_latestversion';
      $datam['value']     = $data->latestversion;
      $datam['id']        = $latestversion->id;
      insert_record("options", $datam);
  
      $limitbefore = get_record("options", array("name" => "_limitbefore"));
  
      $datam              = array();
      $datam['name']      = '_limitbefore';
      $datam['value']     = $data->limitbefore;
      $datam['id']        = $limitbefore->id;
      insert_record("options", $datam);
    }
    
    if ($blockingsite) 
      die("Please contact with NetCourse.org support. {$mcm_server}/contacts.php");
    
    
    return true;
}

function statusmessage($text,$color = "blue") {
  global $CFG;
  
  if ($color == "red") $text = "Error. ".$text;
  
  $data = '<div id="message-'.$color.'">
				<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tbody><tr>
					<td class="red-left">'.$text.'</td>
					<td class="red-right"><a class="close-red"><img alt="" src="'.$CFG['wwwroot'].'/images/table/icon_close_'.$color.'.gif"></a></td>
				</tr>
				</tbody></table>
				</div>';
				
	return $data;
}


function mobilehtmlheader($title) {
  global $CFG;
  
  if (strstr($_SERVER['HTTP_USER_AGENT'], "Chrome") ||
      strstr($_SERVER['HTTP_USER_AGENT'], "Firefox") ||
      strstr($_SERVER['HTTP_USER_AGENT'], "MSIE") ||
      strstr($_SERVER['HTTP_USER_AGENT'], "Opera") ||
      strstr($_SERVER['HTTP_USER_AGENT'], "Safari"))
    $css = 'iphonenavbr.css';
  else
    $css = 'iphonenav.css';
  ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $title; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=320; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;"/>
<meta name="apple-mobile-web-app-capable" content="yes" />
<link rel="apple-touch-icon" href="<?php echo $CFG['wwwroot']; ?>/images/apple-touch-icon-iphone.png" />
<link rel="apple-touch-icon" sizes="72x72" href="<?php echo $CFG['wwwroot']; ?>/images/apple-touch-icon-ipad.png" />
<link rel="apple-touch-icon" sizes="114x114" href="<?php echo $CFG['wwwroot']; ?>/images/apple-touch-icon-iphone4.png" />
<style type="text/css" media="screen">@import "<?php echo $CFG['wwwroot']; ?>/webiphone/<?php echo $css; ?>";</style>
<script type="application/x-javascript" src="http://yandex.st/jquery/2.0.3/jquery.min.js"></script>
<script type="application/x-javascript" src="http://yandex.st/jquery-ui/1.10.3/jquery-ui.min.js"></script>
<script type="application/x-javascript" src="<?php echo $CFG['wwwroot']; ?>/webiphone/iphonenav.js"></script>
</head>

<body>
  <?php
  
}

function mobilehtmlfooter() {
  echo '</body></html>';
}


function browser_detect() {
  global $CFG;
  
  include_once $CFG['dirroot'] . "/inc/Browser.php";
  
  $apples = array("iphone", "ipod", "ipad", "ios", "safari");
  $android = array("android");
  $ie = array("internet explorer");
  
  $browser = new Browser();

  if (in_array(strtolower($browser->getBrowser()), $apples)) {
    if (strstr($browser->getVersion(), ".")) {
      $versionarray = explode(".", $browser->getVersion());
      $version = "";
      while(list($key, $value)=each($versionarray)) {
        $version .= $value;
        if ($key == 0) $version .= ".";
      }
    } else {
      $version = $browser->getVersion();
    }
    if ($version == "unknown") $version = 4.2;   //---------Full screen mode-----------------//
    //$version = 0;                // REMOVE THIS LINE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    if ($version <= 4.05) {
      $folder = "iOS4.1";
    } else {
      $folder = "iOS4.2";
    }
  } else if (in_array(strtolower($browser->getBrowser()), $android)) {
    $folder = "Android2.2";
  } else if (in_array(strtolower($browser->getBrowser()), $ie)) {
    $folder = "iOS4.1";
  } else {
    //$folder = "iOS4.1";
    $folder = "Android2.2";
  }
  
  //$folder = "Android2.2";
  return $folder;
}


function get_user_score ($userid,$appsid) {
  if (!$score = get_record("score", array("userid" => $userid, "apps" => $appsid), 'score DESC')) {
    $score = new stdClass;
    $score->score = 0;
  }
  
  return $score->score;
}


function printimage($link) {
  global $CFG;
  
  if (is_file($CFG['dirroot'].$link.".jpg"))
    return $CFG['wwwroot'] . $link.".jpg";
  else
    return $CFG['wwwroot'] . $link.".png";
  
}


function add_log($userid,$appsid,$time) {
  
  if (isset($_SESSION['currentcourse'])) 
    $courseid = $_SESSION['currentcourse'];
  else if (isset($_COOKIE['mcm_course']))
    $courseid = $_COOKIE['mcm_course'];
  else
    $courseid = 0;
  
  $datam               = array();
  $datam['userid']     = $userid;
  $datam['apps']       = $appsid;
  $datam['time']       = $time;
  $datam['courseid']   = $courseid;
  
  $id = insert_record("log", $datam);
  
  return $id;
}

function createRandomPassword() {
    $chars = "abcdefghijkmnopqrstuvwxyz023456789";
    srand((double)microtime()*1000000);
    $i = 0;
    $pass = '' ;

    while ($i <= 7) {
        $num = rand() % 33;
        $tmp = substr($chars, $num, 1);
        $pass = $pass . $tmp;
        $i++;
    }
    
    return $pass;
}


function get_image_type($name) {
  if (strstr(strtolower($name), ".jpg")) 
    return "jpg";
  else
    return "png";
}


function help_link($title,$file){
  global $CFG;
  
  return '<span class="helplink"><a title="'.$title.'" href="'.$CFG['wwwroot'].'/admin/help.php?file='.$file.'" onclick="this.target=\'popup\'; return openpopup(\''.$CFG['wwwroot'].'/admin/help.php?file='.$file.'\', \'popup\', \'menubar=0,location=0,scrollbars,resizable,width=500,height=400\', 0);"><img class="iconhelp" alt="'.$title.'" src="'.$CFG['wwwroot'].'/images/help.gif" /></a></span>';
}



function ldap_login($extusername, $extpassword){
    global $options;
    
    if ($options['ldap_enable'] != 1)
      return false;

    $ldapconnection = ldap_connect_moodle();

    $ldap_user_dn   = ldap_find_userdn($ldapconnection, $extusername);

    // If ldap_user_dn is empty, user does not exist
    if (!$ldap_user_dn) {
      ldap_close($ldapconnection);
      return false;
    }

    // Try to bind with current username and password
    $ldap_login = @ldap_bind($ldapconnection, $ldap_user_dn, $extpassword);
    ldap_close($ldapconnection);
    if ($ldap_login) {
      return true;
    }
    return false;
}


function ldap_connect_moodle() {
    global $options;

    if (empty($options['ldap_host_url']) || empty($options['ldap_version']) || empty($options['ldap_user_type'])) {
        $debuginfo = 'No LDAP Host URL, Version or User Type specified in your LDAP settings';
        return false;
    }

    $debuginfo = '';
    $urls = explode(';', $options['ldap_host_url']);
    foreach ($urls as $server) {
        $server = trim($server);
        if (empty($server)) {
            continue;
        }

        $connresult = ldap_connect($server); // ldap_connect returns ALWAYS true

        if (!empty($options['ldap_version'])) {
            ldap_set_option($connresult, LDAP_OPT_PROTOCOL_VERSION, $options['ldap_version']);
        }

        // Fix MDL-10921
        if ($options['ldap_user_type'] === 'ad') {
            ldap_set_option($connresult, LDAP_OPT_REFERRALS, 0);
        }

        if (!empty($options['ldap_opt_deref'])) {
            ldap_set_option($connresult, LDAP_OPT_DEREF, $options['ldap_opt_deref']);
        }

        if (!empty($options['ldap_bind_dn'])) {
            $bindresult = @ldap_bind($connresult, $options['ldap_bind_dn'], $options['ldap_bind_pw']);
        } else {
        // Bind anonymously
            $bindresult = @ldap_bind($connresult);
        }

        if ($bindresult) {
            return $connresult;
        }

        $debuginfo .= "Server: '$server', Connection: '$connresult', Bind result: '$bindresult'\n";
    }

    // If any of servers were alive we have already returned connection.
    return false;
}


function ldap_find_userdn($ldapconnection, $username) {
    global $options;
    
    // Default return value
    $ldap_user_dn = false;
    
    $contexts = explode(';', $options['ldap_context']);

    // Get all contexts and look for first matching user
    foreach ($contexts as $context) {
        $context = trim($context);
        if (empty($context)) {
            continue;
        }

        $ldap_result = ldap_search($ldapconnection, $context,
                                       '(&(objectClass=posixaccount)(uid='.ldap_filter_addslashes($username).'))',
                                       array('uid'));

        $entry = ldap_first_entry($ldapconnection, $ldap_result);
        if ($entry) {
            $ldap_user_dn = ldap_get_dn($ldapconnection, $entry);
            break;
        }
    }

    return $ldap_user_dn;
}


function ldap_filter_addslashes($text) {
    $text = str_replace('\\', '\\5c', $text);
    $text = str_replace(array('*',    '(',    ')',    "\0"),
                        array('\\2a', '\\28', '\\29', '\\00'), $text);
    return $text;
}


function data_submitted() {

    if (empty($_POST)) {
        return false;
    } else {
        return (object)fix_utf8($_POST);
    }
}


function fix_utf8($value) {
    if (is_null($value) or $value === '') {
        return $value;

    } else if (is_string($value)) {
        if ((string)(int)$value === $value) {
            // shortcut
            return $value;
        }

        // Lower error reporting because glibc throws bogus notices.
        $olderror = error_reporting();
        if ($olderror & E_NOTICE) {
            error_reporting($olderror ^ E_NOTICE);
        }

        // Note: this duplicates min_fix_utf8() intentionally.
        static $buggyiconv = null;
        if ($buggyiconv === null) {
            $buggyiconv = (!function_exists('iconv') or iconv('UTF-8', 'UTF-8//IGNORE', '100'.chr(130).'ˆ') !== '100ˆ');
        }

        if ($buggyiconv) {
            if (function_exists('mb_convert_encoding')) {
                $subst = mb_substitute_character();
                mb_substitute_character('');
                $result = mb_convert_encoding($value, 'utf-8', 'utf-8');
                mb_substitute_character($subst);

            } else {
                // Warn admins on admin/index.php page.
                $result = $value;
            }

        } else {
            $result = iconv('UTF-8', 'UTF-8//IGNORE', $value);
        }

        if ($olderror & E_NOTICE) {
            error_reporting($olderror);
        }

        return $result;

    } else if (is_array($value)) {
        foreach ($value as $k=>$v) {
            $value[$k] = fix_utf8($v);
        }
        return $value;

    } else if (isset($value)) {
        $value = clone($value); // do not modify original
        foreach ($value as $k=>$v) {
            $value->$k = fix_utf8($v);
        }
        return $value;

    } else {
        // this is some other type, no utf-8 here
        return $value;
    }
}


function shuffle_assoc($array) { 
    $shuffled_array = array();

    $shuffled_keys = array_keys($array);
    shuffle($shuffled_keys);

    foreach ( $shuffled_keys AS $shuffled_key ) {
        $shuffled_array[  $shuffled_key  ] = $array[  $shuffled_key  ];
    }

    return $shuffled_array;
}
