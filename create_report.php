<?php
session_start();
if (!isset($_SESSION['id'])) {
    if (isset($_GET['Form_id']) || $_SESSION['status'] == 3) {
        $_SESSION['Form_id'] = $_GET['Form_id'];
        $_SESSION['page_link'] = 2;
        header('location:index.php');
    }
} else if ($_SESSION['status'] == 1) {
    header('location:requestAdmin.php?Form_id=' . $_GET['Form_id'] . '');
} else if ($_SESSION['status'] == 3) {
    header('location:indexUser.php');
} else {
    unset($_SESSION['Form_id']);
    unset($_SESSION['page_link']);
}
include('header.html');
include("connect.php");
include($_SESSION['navbar']);


$sql = $conn->query("SELECT * FROM itoss_agency WHERE state_id =1;");
$filter[0] = $sql->fetchAll();
$sql = $conn->query("SELECT * FROM itoss_user WHERE state_id =1;");
$filter[1] = $sql->fetchAll();
$sql = $conn->query("SELECT * FROM itoss_jobtype WHERE state_id =1;");
$jobChoice = $sql->fetchAll();
$sql = $conn->query("SELECT * FROM itoss_status_form");
$filter[3] = $sql->fetchAll();
$Form_id = $_GET["Form_id"];

if (isset($_POST['send_approve'])) {
    if (count($_FILES['img_file']['name']) <= 5) {
        $stmt = $conn->prepare("INSERT INTO itoss_sign VALUES ('', ?, NULL)");
        $stmt->bindParam(1, $_POST['Sign_image']);
        $stmt->execute();
        $id = $conn->lastInsertId();
        $stmt = $conn->prepare("INSERT INTO itoss_report VALUES ('', ?, ?, ?, ?, ?, now() , ?, now(), ?)");
        $stmt->bindParam(1, $_POST["Report_Detail"]);
        $stmt->bindParam(2, $_POST["Report_Start_Date"]);
        $stmt->bindParam(3, $_POST["Report_Stop_Date"]);
        $stmt->bindParam(4, $_POST["Report_Status"]);
        $stmt->bindParam(5, $_POST["Report_follow_date"]);
        // $stmt->bindParam(6, $_POST["Report_date_client"]);
        $stmt->bindParam(6, $id);
        // $stmt->bindParam(8, $_POST["Report_date_client"]);
        $stmt->bindParam(7, $Form_id);
        $stmt->execute();
        $Report_id = $conn->lastInsertId();
        $Status_form_id = $_POST["Report_Status"];
        $stmt = $conn->prepare("UPDATE itoss_form SET Status_form_id = '$Status_form_id' where Form_id = '$Form_id'");
        $stmt->execute();
        $sql_img = $conn->query("SELECT itoss_img.img_file FROM itoss_img WHERE Report_id = $Report_id;");
        while ($img_row = $sql_img->fetch()) {
            $checkDel = unlink('./' . $img_row['img_file'] . '');
        }
        $sql_img = $conn->query("DELETE FROM itoss_img WHERE Report_id = $Report_id;");

        $count = count($_FILES['img_file']['name']);
        $date1 = date("dmY_His");
        foreach ($_FILES['img_file']['tmp_name'] as $key => $value) {
            $file_names = $_FILES['img_file']['name'];
            $type = strrchr($_FILES['img_file']['name'][$key], ".");
            $new_name = $Report_id . "00" . $date1 . $key . $type;
            if (move_uploaded_file($_FILES['img_file']['tmp_name'][$key], "img_work/" . $new_name)) {
                $path_link = "img_work/" . $new_name;
                $sql = "INSERT INTO itoss_img (img_file, date_img, Report_id) 
                        VALUES ( :image , now() , $Report_id)";
                $stmt = $conn->prepare($sql);
                $params = array(
                    'image' => $path_link
                );
                $stmt->execute($params);
            }
        }
        include("message.php");
        $_SESSION['ch'] = $_POST["Report_Status"];
        echo '<script language="javascript">';
        echo 'location.href="indexUser.php"';
        echo '</script>';
    } else {
        echo '<script>toastr.warning("อัพโหลดได้ไม่เกิน 5 รูป");</script>';
    }
}

