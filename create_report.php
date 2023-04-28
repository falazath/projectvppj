<?php
session_start();
if (!isset($_SESSION['id'])) {
    header('location:login.php');
}
include('header.html');
include("connect.php");

$sql = $conn->query("SELECT * FROM itoss_agency WHERE state_id =1;");
$filter[0] = $sql->fetchAll();
$sql = $conn->query("SELECT * FROM itoss_user WHERE state_id =1;");
$filter[1] = $sql->fetchAll();
$sql = $conn->query("SELECT * FROM itoss_jobtype;");
$filter[2] = $sql->fetchAll();
$sql = $conn->query("SELECT * FROM itoss_status_form");
$filter[3] = $sql->fetchAll();
$Form_id = $_GET["Form_id"];

if (isset($_POST['send_approve'])) {
    $stmt = $conn->prepare("INSERT INTO itoss_sign VALUES ('', ?, NULL)");
    $stmt->bindParam(1, $_POST['Sign_image']);
    $stmt->execute();
    $id = $conn->lastInsertId();
    $stmt = $conn->prepare("INSERT INTO itoss_report VALUES ('', ?, ?, ?, ?, ?, ? , ?, ?, ?)");
    $stmt->bindParam(1, $_POST["Report_Detail"]);
    $stmt->bindParam(2, $_POST["Report_Start_Date"]);
    $stmt->bindParam(3, $_POST["Report_Stop_Date"]);
    $stmt->bindParam(4, $_POST["Report_Status"]);
    $stmt->bindParam(5, $_POST["Report_follow_date"]);
    $stmt->bindParam(6, $_POST["Report_date_client"]);
    $stmt->bindParam(7, $id);
    $stmt->bindParam(8, $_POST["Report_date_client"]);
    $stmt->bindParam(9, $Form_id);
    $stmt->execute();
    $Status_form_id = $_POST["Report_Status"];
    $stmt = $conn->prepare("UPDATE itoss_form SET Status_form_id = '$Status_form_id' where Form_id = '$Form_id'");
    $stmt->execute();

    include("message.php");
    echo '<script language="javascript">';
    echo 'alert("บันทึกรายงานเรียบร้อย"); location.href="indexUser.php"';
    echo '</script>';
}

