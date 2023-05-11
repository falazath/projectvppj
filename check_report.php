<?php
session_start();
include('header.html');
include("connect.php");
if (!isset($_SESSION['id'])) {
    header('location:login.php');
}
include($_SESSION['navbar']);

$sql = $conn->query("SELECT * FROM itoss_jobtype;");
$jobChoice = $sql->fetchAll();
$Form_id = $_GET["Form_id"];

if (isset($_POST['save'])) {
    $num = $_POST['index'];
        $stmt = $conn->prepare("UPDATE itoss_report SET Report_Detail=?, Report_Start_Date=?, Report_Stop_Date=?, Report_Status=?, Report_follow_date=?, Report_date_user=?, Report_sign_client=?, Report_date_client=? WHERE Report_id=?");
        $stmt->bindParam(1, $_POST["Report_Detail"][$num]);
        $stmt->bindParam(2, $_POST["Report_Start_Date"][$num]);
        $stmt->bindParam(3, $_POST["Report_Stop_Date"][$num]);
        $stmt->bindParam(4, $_POST["Report_Status"][$num]);
        $stmt->bindParam(5, $_POST["Report_follow_date"][$num]);
        $stmt->bindParam(6, $_POST["Report_date_user"][$num]);
        $stmt->bindParam(7, $_POST["Sign_id"]);
        $stmt->bindParam(8, $_POST["Report_date_client"][$num]);
        $stmt->bindParam(9, $_POST['save']);
        $stmt->execute();
        $stmt = $conn->prepare("UPDATE itoss_sign SET Sign_image=? WHERE Sign_id = ?");
        $stmt->bindParam(1, $_POST['Sign_image']);
        $stmt->bindParam(2, $_POST['Sign_id']);
        $stmt->execute();
        $stmt = $conn->query("UPDATE itoss_form SET Status_form_id=".$_POST["Report_Status"][$num]." WHERE Form_id=".$Form_id."");
        
        $_SESSION['ch'] = 4;
        echo '<script language="javascript">';
        echo 'location.href="indexUser.php"';
        echo '</script>';
} else if (isset($_POST['success'])) {
    $stmt = $conn->prepare("UPDATE itoss_form SET Status_form_id = 8 where Form_id = '$Form_id'");
    $stmt->execute();

    include("message.php");
    $_SESSION['ch'] = 8;
    echo '<script language="javascript">';
    echo 'location.href="indexAdmin.php"';
    echo '</script>';
} else if (isset($_POST['editPhoto'])) {
    $Report_id = $_POST['editPhoto'];
    $stmt = $conn->query("DELETE FROM itoss_img WHERE Report_id = '$Report_id'");
    $count = count($_FILES['img_file']['name']);
    if ($count >= 3 && $count <= 5) {
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
    } else {
        echo "ERROR";
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

$sql_job = $conn->query("SELECT itoss_job.Job_id,itoss_job.Jobtype_id,itoss_job.name_other,itoss_jobtype.Jobtype_name 
FROM itoss_job,itoss_jobtype WHERE itoss_job.Jobtype_id = itoss_jobtype.Jobtype_id AND itoss_job.Form_id = '$Form_id'");
$job = $sql_job->fetchAll();

$sql = $conn->query("SELECT * FROM other_agency WHERE Form_id = '$Form_id' ORDER BY id DESC LIMIT 1");
$data = $sql->fetch();
$agency = isset($data['name']) ? $data['name'] : $row['Agency_Name'];

$sqlAdmin = $conn->query("SELECT * FROM itoss_sign INNER JOIN itoss_user ON itoss_sign.User_id = itoss_user.User_id where itoss_sign.User_id = " . $row['assign_id'] . "");
$signAdmin = $sqlAdmin->fetch();

$stmt5 = $conn->query("SELECT * FROM itoss_report INNER JOIN itoss_sign ON itoss_report.Report_sign_client = itoss_sign.Sign_id where itoss_report.Form_id = '$Form_id' ORDER BY Report_id ASC");
$report = $stmt5->fetchAll();

$User_id = $_SESSION['id'];
$sql_user = $conn->query("SELECT * FROM itoss_sign INNER JOIN itoss_user ON itoss_sign.User_id = itoss_user.User_id where itoss_sign.User_id = " . $row['User_id'] . "");
$signUser = $sql_user->fetch();

?>
<main>
    <p id="show"></p>
    <div class="row justify-content-center mt-5 ">
        <div class="col col-sm-3 col-xl-3 d-block mx-auto ">
            <p class="text-dark text-center fhead fw-bold">คำขอปฏิบัติงาน</p>
        </div>
    </div>
    <div class="col-auto col-xl-2 ms-auto">
        <a class="btn btn-outline-success col-auto d-block ms-xl-auto me-xl-0 me-auto ms-2 " href="fileprint.php?Form_id=<?= $Form_id ?>" target="_blank">พิมพ์เอกสาร</a>
    </div>

    <form action="" method="post">
        <div class="row justify-content-start mb-0 mb-xl-3" id="dsk">
            <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                <p class="ftitle fw-bold mb-0" id="demo">ชื่อผู้ติดต่อ</p>
                <input type="hidden" name="Form_date" value="<?= $row['Form_date'] ?>">
                <input type="text" class="form-control ftitle" name="Form_Name" id="contact" value="<?= $row["Form_Name"] ?>" disabled>
                <input type="hidden" name="Status_form_id" value="1">
            </div>
            <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                <p class="ftitle fw-bold mb-0">หน่วยงาน</p>
                <input class="form-control" type="text" value="<?= $agency ?>" disabled>
            </div>
            <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                <p class="ftitle fw-bold mb-0">เบอร์โทรศัพท์</p>
                <input type="text" class="form-control ftitle" name="Form_Phone" value="<?= $row["Form_Phone"] ?>" disabled>
            </div>
        </div>
        <div class="row mb-2 mb-xl-3 mb-xl-0">
            <div class="col-12 col-xl-12 mb-2 mb-xl-3">
                <p class="ftitle fw-bold mb-0">รายละเอียดงาน</p>
                <div class="form-control text-light" name="Form_Work" id="Detail" cols="30" rows="10">
                    <?= $row['Form_Work'] ?>
                </div>
            </div>
        </div>
        <div class="row mb-3 mb-xl-3">
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
                                    <input type="checkbox" class="form-check-input my-0 required" name="Jobtype_id[]" id="name<?= $jobChoice[$i]['Jobtype_id'] ?>" value="<?= $jobChoice[$i]['Jobtype_id'] ?>" onclick="deRequireCb('required')" disabled <?= $ch ?>>
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
                if ($index) {
                    $ch = 'checked';
                    $valueOther = $job[$index]['name_other']; ?>

                    <div class="col-12 col-lg-2 mb-0">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input mb-2 my-xl-0 required" name="Jobtype_id[]" id="name0" value="0" onclick="deRequireCb('required')" disabled <?= $ch ?>>
                            <label class="form-check-label ms-1 my-0" for="name" id="labelOther">
                                อื่น ๆ
                            </label>
                        </div>
                        <input class="d-none form-control mt-1" type="text" name="Jobtype_orther_name" id="other_job" value="<?= $valueOther ?>" placeholder="กรอกประเภทงาน" disabled>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="row mb-xl-5 ">
            <div class="col-12 col-xl-6 mx-xl-auto" id="userSignBox">
                <div class="col-12 col-xl-3 mx-xl-auto mb-3">
                    <p class="ftitle fw-bold mb-1 text-center">เจ้าหน้าที่ผู้รับผิดชอบ</p>
                </div>
                <div class="col-auto mx-auto col-xl-auto mx-xl-auto mb-xl-0 align-self-center">
                    <img class="w-100 h-auto" src="data:<?= $signUser['Sign_image'] ?>" alt="">
                </div>
                <div class="col-6 col-xl-6 mx-auto mb-5">
                    <input type="text" class="ftitle form-control text-center" id="name-user" value="<?= $row['User_Name'] ?>" disabled>
                </div>
            </div>
            <div class="col-12 col-xl-6 mx-xl-auto">
                <div class="col-12 col-xl-3 mx-xl-auto mb-3">
                    <p class="ftitle fw-bold mb-1 text-center">ผู้มอบหมายงาน</p>
                </div>
                <div class="col-auto mx-auto col-xl-auto mx-xl-auto mb-xl-0 align-self-center">
                    <img class="w-100 h-auto" src="data:<?= $signAdmin['Sign_image'] ?>" alt="">
                </div>
                <div class="col-6 col-xl-6 mx-auto mb-5">
                    <input type="text" class="ftitle form-control text-center" id="name-user" value="<?= $signAdmin['User_Name'] ?>" disabled>
                </div>
            </div>
        </div>
    </form>
    <br><br>
    <hr>
    <!--ส่วนรายงาน-->
    <form action="" method="post">
        <div class="row justify-content-center mt-5 ">
            <div class="col col-sm-3 col-xl-3 d-block mx-auto ">
                <p class="text-dark text-center fhead fw-bold" id="text">รายงานการปฏิบัติงาน</p>
            </div>
        </div>
        <?php
        for ($i = 0; $i < count($report); $i++) {
            $class = "";
            $a = "";
            $none = "";
            if ($i == count($report) - 1) {
                $class = "data";
                $a = "a";
                $none = "d-none";
            }
        ?>
            <div class="row mb-0 mb-xl-3 mb-xl-0">
                <div class="col-auto col-xl-6">
                    <p class="ftitle fw-bold mb-1">รายละเอียดงาน</p>
                </div>
                <div class="col-auto mb-1 col-xl-6 ms-auto">
                    <button class="btn btn-outline-success d-block ms-xl-auto me-xl-0" type="button" data-bs-toggle="modal" data-bs-target="#show-img<?= $i ?>">แสดงรูปภาพ</button>
                </div>
                <div class="col-12 col-xl-12 mb-3">

                    <!-- <div class="col-auto col-xl-3">
                    </div> -->
                    <?php
                    if ($i == count($report) - 1) {
                    ?>
                        <div class="form-control text-light" id="showDetail">
                            <?= $report[$i]['Report_Detail'] ?>
                        </div>
                        <textarea class="<?= $a ?> form-control text-light d-none" name="Report_Detail[<?= $i ?>]" id="detail" cols="30" rows="10" required>
                                <?= $report[$i]['Report_Detail'] ?>
                            </textarea>

                    <?php
                    } else {
                    ?>
                        <div class="form-control text-light" id="detailBox">
                            <?= $report[$i]['Report_Detail'] ?>
                        </div>
                        <textarea class="<?= $a ?> form-control text-light d-none" name="Report_Detail[<?= $i ?>]" cols="30" rows="10" required>
                                <?= $report[$i]['Report_Detail'] ?>
                            </textarea>

                    <?php
                    }
                    ?>
                </div>
            </div>
            <div class="row mb-5 mb-xl-5">
                <div class="col-xl-4">
                    <p class="ftilte fw-bold">เวลาเริ่มดำเนินงาน</p>
                    <input class="<?= $class ?> form-control" type="datetime" name="Report_Start_Date[<?= $i ?>]" id="Report_Start_Date" value="<?= $report[$i]['Report_Start_Date'] ?>" disabled>
                </div>
                <div class="col-xl-4">
                    <p class=" ftilte fw-bold">เวลาเสร็จสิ้นการดำเนินงาน</p>
                    <input class="<?= $class ?> form-control" type="datetime" name="Report_Stop_Date[<?= $i ?>]" id="Report_Stop_Date" value="<?= $report[$i]['Report_Stop_Date'] ?>" disabled>
                </div>
                <div class="col-xl-3">
                    <p class="ftilte fw-bold">สถานะ:</p>

                    <?php
                    if ($report[$i]['Report_Status'] == 6) {
                        $follow = 'checked';
                        $finish = '';
                    } else if ($report[$i]['Report_Status'] == 7) {
                        $follow = '';
                        $finish = 'checked';
                    }
                    ?>
                    <div class="row">
                        <div class="col-6 col-xl-6 form-check">
                            <input class="<?= $class ?> finish form-check-input mx-auto me-2" type="radio" name="Report_Status[<?= $i ?>]" value="7" id="finish<?= $i ?>" onclick="checkStatusReport()" disabled <?= $finish ?>>
                            <label class="form-check-label ftitle" for="finish">
                                ปิดงาน
                            </label>
                        </div>
                        <div class="col-6 col-xl-6 form-check">
                            <input class="<?= $class ?> follow form-check-input me-2" type="radio" name="Report_Status[<?= $i ?>]" id="follow<?= $i ?>" value="6" onclick="checkStatusReport()" disabled <?= $follow ?>>
                            <label class="form-check-label ftitle" for="follow" id="lab-other">
                                ติดตามงาน
                            </label>
                        </div>

                        <div class="col-xl-12">
                            <?php
                            if ($i == count($report) - 1) {
                                echo '<input type="date" class="data form-control mt-xl-2" name="Report_follow_date['.$i.']" id="follow-date' . $i . '" value="' . date('Y-m-d', strtotime($report[$i]['Report_follow_date'])) . '" disabled>';
                            } else {
                                echo '<input type="date" class="form-control mt-xl-2" name="Report_follow_date['.$i.']" id="follow-date' . $i . '" value="' . date('Y-m-d', strtotime($report[$i]['Report_follow_date'])) . '" disabled>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row mb-xl-5 ">
                <div class="col-12 col-xl-6 mx-xl-auto" id="userSignBox">
                    <div class="col-12 col-xl-3 mx-xl-auto mb-3">
                        <p class="ftitle fw-bold mb-1 text-center">เจ้าหน้าที่ผู้รับผิดชอบ</p>
                    </div>
                    <div class="col-auto mx-auto col-xl-auto mx-xl-auto mb-xl-0 align-self-center">
                        <img class="w-100 h-auto" src="data:<?= $signUser['Sign_image'] ?>" alt="">
                    </div>
                    <div class="col-6 col-xl-6 mx-auto mb-5">
                        <label class="ftilte fw-bold text-end mb-0 mt-0" for="start">วันที่</label>
                        <input class="<?= $class ?> form-control ms-0  col-xl-1" type="date" name="Report_date_user[<?= $i ?>]" id="start" value="<?= date('Y-m-d', strtotime(isset($report[$i]['Report_date_user'])?$report[$i]['Report_date_user']:"now")) ?>" disabled>
                    </div>
                </div>

                <div class="col-12 col-xl-6 mx-xl-auto">
                    <div class="col-12 col-xl-3 mx-xl-auto mb-3">
                        <p class="ftitle fw-bold mb-1 text-center">ผู้ใช้บริการ</p>
                    </div>

                    <?php
                    if ($i == count($report) - 1) {
                    ?>
                        <div class="col-auto mx-auto col-xl-12 mx-xl-auto mb-xl-0" id="sent_img">
                            <img class="d-block w-100 h-100" src="data:<?= $report[$i]['Sign_image'] ?>" alt="">
                        </div>
                        <div class="col-xl-12 mx-auto <?= $none ?>" id="signa">
                            <input type="hidden" name="Sign_id" id="Sign_id" value="<?= $report[$i]['Sign_id'] ?>" required>
                            <input type="hidden" name="Sign_image" id="Signimg" value="..." required>
                            <div class="mb-3 col-12" id="signature"></div>
                            <div class="col mt-xl-5" id="tools"></div>
                        </div>
                    <?php
                    } else {
                    ?>
                        <div class="col-auto mx-auto col-xl-auto mx-xl-auto mb-xl-0 align-self-center">
                            <img class="w-100 h-auto" src="data:<?= $report[$i]['Sign_image'] ?>" alt="">
                        </div>
                    <?php
                    }
                    ?>
                    <div class="col-6 col-xl-6 mx-auto mb-5">
                        <label class="ftilte fw-bold text-end mb-0 mt-0" for="client">วันที่</label>
                        <input class="<?= $class ?> form-control ms-0  col-xl-1" type="date" name="Report_date_client[<?= $i ?>]" id="client" value="<?= $report[$i]['Report_date_client'] ?>" disabled>
                    </div>

                </div>

            </div>
            <div class="modal fade" id="show-img<?= $i ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"><!--แสดงรูปภาพการปฏิบัติงาน-->
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <p class="ftitle fw-bold text-center modal-title">รูปภาพการปฏิบัติงาน</p>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <?php
                            $stmt = $conn->query("SELECT * FROM itoss_img WHERE Report_id = " . $report[$i]['Report_id'] . "");
                            while ($pic = $stmt->fetch()) { ?>
                                <img class="w-100 h-auto me-0 text-center mb-4" src="<?= $pic['img_file'] ?>">
                            <?php } ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary mx-auto" data-bs-dismiss="modal">กลับ</button>
                            <?php
                            if ($row['User_id'] == $_SESSION['id']) {
                                echo '<button type="button" class="btn btn-primary mx-auto" data-bs-toggle="modal" data-bs-target="#upload" id="newUpload">อัพโหลดใหม่</button>';
                            }
                            ?>

                        </div>
                    </div>
                </div>
            </div>
            <hr class="my-5">


        <?php
        }
        ?>
        <div class="row justify-content-center mb-5 mt-xl-5">
            <div class="col-auto col-xl-3 d-flex" id="homeCol">
                <?php
                if ($_SESSION['status'] == 1) {
                ?>
                    <a class="btn btn-secondary me-2 mx-xl-auto ftitle" href="indexAdmin.php" id="home">กลับสู่หน้าหลัก</a>
                <?php
                } else if ($_SESSION['status'] == 2) {
                ?>
                    <a class="btn btn-secondary me-2 mx-xl-auto ftitle" href="indexUser.php" id="home">กลับสู่หน้าหลัก</a>
                <?php
                }
                ?>
            </div>
            <div class="col-auto col-xl-3" id="saveCol">
                <input type="hidden" name="index" value="<?= count($report) - 1 ?>">
                <button class="btn btn-primary d-block mx-auto mx-xl-auto ftitle d-none" type="submit" name="save" id="save" value="<?= $report[count($report) - 1]['Report_id'] ?>" onclick="checkEmpty()">บันทึก</button>

                <?php
                if ($report[count($report) - 1]['Report_Status'] == 7) {
                    if ($_SESSION['status'] == 1) {
                        $conch = 'd-none';
                ?>
                        <button class="btn btn-primary d-block me-auto ms-2 ms-xl-5 ftitle" type="submit" id="edit" name="success">เสร็จสิ้น</button>
                    <?php
                    } else if ($_SESSION['status'] == 2) {
                    ?>
                        <button class="btn btn-warning d-block ms-2 mx-xl-auto ftitle" type="button" id="edit" onclick="disableFalse()">แก้ไข</button>
                        <div id="continueCol"></div>
                <?php
                    }
                    echo '</div>';
                }
                if($row['Status_form_id'] == 8){
                   echo '<div id="continueCol"></div>';
                    
                }
                ?>

                <?php
                if ($report[count($report) - 1]['Report_Status'] == 6 && $_SESSION['status'] == 2) {
                ?>
                    <button class="btn btn-warning d-block ms-2 mx-xl-auto ftitle" type="button" id="edit" onclick="disableFalse()">แก้ไข</button>
            </div>
            <div class="col-auto col-xl-3 align-self-center <?= isset($conch) ? $conch : '' ?>" id="continueCol">
                <a href="create_report.php?Form_id=<?= $Form_id ?>" class="btn btn-primary col-xl-5 col-sm-4  d-block mx-xl-auto ftitle">ดำเนินงานต่อ</a>
            </div>
        <?php
                }
        ?>
        </div>
    </form>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="modal fade" id="upload" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"><!--อัพรูปภาพการปฏิบัติงาน-->
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <p class="ftitle fw-bold text-center">อัพโหลดตรงนี้</p>
                        <p class="fsub fw-bold text-danger">*อัพโหลดได้เฉพาะรูปภาพเท่านั้น(.jpg , .png , .svg) </p>
                        <input type="file" name="img_file[]" multiple="multiple" class="form-control" accept="image/*" required> <br>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary mx-auto" data-bs-dismiss="modal">กลับ</button>
                        <button type="submit" class="btn btn-primary mx-auto" name="editPhoto" value="<?= $report[0]['Report_id'] ?>">อัพโหลด</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

</main>
<script src="./libs/jquery.js"></script>
<script src="./libs/jSignature.min.noconflict.js"></script>
<script>
    var id = '<?= $row['Status_form_id'] ?>';
    if (id == 8) {
        document.getElementById('home').classList.remove('btn-secondary');
        document.getElementById('home').classList.remove('ms-auto');
        document.getElementById('home').classList.remove('me-2');
        document.getElementById('home').classList.remove('me-xl-5');
        document.getElementById('home').classList.add('btn-primary');
        document.getElementById('home').classList.add('mx-auto');
        document.getElementById('homeCol').classList.add('mx-auto');
        document.getElementById('homeCol').classList.remove('ms-auto');
        document.getElementById('saveCol').classList.add('d-none');
        document.getElementById('continueCol').classList.add('d-none');
    }
    checkStatusReport();

    function checkStatusReport() {
        const finish = document.getElementsByClassName('finish');
        const follow = document.getElementsByClassName('follow');
        for (i = 0; i < finish.length; i++) {
            if (follow[i].checked == true) {
                document.getElementById('follow-date' + i).classList.remove('d-none');
                document.getElementById('follow-date' + i).required = true;
            } else if (finish[i].checked == true) {
                document.getElementById('follow-date' + i).classList.add('d-none');
                document.getElementById('follow-date' + i).required = false;
            }
        }
    }

    function disableFalse() {
        var data = document.getElementsByClassName('data');
        var editbtn = document.getElementById('edit');
        var homebtn = document.getElementById('home');
        var savebtn = document.getElementById('save');
        for (var i = 0; i < data.length; i++) {
            data[i].disabled = false;
        }
        const conCol = document.getElementById('continueCol');
        conCol.classList.add('d-none');
        document.getElementById('showDetail').classList.add('d-none');
        document.getElementById('detail').classList.remove('d-none');

        document.getElementById('signa').classList.remove('d-none');
        document.getElementById('sent_img').classList.add('d-none');

        editbtn.classList.add('d-none');
        savebtn.classList.remove('d-none');
        var a = document.getElementsByClassName('a');

        var editor = CKEDITOR.replace('detail');
        editor.on('required', function(evt) {
            toastr.warning("กรุณากรอก รายละเอียดงาน");
            evt.cancel();
        });
        (function($) {
            $(document).ready(function() {
                var $sigdiv = $("#signature").jSignature({
                        'UndoButton': false
                    }),
                    $tools = $('#tools')
                $("#save").on('click', function() {
                    var data = $sigdiv.jSignature('getData', 'image');
                    $("#Signimg").val(data);
                });
                $('<input class="btn btn-secondary d-block mx-auto" type="button" value="ล้างลายเซ็น">').bind('click', function(e) {
                    $sigdiv.jSignature('reset')
                }).appendTo($tools)
            })
        })(jQuery)

    }
    const otherJob = document.getElementById('name0');
    const inpOther = document.getElementById('other_job');
    if (otherJob.checked == true) {
        inpOther.classList.remove('d-none');
        inpOther.required = true;
    } else if (otherJob.checked == false) {

        inpOther.classList.add('d-none');
        inpOther.required = false;
    }
    $('#name0').click(function() {
        const otherJob = document.getElementById('name0');
        const inpOther = document.getElementById('other_job');
        if (otherJob.checked == true) {
            inpOther.classList.remove('d-none');
            inpOther.required = true;
        } else if (otherJob.checked == false) {

            inpOther.classList.add('d-none');
            inpOther.required = false;
        }
    });
    // const finish = document.getElementById('finish0');
    // const follow = document.getElementById('follow0');

    // finish.addEventListener("click", function() {
    //     var date = document.getElementById('follow-date').classList.add('d-none');
    // })
    // follow.addEventListener("click", function() {
    //     var date = document.getElementById('follow-date').classList.remove('d-none');
    // })

    // function showSig() {
    //     const pic = document.getElementById('picSig');
    //     pic.src = "./asset/exSignature.png";
    // }
</script>

</main>
</body>

</html>