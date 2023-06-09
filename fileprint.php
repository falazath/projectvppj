<?php
session_start();
include('header.html');
?>

<body>

	<style>
		@import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;700&display=swap');

		* {
			font-family: 'Sarabun', sans-serif;
		}

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
	$sqlFinal = $conn->query("SELECT * FROM itoss_sign INNER JOIN itoss_user ON itoss_sign.User_id = itoss_user.User_id where itoss_sign.User_id = 2");
	$sign = $sqlFinal->fetch();
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
			<div class="row mt-2">

			</div>
			<?php
			$sql_report = $conn->query("SELECT * FROM itoss_report WHERE Form_id = '" . $row['Form_id'] . "'");
			$report = $sql_report->fetchAll();
			$sql = $conn->query("SELECT * FROM other_agency WHERE Form_id = '" . $row['Form_id'] . "' ORDER BY id DESC LIMIT 1");
			$data = $sql->fetch();
			$agency = isset($data['name']) ? $data['name'] : $row['Agency_Name'];
			if (empty(count($report))) {
				$count = 1;
			} else {
				$count = count($report);
			}
			for ($i = 0; $i < $count; $i++) {
			?>

				<div style="page-break-after: always">

					<div class="row mt-0">
						<div class="col-auto me-auto">
							<img class="w-75 h-auto" src="./asset/Logo/VP.svg" alt="">
						</div>
						<?php
						if ($i == 0) {
						?>
							<div class="col-auto ms-auto">
								<button button id="hid" onclick="window.print();" class="btn btn-primary"> กดเพื่อพิมพ์เอกสาร </button>
							</div>
						<?php
						}
						?>
					</div>
					<div class="row my-4">
						<div class="col">
							<p class="ftitle text-center fw-bold mb-0">IT onsite service</p>
						</div>
					</div>
					<div class="row">
						<div class="col-auto ms-auto me-3 mb-1">
							<p class="fsub text-end mb-0">วันที่ <?= convertDate($row["Form_date"]) ?></p>
						</div>
					</div>
					<div class="row border border-2 border-dark mx-3">
						<div class="col">
							<div class="row mt-3">
								<div class="col-5">
									<p class="fsub"><b> ชื่อผู้ติดต่อ : </b><?= $row["Form_Name"] ?></p>
								</div>
								<div class="col-7">
									<p class="fsub"><b>หน่วยงาน : </b><?= $agency ?></p>
								</div>
							</div>
							<div class="row">
								<div class="col-5">
									<p class="fsub"><b>เบอร์โทรศัพท์ :</b> <?= $row["Form_Phone"] ?></p>
								</div>
								<div class="col-7">
									<p class="fsub"><b>ประเภทงาน :</b> <?= $type ?></p>
								</div>
							</div>
							<div class="row mb-2">
								<div class="col">
									<p class="fsub mb-0"><b>รายละเอียดการปฏิบัติงาน :</b></p>
								</div>
							</div>
							<div class="row">
								<div class="col-11 ms-3 detail fsub">
									<?= $row["Form_Work"] ?>
								</div>

							</div>
							<div class="row">
								<div class="col-4 ms-auto">
									<div class="row">
										<div class="col">
											<img class="d-block w-50 h-auto mx-auto" src="data:<?= $signAdmin['Sign_image'] ?>" id="sign_admin" alt="">
										</div>
									</div>
									<div class="row">
										<div class="col-12">
											<p class="fbody text-center">ผู้มอบหมายงาน</p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row border border-2 border-dark mx-3 mt-4">
						<div class="col">
							<div class="row mb-2 mt-3">
								<div class="col">
									<p class="fsub fw-bold mb-0">รายละเอียดการปฏิบัติงาน :</p>
								</div>
							</div>
							<div class="row">
								<div class="col-11 ms-3 detail fsub">
									<?= $report[$i]['Report_Detail'] ?>
								</div>
							</div>
							<div class="row mt-3">
								<div class="col mt-2">
									<p class="fsub mb-1"><b>เวลาดำเนินงาน</b></p>
								</div>
							</div>
							<div class="row">
								<div class="col-5 ms-3">
									<p class="fsub"><b>เริ่ม : </b><?= convertDate($report[$i]['Report_Start_Date']) . ' ' . date('H:i', strtotime($report[$i]['Report_Start_Date'])) ?></p>
								</div>
								<div class="col-6">
									<p class="fsub"><b>เสร็จสิ้น : </b><?= convertDate($report[$i]['Report_Stop_Date']) . ' ' . date('H:i', strtotime($report[$i]['Report_Stop_Date'])) ?></p>
								</div>
							</div>
							<div class="row">
								<div class="col">
									<?php if ($report[$i]['Report_Status'] == 6) { ?>
										<p class="fsub"><b>สถานะงาน :</b> ติดตามงาน วันที่ <?= convertDate($report[$i]['Report_follow_date']) ?></p>
									<?php } else if ($report[$i]['Report_Status'] == 7) { ?>
										<p class="fsub"><b>สถานะงาน :</b> ปิดงาน</p>
									<?php } ?>
								</div>
							</div>
							<div class="row">
								<div class="col-4 align-self-end">
									<div class="row">
										<div class="col">
											<img class="d-block w-50 h-auto mx-auto" src="data:<?= $signUser['Sign_image'] ?>" id="sign_User" alt="">
										</div>
									</div>
									<div class="row">
										<div class="col-auto mx-auto">
											<p class="fbody mb-0">วันที่ <?= convertDate($report[$i]['Report_date_user']) ?></p>
										</div>
									</div>
									<div class="row">
										<div class="col-auto mx-auto">
											<p class="fbody">เจ้าหน้าที่ไอที</p>
										</div>
									</div>
								</div>
								<div class="col-4 align-self-end">
									<div class="row">
										<div class="col">
											<?php
											$sql = $conn->query("SELECT * FROM itoss_sign WHERE Sign_id = " . $report[$i]['Report_sign_client'] . "");
											$signClient = $sql->fetch();
											?>
											<img class="d-block w-50 h-auto mx-auto" src="data:<?= $signClient['Sign_image'] ?>" id="sign_client" alt="">
										</div>
									</div>
									<div class="row">
										<div class="col-auto mx-auto">

											<p class="fbody mb-0">วันที่ <?= convertDate($report[$i]['Report_date_client']) ?></p>
										</div>
									</div>
									<div class="row">
										<div class="col-auto mx-auto">
											<p class="fbody">ผู้ตรวจสอบ</p>
										</div>
									</div>
								</div>
								<div class="col-4">
									<div class="row">
										<div class="col">
											<img class="d-block w-75 h-100 mx-auto" src="data:<?= $sign['Sign_image'] ?>" id="sign_IT" alt="">
										</div>
									</div>
									<div class="row">
										<div class="col-auto mx-auto">
											<p class="fbody mb-0">วันที่ <?= convertDate(date('d-m-Y')) ?></p>
										</div>
									</div>
									<div class="row">
										<div class="col-auto mx-auto">
											<p class="fbody">หัวหน้าเทคโนโลยีสารสนเทศ</p>
										</div>
									</div>
								</div>

							</div>
						</div>
					</div>
				</div>
				<hr id="hid" class="my-5">
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