$stmt = $conn->prepare("SELECT * FROM itoss_form 
    INNER JOIN itoss_jobtype ON itoss_form.Jobtype_id = itoss_jobtype.Jobtype_id 
    INNER JOIN itoss_user ON itoss_form.User_id = itoss_user.User_id 
    INNER JOIN itoss_status_form ON itoss_form.Status_form_id = itoss_status_form.Status_form_id
    INNER JOIN itoss_agency ON itoss_form.Agency_id = itoss_agency.Agency_id 
    WHERE Form_id = ?");
$stmt->bindParam(1, $Form_id);
$stmt->execute();
$row = $stmt->fetch();


$sql = $conn->query("SELECT * FROM other_agency WHERE Form_id = '$Form_id' ORDER BY id DESC LIMIT 1");
$data = $sql->fetch();
$agency = isset($data['name']) ? $data['name'] : $row['Agency_Name'];

$stmt1 = $conn->query("SELECT * FROM itoss_jobtype_orther where Form_id = '$Form_id'");
$row1 = $stmt1->fetch();
isset($row1['Jobtype_orther_name']) ? $job_other = $row1['Jobtype_orther_name'] : $job_other = $row['Jobtype_name'];

$stmt3 = $conn->query("SELECT * FROM itoss_text where Form_id = '$Form_id' ORDER BY Text_id DESC");
$row3 = $stmt3->fetch();
isset($row3['Text_name']) ? $text = $row3['Text_name'] : $text = "";
isset($row3['Status_form_id']) ? $Status = $row3['Status_form_id'] : $Status = $row['Status_form_id'];

$stmt4 = $conn->query("SELECT * FROM itoss_sign INNER JOIN itoss_user ON itoss_sign.User_id = itoss_user.User_id where itoss_sign.User_id = " . $_SESSION['id'] . "");
$row4 = $stmt4->fetch();
include($_SESSION['navbar']);

?>
<main>
    <p id="show"></p>
    <div class="row justify-content-center mt-5 ">
        <div class="col col-sm-3 col-xl-3 d-block mx-auto ">
            <p class="text-dark text-center fhead fw-bold">คำขอปฏิบัติงาน</p>
            <p class="text-end ftitle text-danger">สถานะ : <?= $row['Status_form_name'] ?></p>

        </div>
    </div>

    <div class="row">
        <div class="col">

        </div>
    </div>

    <div class="row mb-0 mb-xl-3 mb-xl-0 d-none" id="editBox">
        <div class="col-12 col-xl-12 mb-3">
            <p class="ftitle fw-bold mb-1">รายละเอียดการแก้ไขงาน</p>
            <div class="form-control text-light" id="Detail" cols="30" rows="10">

            </div>
        </div>
        <hr>
    </div>

    <div class="row justify-content-start mb-0 mb-xl-3" id="dsk">
        <div class="col-12 col-xl-4 mb-2 mb-xl-3 mb-xl-0">
            <p class="ftitle fw-bold mb-0" id="demo">ชื่อผู้ติดต่อ</p>
            <input type="text" class="form-control ftitle" name="Form_Name" id="contact" value="<?= $row["Form_Name"] ?>" disabled>
        </div>
        <div class="col-12 col-xl-4 mb-2 mb-xl-3 mb-xl-0">
            <p class="ftitle fw-bold mb-0">หน่วยงาน</p>
            <input type="text" class="form-control" name="" id="" value="<?= $agency ?>" disabled>
        </div>
        <div class="col-12 col-xl-4 mb-2 mb-xl-3 mb-xl-0">
            <p class="ftitle fw-bold mb-0">เบอร์โทรศัพท์</p>
            <input type="text" class="data form-control ftitle" name="Form_Phone" value="<?= $row["Form_Phone"] ?>" disabled>
        </div>
    </div>
    <div class="row mb-xl-3">
        <div class="col-12 col-xl-4 mb-2 mb-xl-3 mb-xl-0">
            <p class="ftitle fw-bold mb-0">ประเภทงาน</p>
            <input type="text" class="form-control" value="<?= $job_other ?>" disabled>
        </div>
    </div>
    <div class="row mb-2 mb-xl-3 mb-xl-0">
        <div class="col-12 col-xl-12 mb-2 mb-xl-3">
            <p class="ftitle fw-bold mb-0">รายละเอียดงาน</p>
            <div class="form-control text-light" name="Form_Work" id="detail" cols="30" rows="10">
                <?= $row['Form_Work'] ?>
            </div>
        </div>
    </div>
    <div class="row mb-4 mb-xl-5">
        <div class="col-12 col-xl-3 mx-xl-auto mb-2">
            <p class="ftitle fw-bold mb-0 text-center">เจ้าหน้าที่ผู้รับผิดชอบ</p>
            <img class="d-block w-100 h-100 text-center" src="data:<?= $row4['Sign_image'] ?>">
        </div>
        <div class="col-6 col-xl-3 mx-auto mb-2">
            <input type="text" class="ftitle form-control text-center" id="name-user" name="User_Name" value="<?= $row['User_Name'] ?>" disabled>
        </div>
    </div>
    <hr>
    <form action="" method="post">
        <div class="row justify-content-center mt-5 ">
            <div class="col col-sm-3 col-xl-3 d-block mx-auto ">
                <p class="text-dark text-center fhead fw-bold">รายงานการปฏิบัติงาน</p>
            </div>
        </div>
        <div class="row mb-0 mb-xl-3 mb-xl-0">
            <div class="col-12 col-xl-12 mb-3">
                <p class="ftitle fw-bold mb-0">รายละเอียดงาน</p>
                <textarea class="data form-control" name="Report_Detail" id="detail-report" cols="30" rows="10" required></textarea>
            </div>
        </div>
        <div class="row mb-5 mb-xl-5">
            <div class="col-12 col-xl-4 mb-2">
                <p class="ftilte fw-bold mb-0">เวลาเริ่มดำเนินงาน</p>
                <input class="form-control" type="datetime-local" name="Report_Start_Date" required>
            </div>
            <div class="col-12 col-xl-4 mb-2">
                <p class="ftilte fw-bold mb-0">เวลาเสร็จสิ้นการดำเนินงาน</p>
                <input class="form-control" type="datetime-local" name="Report_Stop_Date" required>
            </div>
            <div class="col-12 col-xl-3 mb-2">
                <p class="ftilte fw-bold mb-0">สถานะ:</p>
                <div class="row">
                    <div class="col-6 col-xl form-check">
                        <input class="form-check-input mx-auto me-2" type="radio" name="Report_Status" value="7" id="finish">
                        <label class="form-check-label ftitle" for="flexRadioDefault1">
                            ปิดงาน
                        </label>
                    </div>
                    <div class="col-6 col-xl form-check">
                        <input class="form-check-input me-2" type="radio" name="Report_Status" id="follow" value="6" checked>
                        <label class="form-check-label ftitle" for="flexRadioDefault2">
                            ติดตามงาน
                        </label>
                    </div>
                    <div class="col-12 mt-2">
                        <input class="ms-0 form-control" type="date" name="Report_follow_date" id="follow-date" value="<?= date('d/M/yyyy') ?>">
                    </div>
                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-6 mx-auto">
                <p class="ftilte fw-bold text-center">ผู้ใช้บริการ</p>
            </div>
        </div>
        <div class="row mb-3 mb-xl-5 text-center">
            <div class="col-xl-6 mx-auto">
                <div id="signature"></div>
                <input type="hidden" name="Sign_image" id="Sign_image" value="..." required>

            </div>
        </div>

        <div class="row mb-5 justify-content-center">
            <div class="col-10 col-xl-3 me-0 align-self-center">
                <label class="ftilte fw-bold text-end mb-0 mt-0" for="start">วันที่</label>
                <input class="form-control ms-0  col-xl-1" type="date" name="Report_date_client" id="start" required>
            </div>
        </div>
        <div class="row justify-content-around mb-5 mt-xl-5">
            <div class="col-auto col-xl-4">
                <a class="col-xl-3 btn btn-secondary d-block ms-auto me-2 me-xl-5 ftitle" href="indexUser.php" id="home">ยกเลิก</a>
            </div>
            <div class="col-auto col-xl-4">
                <button class="btn btn-primary d-block me-auto ms-2 ms-xl-5" type="submit" name="send_approve" id="send_approve">บันทึก</button>
            </div>
        </div>
    </form>

    <?php

    $conn = null;
    ?>

</main>
<script src="./libs/jquery.js"></script>
<script src="./libs/jSignature.min.noconflict.js"></script>
<script>
    (function($) {

        $(document).ready(function() {

            var $sigdiv = $("#signature").jSignature({
                    'UndoButton': true
                }),
                $tools = $('#tools')

            $("#send_approve").on('click', function() {
                var data = $sigdiv.jSignature('getData', 'image');
                $("#Sign_image").val(data);
            });
            $('<input class="btn btn-secondary d-block mx-auto my-5" type="button" value="Reset">').bind('click', function(e) {
                $sigdiv.jSignature('reset')
            }).appendTo($tools)
        })

    })(jQuery)

    CKEDITOR.replace('detail-report');

    function otherCheck() {
        var check = document.getElementById('other');
        if (check.checked == true) {
            document.getElementById('lab-other').classList.add('d-none');
            document.getElementById('inp-other').classList.remove('d-none');
        } else {
            document.getElementById('lab-other').classList.remove('d-none');
            document.getElementById('inp-other').classList.add('d-none');
        }
    }
    const finish = document.getElementById('finish');
    const follow = document.getElementById('follow');
    const date = document.getElementById('follow-date');

    finish.addEventListener("click", function() {
        var date = document.getElementById('follow-date').classList.add('d-none');
    })
    follow.addEventListener("click", function() {
        var date = document.getElementById('follow-date').classList.remove('d-none');
    })
    $('#Agency_id').change(function() {
        let a = $('#Agency_id').val();
        if (a == "0") {
            $('#other_agency').removeClass('d-none');
            document.getElementById('other_agency').required = true;
        } else {
            $('#other_agency').addClass('d-none');
            document.getElementById('other_agency').required = false;
        }
    });
</script>
</body>

</html>