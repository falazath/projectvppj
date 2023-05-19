<?php
session_start();
include("connect.php");
if (!isset($_SESSION['id'])) {
	header('location:index.php');
}
include('header.html');
if (isset($_POST['send_approve'])) {
	if (isset($_GET['User_id'])) {
		$stmt = $conn->prepare("UPDATE itoss_sign SET Sign_image=? WHERE User_id = ?");
		$stmt->bindParam(1, $_POST['Sign_image']);
		$stmt->bindParam(2, $_GET['User_id']);
		$stmt->execute();

		if ($_SESSION['status'] == 1) {
			$_SESSION['Sign_image'] = $_POST['Sign_image'];
			echo '<script language="javascript">';
			echo 'alert("บันทึกลายเซ็นแล้ว"); location.href="manageUser.php"';
			echo '</script>';
		}
	} else {
		$stmt = $conn->prepare("INSERT INTO itoss_sign VALUES ('', ?, ?)");
		$stmt->bindParam(1, $_POST['Sign_image']);
		$stmt->bindParam(2, $_SESSION['id']);
		$stmt->execute();

		if ($_SESSION['status'] == 1) {
			$_SESSION['Sign_image'] = $_POST['Sign_image'];
			echo '<script language="javascript">';
			echo 'alert("บันทึกลายเซ็นแล้ว"); location.href="indexAdmin.php"';
			echo '</script>';
		} else if ($_SESSION['status'] == 2 || $_SESSION['status'] == 3) {
			$_SESSION['Sign_image'] = $_POST['Sign_image'];
			echo '<script language="javascript">';
			echo 'alert("บันทึกลายเซ็นแล้ว"); location.href="indexUser.php"';
			echo '</script>';
		}
	}
}
?>

<body>
	<div class="row justify-content-center">
		<div class="col-10 col-xl-6 login align-self-center position-absolute top-50 start-50 translate-middle">
			<img class="m-5 d-block mx-auto w-50 h-auto" src="./asset/Logo/VP.svg" alt="">
			<form method="post">
				<div class="form-floating mb-3">
					<div id="content">
						<input type="hidden" name="Sign_image" id="Sign_image" value="..." rows="3" cols="50" style="width : 100%; height : 100px;"></input>
						<h2><b>
								<center>ลายเซ็น<center><b></h2>
					</div>
					<div class="row">
						<div class="col">
							<div class="form-floating border border-2">
								<div id="signature"></div>
							</div>
						</div>
					</div>
					<div class="form-floating">
						<div class="row justify-content-center my-5">
							<div class="col-auto">
								<div class="mt-xl-5" id="tools"></div>
							</div>
							<div class="col-3">
								<button class="btn btn-primary d-block mx-auto" type="submit" name='send_approve' id='send_approve' value="บันทึก">บันทึก</button>
							</div>
						</div>
					</div>
				</div>
			</form>
	</div>
	<script src="./libs/jquery.js"></script>
	<script src="./libs/jSignature.min.noconflict.js"></script>
	<script>
		(function($) {

			$(document).ready(function() {

				var $sigdiv = $("#signature").jSignature({
						'UndoButton': false
					}),
					$tools = $('#tools')

				$("#send_approve").on('click', function() {
					var data = $sigdiv.jSignature('getData', 'image');
					$("#Sign_image").val(data);
				});
				$('<input class="btn btn-secondary d-block mx-auto" type="button" value="ล้างลายเซ็น">').bind('click', function(e) {
					$sigdiv.jSignature('reset')
				}).appendTo($tools)
			})

		})(jQuery)
	</script>

</body>

</html>