$stmt = $conn->prepare("SELECT * FROM itoss_form
    INNER JOIN itoss_user ON itoss_form.User_id = itoss_user.User_id 
    INNER JOIN itoss_status_form ON itoss_form.Status_form_id = itoss_status_form.Status_form_id
    INNER JOIN itoss_agency ON itoss_form.Agency_id = itoss_agency.Agency_id 
    WHERE Form_id = ?");
$stmt->bindParam(1, $Form_id);
$stmt->execute();
$row = $stmt->fetch();
isset($row['Status_form_id']) ? $Status1 = $row['Status_form_id'] : $Status1 = $row3['Status_form_id'];

$sql = $conn->query("SELECT * FROM other_agency WHERE Form_id = '$Form_id' ORDER BY id DESC LIMIT 1");
$data = $sql->fetch();
$agency = isset($data['name']) ? $data['name'] : $row['Agency_Name'];

$sql_job = $conn->query("SELECT itoss_job.Job_id,itoss_job.Jobtype_id,itoss_job.name_other,itoss_jobtype.Jobtype_name 
FROM itoss_job,itoss_jobtype WHERE itoss_job.Jobtype_id = itoss_jobtype.Jobtype_id AND itoss_job.Form_id = '$Form_id'");
$job = $sql_job->fetchAll();

$sqlAdmin = $conn->query("SELECT * FROM itoss_sign INNER JOIN itoss_user ON itoss_sign.User_id = itoss_user.User_id where itoss_sign.User_id = " . $row['assign_id'] . "");
$signAdmin = $sqlAdmin->fetch();

$stmt3 = $conn->query("SELECT * FROM itoss_text where Form_id = '$Form_id' ORDER BY Text_id DESC");
$row3 = $stmt3->fetch();
$editor = isset($row3['editor']) ? $row3['editor'] : '';
isset($row3['Text_name']) ? $text = $row3['Text_name'] : $text = "";
isset($row3['Status_form_id']) ? $Status = $row3['Status_form_id'] : $Status = $row['Status_form_id'];

$sql_user = $conn->query("SELECT * FROM itoss_sign INNER JOIN itoss_user ON itoss_sign.User_id = itoss_user.User_id where itoss_sign.User_id = " . $row['User_id'] . "");
$signUser = $sql_user->fetch();

?>
<main>
    <p id="show"></p>
    <div class="row justify-content-center mt-5 ">
        <div class="col col-sm-12 col-xl-3 d-block mx-auto ">
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
            <div class="form-control text-light text-break" id="Detail" cols="30" rows="10">

            </div>
        </div>
        <hr>
    </div>

    <div class="row justify-content-start mb-0 mb-xl-3" id="dsk">
        <div class="col-12 col-sm-6 col-xl-4 mb-2 mb-xl-3 mb-xl-0">
            <p class="ftitle fw-bold mb-0" id="demo">ชื่อผู้ติดต่อ</p>
            <input type="text" class="form-control ftitle" name="Form_Name" id="contact" value="<?= $row["Form_Name"] ?>" disabled>
        </div>
        <div class="col-12 col-sm-6 col-xl-4 mb-2 mb-xl-3 mb-xl-0">
            <p class="ftitle fw-bold mb-0">หน่วยงาน</p>
            <input type="text" class="form-control" name="" id="" value="<?= $agency ?>" disabled>
        </div>
        <div class="col-12 col-sm-6 col-xl-4 mb-2 mb-xl-3 mb-xl-0">
            <p class="ftitle fw-bold mb-0">เบอร์โทรศัพท์</p>
            <input type="text" class="data form-control ftitle" name="Form_Phone" value="<?= $row["Form_Phone"] ?>" disabled>
        </div>
    </div>
    <div class="row mb-xl-3">
        <div class="row mb-0">
            <div class="col-12 col-lg-6 mb-0">
                <p class="ftitle fw-bold mb-0">ประเภทงาน</p>
            </div>
        </div>
        <div class="row mb-xl-3">
            <?php

            for ($i = 1; $i < count($jobChoice); $i++) {
                $ch = '';
                for ($j = 0; $j < count($job); $j++) {
                    if ($job[$j]['Jobtype_id'] == $jobChoice[$i]['Jobtype_id']) {
                        $ch = 'checked';
            ?>


                        <div class="col-4 col-lg-2 mb-2 mb-xl-0">
                            <div class="form-check">
                                <input type="checkbox" class="data form-check-input my-0 required" name="Jobtype_id[]" id="name<?= $jobChoice[$i]['Jobtype_id'] ?>" value="<?= $jobChoice[$i]['Jobtype_id'] ?>" onclick="deRequireCb('required')" disabled <?= $ch ?>>
                                <label class="form-check-label ms-1 my-0" for="name<?= $jobChoice[$i]['Jobtype_id'] ?>">
                                    <?= $jobChoice[$i]['Jobtype_name'] ?>
                                </label>
                            </div>
                        </div>
                <?php
                    }
                }
            }
            $index = array_search('0', array_column($job, 'Jobtype_id'));
            if ($index !== false) {
                $ch = 'checked';
                $valueOther = $job[$index]['name_other']; ?>
                <div class="col-12 col-lg-2 mb-0">
                    <div class="form-check">
                        <input type="checkbox" class="data form-check-input mb-2 my-xl-0 required" name="Jobtype_id[]" id="name0" value="0" onclick="deRequireCb('required')" disabled <?= $ch ?>>
                        <label class="form-check-label ms-1 my-0" for="name" id="labelOther">
                            อื่น ๆ
                        </label>
                    </div>
                    <input class="data form-control mt-1" type="text" name="Jobtype_orther_name" id="other_job" value="<?= $valueOther ?>" placeholder="กรอกประเภทงาน" disabled>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="row mb-2 mb-xl-3 mb-xl-0">
        <div class="col-12 col-xl-12 mb-2 mb-xl-3">
            <p class="ftitle fw-bold mb-0">รายละเอียดงาน</p>
            <div class="form-control text-light text-break" name="Form_Work" id="detail" cols="30" rows="10">
                <?= $row['Form_Work'] ?>
            </div>
        </div>
    </div>
    <div class="row mb-xl-5 ">
        <div class="col-12 col-sm-6 mx-xl-auto" id="userSignBox">
            <div class="col-12 col-xl-3 mx-xl-auto mb-3">
                <p class="ftitle fw-bold mb-1 text-center">เจ้าหน้าที่ผู้รับผิดชอบ</p>
            </div>
            <div class="col-auto mx-auto col-xl-auto mx-xl-auto mb-xl-0 align-self-center">
                <img class="d-block mx-auto w-50 h-auto" src="data:<?= $signUser['Sign_image'] ?>" alt="">
            </div>
            <div class="col-6 col-xl-6 mx-auto mb-5">
                <input type="text" class="ftitle form-control text-center" id="name-user" value="<?= $row['User_Name'] ?>" disabled>
            </div>
        </div>
        <div class="col-12 col-sm-6 mx-xl-auto">
            <div class="col-12 col-xl-3 mx-xl-auto mb-3">
                <p class="ftitle fw-bold mb-1 text-center">ผู้มอบหมายงาน</p>
            </div>
            <div class="col-auto mx-auto col-xl-auto mx-xl-auto mb-xl-0 align-self-center">
                <img class="d-block mx-auto w-50 h-auto" src="data:<?= $signAdmin['Sign_image'] ?>" alt="">
            </div>
            <div class="col-6 col-xl-6 mx-auto mb-5">
                <input type="text" class="ftitle form-control text-center" id="name-user" value="<?= $signAdmin['User_Name'] ?>" disabled>
            </div>
        </div>
    </div>
    <hr>
    <form action="" method="post" enctype="multipart/form-data">

        <div class="row justify-content-center mt-5 ">
            <div class="col col-sm-6 col-xl-3 d-block mx-auto ">
                <p class="text-dark text-center fhead fw-bold">รายงานการปฏิบัติงาน</p>
            </div>
        </div>
        <div class="row mb-0 mb-xl-3 mb-xl-0">
            <div class="col-12 col-xl-12 mb-3">
                <p class="ftitle fw-bold mb-0">รายละเอียดงาน</p>
                <textarea class="data form-control" name="Report_Detail" id="detail-report" cols="30" rows="10" required></textarea>
            </div>
        </div>
        <div class="row mb-3 mb-xl-5">
            <div class="col-12 col-sm-6 col-xl-4 mb-2">
                <p class="ftilte fw-bold mb-0">เวลาเริ่มดำเนินงาน</p>
                <input class="form-control" type="datetime-local" name="Report_Start_Date" value="<?= date("Y-m-d H:i") ?>" required>
            </div>
            <div class="col-12 col-sm-6 col-xl-4 mb-2">
                <p class="ftilte fw-bold mb-0">เวลาเสร็จสิ้นการดำเนินงาน</p>
                <input class="form-control" type="datetime-local" name="Report_Stop_Date" value="<?= date("Y-m-d H:i") ?>" required>
            </div>
            <div class="col-12 col-xl-3 mb-2">
                <p class="ftilte fw-bold mb-0">สถานะ:</p>
                <div class="row">
                    <div class="col-6 col-xl form-check">
                        <input class="form-check-input mx-auto me-2" type="radio" name="Report_Status" value="7" id="finish" checked>
                        <label class="form-check-label ftitle" for="flexRadioDefault1">
                            ปิดงาน
                        </label>
                    </div>
                    <div class="col-6 col-xl form-check">
                        <input class="form-check-input me-2" type="radio" name="Report_Status" id="follow" value="6">
                        <label class="form-check-label ftitle" for="flexRadioDefault2">
                            ติดตามงาน
                        </label>
                    </div>
                    <div class="col-12 mt-2">
                        <input class="ms-0 form-control" type="date" name="Report_follow_date" id="follow-date" value="<?= date('Y-m-d') ?>">
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
            <div class="col-sm-6 mx-auto">
                <div class="row">
                    <div class="mb-1" id="signature"></div>
                </div>
                <div class="col mt-xl-5 mt-sm-1" id="tools"></div>
                <input type="hidden" name="Sign_image" id="Sign_image" value="..." required>
            </div>
        </div>

        <div class="row mb-5 justify-content-center">
            <div class="col-10 col-sm-4 col-xl-3 me-0 align-self-center">
                <label class="ftilte fw-bold text-end mb-0 mt-0" for="start">วันที่</label>
                <input class="form-control ms-0 col-xl-1" type="date" name="Report_date_client" id="start" value="<?= date('Y-m-d') ?>" disabled>
            </div>

        </div>
        <div class="row">
            <div class="col-xl-6 col-6 col-sm-6 mx-auto mx-sm-auto mx-xl-auto">
                <p class="ftitle fw-bold text-center">อัพโหลดรูปภาพ</p>
                <input type="file" name="img_file[]" id="img_file" multiple="multiple" class="form-control" accept="image/*" required>
                <p class="fsub fw-bold text-danger">*กรุณาอัพโหลดรูปภาพ </p>
            </div>
        </div>
        <div class="row justify-content-around my-5 mt-xl-5">
            <div class="col-auto col-xl-3 d-flex">
                <a class="btn btn-secondary me-2 mx-xl-auto ftitle" href="indexUser.php" id="home">กลับสู่หน้าหลัก</a>
            </div>
            <div class="col-auto col-xl-3">
                <button class="btn btn-primary d-block mx-xl-auto" type="submit" name="send_approve" id="send_approve" onclick="checkUploadPhoto()">บันทึก</button>
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
                    'UndoButton': false
                }),
                $tools = $('#tools')

            $("#send_approve").on('click', function() {
                var data = $sigdiv.jSignature('getData', 'image');
                $("#Sign_image").val(data);
            });
            $('<input class="btn btn-secondary d-block mx-auto mt-3" type="button" value="ล้างลายเซ็น">').bind('click', function(e) {
                $sigdiv.jSignature('reset')
            }).appendTo($tools)
        })

    })(jQuery)
    var editor = CKEDITOR.replace('detail-report');
    editor.on('required', function(evt) {
        toastr.warning("กรุณากรอกรายละเอียดงาน");
        evt.cancel();
    });

    function checkStatusReport() {
        const finish = document.getElementById('finish');
        const follow = document.getElementById('follow');
        const date = document.getElementById('follow-date');

        if (follow.checked == true) {
            date.classList.remove('d-none');
            date.required = true;
        } else if (finish.checked == true) {
            date.classList.add('d-none');
            date.required = false;
        }
    }
    checkStatusReport();

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