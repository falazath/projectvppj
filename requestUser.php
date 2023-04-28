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

if (isset($_POST['save'])) {
    $stmt = $conn->prepare("UPDATE itoss_form SET Form_date=?, Form_Name=?, Agency_id=?, Form_Phone=?, Jobtype_id=?, Form_Work=?, Status_form_id=?, User_id=? WHERE Form_id=?");
    $stmt->bindParam(1, $_SESSION['date']);
    $stmt->bindParam(2, $_POST["Form_Name"]);
    $stmt->bindParam(3, $_POST["Agency_id"]);
    $stmt->bindParam(4, $_POST["Form_Phone"]);
    $stmt->bindParam(5, $_POST["Jobtype_id"]);
    $stmt->bindParam(6, $_POST["Form_Work"]);
    $stmt->bindParam(7, $_POST["Status_form_id"]);
    $stmt->bindParam(8, $_SESSION['id']);
    $stmt->bindParam(9, $Form_id);
    $stmt->execute();

    if ($_POST['Jobtype_orther_name']) {
        $stmt = $conn->prepare("UPDATE itoss_jobtype_orther SET Jobtype_orther_name=? WHERE Form_id=?");
        $stmt->bindParam(1, $_POST["Jobtype_orther_name"]);
        $stmt->bindParam(2, $Form_id);
        $stmt->execute();
    }
    if ($_POST['other_agency']) {
        $stmt = $conn->query("DELETE FROM other_agency WHERE Form_id = '$Form_id'");
        $stmt = $conn->prepare("INSERT INTO other_agency VALUES ('',?,?)");
        $stmt->bindParam(1, $_POST["other_agency"]);
        $stmt->bindParam(2, $Form_id);
        $stmt->execute();
    }

    include("message.php");

    if($_SESSION['status'] == 1){
        echo '<script language="javascript">';
    echo 'location.href="indexAdmin.php"';
    echo '</script>';

    }else if($_SESSION['status'] == 2){
        echo '<script language="javascript">';

        echo 'location.href="indexUser.php"';
    echo '</script>';

    }
} else if (isset($_POST['cancel'])) {
    $stmt = $conn->query("UPDATE itoss_form SET Status_form_id=3 WHERE Form_id=" . $_POST['cancel'] . "");
    include("message.php");

    if($_SESSION['status'] == 1){
        echo '<script language="javascript">';
    echo 'location.href="indexAdmin.php"';
    echo '</script>';

    }else if($_SESSION['status'] == 2){
        echo '<script language="javascript">';

        echo 'location.href="indexUser.php"';
    echo '</script>';

    }
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

$stmt2 = $conn->query("SELECT * FROM itoss_sign INNER JOIN itoss_user ON itoss_sign.User_id = itoss_user.User_id where itoss_sign.User_id = 1");
$row2 = $stmt2->fetch();

$stmt3 = $conn->query("SELECT * FROM itoss_text where Form_id = '$Form_id' ORDER BY Text_id DESC");
$row3 = $stmt3->fetch();
isset($row3['Text_name']) ? $text = $row3['Text_name'] : $text = "";
isset($row3['Status_form_id']) ? $Status = $row['Status_form_id'] : $Status = $row3['Status_form_id'];

$stmt4 = $conn->query("SELECT * FROM itoss_sign INNER JOIN itoss_user ON itoss_sign.User_id = itoss_user.User_id where itoss_sign.User_id = " . $row['User_id'] . "");
$row4 = $stmt4->fetch();
include($_SESSION['navbar']);
echo 'asfsdfasdfasdfsadfsadfsd'.$Status;
?>
<main>
    <div class="row justify-content-center mt-5 ">
        <div class="col col-sm-3 col-xl-3 d-block mx-auto ">
            <p class="text-dark text-center fhead fw-bold">คำขอปฏิบัติงาน</p>
            <p class="text-end ftitle text-danger">สถานะ : <?= $row['Status_form_name'] ?></p>
        </div>
    </div>

    <form action="requestUser.php?Form_id=<?= $Form_id ?> " method="post">
        <div class="row mb-0 mb-xl-3 mb-xl-0 d-none" id="editBox">
            <div class="col-12 col-xl-12 mb-3">
                <p class="ftitle fw-bold mb-0">รายละเอียดการแก้ไขงาน</p>
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
                <input type="hidden" name="Status_form_id" value="1">
            </div>
            <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                <p class="ftitle fw-bold mb-0">หน่วยงาน</p>
                <select class="form-select data form-control ftitle" id="Agency_id" name="Agency_id" disabled>
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
            <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                <p class="ftitle fw-bold mb-0">เบอร์โทรศัพท์</p>
                <input type="text" class="data form-control ftitle" name="Form_Phone" value="<?= $row["Form_Phone"] ?>" disabled>
            </div>
        </div>
        <div class="row mb-0 mb-xl-3">
            <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                <p class="ftitle fw-bold mb-0">ประเภทงาน</p>
                <select class="form-select data form-control ftitle" id="Jobtype_id" name="Jobtype_id" disabled>
                    <option selected value="<?= $row["Jobtype_id"] ?>"><?= $row["Jobtype_name"] ?></option>
                    <?php
                    for ($i = 1; $i < count($filter[2]); $i++) {

                        echo '<option value="' . $filter[2][$i]['Jobtype_id'] . '">' . $filter[2][$i]['Jobtype_name'] . '</option>';
                    }
                    ?>
                    <option value="0">อื่นๆ</option>
                </select>
                <input type="text" class="d-none data form-control ftitle mt-1" name="Jobtype_orther_name" id="Jobtype_orther_name" value="<?= $job_other ?>" placeholder="กรอกประเภทงาน" disabled>
            </div>
        </div>
        <div class="row mb-3 mb-xl-3 mb-xl-0">
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
        <div class="row mb-xl-5 ">
            <div class="col-12 col-xl-6">
                <div class="col-12 col-xl-3 mx-xl-auto mb-3">
                    <p class="ftitle fw-bold mb-1 text-center">เจ้าหน้าที่ผู้รับผิดชอบ</p>
                </div>
                <div class="row mb-xl-0">
                    <div class="col-12 col-xl-12 mx-auto mb-xl-0">
                        <img class="d-block mx-auto w-75 h-auto" src="data:<?= $row4['Sign_image'] ?>"><br>
                    </div>
                </div>
                <div class="col-6 col-xl-3 mx-auto mb-5">
                <input type="text" class="ftitle form-control text-center" id="name-user" name="User_Name" value="<?= $row['User_Name'] ?>" disabled>


                </div>
            </div>
            <div class="col-12 col-xl-6 mb-5">
                <div class="col-12 col-xl-6 mx-xl-auto mb-3">
                    <p class="ftitle fw-bold mb-1 text-center">ผู้มอบหมายงาน</p>
                </div>
                <div class="row mb-xl-0">
                    <div class="col-12 col-xl-12 mx-auto mb-xl-0">
                        <img class="d-block mx-auto w-75 h-auto" src="data:<?= $row2['Sign_image'] ?>"><br>
                    </div>
                </div>
                <div class="col-6 col-xl-3 mx-auto">
                    <input type="text" class="ftitle form-control text-center" id="name-user" name="User_Name" value="<?= $row2['User_Name'] ?>" disabled>

                </div>
            </div>
        </div>
        <div class="row justify-content-around mb-5 mt-xl-5">
            <div class="col-auto col-xl-6 d-flex" id="homeCol">
                <a class="col-xl-3 btn btn-secondary  me-2 me-xl-5 ftitle" href="indexUser.php" id="home">กลับสู่หน้าหลัก</a>
            </div>
            <div class="col-auto col-xl-6" id="saveCol">
                <button class="btn btn-primary d-block me-auto ms-2 ms-xl-5 d-none" type="submit" name="save" id="save">บันทึก</button>
                <button class="btn btn-primary ms-2 ms-xl-5 ftitle" type="button" id="edit" onclick="disableFalse()">แก้ไข</button>
            </div>
            <div class="col-auto col-xl-6" id="cancelCol">
                <button class="btn btn-primary ms-2 ms-xl-5 ftitle" type="submit" name="cancel" id="cancel" value="<?= $Form_id ?>">ยกเลิก</button>
            </div>
        </div>
    </form>
</main>
<script>
    function disableFalse() {
        var data = document.getElementsByClassName('data');
        var editbtn = document.getElementById('edit');
        var homebtn = document.getElementById('home');
        var savebtn = document.getElementById('save');
        for (var i = 0; i < data.length; i++) {
            data[i].disabled = false;
        }
        document.getElementById('show-detail').classList.add('d-none');
        document.getElementById('detail').classList.remove('d-none');

        editbtn.classList.add('d-none');
        homebtn.innerText = "ยกเลิก";
        savebtn.classList.remove('d-none');
        CKEDITOR.replace('detail');
    }
    const status = <?= $Status ?>;
    const box = document.getElementById('editBox');
    const topic = box.getElementsByTagName('p');
    var user = <?= $row['User_id'] ?>;
    var id = <?= $_SESSION['id'] ?>;
    var str;
    if (user == id) {
        if (status == 2) {
            box.classList.remove('d-none');
            topic[0].innerText = 'รายละเอียดที่ต้องการแก้ไข โดย ';

        } else if (status == 3) {
            document.getElementById('homeCol').classList.remove('ms-auto');
            document.getElementById('home').classList.add('mx-auto');
            document.getElementById('home').classList.remove('ms-auto', 'me-xl-5', 'me-2');
            document.getElementById('home').classList.add('btn-primary');
            document.getElementById('home').classList.remove('btn-secondary');
            document.getElementById('saveCol').classList.add('d-none');
            document.getElementById('cancelCol').classList.add('d-none');

        } else if (status == 4) {
            box.classList.remove('d-none');
            topic[0].innerText = 'สาเหตุที่ไม่อนุมัติ โดย';
            document.getElementById('homeCol').classList.remove('ms-auto');
            document.getElementById('home').classList.add('mx-auto');
            document.getElementById('home').classList.remove('ms-auto', 'me-xl-5', 'me-2');
            document.getElementById('home').classList.add('btn-primary');
            document.getElementById('home').classList.remove('btn-secondary');

            document.getElementById('saveCol').classList.add('d-none');
        }else if (status == 5) {
            alert();
            document.getElementById('homeCol').classList.remove('ms-auto');
            document.getElementById('home').classList.add('mx-auto');
            document.getElementById('home').classList.remove('ms-auto', 'me-xl-5', 'me-2');
            document.getElementById('home').classList.add('btn-primary');
            document.getElementById('home').classList.remove('btn-secondary');

            document.getElementById('saveCol').classList.add('d-none');

        }
    } else {
        document.getElementById('homeCol').classList.remove('ms-auto');
        document.getElementById('home').classList.add('mx-auto');
        document.getElementById('home').classList.remove('ms-auto', 'me-xl-5', 'me-2');
        document.getElementById('home').classList.add('btn-primary');
        document.getElementById('home').classList.remove('btn-secondary');
        document.getElementById('saveCol').classList.add('d-none');
        document.getElementById('cancelCol').classList.add('d-none');
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
</script>
<?php
$conn = null;
?>
</body>

</html>