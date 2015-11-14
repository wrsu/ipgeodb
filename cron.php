<?php

// Configs
error_reporting( 0 );
define( 'PATH', dirname(__FILE__).'/' );
define( 'DB_HOST', 'localhost' );
define( 'DB_NAME', 'geoip' );
define( 'DB_USER', 'root' );
define( 'DB_PASS', '' );

// Download the file
file_put_contents( PATH . 'geo.zip', file_get_contents( 'http://ipgeobase.ru/files/db/Main/geo_files.zip' ) );
$mdf = md5_file( PATH . 'geo.zip' );
if ( $mdf == file_get_contents( PATH . 'md5.txt' ) ) {	unlink( PATH . 'geo.zip' );
	die('uptodate');
}

// Open archive and extract DB files
$zip = new ZipArchive;
if ( $zip->open( PATH . 'geo.zip' ) ) {	$zip->extractTo( PATH );
	$zip->close();
	file_put_contents( PATH . 'md5.txt', $mdf );
} else {	@unlink( PATH . 'geo.zip' );
	die( 'nofile' );
}

// Connect to DB
$db = mysql_connect( DB_HOST, DB_USER, DB_PASS );
if ( ! $db ) die( 'db' );
if ( ! mysql_select_db( DB_NAME, $db ) ) die( 'db' );
mysql_query( "SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8', collation_connection='utf8_general_ci'", $db );

// Load IPs
$ip = fopen( PATH . 'cidr_optim.txt', 'r' );
if ( $ip ) {	mysql_query( "TRUNCATE `ipwork`" );
	while ( $line = fgets( $ip ) ) {
		$ld = explode( "\t", trim($line) );
		$li = array( $ld[0], $ld[1], strtolower( $ld[3] ), (int) $ld[4] );
		$ll = "'" . implode( "', '", $li ) . "'";
		mysql_query( "INSERT INTO `ipwork` VALUES( $ll )" );
	} fclose( $ip );
	mysql_query( "RENAME TABLE `ips` TO `ip2`" );
	mysql_query( "RENAME TABLE `ipwork` TO `ips`" );
	mysql_query( "ALTER TABLE `ips` ORDER BY `ip` DESC" );
	mysql_query( "TRUNCATE `ip2`" );
	mysql_query( "RENAME TABLE `ip2` TO `ipwork`" );
}

// Load cities
$cf = fopen( PATH . 'cities.txt', 'r' );
if ( $cf ) {
	mysql_query( "TRUNCATE `city`" );
	while ( $line = fgets( $cf ) ) {		$line = iconv( 'windows-1251', 'utf-8', trim($line) );		$ld = explode( "\t", $line );
		$ll = "'" . implode( "', '", $ld ) . "'";
		mysql_query( "INSERT INTO `city` VALUES( $ll )" );
	} fclose( $cf );
}

// Finished
unlink( PATH . 'geo.zip' );
unlink( PATH . 'cidr_optim.txt' );
unlink( PATH . 'cities.txt' );
