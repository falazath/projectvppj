<?php
session_start();
include('header.html');
?>

<body>

	<style>
		@media print {
			#hid {
				display: none;
				/* ซ่อน  */
			}
		}

		#hid {
			font-size: 18px;
		}

		textarea {
			resize: none;
		}

		.detail p {
			margin-bottom: 0;
		}
	</style>

	<?php
	include('connect.php');
	if (!isset($_SESSION['id'])) {
		header('location:index.php');
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
	for ($i = 0; $i < count($job); $i++) {
		if ($i != 0) {
			$type .= '/';
		}
		if ($job[$i]['Jobtype_id'] == 0) {
			$type .= $job[$i]['name_other'];
		} else {
			$type .= $job[$i]['Jobtype_name'];
		}
	}
	function convertDate($date)
	{
		$dd = date('d', strtotime($date));
		$mm = date('m', strtotime($date));
		$yy = date('Y', strtotime($date));
		switch ($mm) {
			case 1:
				$mm = "มกราคม";
				break;
			case 2:
				$mm = "กุมพาพันธ์";
				break;
			case 3:
				$mm = "มีนาคม";
				break;
			case 4:
				$mm = "เมษายน";
				break;
			case 5:
				$mm = "พฤษภาคม";
				break;
			case 6:
				$mm = "มิถุนายน";
				break;
			case 7:
				$mm = "กรกฎาคม";
				break;
			case 8:
				$mm = "สิงหาคม";
				break;
			case 9:
				$mm = "กันยายน";
				break;
			case 10:
				$mm = "ตุลาคม";
				break;
			case 11:
				$mm = "พฤศจิกายน";
				break;
			case 12:
				$mm = "ธันวาคม";
				break;
		}
		$date = $dd . " " . $mm . " " . ($yy + 543);
		return $date;
	}
	?>
	<main class="bg-white">
		<div class="container">
			<div class="row mt-0">
				<div class="col-auto me-auto">
					<img src="./asset/Logo/VP.svg" alt="">
				</div>
				<div class="col-auto">
					<button button id="hid" onclick="window.print();" class="btn btn-primary"> พิมพ์ </button>
				</div>
			</div>
			<div class="row my-2">
				<div class="col">
					<p class="fhead text-center fw-bold mb-0">บันทึกการปฏิบัติงานของไอที</p>
				</div>
			</div>
			<div class="row">
				<div class="col-auto ms-auto me-3">
					<p class="fpr text-end mb-0">วันที่ <?= convertDate($row["Form_date"]) ?></p>
				</div>
			</div>
			<div class="row border border-2 border-dark mx-3">
				<div class="col">
					<div class="row mt-3">
						<div class="col-6">
							<p class="fpr"><b> ชื่อผู้ติดต่อ : </b><?= $row["Form_Name"] ?></p>
						</div>
						<div class="col-6">
							<p class="fpr"><b>หน่วยงาน : </b><?= $agency ?></p>
						</div>
					</div>
					<div class="row">
						<div class="col-6">
							<p class="fpr"><b>เบอร์โทรศัพท์ :</b> <?= $row["Form_Phone"] ?></p>
						</div>
						<div class="col-6">
							<p class="fpr"><b>ประเภทงาน :</b> <?= $type ?></p>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<p class="fpr mb-0"><b>รายละเอียดการปฏิบัติงาน :</b></p>
						</div>
					</div>
					<div class="row">
						<div class="col-11 ms-3 detail">
							<p>wwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwww</p>
							<?= $row["Form_Work"] ?>
							<?= $row["Form_Work"] ?>
							<?= $row["Form_Work"] ?>
							<?= $row["Form_Work"] ?>
						</div>

					</div>
					<div class="row">
						<div class="col-4 ms-auto">
							<div class="row">
								<div class="col">
									<img class="d-block w-100 h-auto float-end" src="data:<?= $signAdmin['Sign_image'] ?>" id="sign_admin" alt="">
								</div>
							</div>
							<div class="row">
								<div class="col-12">
									<p class="fpr text-center me-4">ผู้มอบหมายงาน</p>
								</div>

							</div>

						</div>
					</div>
				</div>
			</div>
			<?php
			for ($i = count($report) - 1; $i >= 0; $i--) {
			?>

				<div class="row border border-2 border-dark mx-3 mt-4">
					<div class="col">
						<div class="row">
							<div class="col">
								<p class="fpr fw-bold mb-0">รายละเอียดการปฏิบัติงาน :</p>
							</div>
						</div>
						<div class="row">
							<div class="col ms-3 detail">
								<?= $report[$i]['Report_Detail'] ?>
								<?= $report[$i]['Report_Detail'] ?>
								<?= $report[$i]['Report_Detail'] ?>
								<?= $report[$i]['Report_Detail'] ?>
								<?= $report[$i]['Report_Detail'] ?>

							</div>
						</div>
						<div class="row">
							<div class="col mt-2">
								<p class="fpr mb-1"><b>เวลาดำเนินงาน</b></p>
							</div>
						</div>
						<div class="row">
							<div class="col-5 ms-3">
								<p class="fpr"><b>เริ่ม : </b><?= convertDate($report[$i]['Report_Start_Date']) . ' ' . date('H:i', strtotime($report[$i]['Report_Start_Date'])) ?></p>
							</div>
							<div class="col-6">
								<p class="fpr"><b>เสร็จสิ้น : </b><?= convertDate($report[$i]['Report_Stop_Date']) . ' ' . date('H:i', strtotime($report[$i]['Report_Stop_Date'])) ?></p>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<?php if ($report[$i]['Report_Status'] == 6) { ?>
									<p class="fpr"><b>สถานะงาน :</b> ติดตามงาน</p>
								<?php } else if ($report[$i]['Report_Status'] == 7) { ?>
									<p class="fpr"><b>สถานะงาน :</b> ปิดงาน</p>
								<?php } ?>
							</div>
						</div>
						<div class="row">
							<div class="col-4">
								<div class="row">
									<div class="col">
										<img class="d-block w-100 h-auto mx-auto" src="data:<?= $signUser['Sign_image'] ?>" id="sign_User" alt="">
									</div>
								</div>
								<div class="row">
									<div class="col-auto mx-auto">
										<p class="fpr fw-bold mb-0">วันที่ <?= convertDate($report[$i]['Report_date_user']) ?></p>
									</div>
								</div>
								<div class="row">
									<div class="col-auto mx-auto">
										<p class="fpr fw-bold">เจ้าหน้าที่ไอที</p>
									</div>
								</div>
							</div>
							<div class="col-4 mt-5">
								<div class="row">
									<div class="col">
										<img class="d-block w-100 h-auto mx-auto" src="data:<?= $signAdmin['Sign_image'] ?>" id="sign_IT" alt="">
									</div>
								</div>
								<div class="row">
									<div class="col-auto mx-auto">
										<p class="fpr fw-bold mb-0">วันที่ <?= convertDate(date('d-m-Y')) ?></p>
									</div>
								</div>
								<div class="row">
									<div class="col-auto mx-auto">
										<p class="fw-bold" style="font-size: 15px;">หัวหน้าเทคโนโลยีสารสนเทศ</p>
									</div>
								</div>
							</div>
							<div class="col-4">
								<div class="row">
									<div class="col">
										<img class="d-block w-100 h-auto mx-auto" src="data:<?= $report[$i]['Sign_image'] ?>" id="sign_client" alt="">
									</div>
								</div>
								<div class="row">
									<div class="col-auto mx-auto">
										<p class="fpr fw-bold mb-0">วันที่ <?= convertDate($report[$i]['Report_date_client']) ?></p>
									</div>
								</div>
								<div class="row">
									<div class="col-auto mx-auto">
										<p class="fpr fw-bold">ผู้ใช้บริการ</p>

									</div>
								</div>
							</div>

						</div>
					</div>
				</div>

			<?php
			}
			?>
		</div>
	</main>
	<script>
		const cl = document.getElementsByClassName('detail');
		for (i = 0; i < cl.length; i++) {
			var text = cl[i].getElementsByTagName('p');
			for (j = 0; j < text.length; j++) {
				text[j].classList.add('text-break');
			}
		}
	</script>
</body>

</html>