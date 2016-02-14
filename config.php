<?php

error_reporting(0); 

$CFG = array();
$CFG['host']        = "localhost";
$CFG['dbname']      = "mcm";
$CFG['dbuser']      = "iphone";
$CFG['dbpass']      = "";

$CFG['prefix']      = "mcm_";

$CFG['wwwroot']     = "http://.../mcm";


$CFG['dirroot']     = "/var/www/html/mcm";


/* PLEAE UPDATE YOUR LDAP SETTINGS OR REMOVE THIS SECTION */
$LDAPCFG = array();
$LDAPCFG['host_url'] = '';
$LDAPCFG['ldap_version'] = 2;
$LDAPCFG['user_type'] = 'rfc2307';
$LDAPCFG['bind_dn'] = '';
$LDAPCFG['bind_pw'] = '';
$LDAPCFG['opt_deref'] = 0;
$LDAPCFG['context'] = '';
/*END*/

if (!empty($_COOKIE[session_name()]) || $_SERVER['REQUEST_METHOD'] == 'POST') {
    session_id() || session_start();
}

include_once $CFG['dirroot']."/inc/ez_sql_core.php";
include_once $CFG['dirroot']."/inc/ez_sql_mysql.php";
include_once $CFG['dirroot']."/inc/f.php";

$optionsarray = get_records("options");
$options = array();
while(list($key,$value)=each($optionsarray)) {
  $options[$value->name] = $value->value;
}
