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
$jobChoice = $sql->fetchAll();
$sql = $conn->query("SELECT * FROM itoss_status_form");
$filter[3] = $sql->fetchAll();
$Form_id = $_GET["Form_id"];

if (isset($_POST['create-text'])) {

    $Status_form_id = $_POST["create-text"];

    $stmt = $conn->prepare("INSERT INTO itoss_text VALUES ('', ?, ?, ?, ?)");
    $stmt->bindParam(1, $_POST["Text_name"]);
    $stmt->bindParam(2, $Form_id);
    $stmt->bindParam(3, $_POST["create-text"]);
    $stmt->bindParam(4, $_SESSION['id']);
    $stmt->execute();

    $stmt = $conn->prepare("UPDATE itoss_form SET Status_form_id = '$Status_form_id' where Form_id = '$Form_id'");
    $stmt->execute();

    include("message.php");
    $_SESSION['ch'] = 2;
    echo '<script language="javascript">';
    echo 'location.href="indexAdmin.php"';
    echo '</script>';
} else if (isset($_POST['approve'])) {
    $Status_form_id = $_POST["approve"];
    $stmt = $conn->prepare("UPDATE itoss_form SET Status_form_id = '$Status_form_id' , assign_id = ".$_SESSION['id']." WHERE Form_id = '$Form_id'");
    $stmt->execute();

    include("message.php");
    $_SESSION['ch'] = 5;

    echo '<script language="javascript">';
    echo 'location.href="indexAdmin.php"';
    echo '</script>';
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

$sqlAdmin = $conn->query("SELECT * FROM itoss_sign INNER JOIN itoss_user ON itoss_sign.User_id = itoss_user.User_id where itoss_sign.User_id = ".$_SESSION['id']."");
$signAdmin = $sqlAdmin->fetch();

$stmt3 = $conn->query("SELECT * FROM itoss_text INNER JOIN itoss_user ON itoss_user.User_id = itoss_text.editor where Form_id = '$Form_id' ORDER BY Text_id DESC");
$row3 = $stmt3->fetch();
$editor = isset($row3['editor'])?$row3['editor']:'';

isset($row3['Text_name']) ? $text = $row3['Text_name'] : $text = "";
isset($row3['Status_form_id']) ? $Status = $row3['Status_form_id'] : $Status = $row['Status_form_id'];

$sql_user = $conn->query("SELECT * FROM itoss_sign INNER JOIN itoss_user ON itoss_sign.User_id = itoss_user.User_id where itoss_sign.User_id = " . $row['User_id'] . "");
$signUser = $sql_user->fetch();

include($_SESSION['navbar']);
?>
<main>
    <div class="row justify-content-center mt-5 ">
        <div class="col col-xl d-block mx-auto ">
            <p class="text-dark text-center fhead fw-bold">คำขอปฏิบัติงาน</p>
            <p class="text-end ftitle text-danger">สถานะ : <?= $row['Status_form_name'] ?></p>

        </div>
    </div>

    <form action="" method="post">
        <div class="modal fade" id="create-text" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="ftitle fw-bold text-center" id="text-status">วันที่สร้างคำขอปฏิบัติงาน</p>
                        <div class="col-xl-10 mx-auto">
                            <textarea class="data form-control" name="Text_name" id="text-detail" cols="30" rows="10">
                        </textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary d-block ms-auto" data-bs-dismiss="modal">ปิด</button>
                        <button type="submit" name="create-text" value="" id="addtext" class="btn btn-primary d-block me-auto">ส่ง</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <form action="" method="post">
        <div class="row mb-0 mb-xl-3 mb-xl-0 d-none" id="editBox">
            <div class="col-12 col-xl-12 mb-2">
                <p class="ftitle d-inline fw-bold mb-0">รายละเอียดการแก้ไขงาน</p>
                <p class="d-inline"><?= $row3['User_Name'] ?></p>
                <div class="form-control text-light" id="Detail" cols="30" rows="10">
                    <?= $text ?>
                </div>
            </div>
            <hr>
        </div>

        <div class="row justify-content-start mb-0 mb-xl-3" id="dsk">
            <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                <p class="ftitle fw-bold mb-0" id="demo">ชื่อผู้ติดต่อ</p>
                <input type="hidden" name="Form_date" value="<?= $row['Form_date'] ?>">
                <input type="text" class="data form-control ftitle" name="Form_Name" id="contact" value="<?= $row["Form_Name"] ?>" disabled>
            </div>
            <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                <p class="ftitle fw-bold mb-0">หน่วยงาน</p>
                <select class="form-select data form-control ftitle" id="Agency_id" name="Agency_id" disabled>
                    <option selected value="<?= $row["Agency_id"] ?>"><?= $agency ?></option>
                    <?php
                    echo $row["Agency_id"];
                    for ($i = 1; $i < count($filter[0]); $i++) {

                        echo '<option value="' . $filter[0][$i]['Agency_id'] . '">' . $filter[0][$i]['Agency_Name'] . '</option>';
                    }
                    ?>
                    <option value="0">อื่นๆ</option>
                </select>
                <input class="d-none form-control mt-1" type="text" name="other_agency" id="other_agency" placeholder="กรอกชื่อหน่วยงาน">
            </div>
            <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                <p class="ftitle fw-bold mb-0">เบอร์โทรศัพท์</p>
                <input type="text" class="data form-control ftitle" name="Form_Phone" value="<?= $row["Form_Phone"] ?>" disabled>
            </div>
        </div>
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
                    }
                }
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
            $index = array_search('0', array_column($job, 'Jobtype_id'));
            
            if (isset($index)) {
                $ch = 'checked';
                $valueOther = $job[$index]['name_other'];
            } else {
                $ch = '';
                $valueOther = '';
            } ?>
            <div class="col-12 col-lg-2 mb-0">
                <div class="form-check">
                    <input type="checkbox" class="data form-check-input mb-2 my-xl-0 required" name="Jobtype_id[]" id="name0" value="0" onclick="deRequireCb('required')" disabled <?= $ch ?>>
                    <label class="form-check-label ms-1 my-0" for="name" id="labelOther">
                        อื่น ๆ
                    </label>
                </div>
                <input class="data d-none form-control mt-1" type="text" name="Jobtype_orther_name" id="other_job" value="<?= $valueOther ?>" placeholder="กรอกประเภทงาน" disabled>
            </div>

        </div>
        </div>
        </div>
        <div class="row mb-0 mb-xl-3 mb-xl-0">
            <div class="col-12 col-xl-12 mb-2">
                <p class="ftitle fw-bold mb-0">รายละเอียดงาน</p>
                <div class="data form-control text-light" name="Form_Work" id="show-detail" cols="30" rows="10">
                    <?= $row['Form_Work'] ?>
                </div>
                <textarea class="data form-control text-light d-none" name="Form_Work" id="detail" cols="30" rows="10">
                        <?= $row['Form_Work'] ?>
                    </textarea>
            </div>
        </div>
        <div class="row mb-xl-5 mb-5">
        <div class="col-12 col-xl-6 mx-xl-auto" id="colSignUser">
                <div class="col-12 col-xl-3 mx-xl-auto mb-3">
                    <p class="ftitle fw-bold mb-1 text-center">เจ้าหน้าที่ผู้รับผิดชอบ</p>
                </div>
                <div class="row signBox my-3 my-xl-5">
                    <div class="col-auto mx-auto col-xl-auto mx-xl-auto mb-xl-0 align-self-center">
                        <?= $signUser['Sign_image'] ?>
                    </div>
                </div>
                <div class="col-6 col-xl-6 mx-auto mb-5">
                    <input type="text" class="ftitle form-control text-center" id="name-user" name="User_Name" value="<?= $signUser['User_Name'] ?>" disabled>
                </div>
            </div>
            <div class="col-12 col-xl-6 d-none" id="colSignAdmin">
                <div class="col-12 col-xl-6 mx-xl-auto mb-3">
                    <p class="ftitle fw-bold mb-1 text-center">ผู้มอบหมายงาน</p>
                </div>
                <div class="row mb-xl-0">
                    <div class="col-12 col-xl-12 mx-auto mb-xl-0">
                        <img class="d-block mx-auto w-75 h-auto" src="data:<?= $signAdmin['Sign_image'] ?>"><br>
                    </div>
                </div>
                <div class="col-6 col-xl-3 mx-auto">
                    <input type="text" class="ftitle form-control text-center" id="name-user" name="User_Name" value="<?= $signAdmin['User_Name'] ?>" disabled>

                </div>
            </div>
        </div>

        </div>

        <div class="row justify-content-around mb-3 mt-xl-5">
            <div class="col-auto col-xl-3 d-none" id="editStatus">
                <button class="btn btn-primary d-block ms-xl-auto" data-bs-toggle="modal" data-bs-target="#create-text" type='button' name='edit' id='edit' value="2">แก้ไข</button>
            </div>
            <div class="col-auto col-xl-3 d-none" id="approveStatus">
                <button class="btn btn-primary d-block mx-xl-auto" data-bs-toggle="modal" type='submit' name='approve' id='approve' value="5">อนุมัติ</button>
            </div>
            <div class="col-auto col-xl-3 d-none" id="disapprovedStatus">
                <button class="btn btn-primary d-block me-xl-auto" data-bs-toggle="modal" data-bs-target="#create-text" type='button' name='Disapproved' id='Disapproved' value="4">ไม่อนุมัติ</button>
            </div>
        </div>
        <div class="row justify-content-around mb-5 mt-xl-5">
            <div class="col-auto col-xl-3 d-flex" id="homeCol">
                <a class="btn btn-secondary me-2 ms-xl-auto mx-xl-auto ftitle" href="indexAdmin.php" id="home">กลับสู่หน้าหลัก</a>
            </div>
        </div>
    </form>
