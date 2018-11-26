<?php

/* 
**  ============
**  PlaatOnline
**  ============
**
**  Created by wplaat
**
**  For more information visit the following website.
**  Website : www.plaatsoft.nl 
**
**  Or send an email to the following address.
**  Email   : info@plaatsoft.nl
**
**  All copyrights reserved (c) 1996-2016 PlaatSoft
*/


$lang = array();

$time_start = microtime(true);

include "database.php";
include "general.php";
include "english.php";

/*
** ---------------------------------------------------------------- 
** Config file
** ---------------------------------------------------------------- 
*/

if (!file_exists( "config.php" )) {

	echo plaatonline_ui_header();	
	echo plaatonline_ui_banner("");

	$page  = '<h1>'.t('GENERAL_WARNING').'</h1>';
	$page .= '<br/>';
   $page .= t('CONGIG_BAD');
	$page .= '<br/>';
	
	echo '<div id="container">'.$page.'</div>';
	
	$time_end = microtime(true);
	$time = $time_end - $time_start;
	
	echo plaatonline_ui_footer($time, 0 );
	
   exit;
}

include "config.php";

/*
** ---------------------------------------------------------------- 
** Database
** ---------------------------------------------------------------- 
*/

/* connect to database */
if (@plaatonline_db_connect($config["dbhost"], $config["dbuser"], $config["dbpass"], $config["dbname"]) == false) {

	echo plaatonline_ui_header();	
	echo plaatonline_ui_banner("");

	$page  = '<h1>'.t('GENERAL_WARNING').'</h1>';
	$page .= '<br/>';
   $page .= t('DATABASE_CONNECTION_FAILED');
	$page .= '<br/>';
	
	echo '<div id="container">'.$page.'</div>';
	
	$time_end = microtime(true);
	$time = $time_end - $time_start;
	
	echo plaatonline_ui_footer($time, 0 );

	exit;
}

/* create / patch database if needed */
plaatonline_db_check_version();

/* Set default timezone */
date_default_timezone_set ( plaatonline_db_config_get("timezone" ) );

/*
** ---------------------------------------------------------------- 
** Global variables
** ---------------------------------------------------------------- 
*/

plaatonline_debug('-----------------');

$page = "";
$token = "";
$title = "";

$pid = PAGE_HOME;     // Page Id
$id = 0;

/* 
** ---------------------------------------------------------------- 
** POST parameters
** ----------------------------------------------------------------
*/	

if (strlen($token)>0) {
	
	/* Decode token */
	$token = gzinflate(base64_decode($token));	
	$tokens = @preg_split("/&/", $token);
	
	foreach ($tokens as $item) {
		$items = preg_split ("/=/", $item);				
		${$items[0]} = $items[1];	
		
		if (DEBUG == 1) {
			echo $items[0].'='.$items[1].'<br>';
		}
	}
}


/*
** ---------------------------------------------------------------- 
** State Machine
** ----------------------------------------------------------------
*/

/* Global Page Handler */
switch ($pid) {
					
	case PAGE_HOME: 	
				include "home.php";
				$page = plaatonline_home();
				break;
}

/*
** ---------------------------------------------------------------- 
** Create html response
** ----------------------------------------------------------------
*/

echo plaatonline_ui_header($title);	
	
echo '<div id="container">'.$page.'</div>';

$time_end = microtime(true);
$time = $time_end - $time_start;

$time = round($time*1000);

echo plaatonline_ui_footer($time, plaatonline_db_count() );

plaatonline_debug('Page render time = '.$time.' ms.');
plaatonline_debug('Amount of queries = '.plaatonline_db_count());

plaatonline_db_close();

/*
** ---------------------------------------------------------------- 
** THE END
** ----------------------------------------------------------------
*/

?>