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

/**
 * @file
 * @brief contain home page
 */
 
/*
** ---------------------
** PARAMETERS
** ---------------------
*/

/*
** ---------------------
** EVENTS
** ---------------------
*/


/*
** ----------------------
** PAGE
** ----------------------
*/

function plaatonline_home_page() {

	$page = '<h1>';
	$page .= '<img src="images/online.png" width="32" height="32">';
   $page .= ' '.t('TITLE').' ';
	$page .= '<img src="images/online.png" width="32" height="32">';
	$page .= '</h1>';

	$page .= '<div class="home">';

	$page .= '<table>';
		
	$page .= '<tr>';
	$page .= '<th width="25%">Id</th>';
	$page .= '<th width="25%">Name</th>';
	$page .= '<th width="25%">Address</th>';
	$page .= '<th width="25%">Online</th>';
	$page .= '</tr>';
	
	$query  = 'select iid, name, address from inventory order by iid';
	$result = plaatonline_db_query($query);
		
	while ($data=plaatonline_db_fetch_object($result)) {		
		
		$page .= '<tr>';	
		$page .= '<td>';
		$page	.= $data->iid;
		$page .= '</td>';		
		
		$page .= '<td>';		
		$page	.= $data->name;
		$page .= '</td>';
		
		$page .= '<td>';
		$page	.= $data->address;
		$page .= '</td>';			

		$page .= '<td>';
		$query2  = 'select iid from online where iid='.$data->iid.' and created>="'.date("Y-m-d 00:00:00").'"  and created<="'.date("Y-m-d 23:59:59").'"';
		$result2 = plaatonline_db_query($query2);		
		$page .= plaatonline_db_num_rows($result2);
		$page .= '</td>';			
		$page .= '</tr>';
	}
	
	$page .= '</table>';
	
	$page .= '<br/>';	
			
	return $page;
}

/*
** ---------------------
** HANDLER
** ---------------------
*/

/**
 * Home Page Handler
 * @return HTML block which contain home page.
 */
function plaatonline_home() {

	/* input */
	global $pid;
			
	/* Page handler */
	switch ($pid) {
					
		case PAGE_HOME:
			return plaatonline_home_page();
			break;
	}
}

/*
** ---------------------
** THE END
** ---------------------
*/

?>
