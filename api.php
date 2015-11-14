<?php

// Configs
error_reporting( 0 );
define( 'DB_HOST', 'localhost' );
define( 'DB_NAME', 'geoip' );
define( 'DB_USER', 'root' );
define( 'DB_PASS', '' );

// End Function
function stop ( $error, $db = false ) {
	if ( $db ) mysql_close( $db );
	echo json_encode(array( 'status' => 'error', 'error' => $error ));
	die();
}

// IP value
$ip = preg_replace( '#[^0-9\.]+#', '', $_GET['ip'] );
if ( !preg_match( '#^([0-9]+)\.([0-9]+)\.([0-9]+)\.([0-9]+)$#i', $ip ) ) stop( 'value' );
$ip = sprintf( "%u", ip2long( $ip ) );
if ( ! $ip ) stop( 'value' );

// Connect to DB
$db = mysql_connect( DB_HOST, DB_USER, DB_PASS );
if ( ! $db ) stop( 'db' );
if ( ! mysql_select_db( DB_NAME, $db ) ) stop( 'db', $db );
mysql_query( "SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8', collation_connection='utf8_general_ci'", $db );

// Get the IP data
$q = mysql_query( "SELECT * FROM `ips` WHERE `ip` < '$ip' ORDER BY `ip` DESC LIMIT 1" );
if ( ! $q ) stop( '404' );
$ipd = mysql_fetch_assoc( $q );
if ( $ipd['city'] ) {	$c = mysql_query( "SELECT * FROM `city` WHERE `id` = '".$ipd['city']."' LIMIT 1" );
	$cid = $c ? mysql_fetch_assoc( $c ) : array();
} else $cid = array();

// Make the result array
$result = array(
	'status'	=> 'ok',
	'ip'		=> long2ip( $ip ),
	'from'		=> long2ip( $ipd['ip'] ),
	'to'		=> long2ip( $ipd['last'] ),
	'country'	=> $ipd['country']
);
if ( $cid['city'] )		$result['city']		= $cid['city'];
if ( $cid['region'] )	$result['region']	= $cid['region'];
if ( $cid['district'] )	$result['district']	= $cid['district'];
if ( $cid['lat'] )		$result['lat']		= $cid['lat'];
if ( $cid['lng'] )		$result['lng']		= $cid['lng'];

// Return the data and finish
echo json_encode( $result );
mysql_close( $db );
// end. =)