</main>



<script>
    function disableFalse() {
        var data = document.getElementsByClassName('data');
        var editbtn = document.getElementById('edit');
        var homebtn = document.getElementById('home');
        for (var i = 0; i < data.length; i++) {
            data[i].disabled = false;
        }
        document.getElementById('show-detail').classList.add('d-none');

        editbtn.classList.add('d-none');
        homebtn.innerText = "ยกเลิก";
        CKEDITOR.replace('detail');
    }

    $(document).ready(function() {
        $("#edit").click(function() {
            const topic = document.getElementById('text-status');
            topic.innerText = 'รายละเอียดที่ต้องการให้สมาชิกแก้ไข ';
            document.getElementById('addtext').value = 2;
        });
    });

    $(document).ready(function() {
        $("#Disapproved").click(function() {
            const topic = document.getElementById('text-status');
            topic.innerText = 'รายละเอียดที่ไม่อนุมัติเอกสารให้สมาชิก ';
            document.getElementById('addtext').value = 4;
        });
    });

    // $(document).ready(function() {
    //     const status = <?php echo $row['Status_form_id'] ?>;
    //     if (status == 5) {
    //         document.getElementById('send-text').classList.remove('d-none');
    //     }
    // });

    const status1 = <?= $Status1 ?> //ค่า status 
    const status = <?= $Status ?> //ค่า status 
    const box = document.getElementById('editBox');
    const topic = box.getElementsByTagName('p');
