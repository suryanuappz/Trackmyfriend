<?php 

function get_price($find){
	 
	 $host="localhost";
	 $username="root";
	 $pass="";
	 $database ="testphp";
	 $con=mysqli_connect($host,$username,$pass,$database);
	
	$sql=mysqli_query($con,"SELECT * FROM users");
	
	while($row = mysqli_fetch_array($sql,MYSQL_ASSOC))
	{
		if($find != ""){
			if($row['user_id']==$find)
			{
				return $row;
				break;
			}
		}else{
			return $row;
			break;
		}
	}


}
	?>