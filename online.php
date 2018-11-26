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
**  All copyrights reserved (c) 2008-2016 PlaatSoft
*/

include "general.php";
include "database.php";
include "config.php";

/*
** ---------------------
** SETTINGS
** ---------------------
*/

define('DEMO', 0);

/*
** ---------------------
** PARAMETERS
** ---------------------
*/

define( 'LOCK_FILE', "/tmp/".basename( $argv[0], ".php" ).".lock" ); 
if( plaatonline_islocked() ) die( "Already running.\n" ); 

$sleep = 1000000;
$stop = false;


/**
 ********************************
 * Tools
 ********************************
 */

function ping ($host, $timeout = 1) {
    /* ICMP ping packet with a pre-calculated checksum */
    $package = "\x08\x00\x7d\x4b\x00\x00\x00\x00PingHost";
    $socket = socket_create(AF_INET, SOCK_RAW, 1);
    socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array('sec' => $timeout, 'usec' => 0));
    socket_connect($socket, $host, null);

    $ts = microtime(true);
    socket_send($socket, $package, strLen($package), 0);
    if (socket_read($socket, 255)) {    
        $result = true;
    } else {
        $result = false;
    }
    socket_close($socket);

    return $result;
}
/**
 ********************************
 * Core
 ********************************
 */
 
plaatonline_db_connect($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);

plaatonline_log("Online check starting.....");

$query  = 'select iid, name, address from inventory';
$result = plaatonline_db_query($query);
		
while ($data=plaatonline_db_fetch_object($result)) {		
	
	plaatonline_log("ping ".$data->address);
	
	if ( ping($data->address, 1)) {
		plaatonline_db_online_insert($data->iid);
	}
}


plaatonline_log(" Online check stopping.....");

unlink( LOCK_FILE ); 
exit(0); 

/**
 ********************************
 * The End
 ********************************
 */
 
?>