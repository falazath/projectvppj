<!DOCTYPE html>
<html lang="en">
<?php
session_start();
include('header.html');
include("connect.php");
if (!isset($_SESSION['id'])) {
    header('location:login.php');
}

$sql = $conn->query("SELECT * FROM itoss_jobtype;");
$jobChoice = $sql->fetchAll();
$Form_id = $_GET["Form_id"];

$stmt = $conn->prepare("SELECT * FROM itoss_form
    INNER JOIN itoss_user ON itoss_form.User_id = itoss_user.User_id 
    INNER JOIN itoss_status_form ON itoss_form.Status_form_id = itoss_status_form.Status_form_id
    INNER JOIN itoss_agency ON itoss_form.Agency_id = itoss_agency.Agency_id 
    WHERE Form_id = ?");
$stmt->bindParam(1, $Form_id);
$stmt->execute();
$row = $stmt->fetch();

$sql_job = $conn->query("SELECT itoss_job.Job_id,itoss_job.Jobtype_id,itoss_job.name_other,itoss_jobtype.Jobtype_name 
FROM itoss_job,itoss_jobtype WHERE itoss_job.Jobtype_id = itoss_jobtype.Jobtype_id AND itoss_job.Form_id = '$Form_id'");
$job = $sql_job->fetchAll();

$sql = $conn->query("SELECT * FROM other_agency WHERE Form_id = '$Form_id' ORDER BY id DESC LIMIT 1");
$data = $sql->fetch();
$agency = isset($data['name']) ? $data['name'] : $row['Agency_Name'];

$sqlAdmin = $conn->query("SELECT * FROM itoss_sign INNER JOIN itoss_user ON itoss_sign.User_id = itoss_user.User_id where itoss_sign.User_id = " . $row['assign_id'] . "");
$signAdmin = $sqlAdmin->fetch();

$stmt5 = $conn->query("SELECT * FROM itoss_report INNER JOIN itoss_sign ON itoss_report.Report_sign_client = itoss_sign.Sign_id where itoss_report.Form_id = '$Form_id' ORDER BY Report_id DESC");
$report = $stmt5->fetchAll();

$User_id = $_SESSION['id'];
$sql_user = $conn->query("SELECT * FROM itoss_sign INNER JOIN itoss_user ON itoss_sign.User_id = itoss_user.User_id where itoss_sign.User_id = " . $row['User_id'] . "");
$signUser = $sql_user->fetch();


$type = '';
for($i=0;$i<count($job);$i++){
	if($i != 0){
		$type .= '/';
	}
	if ($job[$i]['Jobtype_id'] == 0) {
		$type .= $job[$i]['name_other'];
	} else {
		$type .= $job[$i]['Jobtype_name'];
	}
}

?>
<style type="text/css">
  @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300&display=swap');

		html{
			font-family: 'Noto Sans Thai', sans-serif;
		}
		#body {
			position: fixed;
			width: 695px;

			background: #FFFFFF; }
		#VP{
			position: fixed;
			width: 141px;
			height: 30px;
			left: 31px;
			top: 23px;
		}
		h1{
			position: fixed;
			width: 334px;
			height: 24px;
			left: 250px;
			top: 54px;
			font-weight: 400;
			font-size: 20px;
			line-height: 24px;
			color: #000000;
		}
		h3{
			position: fixed;
			width: 194px;
			height: 17px;
			left: 550px;
			top: 110px;
			font-weight: 400;
			font-size: 14px;
			line-height: 17px;
			/* identical to box height */
			color: #000000;
		}
		#body1{
			box-sizing: border-box;
			position: fixed;
			width: 634px;
			height: 351px;
			left: 31px;
			top: 130px;
			background: #FFFFFF;
			border: 1px solid #000000;
		}
		#body2{
			box-sizing: border-box;
			position: fixed;
			width: 634px;
			height: 500px;
			left: 31px;
			top: 510px;
			background: #FFFFFF;
			border: 1px solid #000000;
		}
		p{
			height: 10px;
		}
		#Form_Name{
			position: fixed;
			width: 550px;
			height: 19px;
			left: 65px;
			top: 160px;
			font-weight: 400;
			font-size: 16px;
			color: #000000;
		}
		#Agency_Name{
			position: fixed;
			width: 550px;
			height: 19px;
			left: 335px;
			top: 160px;
			font-weight: 400;
			font-size: 16px;
			color: #000000;
		}
		#Form_Phone{
			position: fixed;
			width: 550px;
			height: 19px;
			left: 65px;
			top: 200px;
			font-weight: 400;
			font-size: 16px;
			color: #000000;
		}
		#Form_Type{
			position: fixed;
			width: 550px;
			height: 19px;
			left: 335px;
			top: 200px;
			font-weight: 400;
			font-size: 16px;
			color: #000000;
		}
		#Work{
			position: fixed;
			width: 269px;
			height: 19px;
			left: 65px;
			top: 240px;
			font-weight: 400;
			font-size: 16px;
			color: #000000;
		}
		#Form_Work{
			position: fixed;
			width: 100%;
			height: 100px;
			left: 94px;
			top: 270px;
			font-weight: 400;
			font-size: 16px;
			color: #000000;
		}
		#sign_admin{
			position: fixed;
			width: 120px;
			left: 520px;
			top: 370px;
		}
		#name_admin{
			position: fixed;
			width: 205px;
			height: 19px;
			left: 530px;
			top: 430px;
			font-weight: 400;
			font-size: 16px;
			color: #000000;
		}
		#Report{
			position: fixed;
			width: 550px;
			height: 19px;
			left: 64px;
			top: 540px;
			font-weight: 400;
			font-size: 16px;
			color: #000000;
		}
		#Report_detail{
			position: fixed;
			width: 100%;
			height: 100px;
			left: 97px;
			top: 570px;
			font-weight: 400;
			font-size: 16px;
			color: #000000;
		}
		#Time{
			position: fixed;
			width: 550px;
			height: 19px;
			left: 65px;
			top: 695px;
			font-weight: 400;
			font-size: 16px;
			color: #000000;
		}
		#Start{
			position: fixed;
			width: 550px;
			height: 19px;
			left: 97px;
			top: 725px;
			font-weight: 400;
			font-size: 16px;
			color: #000000;
		}
		#Stop{
			position: fixed;
			width: 550px;
			height: 19px;
			left: 361px;
			top: 725px;
			font-weight: 400;
			font-size: 16px;
			color: #000000;
		}
		#Status{
			position: fixed;
			width: 550px;
			height: 19px;
			left: 65px;
			top: 775px;
			font-weight: 400;
			font-size: 16px;
			color: #000000;
		}
		#sign_User{
			position: fixed;
			width: 120px;
			left: 115px;
			top: 830px;
		}
		#Date_User{
			position: fixed;
			width: 550px;
			height: 19px;
			left: 115px;
			top: 890px;
			font-weight: 400;
			font-size: 16px;
			color: #000000;
		}
		#name_User{
			position: fixed;
			width: 550px;
			height: 19px;
			left: 135px;
			top: 910px;
			font-weight: 400;
			font-size: 16px;
			color: #000000;
		}
		#sign_client{
			position: fixed;
			width: 120px;
			left: 480px;
			top: 830px;
		}
		#Date_client{
			position: fixed;
			width: 550px;
			height: 19px;
			left: 475px;
			top: 890px;
			font-weight: 400;
			font-size: 16px;
			color: #000000;
		}
		#name_client{
			position: fixed;
			width: 530px;
			height: 19px;
			left: 505px;
			top: 910px;
			font-weight: 400;
			font-size: 16px;
			color: #000000;
		}
		#sign_IT{
			position: fixed;
			width: 120px;
			left: 290px;
			top: 895px;
		}
		#name_IT{
			position: fixed;
			width: 550px;
			height: 19px;
			left: 260px;
			top: 950px;
			font-weight: 400;
			font-size: 16px;
			color: #000000;
		}
		@media print{
		#hid{
		   display: none; /* ซ่อน  */
		}
		}
		#hid{
			position: fixed;
			width: 60px;
			height: 40px;
			left: 610px;
			top: 23px;
			font-size: 18px;
		}
  	</style>
