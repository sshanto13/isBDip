<?php
    set_time_limit (0);
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    require_once (dirname(__DIR__) . "/wp-load.php");
    
	$client_ip      = '103.51.2.246';//get it from function
	##$client_ip    = '23.226.128.226';//outside of BD
	$client_ip2long = ip2long ($client_ip);
	$isBDIp         = isBDIp ($client_ip2long);
	
	if ($isBDIp){
		echo "BDT";	
	}else{
		echo "USD";	
	}

	function isBDIp ($client_ip2long){
    	global $wpdb;
		$table_name = $wpdb->prefix . 'ip_data';
		$sql        = "SELECT * FROM $table_name WHERE $client_ip2long BETWEEN ip_from AND ip_to ORDER BY ip_from ASC LIMIT 1";
		$ipdata     = $wpdb->get_results( $wpdb->prepare( $sql), ARRAY_A );
		
		if (count($ipdata) > 0){
			return true;
		}	
		
		return false;
	}    
    
    
    function dumpVar ($message = null){
		echo "<xmp>";
		print_r ($message);
		echo "</xmp>";
    }