//button
    const btnEdit = document.getElementById('editStatus');
    const btnApprove = document.getElementById('approveStatus');
    const btnDisapp = document.getElementById('disapprovedStatus');
    const btnHome = document.getElementById('home');
    //button
    const signUser = document.getElementById('colSignUser');
    const signAdmin = document.getElementById('colSignAdmin');
    var str;
    if (status == 1) {
        btnEdit.classList.remove('d-none');
        btnApprove.classList.remove('d-none');
        btnDisapp.classList.remove('d-none');
    } else if (status == 2 && status1 != 1) {
        box.classList.remove('d-none');
        topic[0].innerText = 'รายละเอียดที่ต้องการแก้ไข โดย ';
        document.getElementById('homeCol').classList.remove('ms-auto');
        btnHome.classList.add('mx-auto');
        btnHome.classList.remove('ms-auto', 'me-xl-5', 'me-2');
        btnHome.classList.add('btn-primary');
        btnHome.classList.remove('btn-secondary');
        btnEdit.classList.add('d-none');
        btnApprove.classList.add('d-none');
        btnDisapp.classList.add('d-none');

    } else if (status == 3 && status1 != 1) {
        document.getElementById('homeCol').classList.remove('ms-auto');
        btnHome.classList.add('mx-auto');
        btnHome.classList.remove('ms-auto', 'me-xl-5', 'me-2');
        btnHome.classList.add('btn-primary');
        btnHome.classList.remove('btn-secondary');
        btnEdit.classList.add('d-none');
        btnApprove.classList.add('d-none');
        btnDisapp.classList.add('d-none');

    } else if (status == 4 && status1 != 1) {
        box.classList.remove('d-none');
        topic[0].innerText = 'สาเหตุที่ไม่อนุมัติ โดย';
        document.getElementById('homeCol').classList.remove('ms-auto');
        btnHome.classList.add('mx-auto');
        btnHome.classList.remove('ms-auto', 'me-xl-5', 'me-2');
        btnHome.classList.add('btn-primary');
        btnHome.classList.remove('btn-secondary');
        btnEdit.classList.add('d-none');
        btnApprove.classList.add('d-none');
        btnDisapp.classList.add('d-none');
    } else if (status == 2 && status1 == 1) {
        box.classList.remove('d-none');
        topic[0].innerText = 'รายละเอียดที่ต้องการแก้ไข โดย ';
        btnEdit.classList.remove('d-none');
        btnApprove.classList.remove('d-none');
        btnDisapp.classList.remove('d-none');
    } else if (status == 5) {
        signAdmin.classList.remove('d-none');
        document.getElementById('homeCol').classList.remove('ms-auto');
        btnHome.classList.add('mx-auto');
        btnHome.classList.remove('ms-auto', 'me-xl-5', 'me-2');
        btnHome.classList.add('btn-primary');
        btnHome.classList.remove('btn-secondary');
        btnEdit.classList.add('d-none');
        btnApprove.classList.add('d-none');
        btnDisapp.classList.add('d-none');
    }
    $('#Jobtype_id').change(function() {
        let a = $('#Jobtype_id').val();
        if (a == "0") {
            $('#Jobtype_orther_name').removeClass('d-none');
            document.getElementById('Jobtype_orther_name').required = true;
        } else {
            $('#Jobtype_orther_name').addClass('d-none');
            document.getElementById('Jobtype_orther_name').required = false;
        }
    });

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

    CKEDITOR.replace('text-detail');
</script>
</body>
<?php

$conn = null;
?>

</html>