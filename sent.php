<?php include ("connect.php");session_start();?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="initial-scale=1.0, target-densitydpi=device-dpi" /><!-- this is for mobile (Android) Chrome -->
<meta name="viewport" content="initial-scale=1.0, width=device-height"><!--  mobile Safari, FireFox, Opera Mobile  -->
<script src="./libs/modernizr.js"></script>
<script type="text/javascript" src="./libs/flashcanvas.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.9.1.min.js"></script>
<link href="./dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="./dist/css/bootstrap.css" rel="stylesheet">
    <script src="./dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="./style.css">
    <title>แสดงข้อมูล</title>
</head>
<body>
    <div class="row justify-content-center">
        <div class="col-6 login align-self-center position-absolute top-50 start-50 translate-middle">
            <img class="m-5 d-block mx-auto w-50 h-auto" src="./asset/Logo/VP.svg" alt="">
            <form method="post">
				<div class="form-floating mb-3">
					<div id="content">
						<input type="hidden" name="Sign_image" id="Sign_image" value="..." rows="3" cols="50" style="width : 100%; height : 100px;"></input></p>
						<h2><b><center>ลายเซ็น<center><b></h2>	
					</div>
					<div class="form-floating">
						<div id="signature"></div>
					</div>
					<div class="form-floating">
						<div class="row justify-content-center">
							<div class="col-3">
								<div id="tools"></div>
							</div>
							<div class="col-3">
								<button class="btn btn-primary d-block mx-auto my-5" type="submit" name='send_approve' id='send_approve' value="บันทึก">บันทึก</button>
						</div>
					</div>
				</div>
            </form>
        </div>
    </div>
<script src="./libs/jquery.js"></script>
<script src="./libs/jSignature.min.noconflict.js"></script>
<script>
(function($){

$(document).ready(function() {

	var $sigdiv = $("#signature").jSignature({'UndoButton':true})
	, $tools = $('#tools')

    $("#send_approve").on('click',function(){
        var data = $sigdiv.jSignature('getData','image');
        $("#Sign_image").val(data);
    });
	$('<input class="btn btn-secondary d-block mx-auto my-5" type="button" value="Reset">').bind('click', function(e){
		$sigdiv.jSignature('reset')
	}).appendTo($tools)
})

})(jQuery)
</script>
    <?php
    if (isset($_POST['send_approve']))
        {  
            $user = $_SESSION['id'];

			$stmt = $conn->prepare("INSERT INTO itoss_sign VALUES ('', ?, ?)");
            $stmt->bindParam(1, $_POST['Sign_image']);
            $stmt->bindParam(2, $_SESSION['id']);
            $stmt->execute();

            if($_SESSION['Status_id'] == 1){
				$_SESSION['Sign_image'] = $_POST['Sign_image'];
				echo '<script language="javascript">';
				echo 'alert("บันทึกลายเซ็นแล้ว"); location.href="indexAdmin.php?User_id='.$user.'"';
				echo '</script>';
            }
            else if($_SESSION['Status_id'] == 2){
				$_SESSION['Sign_image'] = $_POST['Sign_image'];
                echo '<script language="javascript">';
                echo 'alert("บันทึกลายเซ็นแล้ว"); location.href="indexUser.php?User_id='.$user.'"';
                echo '</script>';
            }
        }
    ?>
</body>
</html>
