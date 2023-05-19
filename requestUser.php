<?php
session_start();
if (!isset($_SESSION['id'])) {
    if(isset($_GET['Form_id'])){
        $_SESSION['Form_id'] = $_GET['Form_id'];
        $_SESSION['page_link'] = 1;
        header('location:index.php');
    }
}else if($_SESSION['status'] == 1){
    header('location:requestAdmin.php?Form_id='.$_GET['Form_id'].'');
}else{
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
$sql = $conn->query("SELECT * FROM itoss_jobtype;");
$jobChoice = $sql->fetchAll();
$sql = $conn->query("SELECT * FROM itoss_status_form");
$filter[3] = $sql->fetchAll();
$Form_id = $_GET["Form_id"];

if (isset($_POST['save'])) {
    $stmt = $conn->prepare("UPDATE itoss_form SET Form_date=?, Form_Name=?, Agency_id=?, Form_Phone=?, Form_Work=?, Status_form_id=?, User_id=? WHERE Form_id=?");
    $stmt->bindParam(1, $_SESSION['date']);
    $stmt->bindParam(2, $_POST["Form_Name"]);
    $stmt->bindParam(3, $_POST["Agency_id"]);
    $stmt->bindParam(4, $_POST["Form_Phone"]);
    $stmt->bindParam(5, $_POST["Form_Work"]);
    $stmt->bindParam(6, $_POST["Status_form_id"]);
    $stmt->bindParam(7, $_SESSION['id']);
    $stmt->bindParam(8, $Form_id);
    $stmt->execute();

    $stmt = $conn->query("DELETE FROM itoss_job WHERE Form_id = '$Form_id'");
    foreach ($_POST['Jobtype_id'] as $value) {

        if ($value == 0) {

            $stmt = $conn->prepare("INSERT INTO itoss_job VALUES ('', ?, ? , ?)");
            $stmt->bindParam(1, $value);
            $stmt->bindParam(2, $_POST["Jobtype_orther_name"]);
            $stmt->bindParam(3, $Form_id);
            $stmt->execute();
        } else {
            $stmt = $conn->prepare("INSERT INTO itoss_job VALUES ('', ?,'', ?)");
            $stmt->bindParam(1, $value);
            $stmt->bindParam(2, $Form_id);
            $stmt->execute();
        }
    }
    if ($_POST['other_agency']) {
        $stmt = $conn->query("DELETE FROM other_agency WHERE Form_id = '$Form_id'");
        $stmt = $conn->prepare("INSERT INTO other_agency VALUES ('',?,?)");
        $stmt->bindParam(1, $_POST["other_agency"]);
        $stmt->bindParam(2, $Form_id);
        $stmt->execute();
    }
    include("message.php");

        $_SESSION['ch'] = 2; //ส่งค่า ch ไปเช็คที่ indexUser
        echo '<script language="javascript">location.href="indexUser.php";</script>';

} else if (isset($_POST['cancel'])) {
    $stmt = $conn->query("UPDATE itoss_form SET Status_form_id=3 WHERE Form_id=" . $_POST['cancel'] . "");
    include("message.php");

    $_SESSION['ch'] = 3; //ส่งค่า ch ไปเช็คที่ indexUser
        echo '<script language="javascript">';
        echo 'location.href="indexUser.php"';
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


$stmt3 = $conn->query("SELECT * FROM itoss_text INNER JOIN itoss_user ON itoss_user.User_id = itoss_text.editor where Form_id = '$Form_id' ORDER BY Text_id DESC");
$row3 = $stmt3->fetch();
isset($row3['Text_name']) ? $text = $row3['Text_name'] : $text = "";
isset($row3['Status_form_id']) ? $Status = $row3['Status_form_id'] : $Status = $row['Status_form_id'];

$stmt4 = $conn->query("SELECT * FROM itoss_sign INNER JOIN itoss_user ON itoss_sign.User_id = itoss_user.User_id where itoss_sign.User_id = " . $row['User_id'] . "");
$signUser = $stmt4->fetch();
?>
<main>
    <div class="row justify-content-center mt-5 ">
        <div class="col-12 col-sm-12 col-xl d-block mx-auto ">
            <p class="text-dark text-center fhead fw-bold">คำขอปฏิบัติงาน</p>
            <p class="text-end ftitle text-danger">สถานะ : <?= $row['Status_form_name'] ?></p>
        </div>
    </div>
    <form action="requestUser.php?Form_id=<?= $Form_id ?> " method="post">
        <div class="row mb-0 mb-xl-3 mb-xl-0 d-none" id="editBox"> <!--แสดงข้อความ หากมีการแก้ไข/ไม่อนุมัติ-->
            <div class="col-12 col-xl-12 mb-3">
                <p class="ftitle d-inline fw-bold mb-0">รายละเอียดการแก้ไขงาน</p>
                <p class="d-inline"><?= $row3['User_Name'] ?></p>

                <div class="form-control text-light text-break" id="Detail" cols="30" rows="10">
                    <?= $text ?>
                </div>
            </div>
            <hr>
        </div>

        <div class="row justify-content-start mb-0 mb-xl-3" id="dsk">
            <div class="col-12 col-sm-6 col-xl-4 mb-2 mb-xl-0 text-break"> <!--ชื่อผู้ติดต่อ-->
                <label class="ftitle fw-bold form-label mb-0">ชื่อผู้ติดต่อ</label>
                <input type="hidden" name="Form_date" value="<?= $row['Form_date'] ?>">
                <input type="text" class="data form-control ftitle" name="Form_Name" id="contact" value="<?= $row["Form_Name"] ?>" disabled required>
                <input type="hidden" name="Status_form_id" value="1">
            </div>
            <div class="col-12 col-sm-6 col-xl-4 mb-2 mb-xl-0"> <!--หน่วยงาน-->
                <p class="ftitle fw-bold mb-0">หน่วยงาน</p>
                <select class="form-select data form-control ftitle" id="Agency_id" name="Agency_id" disabled required>
                    <option selected value="<?= $row["Agency_id"] ?>"><?= $agency ?></option>
                    <?php
                    for ($i = 1; $i < count($filter[0]); $i++) {

                        echo '<option value="' . $filter[0][$i]['Agency_id'] . '">' . $filter[0][$i]['Agency_Name'] . '</option>';
                    }
                    ?>
                    <option value="0">อื่นๆ</option>
                </select>
                <input class="d-none form-control mt-1" type="text" name="other_agency" id="other_agency" placeholder="กรอกชื่อหน่วยงาน">
            </div>
            <div class="col-12 col-sm-6 col-xl-4 mb-2 mb-xl-0 text-break"> <!--เบอร์โทรศัพท์-->
                <p class="ftitle fw-bold mb-0">เบอร์โทรศัพท์</p>
                <input type="text" class="data form-control ftitle" name="Form_Phone" value="<?= $row["Form_Phone"] ?>" disabled>
            </div>
        </div>
        <div class="row mb-0">
            <div class="col-12 col-lg-6 mb-0">
                <p class="ftitle fw-bold mb-0">ประเภทงาน</p>
            </div>
        </div>
        <div class="row mb-0 mb-xl-3">
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
            // print_r($job);
            if ($index !== false) {

                $ch = 'checked';
                $valueOther = $job[$index]['name_other'];
            } else {
                $ch = '';
                $valueOther = '';
            } ?>
            <div class="col-12 col-lg-2 mb-0">
                <div class="form-check">
                    <input type="checkbox" class="data form-check-input my-0 required" name="Jobtype_id[]" id="name0" value="0" onclick="deRequireCb('required')" disabled <?= $ch ?>>
                    <label class="form-check-label ms-1 my-0" for="name" id="labelOther">
                        อื่น ๆ
                    </label>
                </div>
                <div class="col col-sm-3">
                    <input class="d-none form-control mt-1 mb-2" type="text" name="Jobtype_orther_name" id="other_job" value="<?= $valueOther ?>" placeholder="กรอกประเภทงาน" disabled>
                </div>
            </div>
        </div>
        <div class="row mb-3 mb-xl-3 mb-xl-0">
            <div class="col-12 col-sm-12 col-xl-12 mb-2">
                <p class="ftitle fw-bold mb-0">รายละเอียดงาน</p>
                <div class="data form-control text-light text-break" name="Form_Work" id="show-detail" cols="30" rows="10">
                    <?= $row['Form_Work'] ?>
                </div>
                <textarea class="data form-control text-light d-none text-break" name="Form_Work" id="detail" cols="30" rows="10">
                        <?= $row['Form_Work'] ?>
                    </textarea>
            </div>
        </div>
        <div class="row mb-sm-0 mb-xl-5 ">
            <div class="col-12 col-xl-6 mx-xl-auto" id="userSignBox">
                <div class="col-12 col-xl-3 mx-xl-auto mb-3">
                    <p class="ftitle fw-bold mb-1 text-center">เจ้าหน้าที่ผู้รับผิดชอบ</p>
                </div>
                <div class="col-auto mx-auto col-xl-auto mx-xl-auto mb-xl-0 align-self-center">
                    <img class="d-block mx-auto w-50 h-auto" src="data:<?= $signUser['Sign_image'] ?>" alt="">
                </div>

                <div class="col-6 col-xl-6 mx-auto mb-5">
                    <input type="text" class="ftitle form-control text-center" id="name-user" name="User_Name" value="<?= $row['User_Name'] ?>" disabled>
                </div>
            </div>
        </div>
        <div class="row justify-content-center mb-5 mt-xl-5">
            <div class="col-auto col-xl-3 align-items-center" id="homeCol">
                <a class="btn btn-secondary mx-xl-auto ftitle col-sm-auto mx-sm-auto" href="indexUser.php" id="home">กลับสู่หน้าหลัก</a>
            </div>
            <div class="col-auto col-sm-4 col-xl-3" id="saveCol">
                <button class="btn btn-primary d-block me-auto ms-2 mx-xl-auto mx-sm-auto d-none" type="submit" name="save" id="save">บันทึก</button>
                <button class="btn btn-warning d-block ms-2 mx-xl-auto mx-sm-auto ftitle" type="button" id="edit" onclick="disableFalse()">แก้ไข</button>
            </div>
            <div class="col-auto col-xl-3" id="cancelCol">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalId" id="cancel">
                    ยกเลิก
                </button>
                
                <!-- Modal -->
                <div class="modal fade" id="modalId" tabindex="-1" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <p class="modal-title fhead fw-bold text-center">ยืนยันการยกเลิก</p>
                            </div>
                            <div class="modal-body my-3 my-xl-3 text-center">
                                <p class="ftitle text-center d-inline">คุณต้องการยกเลิกคำขอปฏิบัติงานหรือไม่  </p>
                            </div>
                            <div class="modal-footer justify-content-center">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">กลับ</button>
                                <button class="btn btn-danger ftitle" type="submit" name="cancel"  value="<?= $Form_id ?>">ยืนยัน</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>
</main>
<script>
    function deRequireCb(elClass) {
        el = document.getElementsByClassName(elClass);

        var atLeastOneChecked = false; //at least one cb is checked
        for (i = 0; i < el.length; i++) {
            if (el[i].checked === true) {
                atLeastOneChecked = true;
            }
        }

        if (atLeastOneChecked === true) {
            for (i = 0; i < el.length; i++) {
                el[i].required = false;
            }
        } else {
            for (i = 0; i < el.length; i++) {
                el[i].required = true;
            }
        }
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

    function disableFalse() {
        var data = document.getElementsByClassName('data');
        var editbtn = document.getElementById('edit');
        var homebtn = document.getElementById('home');
        var savebtn = document.getElementById('save');
        for (var i = 0; i < data.length; i++) {
            data[i].disabled = false;
        }
        document.getElementById('other_job').disabled = false;
        document.getElementById('show-detail').classList.add('d-none');
        document.getElementById('detail').classList.remove('d-none');
        document.getElementById('cancelCol').classList.add('d-none');
        editbtn.classList.add('d-none');
        savebtn.classList.remove('d-none');
        var editor = CKEDITOR.replace('detail');
        editor.on('required', function(evt) {
            toastr.warning("กรุณากรอก รายละเอียดงาน");
            evt.cancel();
        });
    }
    const status1 = <?= $Status1 ?> //ค่า status 
    const status = <?= $Status ?>;
    const box = document.getElementById('editBox');
    const topic = box.getElementsByTagName('p');
    var user = <?= $row['User_id'] ?>;
    var id = <?= $_SESSION['id'] ?>;

    const home = document.getElementById('home');
    const cancelCol = document.getElementById('cancelCol');
    const homeCol = document.getElementById('homeCol');
    const saveCol = document.getElementById('saveCol');
    const userSignBox = document.getElementById('userSignBox');
    var str;
    if (user == id) {
        // alert(status+' '+status1)

        if (status == 1) {
            userSignBox.classList.add('mx-auto');
        }
        if (status == 2 && status1 != 3) {
            box.classList.remove('d-none');
            topic[0].innerText = 'รายละเอียดที่ต้องการแก้ไข โดย ';
        } else if (status == 2 && status1 == 3) {
            userSignBox.classList.add('mx-auto');
            homeCol.classList.remove('ms-auto');
            home.classList.add('mx-xl-auto');
            home.classList.remove('ms-xl-auto', 'me-xl-0', 'me-2');
            home.classList.add('btn-primary');
            home.classList.remove('btn-secondary');
            saveCol.classList.add('d-none');
            cancelCol.classList.add('d-none');
            
        } else if (status == 4) {
            box.classList.remove('d-none');
            topic[0].innerText = 'สาเหตุที่ไม่อนุมัติ โดย';
            homeCol.classList.remove('ms-auto');
            home.classList.add('mx-auto');
            home.classList.remove('ms-auto', 'me-xl-5', 'me-2');
            home.classList.add('btn-primary');
            home.classList.remove('btn-secondary');
            cancelCol.classList.add('d-none');
            saveCol.classList.add('d-none');
        } else if (status == 5 || status == 3 ) {
            homeCol.classList.remove('ms-auto');
            home.classList.add('mx-xl-auto');
            home.classList.remove('me-xl-0', 'ms-xl-auto');
            home.classList.add('btn-primary');
            home.classList.remove('btn-secondary');
            saveCol.classList.add('d-none');
            cancelCol.classList.add('d-none');
        }
    } else {
        homeCol.classList.remove('ms-auto');
        home.classList.add('mx-xl-auto');
        home.classList.remove('me-xl-0', 'ms-xl-auto', 'me-2');
        home.classList.add('btn-primary');
        home.classList.remove('btn-secondary');
        saveCol.classList.add('d-none');
        cancelCol.classList.add('d-none');
    }

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
<?php
$conn = null;
?>
</body>

</html>