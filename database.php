<?php

/* 
**  ==========
**  PlaatSign
**  ==========
**
**  Created by wplaat
**
**  For more information visit the following website.
**  Website : www.plaatsoft.nl 
**
**  Or send an email to the following address.
**  Email   : info@plaatsoft.nl
**
**  All copyrights reserved (c) 2008-2016 PlaatSoft
*/

/* 
** -----------------
** GENERAL
** ----------------- 
*/

/**
 * connect to database
 * @param $dbhost database hostname
 * @param $dbuser database username
 * @param $dbpass database password
 * @param $dbname database name
 * @return connect result (true = successfull connected | false = connection failed)
 */
function plaatonline_db_connect($dbhost, $dbuser, $dbpass, $dbname) {

	global $db;

   $db = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);	
	if (mysqli_connect_errno()) {
		plaatonline_db_error();
		return false;		
	}
	return true;
}

/**
 * Disconnect from database  
 * @return disconnect result
 */
function plaatonline_db_close() {

	global $db;

	mysqli_close($db);

	return true;
}

/**
 * Show SQL error 
 * @return HTML formatted SQL error
 */
function plaatonline_db_error() {

	if (DEBUG == 1) {
		echo mysqli_connect_error(). "<br/>\n\r";
	}
}

/**
 * Count queries 
 * @return queries count
 */
$query_count=0;
function plaatonline_db_count() {

	global $query_count;
	return $query_count;
}

/**
 * Execute database multi query
 */
function plaatonline_db_multi_query($queries) {

	$tokens = @preg_split("/;/", $queries);
	foreach ($tokens as $token) {
	
		$token=trim($token);
		if (strlen($token)>3) {
			plaatonline_db_query($token);		
		}
	}
}

/**
 * Execute database query
 * @param $query SQL query with will be executed.
 * @return Database result
 */
function plaatonline_db_query($query) {
			
	global $query_count;
	global $db;
	
	$query_count++;

	if (DEBUG == 1) {
		echo $query."<br/>\r\n";
	}

	@$result = mysqli_query($db, $query);

	if (!$result) {
		plaatonline_db_error();		
	}
	
	return $result;
}

/**
 * escap database string
 * @param $data  input.
 * @return $data escaped
 */
function plaatonline_db_escape($data) {

	global $db;
	
	return mysqli_real_escape_string($db, $data);
}

/**
 * Fetch query result 
 * @return mysql data set if any
 */
function plaatonline_db_fetch_object($result) {
	
	$row="";
	
	if (isset($result)) {
		$row = $result->fetch_object();
	}
	return $row;
}

/**
 * Return number of rows
 * @return number of row in dataset
 */
function plaatonline_db_num_rows($result) {
	
	return mysqli_num_rows($result);
}

/*
** ------------------------
** CREATED / PATCH DATABASE
** ------------------------
*/

function startsWith($haystack, $needle){
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}

/**
 * Execute SQL script
 * @param $version Version of sql patch file
 */
function plaatonline_db_execute_sql_file($version) {

    $filename = 'database/patch-'.$version.'.sql';

    $commands = file_get_contents($filename);

    //delete comments
    $lines = explode("\n",$commands);
    $commands = '';
    foreach($lines as $line){
        $line = trim($line);
        if( $line && !startsWith($line,'--') ){
            $commands .= $line . "\n";
        }
    }

    //convert to array
    $commands = explode(";\n", $commands);

    //run commands
    $total = $success = 0;
    foreach($commands as $command){
        if(trim($command)){
	         $success += (@plaatonline_db_query($command)==false ? 0 : 1);
            $total += 1;
        }
    }

    //return number of successful queries and total number of queries found
    return array(
        "success" => $success,
        "total" => $total
    );
}

/**
 * Check db version and upgrade if needed!
 */
function plaatonline_db_check_version() {

   // Create database if needed	
   $sql = "select 1 FROM config limit 1" ;
   $result = plaatonline_db_query($sql);
   if (!$result) {
		$version="0.1";
      plaatonline_db_execute_sql_file($version);
   }

   // Path database if needed	
	//$version = plaatonline_db_config_get("database_version");
   //if ($version=="0.1") {
//		$version="0.2";
      //plaatonline_db_execute_sql_file($version);		
   //}
}

/*
** -----------------
** CONFIG
** -----------------
*/

function plaatonline_db_config($token) {
	
	$query  = 'select id, token, category, value, options, last_update, readonly from config where token="'.$token.'"';	
		
	$result = plaatonline_db_query($query);
	$data = plaatonline_db_fetch_object($result);
	
	return $data;	
}

function plaatonline_db_config_get($token) {
	
	$query  = 'select value from config where token="'.$token.'"';	
		
	$result = plaatonline_db_query($query);
	$data = plaatonline_db_fetch_object($result);
	
	return $data->value;	
}

function plaatonline_db_config_update($data) {
		
	$query  = 'update config set '; 
	$query .= 'value="'.$data->value.'", ';
	$query .= 'last_update="'.date("Y-m-d H:i:s").'" ';
	$query .= 'where id='.$data->id; 
	
	plaatonline_db_query($query);
}

/*
** -----------------
** ONLINE
** -----------------
*/

function plaatonline_db_online_insert($iid) {

	$query  = 'insert into online (iid, created) ';
	$query .= 'values ('.$iid.',"'.date("Y-m-d H:i:s").'")';
	plaatonline_db_query($query);
}

/*
** ---------------------
** THE END
** ---------------------
*/

?>