<body>
<button id="hid" onclick="window.print();" class="btn btn-primary"> พิมพ์ </button>
	<div id="body">
	<img src="./asset/Logo/VP.svg" id="VP" alt="">
	<h1><b>บันทึกการปฏิบัติงานของไอที</b></h1>
	<?php $Year_form = date("Y",strtotime($row["Form_date"]));
		  $Form_date = $Year_form + 543;?>
	<h3><b>วันที่  <?=date("d/m/$Form_date",strtotime($row["Form_date"]))?></b></h3>
	<div id="body1">
		<p id="Form_Name"><b>ชื่อผู้ติดต่อ :</b> <?=$row["Form_Name"]?></p>
		<p id="Agency_Name"><b>หน่วยงาน :</b> <?=$row["Agency_Name"]?></p>
		<p id="Form_Phone"><b>เบอร์โทรศัพท์ :</b> <?=$row["Form_Phone"]?></p>
		<p id="Form_Type"><b>ประเภทงาน :</b> <?=$type?></p>
		<p id="Work"><b>รายละเอียดการปฏิบัติงาน</b></p>
		<div id="Form_Work">
			<?=$row["Form_Work"]?>
		<div>
		<img src="data:<?=$signAdmin['Sign_image']?>" id="sign_admin" alt="">
		<p id="name_admin"><b>ผู้มอบหมายงาน</b></p>
	</div>
	<?php for ($i = count($report) - 1; $i >= 0; $i--) {?>
	<div id="body2">
		<p id="Report"><b>รายละเอียดการปฏิบัติงาน</b></p>
		<div id="Report_detail">
			<?= $report[$i]['Report_Detail'] ?>
		<div>
		<p id="Time"><b>เวลาดำเนินงาน</b></p>
		<?php $Year_Start_Date = date("Y",strtotime($report[$i]['Report_Start_Date']));
		  $Report_Start_Date = $Year_Start_Date + 543;?>
		<p id="Start"><b>เริ่ม :</b>วันที่ <?=date("d/m/$Report_Start_Date เวลา H:i น.",strtotime($report[$i]['Report_Start_Date']))?></p>
		<?php $Year_Stop_Date = date("Y",strtotime($report[$i]['Report_Stop_Date']));
		  $Report_Stop_Date = $Year_Stop_Date + 543;?>
		<p id="Stop"><b>เสร็จสิ้น :</b>วันที่ <?=date("d/m/$Report_Stop_Date เวลา H:i น.",strtotime($report[$i]['Report_Stop_Date']))?></p>
		 <?php if ($report[$i]['Report_Status'] == 6) { ?>
		<p id="Status"><b>สถานะงาน :</b> ติดตามงาน</p>
		<?php }else if ($report[$i]['Report_Status'] == 7) { ?>
		<p id="Status"><b>สถานะงาน :</b> ปิดงาน</p>
		<?php } ?>
		<img src="data:<?=$signUser['Sign_image']?>" id="sign_User" alt="">
		<?php $Year_date_user = date("Y",strtotime($report[$i]['Report_date_user']));
		  $Report_date_user = $Year_date_user + 543;?>
		<p id="Date_User"><b>วันที่ <?=date("d/m/$Report_date_user",strtotime($report[$i]['Report_date_user']))?></b></p>
		<p id="name_User"><b>เจ้าหน้าทีไอที</b></p>
		<img src="data:<?=$report[$i]['Sign_image']?>" id="sign_client" alt="">
		<?php $Year_date_client = date("Y",strtotime($report[$i]['Report_date_client']));
		  $Report_date_client = $Year_date_client + 543;?>
		<p id="Date_client"><b>วันที่ <?=date("d/m/$Report_date_client",strtotime($report[$i]['Report_date_client']))?></b></p>
		<p id="name_client"><b>ผู้ใช้บริการ</b></p>
		<img src="data:<?=$signAdmin['Sign_image']?>" id="sign_IT" alt="">
		<p id="name_IT"><b>หัวหน้าเทคโนโลยีสารสนเทศ</b></p>
	</div>
	<?php }?>
	</div>
</body>
</html>