<?php
 $request = $_SERVER['REQUEST_URI'];
 header("Contact-Type:application/json");
 include('function.php');

	 
if(!empty($_GET['name']))
{
	$name=$_GET['name'];
	$price=get_price($name);
	
	if(empty($price))
	{
	//book no find
	deliver_response(200,"book not found",NULL);
	}
	else
	{
	//respond book price
	deliver_response($price);
	}
}
else
{
	$name="";
	$price=get_price($name);
		if(empty($price))
	{
	//book no find
	deliver_response(200,"book not found",NULL);
	}
	else
	{
	//respond book price
	deliver_response(200,"book",$price);
	}
	//deliver_response(400,"INVALID REQUEST",NULL);
}
function deliver_response($data)
{
	//header("HTTP/1.1 $status $status_message");
	//$response['status']=$status;
	//$response['status_message']=$status_message;
	$response['data']=$data;
	
	$json_response=json_encode($response);
	echo $json_response;
}
?>