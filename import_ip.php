<?php
    define('DB_NAME', 'bd_ip2location');
    define('DB_USER', 'root');
    define('DB_PASSWORD', '');
    define('DB_HOST', 'localhost');
##Server: 127.0.0.1 »Database: bd_ip2location »Table: ip_data

	set_time_limit (0);
    require_once ('DB.php');
    require_once ('main.php');

	$client_ip      = '103.51.2.246';//get it from function
	##$client_ip      = '23.226.128.226';//outside of BD
	$client_ip2long = ip2long ($client_ip);
	$isBDIp         = isBDIp ($client_ip2long);
	
	if ($isBDIp){
		echo "BDT";	
	}else{
		echo "USD";	
	}


	/*
	//import is done once, so this part is commented
	$csv = array_map('str_getcsv', file('ip_range.txt')); 
	
	foreach ($csv as $aRow){
		$ip_from_dotted = trim($aRow[0]);
		$ip_to_dotted   = trim($aRow[1]);
		$ip_from 		= ip2long ($ip_from_dotted);
		$ip_to   		= ip2long ($ip_to_dotted);
		
		$insertData                   = [];
		$insertData['ip_from']        = $ip_from;
		$insertData['ip_to']          = $ip_to;
		$insertData['ip_from_dotted'] = $ip_from_dotted;
		$insertData['ip_to_dotted']   = $ip_to_dotted;
		DB::insertData ('ip_data', $insertData);
		dumpVar ($insertData);
	}
	*/
	
	function isBDIp ($client_ip2long){
		$sql    = "SELECT * FROM `ip_data` WHERE $client_ip2long BETWEEN ip_from AND ip_to ORDER BY ip_from ASC LIMIT 1";
		$result = DB::mquery ($sql);
		$row    = DB::fetch ($result);
		
		if (empty($row)){
			return false;
		}	
		
		return true;
	}