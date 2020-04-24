<?php
/**
 *
 * DB class
 *
 *
 * This uses PDO (inherts for transactions)
 *
 */
class DB{

  private $_mysqli;
  private static $_instance;
  public function __construct()
  {
    try {
      /*
      * This create instance of class
      */
      mysqli_report(MYSQLI_REPORT_STRICT);
      $this->_mysqli = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
      mysqli_query($this->_mysqli, 'SET names=utf8');
      mysqli_query($this->_mysqli, 'SET character_set_results=utf8');
    } catch (Exception $e) {
      //die($e->getMessage());
      die("<h2>MySQL: Unable to connect</h2>");
    }
  }

	public function connect(){
		return $this->_mysqli;
	}
	
  /**
   * getDBInstance
   *
   * It will return DBInstance if exist. else create new.
   * It helps to maintain single instance of DB.
   * @param bool|false $xml_insert Set True If You using batch Insert.
   *
   * @return DB|null
   */
  public static function getDBInstance(){

    if (!isset(self::$_instance)) {
      self::$_instance = new DB();
    }

    return self::$_instance->_mysqli;

  }
  
  public static function mquery($query){
  	$q_obj = self::getDBInstance()->query($query);
  	
  	if(mysqli_error(self::getDBInstance())){
  		var_dump($query);
  		p(mysqli_error(self::getDBInstance()));

  		response('Query error');
  	}
  	return $q_obj;
  }
  
  public static function id(){
  	return DB::getDBInstance()->insert_id;
  }
  
  public static function insertData($table, $data_array){
  	$columns_arr = array_keys($data_array);
  	$columns_csv = implode(',', $columns_arr);
  	
  	$values_arr = [];
  	foreach($data_array as $val){
  		if(is_numeric($val)){
  			$values_arr[] = $val;
  		}
  		else{
  			$val = clean($val);
  			$values_arr[] = "'$val'";
  		}
  	}
  	$values_csv = implode(',', $values_arr);
  	$q_obj = self::mquery("insert into $table ($columns_csv) values($values_csv)");
  	
  	return $q_obj;
  }
  
  public static function insertBatch($table, $data_array){
  
  	if(!$data_array){
  		display('Empty batch provided');
  		return false;
  	}
  	
 	 	$columns_arr = array_keys($data_array[0]);
  	$columns_csv = implode(',', $columns_arr);
  	$values_csv = [];
  	
  	foreach($data_array as $data2_array){
			$values_arr = [];
			foreach($data2_array as $val){
				if(is_numeric($val)){
					$values_arr[] = $val;
				}
				else{
					$val = clean($val);
					$values_arr[] = "'$val'";
				}
			}
			$values_csv[] = '('.implode(',', $values_arr).')';
  	}
  	
  	if(!empty($values_csv)){
  		$values_csv = implode(',', $values_csv);
	  	$q_obj = self::mquery("insert into $table ($columns_csv) values $values_csv");
  		return $q_obj;
  	}
  	else{
  		display('Empty CSV values found');
  	}
  	
  }
  
  /**
   * To get proper array of key=>value from fetch_Array
   *
   */
  public static function fetch($conn){
  	$data = array();
  	$array = $conn->fetch_array();
  	if(!empty($array)){
			foreach($array as $k => $v){
				if(!is_numeric($k)){
					$data[$k] = $v;
				}
			}
			return $data;
  	}
  	else{
  		return false;
  	}
  }

  /**
   * To get proper array of key=>value from fetch_Array
   *
   */
  public static function fetchAll($conn, $non_numeric = false){
  	$data = array();
  	$k = 0;
  	while($array = $conn->fetch_array()){
  		
  		if($non_numeric){
				foreach($array as $vk => $u){
					if(!is_numeric($vk)){
						$data[$k][$vk] = $u;
					}
				
				}
  		}
  		else{
  			$data[] = $array;
			}
			
			$k++;
					
  	}
  	return $data;
  	
  }

  /**
   * To get count using fetch_Array
   *
   */
  public static function fetchAllCount($conn){
  	$i = 0;
  	while($array = $conn->fetch_array()){
  		$i++;
  	}
  	return $i;
  	
  }

 	public static function update($table, $data, $where){
 	
 		$data_query = array();
 		foreach($data as $k=>$v){
 			if(is_numeric($v)){
 				$data_query[] = "$k = $v";
 			}
 			else{
 				$v = clean($v);
 				$data_query[] = "$k = '$v'";
 			}
 		}
 		$data_query = implode(', ', $data_query);

 		$where_query = array();
 		foreach($where as $k=>$v){
 			if(is_numeric($k)){
 				response("Where clause cannot be integer; k: $k; value: $v");
 			}
 			
 			if(is_numeric($v)){
 				$where_query[] = "$k=$v";
 			}
 			else{
 				$v = clean($v);
 				$where_query[] = "$k = '$v'";
 			}
 		}
 		$where_query = implode(' and ', $where_query);

 		
 		if(empty($where_query)){
 			response('Where-query variable cannot be empty.');		
 		}
 		if(empty($data_query)){
 			response('Data-query variable cannot be empty.');		
 		}
 		
 		$final_query = "update $table set $data_query where $where_query ";
 		
 		self::mquery($final_query);
 		
 	} 	
}