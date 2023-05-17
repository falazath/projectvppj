<?php
session_start();
include("header.html");
include("connect.php");
if (!isset($_SESSION['id'])) {
    header('location:index.php');
}
$sql = $conn->query("SELECT * FROM itoss_department");
$department = $sql->fetchAll();
$sql = $conn->query("SELECT * FROM itoss_status");
$status = $sql->fetchAll();
include($_SESSION['navbar']);

if (isset($_POST['save'])) {
    if (strlen($_POST['User_Password']) < 6 || strlen($_POST['User_Password']) > 20) {
        echo '<script type="text/javascript">';
        echo 'window.location.href = "manageUser.php"; ';
        echo "alert('กรุณาใส่รหัสอย่างน้อย 6 ตัว ไม่เกิน 20 ตัว');";
        echo '</script>';
    } else{
        $flag = false;
        $sql = $conn->query("SELECT * FROM itoss_user WHERE state_id = 1");
        while($check = $sql->fetch()){
            if($_POST['User_Username'] == $check['User_Username']){
                $flag = true;
            }
        }
        if(!$flag){
            $stmt = $conn->prepare("INSERT INTO itoss_user VALUES ('', ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bindParam(1, $_POST["User_Name"]);
            $stmt->bindParam(2, $_POST["User_Jop"]);
            $stmt->bindParam(3, $_POST["User_Phone"]);
            $stmt->bindParam(4, $_POST["User_Username"]);
            $stmt->bindParam(5, $_POST["User_Password"]);
            $stmt->bindParam(6, $_POST["Status_id"]);
            $stmt->bindParam(7, $_POST["Department_id"]);
            $stmt->bindParam(8, $_POST["state_id"]);
            $stmt->execute();
            echo '<script language="javascript">';
            echo 'alert("ข้อมูล User ถูกเพิ่มแล้ว"); location.href="manageUser.php"';
            echo '</script>';
            $User_id = $conn->lastInsertId();
        }else{
            echo '<script language="javascript">';
            echo 'toastr.error("Username มีผู้ใช้งานอยู่แล้ว");';
            echo '</script>';
        }
    }
} else if (isset($_POST['edit'])) {
    $sql = $conn->query("SELECT * FROM itoss_user WHERE User_Username = '".$_POST["User_Username"]."'");
    $check = $sql->fetch();
    if(empty($check)){
        $stmt = $conn->prepare("UPDATE itoss_user SET User_Name=?, User_Jop=?, User_Phone=?, User_Username=?, User_Password=?, Status_id=?, Department_id=?, state_id=? WHERE User_id=?"); // เตรยีมคา สง่ั SQL ส าหรบัแกไ้ข
        $stmt->bindParam(1, $_POST["User_Name"]);
        $stmt->bindParam(2, $_POST["User_Jop"]);
        $stmt->bindParam(3, $_POST["User_Phone"]);
        $stmt->bindParam(4, $_POST["User_Username"]);
        $stmt->bindParam(5, $_POST["User_Password"]);
        $stmt->bindParam(6, $_POST["Status_id"]);
        $stmt->bindParam(7, $_POST["Department_id"]);
        $stmt->bindParam(8, $_POST["state_id"]);
        $stmt->bindParam(9, $_POST['edit']);
        $stmt->execute();
    
        echo '<script language="javascript">';
        echo 'toastr.success("แก้ไขเรียบร้อย"); location.href="manageUser.php"';
        echo '</script>';
    }else{
        echo '<script language="javascript">';
        echo 'toastr.warning("Username มีผู้ใช้งานอยู่แล้ว");';
        echo '</script>';
    }
    

    $User_id = $conn->lastInsertId();
}else if (isset($_POST['cancel'])) {
    $stmt = $conn->query("SELECT * FROM itoss_form WHERE User_id = " . $_POST['cancel'] . "");
    $del = $stmt->fetch();
    if (!empty($del)) {
        echo '<script language="javascript">';
        echo 'alert("มีข้อมูลผู้ใช้งานยังใช้งานอยู่ ไม่สามารถลบได้");';
        echo '</script>';
    } else {
        $stmt = $conn->prepare("UPDATE itoss_user SET state_id = 0 WHERE User_id = ?");
        $stmt->bindParam(1, $_POST["cancel"]);
        $stmt->execute();
        echo '<script language="javascript">';
        echo 'toastr.success("ลบผู้ใช้งานเรียบร้อย");';
        echo '</script>';
    }
}
?>
<main>
    <div class="row justify-content-center mt-5 ">
        <div class="col col-sm-3 col-xl-3 d-block mx-auto ">
            <p class="text-dark text-center fhead fw-bold">จัดการบัญชีผู้ใช้</p>
        </div>
    </div>
    <div class="row">
        <div class="col mb-3">
            <button type="button" class="btn btn-primary d-block me-xl-auto" data-bs-toggle="modal" data-bs-target="#create-User">เพิ่มผู้ใช้งาน</button>
        </div>
        <form method="post">
            <div class="modal fade" id="create-User" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body">
                            <p class="ftitle fw-bold text-center">เพิ่มผู้ใช้งาน</p>
                            <div class="col-xl-10 mx-auto">
                                <p class="ftitle fw-bold mb-1">Username</p>
                                <input type="text" class="form-control ftitle" name="User_Username" placeholder="กรอกข้อมูลUsername" required>
                            </div>
                            <div class="col-xl-10 mx-auto">
                                <p class="ftitle fw-bold mb-1">Password</p>
                                <input type="password" class="form-control ftitle" name="User_Password" placeholder="กรอกข้อมูลPassword" required>
                                <input type="hidden" name="state_id" value="1">
                            </div>
                            <div class="col-xl-10 mx-auto">
                                <p class="ftitle fw-bold mb-1">ชื่อ-นามสกุล</p>
                                <input type="text" class="form-control ftitle" name="User_Name" placeholder="กรอกข้อมูลชื่อ-นามสกุล" required>
                            </div>
                            <div class="col-xl-10 mx-auto">
                                <p class="ftitle fw-bold mb-1">ตำแหน่งงาน</p>
                                <input type="text" class="form-control ftitle" name="User_Jop" placeholder="กรอกข้อมูลตำแหน่งงาน" required>
                            </div>
                            <div class="col-xl-10 mx-auto">
                                <p class="ftitle fw-bold mb-1">เบอร์ติดต่อ</p>
                                <input type="number" class="form-control ftitle" name="User_Phone" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="กรอกข้อมูลเบอร์ติดต่อ" required>
                            </div>
                            <div class="col-xl-10 mx-auto">
                                <p class="ftitle fw-bold">แผนกงาน</p>
                                <select class="form-select" id="Department_id" name="Department_id" required>
                                    <option selected disabled value="">เลือกแผนกงาน</option>
                                    <?php
                                    for ($i = 0; $i < count($department); $i++) {
                                        echo '<option value="' . $department[$i]['Department_id'] . '">' . $department[$i]['Department_name'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-xl-10 mx-auto">
                                <p class="ftitle fw-bold">สถานะ</p>
                                <select class="form-select" id="Status_id" name="Status_id" required>
                                    <option selected disabled value="">เลือกสถานะ</option>
                                    <?php
                                    for ($i = 0; $i < count($status); $i++) {
                                        echo '<option value="' . $status[$i]['Status_id'] . '">' . $status[$i]['Status_name'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-around">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">กลับ</button>
                            <button type="submit" name="save" class="btn btn-primary">บันทึก</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="overflow-x-auto">
        <!--ตาราง-->
        <table class="table table-light table-bordered">
            <thead>
                <tr class="d-flex text-center fsub">
                    <th class="col-4 col-xl-2">Username</th>
                    <th class="col-5 col-xl-2">ชื่อ-นามสกุล</th>
                    <th class="col-5 col-xl-2">ตำแหน่ง</th>
                    <th class="col-5 col-xl-2">เบอร์ติดต่อ</th>
                    <th class="col-5 col-xl-2">แผนก</th>
                    <th class="col-1 col-xl-1"></th>
                    <th class="col-1 col-xl-1"></th>
                </tr>
            </thead>
            <tbody>
                <!--สถานะ:รออนุมัติ-->
                <?php
                $stmt = $conn->query("SELECT * FROM itoss_user 
                        INNER JOIN itoss_department ON itoss_user.Department_id = itoss_department.Department_id
                        INNER JOIN itoss_status ON itoss_user.Status_id = itoss_status.Status_id WHERE itoss_user.state_id= 1");
                while ($row = $stmt->fetch()) { ?>
                    <tr class="d-flex text-center fsub">
                        <td class="col-4 col-xl-2"><?= $row['User_Username'] ?></td>
                        <td class="col-5 col-xl-2"><?= $row['User_Name'] ?></td>
                        <td class="col-5 col-xl-2"><?= $row['User_Jop'] ?></td>
                        <td class="col-5 col-xl-2"><?= $row['User_Phone'] ?></td>
                        <td class="col-5 col-xl-2"><?= $row['Department_name'] ?></td>
                        <td class="col-1 col-xl-1"><a data-bs-toggle="modal" data-bs-target="#edit-User<?= $row['User_id'] ?>" href="#"><img src="./asset/icon/Setting.svg" alt=""></a></td>
                        <td class="col-1 col-xl-1"><a data-bs-toggle="modal" data-bs-target="#cancel<?= $row['User_id'] ?>" href="#"><img src="./asset/icon/Delete.svg" alt=""></a></td>
                    </tr>

                        <form action="" method="post">
                        <div class="modal fade" id="cancel<?= $row['User_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <p class="modal-title fhead fw-bold text-center">ยืนยันการยกเลิก</p>
                                    </div>
                                    <div class="modal-body my-3 my-xl-3 text-center">
                                        <p class="ftitle text-center d-inline">คุณต้องการลบบัญชีผู้ใช้หรือไม่  </p>
                                    </div>
                                    <div class="modal-footer justify-content-center">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">กลับ</button>
                                        <button class="btn btn-danger ftitle" type="submit" name="cancel"  value="<?= $row['User_id'] ?>">ยืนยัน</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </form>
            
                    <form method="post">
                        <div class="modal fade" id="edit-User<?= $row['User_id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <p class="ftitle fw-bold text-center">แก้ไขข้อมูลผู้ใช้งาน</p>
                                        <div class="col-xl-10 mx-auto">
                                            <p class="ftitle fw-bold mb-1">Username</p>
                                            <input type="text" class="form-control ftitle" name="User_Username" value="<?= $row['User_Username'] ?>">
                                        </div>
                                        <div class="col-xl-10 mx-auto">
                                            <p class="ftitle fw-bold mb-1">Password</p>
                                            <input type="text" class="form-control ftitle" name="User_Password" value="<?= $row['User_Password'] ?>">
                                            <input type="hidden" name="state_id" value="1">
                                        </div>
                                        <div class="col-xl-10 mx-auto">
                                            <p class="ftitle fw-bold mb-1">ชื่อ-นามสกุล</p>
                                            <input type="text" class="form-control ftitle" name="User_Name" value="<?= $row['User_Name'] ?>">
                                        </div>
                                        <div class="col-xl-10 mx-auto">
                                            <p class="ftitle fw-bold mb-1">ตำแหน่งงาน</p>
                                            <input type="text" class="form-control ftitle" name="User_Jop" value="<?= $row['User_Jop'] ?>">
                                        </div>
                                        <div class="col-xl-10 mx-auto">
                                            <p class="ftitle fw-bold mb-1">เบอร์ติดต่อ</p>
                                            <input type="text" class="form-control ftitle" name="User_Phone" value="<?= $row['User_Phone'] ?>">
                                        </div>
                                        <div class="col-xl-10 mx-auto">
                                            <p class="ftitle fw-bold">แผนกงาน</p>
                                            <select class="form-select" id="Department_id" name="Department_id">
                                                <option selected value="<?= $row['Department_id'] ?>"><?= $row['Department_name'] ?></option>
                                                <?php
                                                for ($i = 0; $i < count($department); $i++) {
                                                    echo '<option value="' . $department[$i]['Department_id'] . '">' . $department[$i]['Department_name'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-xl-10 mx-auto">
                                            <p class="ftitle fw-bold">สถานะ</p>
                                            <select class="form-select" id="Status_id" name="Status_id">
                                                <option selected value="<?= $row['Status_id'] ?>"><?= $row['Status_name'] ?></option>
                                                <?php
                                                for ($i = 0; $i < count($status); $i++) {
                                                    echo '<option value="' . $status[$i]['Status_id'] . '">' . $status[$i]['Status_name'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="ftitle fw-bold text-center mt-4 mb-2">
                                            <a class="btn btn-primary"  name="edit-sent" href="sent.php?User_id=<?=$row['User_id']?>">แก้ไขลายเซ็น</a>
                                        </div>
                                    </div>
                                    <div class="modal-footer justify-content-around">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">กลับ</button>
                                        <button type="submit" class="btn btn-primary"  name="edit" value="<?= $row['User_id'] ?>" >แก้ไข</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</main>

<script>
    $('#Department_id').change(function() {
        let a = $('#Department_id').val();
        if (a == "3") {
            $('#Department').removeClass('d-none');
        } else {
            $('#Department').addClass('d-none');
        }
    });
</script>
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