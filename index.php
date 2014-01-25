<?php
/*devleoper:surya
company:NUAPPZ.com
this index.php have client process of restful webservices of php
main aim to develop the code for to create a admin page for TRACK FRIEND APPLICATION
BULIDING WITH ANGULAR JS AND PHP RESTFUL WEBSERVICES*/
    
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
		private function download(){//this code for download the code in csv format
			$table = 'sample_login';
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
		private function active(){
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$sql = mysql_query("SELECT name, phone_no FROM sample_login WHERE login_status=true", $this->db);
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
		private function findnew(){
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$startdate=2014-01-01;
			$enddate="2014-01-22";
			$sql = mysql_query("SELECT *FROM `sample_login`WHERE date( created_on )BETWEEN '$startdate'AND '$enddate'", $this->db);
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
		private function newuseryearly(){
			if($this->get_request_method() != "GET"){
				$this->response('',406);
				}
			$year="2014";
			$startdate=2014-01-01;
			$enddate="2014-01-22";
			$sql = mysql_query("SELECT *FROM `sample_login`WHERE date( created_on )BETWEEN '$startdate'AND '$enddate'", $this->db);
			if(mysql_num_rows($sql) > 0){
				$result = array();
				while($rlt = mysql_fetch_array($sql,MYSQL_ASSOC)){
					$result[] = $rlt;
				}
				$this->response($this->json($result), 200);		
				}
			$this->response('',204);
			}
		private function permonth(){
			$year="2014";
			$month="02";
			switch($month){ 
				case "01":
					$startdate=$year."-".$month."-01";
					$enddate=$year."-".$month."-31";	
					$sql = mysql_query("SELECT *FROM `sample_login`WHERE date( created_on )BETWEEN '$startdate'AND '$enddate'", $this->db);
			if(mysql_num_rows($sql) > 0){
				$result = array();
				while($rlt = mysql_fetch_array($sql,MYSQL_ASSOC)){
					$result[] = $rlt;
				}
				$this->response($this->json($result), 200);
				}
				$this->response('',204);
					break;
				case "02":
				  $leepyear=$year % 4;
				  if($leepyear == 0){
					$startdate=$year."-".$month."-01";
					$enddate=$year."-".$month."-29";
						
							  $sql = mysql_query("SELECT *FROM `sample_login`WHERE date( created_on )BETWEEN '$startdate'AND '$enddate'", $this->db);
			if(mysql_num_rows($sql) > 0){
				$result = array();
				while($rlt = mysql_fetch_array($sql,MYSQL_ASSOC)){
					$result[] = $rlt;
				}
				$this->response($this->json($result), 200);
				}
			$this->response('',204);
				}
				else{
				$startdate=$year."-".$month."-01";
				$enddate=$year."-".$month."-28";
				$sql = mysql_query("SELECT *FROM `sample_login`WHERE date( created_on )BETWEEN '$startdate'AND '$enddate'", $this->db);
			if(mysql_num_rows($sql) > 0){
				$result = array();
				while($rlt = mysql_fetch_array($sql,MYSQL_ASSOC)){
					$result[] = $rlt;
				}
				$this->response($this->json($result), 200);
				}
			$this->response('',204);
				}
				break;
					case "03":
					$startdate=$year."-".$month."-01";
					$enddate=$year."-".$month."-31";	
					
					$sql = mysql_query("SELECT *FROM `sample_login`WHERE date( created_on )BETWEEN '$startdate'AND '$enddate'", $this->db);
			if(mysql_num_rows($sql) > 0){
				$result = array();
				while($rlt = mysql_fetch_array($sql,MYSQL_ASSOC)){
				$result[] = $rlt;
				}
				$this->response($this->json($result), 200);
				}
			$this->response('',204);
					break;
					case "04":
					$startdate=$year."-".$month."-01";
					$enddate=$year."-".$month."-30";	
					$sql = mysql_query("SELECT *FROM `sample_login`WHERE date( created_on )BETWEEN '$startdate'AND '$enddate'", $this->db);
			if(mysql_num_rows($sql) > 0){
				$result = array();
				while($rlt = mysql_fetch_array($sql,MYSQL_ASSOC)){
					$result[] = $rlt;
				}
				$this->response($this->json($result), 200);
				}
			$this->response('',204);
					break;
					case "05":
					$startdate=$year."-".$month."-01";
					$enddate=$year."-".$month."-31";	
					$sql = mysql_query("SELECT *FROM `sample_login`WHERE date( created_on )BETWEEN '$startdate'AND '$enddate'", $this->db);
			if(mysql_num_rows($sql) > 0){
				$result = array();
				while($rlt = mysql_fetch_array($sql,MYSQL_ASSOC)){
					$result[] = $rlt;
				}
				$this->response($this->json($result), 200);
				}
			$this->response('',204);
					break;
					case "06":
					$startdate=$year."-".$month."-01";
					$enddate=$year."-".$month."-30";	
					$sql = mysql_query("SELECT *FROM `sample_login`WHERE date( created_on )BETWEEN '$startdate'AND '$enddate'", $this->db);
			if(mysql_num_rows($sql) > 0){
				$result = array();
				while($rlt = mysql_fetch_array($sql,MYSQL_ASSOC)){
					$result[] = $rlt;
				}
			$this->response($this->json($result), 200);
				}
			$this->response('',204);
					break;
					case "07":
					$startdate=$year."-".$month."-01";
					$enddate=$year."-".$month."-31";	
					
					$sql = mysql_query("SELECT *FROM `sample_login`WHERE date( created_on )BETWEEN '$startdate'AND '$enddate'", $this->db);
			if(mysql_num_rows($sql) > 0){
				$result = array();
				while($rlt = mysql_fetch_array($sql,MYSQL_ASSOC)){
					$result[] = $rlt;
				}
			$this->response($this->json($result), 200);
				}
			$this->response('',204);
					break;
					case "08":
					$startdate=$year."-".$month."-01";
					$enddate=$year."-".$month."-31";	
					$sql = mysql_query("SELECT *FROM `sample_login`WHERE date( created_on )BETWEEN '$startdate'AND '$enddate'", $this->db);
			if(mysql_num_rows($sql) > 0){
				$result = array();
				while($rlt = mysql_fetch_array($sql,MYSQL_ASSOC)){
					$result[] = $rlt;
				}
				$this->response($this->json($result), 200);
				}
			$this->response('',204);
					break;
					case "09":
					$startdate=$year."-".$month."-01";
					$enddate=$year."-".$month."-30";	
					$sql = mysql_query("SELECT *FROM `sample_login`WHERE date( created_on )BETWEEN '$startdate'AND '$enddate'", $this->db);
			if(mysql_num_rows($sql) > 0){
				$result = array();
				while($rlt = mysql_fetch_array($sql,MYSQL_ASSOC)){
					$result[] = $rlt;
				}
				$this->response($this->json($result), 200);
				}
			$this->response('',204);
					break;
					case "10":
					$startdate=$year."-".$month."-01";
					$enddate=$year."-".$month."-31";	
					$sql = mysql_query("SELECT *FROM `sample_login`WHERE date( created_on )BETWEEN '$startdate'AND '$enddate'", $this->db);
			if(mysql_num_rows($sql) > 0){
				$result = array();
				while($rlt = mysql_fetch_array($sql,MYSQL_ASSOC)){
					$result[] = $rlt;
				}
				$this->response($this->json($result), 200);
				}
			$this->response('',204);
					break;
					case "11":
					$startdate=$year."-".$month."-01";
					$enddate=$year."-".$month."-30";	
					$sql = mysql_query("SELECT *FROM `sample_login`WHERE date( created_on )BETWEEN '$startdate'AND '$enddate'", $this->db);
			if(mysql_num_rows($sql) > 0){
				$result = array();
				while($rlt = mysql_fetch_array($sql,MYSQL_ASSOC)){
					$result[] = $rlt;
				}
				$this->response($this->json($result), 200);
				}
			$this->response('',204);
					break;
					case "12":
					$startdate=$year."-".$month."-01";
					$enddate=$year."-".$month."-31";	
					$sql = mysql_query("SELECT *FROM `sample_login`WHERE date( created_on )BETWEEN '$startdate'AND '$enddate'", $this->db);
			if(mysql_num_rows($sql) > 0){
				$result = array();
				while($rlt = mysql_fetch_array($sql,MYSQL_ASSOC)){
					$result[] = $rlt;
				}
				$this->response($this->json($result), 200);
				}
			$this->response('',204);
					break;
					}
					}
			private function mostsharedlocation(){
				//most shared location used for find wich place was mostly shared from users list
				if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$location="";
			$sql = mysql_query("query inserted for based on locations", $this->db);
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
		private function comparemonth()
				{
					$year="2014";
					$startmonth="01";
					$endmonth="03";
					$test=$endmonth-$startmonth;
					$case="0".$test;
					
					switch($case)
					{
						case "01":
									$startdatefirstmonth=$year."-".$startmonth."-01";
									$enddatefirstmonth=$year."-".$startmonth."-31";	
									$startdatesecmonth=$year."-".$endmonth."-01";
									$enddatesecmonth=$year."-".$endmonth."-31";
									$sql = mysql_query("SELECT count(*)FROM `sample_login`WHERE date( created_on )BETWEEN '$startdatefirstmonth'AND '$enddatefirstmonth'", $this->db);
									if(mysql_num_rows($sql) > 0){
									$result = array();
									while($rlt = mysql_fetch_array($sql,MYSQL_ASSOC)){
									$result[] = $rlt;
									}
				
									$sql = mysql_query("SELECT count(*)FROM `sample_login`WHERE date( created_on )BETWEEN '$startdatesecmonth'AND '$enddatesecmonth'", $this->db);
									if(mysql_num_rows($sql) > 0){
				
									while($rltr = mysql_fetch_array($sql,MYSQL_ASSOC)){
									$result[] = $rltr;
									}
									}
									$this->response($this->json($result), 200);
									}
									$this->response('',204);
									break;
						case "02":
						if($startmonth <10){
									$secmonth= $endmonth - 1;
									$sec="0".$secmonth;
									}
									else
									{
										$secmonth= $endmonth - 1;
										$sec=$secmonth;
									}
									
									
									$startdatefirstmonth=$year."-".$startmonth."-01";
									$enddatefirstmonth=$year."-".$startmonth."-31";	
									$startdatesecmonth=$year."-".$endmonth."-01";
									$enddatesecmonth=$year."-".$endmonth."-31";
									$startdatethirdmonth=$year."-".$sec."-01";
									$enddatethirdmonth=$year."-".$sec."-31";
									$sql = mysql_query("SELECT count(*)FROM `sample_login`WHERE date( created_on )BETWEEN '$startdatefirstmonth'AND '$enddatefirstmonth'", $this->db);
									if(mysql_num_rows($sql) > 0){
									$result = array();
									while($rlt = mysql_fetch_array($sql,MYSQL_ASSOC)){
									$result[] = $rlt;
									}}
				
									$sql = mysql_query("SELECT count(*)FROM `sample_login`WHERE date( created_on )BETWEEN '$startdatethirdmonth'AND '$enddatethirdmonth'", $this->db);
									if(mysql_num_rows($sql) > 0){
				
									while($rltr = mysql_fetch_array($sql,MYSQL_ASSOC)){
									$result[] = $rltr;
									}
									}
									
									$sql = mysql_query("SELECT count(*)FROM `sample_login`WHERE date( created_on )BETWEEN '$startdatesecmonth'AND '$enddatesecmonth'", $this->db);
									if(mysql_num_rows($sql) > 0){
				
									while($rltr = mysql_fetch_array($sql,MYSQL_ASSOC)){
									$result[] = $rltr;
									}
									
									$this->response($this->json($result), 200);
									}
									$this->response('',204);
									
									break;
						
					}
					
				}
				
				private function param()
				{
					$id="";
					if($id == NULL)
					{
						echo "null";
					}
					else
					{
						echo $id;
					}
				}
					
			
			//users function used to what are the find end with users
		
	private function users()
	{				
					
				if($this->get_request_method() != "GET"){
					if($this->get_request_method() != "PUT"){
						if($this->get_request_method() != "POST"){
							//$id=$_GET['id'];
							$id=987456135225;
						$sql = mysql_query("DELETE FROM sample_login WHERE phone_no=$id", $this->db)or die(mysql_error());
						
						}
					else{
						//thie else port for update the data for post data request methode
						$phonenum="9786445186";
						$newname="pragash";						
					$sql = mysql_query("UPDATE sample_login SET name =  '$newname' WHERE phone_no =  '$phonenum'", $this->db);
			
				 $sql = mysql_query("SELECT * FROM `sample_login` WHERE phone_no = $phonenum ", $this->db);
			if(mysql_num_rows($sql) > 0){
				$result = array();
				while($rlt = mysql_fetch_array($sql,MYSQL_ASSOC)){
					$result[] = $rlt;
					
				}
				
				
				
				$this->response($this->json($result), 200);
				
			}
			
					//echo "postmethode";
						}}else{
							//thie else port for insert the data for put data request methode
							$name="raja";
							$phonenumer="012233445566";
							$pass="dd";
							//put methode for insert  the data
							$sql = mysql_query("INSERT INTO `testphp`.`sample_login` (`name`, `phone_no`, `pwd`, `register_status`, `created_on`, `u_id`, `login_status`) VALUES ('$name', '$phonenumer', MD5('$pass'), '1', CURRENT_TIMESTAMP, 'uido5', 'true');", $this->db);
										 $sql = mysql_query("SELECT * FROM `sample_login` WHERE phone_no = $phonenumer", $this->db);
			if(mysql_num_rows($sql) > 0){
				$result = array();
				while($rlt = mysql_fetch_array($sql,MYSQL_ASSOC)){
					$result[] = $rlt;
					
				}
				
				
				
				$this->response($this->json($result), 200);
				
			}
						
							//echo "putmethode";
							}
					}
					else{
						//select the value from the database using get methode
						$id=""; 
						if($id ==NULL)
						{
					$sql = mysql_query("SELECT name, phone_no FROM sample_login ", $this->db);
			if(mysql_num_rows($sql) > 0){
				$result = array();
				while($rlt = mysql_fetch_array($sql,MYSQL_ASSOC)){
					$result[] = $rlt;
					
				}
				$this->response($this->json($result), 200);
				
			}
						}
						else
						{
										 $sql = mysql_query("SELECT name, phone_no FROM sample_login WHERE phone_no=$id", $this->db);
			if(mysql_num_rows($sql) > 0){
				$result = array();
				while($rlt = mysql_fetch_array($sql,MYSQL_ASSOC)){
					$result[] = $rlt;
					
				}
				$this->response($this->json($result), 200);
				
			}
						}
					
					}
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