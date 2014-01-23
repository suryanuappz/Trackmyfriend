<?php
    
	require_once("Rest.inc.php");
	
	class API extends REST {
	
		public $data = "";
		
		const DB_SERVER = "localhost";
		const DB_USER = "root";
		const DB_PASSWORD = "";
		const DB = "testphp";
		
		private $db = NULL;
	
		public function __construct(){
			parent::__construct();				// Init parent contructor
			$this->dbConnect();					// Initiate Database connection
		}
		
		/*
		 *  Database connection 
		*/
		private function dbConnect(){
			$this->db = mysql_connect(self::DB_SERVER,self::DB_USER,self::DB_PASSWORD);
			if($this->db)
				mysql_select_db(self::DB,$this->db);
		}
		
		/*
		 * Public method for access api.
		 * This method dynmically call the method based on the query string
		 *
		 */
		public function processApi(){
			$func = strtolower(trim(str_replace("/","",$_REQUEST['/']))); // u can to pass some URL request along with the URL and make sure the variable name
			if((int)method_exists($this,$func) > 0)
				$this->$func();
			else
				$this->response('',404);				// If the method not exist with in this class, response would be "Page not found".
		}
		
		/* 
		 *	Simple login API
		 *  Login must be POST method
		 *  email : <USER EMAIL>
		 *  pwd : <USER PASSWORD>
		 */
		
				
		private function users(){	
			// Cross validation if the request method is GET else it will return "Not Acceptable" status
			
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
		$sql = mysql_query("SELECT user_id, user_fullname, user_email FROM users  ", $this->db);
			if(mysql_num_rows($sql) > 0){
				$result = array();
				while($rlt = mysql_fetch_array($sql,MYSQL_ASSOC)){
					$result[] = $rlt;
					
				}
				
				
				// If success everythig is good send header as "OK" and return list of users in JSON format
				$this->response($this->json($result), 200);
				
			}
			$this->response('',204);	// If no records "No Content" status
		}
		
		private function user($find){
			
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
		$sql = mysql_query("SELECT user_id, user_fullname, user_email FROM users where user_id=$find  ", $this->db);
			if(mysql_num_rows($sql) > 0){
				$result = array();
				while($rlt = mysql_fetch_array($sql,MYSQL_ASSOC)){
					$result[] = $rlt;
					
				}
				
				
				// If success everythig is good send header as "OK" and return list of users in JSON format
				$this->response($this->json($result), 200);
				
			}
			$this->response('',204);
			
			}
		
		private function download(){//this code for download the code in csv format
			$table = 'users';
$filename = tempnam(sys_get_temp_dir(), "csv");
$conn = mysql_connect("localhost", "root", "");
mysql_select_db("testphp",$conn);
$file = fopen($filename,"w");
// Write column names
$result = mysql_query("show columns from $table",$conn);
for ($i = 0; $i < mysql_num_rows($result); $i++) {
    $colArray[$i] = mysql_fetch_assoc($result);
    $fieldArray[$i] = $colArray[$i]['Field'];
}
fputcsv($file,$fieldArray);
// Write data rows
$result = mysql_query("select * from $table",$conn);
for ($i = 0; $i < mysql_num_rows($result); $i++) {
    $dataArray[$i] = mysql_fetch_assoc($result);
}
foreach ($dataArray as $line) {
    fputcsv($file,$line);
}

fclose($file);

header("Content-Type: application/csv");
header("Content-Disposition: attachment;Filename=users.csv");

// send file to browser
readfile($filename);
unlink($filename);
}
		private function active()
		{
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
		$sql = mysql_query("SELECT user_id, user_fullname, user_email FROM users WHERE user_status=1", $this->db);
			if(mysql_num_rows($sql) > 0){
				$result = array();
				while($rlt = mysql_fetch_array($sql,MYSQL_ASSOC)){
					$result[] = $rlt;
					
				}
				
				
				// If success everythig is good send header as "OK" and return list of users in JSON format
				$this->response($this->json($result), 200);
				
			}
			$this->response('',204);
			
		}
		//the newuserclassused to find what are the new user registed in this curresponding month
		private function newusermonthly()
		{
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
		$sql = mysql_query("SELECT * from sample_login where (SELECT extract(MONTH FROM created_on ))", $this->db);
			if(mysql_num_rows($sql) > 0){
				$result = array();
				while($rlt = mysql_fetch_array($sql,MYSQL_ASSOC)){
					$result[] = $rlt;
					
				}
				
				
				// If success everythig is good send header as "OK" and return list of users in JSON format
				$this->response($this->json($result), 200);
				
			}
			$this->response('',204);
			}
			//success function used to what are the find end with success
		
	private function reportsuccess()
	{
		
	}
	private function reportfail()
	{
		
	}
		/*
		 *	Encode array into JSON
		*/
		private function json($data){
			if(is_array($data)){
				return json_encode($data);
			}
		}
	}
	
	// Initiiate Library
	
	$api = new API;
	$api->processApi();
?>