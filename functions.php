<?php
//code by suman

function isBDIp ($client_ip2long){
      
    	global $wpdb;
		$table_name = $wpdb->prefix . 'ip_data';
		$sql        = "SELECT * FROM $table_name WHERE $client_ip2long BETWEEN ip_from AND ip_to ORDER BY ip_from ASC LIMIT 1";
	    
		$ipdata     = $wpdb->get_results( $sql, ARRAY_A );
		//print_r($ipdata); exit;
		if (count($ipdata) > 0){
			return true;
		}	
		return false;
	   }  
		function get_client_ip() {
		$ipaddress = '';
			if (isset($_SERVER['HTTP_CLIENT_IP']))
				$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
			else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
				$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
			else if(isset($_SERVER['HTTP_X_FORWARDED']))
				$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
			else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
				$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
			else if(isset($_SERVER['HTTP_FORWARDED']))
				$ipaddress = $_SERVER['HTTP_FORWARDED'];
			else if(isset($_SERVER['REMOTE_ADDR']))
				$ipaddress = $_SERVER['REMOTE_ADDR'];
			else
				$ipaddress = 'UNKNOWN';
			return $ipaddress;
		}
function isbdip_func( $atts ) {
	$a = shortcode_atts( array(
		'local_amount' => 'N/A',
		'international_amount' => 'N/A',
	), $atts );
	$client_ip = get_client_ip();
	$client_ip2long = ip2long ($client_ip);
	$isBDIp = isBDIp ($client_ip2long);
	
	if ($isBDIp){
		return "{$a['local_amount']}";
	}else{
		return "{$a['international_amount']}";
	}
}
add_shortcode( 'isbdip', 'isbdip_func' );

//shortcode
//echo do_shortcode ('[isbdip local_amount="850" international_amount="10"]');