<?php
	include("connect.php");
	$stmt = $conn->prepare("SELECT * FROM itoss_form
    INNER JOIN itoss_user ON itoss_form.User_id = itoss_user.User_id 
    INNER JOIN itoss_status_form ON itoss_form.Status_form_id = itoss_status_form.Status_form_id
    WHERE Form_id = ?");
    $stmt->bindParam(1, $Form_id);
    $stmt->execute();
    $row = $stmt->fetch();
	$UserName = $row['User_Name'];
	$UserJop = $row['User_Jop'];
	$Status = $row['Status_form_name'];

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	date_default_timezone_set("Asia/Bangkok");

	$host_name = "http://itservice.vpservice-online.com";
	//$sToken = "hNOcAJ5xaPHnoyn2Q7Feu1KEDNS5Q2BRcdGzw2wQaUC";
	//$sMessage = "\n$R_address";

	if( $row['Status_form_id'] == 5){
		$sToken = "ywRtDO72nKeXNqnAGPI6wBAgfiC0tVTIGbQDi3vqfkD";
		$sMessage = "ของ : $UserName ตำแหน่งงาน $UserJop\nสถานะ : $Status\n$host_name/create_report.php?Form_id=$Form_id";
	}
	else if( $row['Status_form_id'] == 4){
		$sToken = "ywRtDO72nKeXNqnAGPI6wBAgfiC0tVTIGbQDi3vqfkD";
		$sMessage = "ของ : $UserName ตำแหน่งงาน $UserJop\nสถานะ : $Status\n$host_name/requestUser.php?Form_id=$Form_id";
	}
	else if( $row['Status_form_id'] == 3){
		$sToken = "ywRtDO72nKeXNqnAGPI6wBAgfiC0tVTIGbQDi3vqfkD";
		$sMessage = "ของ : $UserName ตำแหน่งงาน $UserJop\nสถานะ : $Status\n$host_name/requestUser.php?Form_id=$Form_id&status_id=".$row['Status_form_id']."";
	}
	else if( $row['Status_form_id'] == 2){
		$sToken = "ywRtDO72nKeXNqnAGPI6wBAgfiC0tVTIGbQDi3vqfkD";
		$sMessage = "ของ : $UserName ตำแหน่งงาน $UserJop\nสถานะ : $Status\n$host_name/requestUser.php?Form_id=$Form_id";
	}
	else if( $row['Status_form_id'] == 1){
		$sToken = "ywRtDO72nKeXNqnAGPI6wBAgfiC0tVTIGbQDi3vqfkD";
		$sMessage = "มีฟอร์มใหม่ส่งเข้ามา จาก $UserName ตำแหน่งงาน $UserJop\nสถานะ : $Status\n$host_name/requestAdmin.php?Form_id=$Form_id";
	}
	else if( $row['Status_form_id'] == 6){
		$sToken = "ywRtDO72nKeXNqnAGPI6wBAgfiC0tVTIGbQDi3vqfkD";
		$sMessage = "มีฟอร์มใหม่ส่งเข้ามา จาก $UserName ตำแหน่งงาน $UserJop\nสถานะ : $Status\n$host_name/check_report.php?Form_id=$Form_id";
	}
	else if( $row['Status_form_id'] == 7){
		$sToken = "ywRtDO72nKeXNqnAGPI6wBAgfiC0tVTIGbQDi3vqfkD";
		$sMessage = "มีฟอร์มใหม่ส่งเข้ามา จาก $UserName ตำแหน่งงาน $UserJop\nสถานะ : $Status\n$host_name/check_report.php?Form_id=$Form_id";
	}
	else if( $row['Status_form_id'] == 8){
		$sToken = "ywRtDO72nKeXNqnAGPI6wBAgfiC0tVTIGbQDi3vqfkD";
		$sMessage = "ของ $UserName ตำแหน่งงาน $UserJop\nสถานะ : $Status\n$host_name/check_report.php?Form_id=$Form_id";
	}
	
	

	$chOne = curl_init(); 
	curl_setopt( $chOne, CURLOPT_URL, "https://notify-api.line.me/api/notify"); 
	curl_setopt( $chOne, CURLOPT_SSL_VERIFYHOST, 0); 
	curl_setopt( $chOne, CURLOPT_SSL_VERIFYPEER, 0); 
	curl_setopt( $chOne, CURLOPT_POST, 1); 
	curl_setopt( $chOne, CURLOPT_POSTFIELDS, "message=".$sMessage); 
	$headers = array( 'Content-type: application/x-www-form-urlencoded', 'Authorization: Bearer ', );//'.$sToken.'
	curl_setopt($chOne, CURLOPT_HTTPHEADER, $headers); 
	curl_setopt( $chOne, CURLOPT_RETURNTRANSFER, 1); 
	$result = curl_exec( $chOne ); 

	//Result error 
	if(curl_error($chOne)) 
	{ 
		echo 'error:' . curl_error($chOne); 
	} 
	else { 
		$result_ = json_decode($result, true); 
		echo '<script language="javascript">';
        echo 'location.href="indexUser.php';
        echo '</script>';
	} 
	curl_close( $chOne );   
?>