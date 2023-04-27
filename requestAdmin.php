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

if (isset($_POST['create-text'])) {

    $Status_form_id = $_POST["create-text"];
    echo 'alert(' . $Status_form_id . ');';

    $stmt = $conn->prepare("INSERT INTO itoss_text VALUES ('', ?, ?, ?)");
    $stmt->bindParam(1, $_POST["Text_name"]);
    $stmt->bindParam(2, $Form_id);
    $stmt->bindParam(3, $_POST["create-text"]);
    $stmt->execute();

    $stmt = $conn->prepare("UPDATE itoss_form SET Status_form_id = '$Status_form_id' where Form_id = '$Form_id'");
    $stmt->execute();

    include("message.php");

    echo '<script language="javascript">';
    echo 'alert("ส่งไปให้ User แล้ว"); location.href="indexAdmin.php"';
    echo '</script>';
} else if (isset($_POST['approve'])) {
    $Status_form_id = $_POST["approve"];
    $stmt = $conn->prepare("UPDATE itoss_form SET Status_form_id = '$Status_form_id' where Form_id = '$Form_id'");
    $stmt->execute();

    include("message.php");

    echo '<script language="javascript">';
    echo 'alert("ส่งไปให้ User แล้ว"); location.href="indexAdmin.php"';
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
isset($row1['Jobtype_orther_name'])?$job_other=$row1['Jobtype_orther_name']:$job_other=$row['Jobtype_name'];

$stmt2 = $conn->query("SELECT * FROM itoss_sign INNER JOIN itoss_user ON itoss_sign.User_id = itoss_user.User_id where itoss_sign.User_id = ".$row['User_id']." ");
$row2 = $stmt2->fetch();


$stmt3 = $conn->query("SELECT * FROM itoss_text where Form_id = '$Form_id' ORDER BY Text_id DESC");
$row3 = $stmt3->fetch();
isset($row3['Text_name'])?$text=$row3['Text_name']:$text="";
    isset($row3['Status_form_id'])?$Status=$row3['Status_form_id']:$Status=$row['Status_form_id'];


$stmt4 = $conn->query("SELECT * FROM itoss_sign INNER JOIN itoss_user ON itoss_sign.User_id = itoss_user.User_id where itoss_sign.User_id = 1");
$row4 = $stmt4->fetch();

include($_SESSION['navbar']);

?>
<main>
    <div class="row justify-content-center mt-5 ">
        <div class="col col-sm-3 col-xl-3 d-block mx-auto ">
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
            <div class="col-11 col-xl-12 mb-3">
                <p class="ftitle fw-bold mb-1">รายละเอียดการแก้ไขงาน</p>
                <div class="form-control text-light" id="Detail" cols="30" rows="10">
                <?= $text ?>
                </div>
            </div>
            <hr>
        </div>

        <div class="row justify-content-start mb-0 mb-xl-3" id="dsk">
            <div class="col-10 col-xl-4 mb-3 mb-xl-0">
                <p class="ftitle fw-bold mb-1" id="demo">ชื่อผู้ติดต่อ</p>
                <input type="hidden" name="Form_date" value="<?= $row['Form_date'] ?>">
                <input type="text" class="data form-control ftitle" name="Form_Name" id="contact" value="<?= $row["Form_Name"] ?>" disabled>
            </div>
            <div class="col-10 col-xl-4 mb-3 mb-xl-0">
                <p class="ftitle fw-bold mb-1">หน่วยงาน</p>
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
            <div class="col-10 col-xl-4 mb-3 mb-xl-0">
                <p class="ftitle fw-bold mb-1">เบอร์โทรศัพท์</p>
                <input type="text" class="data form-control ftitle" name="Form_Phone" value="<?= $row["Form_Phone"] ?>" disabled>
            </div>
        </div>
        <div class="row mb-xl-3">
            <div class="col-10 col-xl-4 mb-3 mb-xl-0">
                <p class="ftitle fw-bold">ประเภทงาน</p>
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
        </div>
        </div>
        <div class="row mb-0 mb-xl-3 mb-xl-0">
            <div class="col-11 col-xl-12 mb-3">
                <p class="ftitle fw-bold mb-1">รายละเอียดงาน</p>
                <div class="data form-control text-light" name="Form_Work" id="show-detail" cols="30" rows="10">
                    <?= $row['Form_Work'] ?>
                </div>
                <textarea class="data form-control text-light d-none" name="Form_Work" id="detail" cols="30" rows="10">
                        <?= $row['Form_Work'] ?>
                    </textarea>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col-10 col-xl-3 mx-xl-auto mb-3">
                <p class="ftitle fw-bold mb-1 text-center">เจ้าหน้าที่ผู้รับผิดชอบ</p>
                <input type="text" class="ftitle form-control text-center" id="name-user" name="User_Name" value="<?= $row['User_Name'] ?>" disabled>
            </div>
        </div>
        <div class="d-none" id="send-text">
            <div class="row">
            <div class="col-12 col-xl-3 mx-xl-auto mb-2">
                <p class="ftitle fw-bold mb-0 text-center">เจ้าหน้าที่ผู้รับผิดชอบ</p>
                <img class="d-block w-100 h-100 text-center" src="data:<?= $row2['Sign_image'] ?>">
            </div>
            <div class="col-12 col-xl-3 mx-xl-auto mb-2">
                <input type="text" class="ftitle form-control text-center" id="name-user" name="User_Name" value="<?= $row['User_Name'] ?>" disabled>
            </div>
            </div>
            <div class="row mb-xl-5">
                <div class="col-xl-6 mx-auto">
                    <img class="d-block w-250 h-300 text-center" src="data:<?= $row4['Sign_image'] ?>"><br>
                    <input type="text" class="ftitle form-control text-center" id="name-user" name="User_Name" value="<?= $row4['User_Name'] ?>" disabled>
                </div>
            </div>
        </div>

        <div class="row justify-content-around mb-3 mt-xl-5">
            <div class="col-auto col-xl-2 col-xl-3 d-none" id="editStatus">
                <button class="btn btn-primary d-block me-xl-auto" data-bs-toggle="modal" data-bs-target="#create-text" type='button' name='edit' id='edit' value="2">แก้ไข</button>
            </div>
            <div class="col-auto col-xl-2 col-xl-3 d-none" id="approveStatus">
                <button class="btn btn-primary d-block me-xl-auto" data-bs-toggle="modal" type='submit' name='approve' id='approve' value="5">อนุมัติ</button>
            </div>
            <div class="col-auto col-xl-2 col-xl-3 d-none" id="disapprovedStatus">
                <button class="btn btn-primary d-block me-xl-auto" data-bs-toggle="modal" data-bs-target="#create-text" type='button' name='Disapproved' id='Disapproved' value="4">ไม่อนุมัติ</button>
            </div>
        </div>
        <div class="row justify-content-around mb-5 mt-xl-5">
            <div class="col-auto col-xl-2 col-xl-4 " id="homeCol">
                <a class="btn btn-secondary d-block ms-auto me-2 me-xl-5 ftitle" href="indexAdmin.php" id="home">กลับสู่หน้าหลัก</a>
            </div>
        </div>
    </form>
</main>



<script>
    alert(1);
    
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

    $(document).ready(function() {
        const status = <?php echo $row['Status_form_id'] ?>;
        if (status == 5) {
            document.getElementById('send-text').classList.remove('d-none');
        }
    });

    const status = <?= $Status ?> //ค่า status 
    const box = document.getElementById('editBox');
    const topic = box.getElementsByTagName('p');
    var str;
    if (status == 1) {
        document.getElementById('editStatus').classList.remove('d-none');
        document.getElementById('approveStatus').classList.remove('d-none');
        document.getElementById('disapprovedStatus').classList.remove('d-none');
    }
    else if (status == 2) {
        box.classList.remove('d-none');
        topic[0].innerText = 'รายละเอียดที่ต้องการแก้ไข โดย ';
        document.getElementById('homeCol').classList.remove('ms-auto');
        document.getElementById('home').classList.add('mx-auto');
        document.getElementById('home').classList.remove('ms-auto', 'me-xl-5', 'me-2');
        document.getElementById('home').classList.add('btn-primary');
        document.getElementById('home').classList.remove('btn-secondary');
        document.getElementById('editStatus').classList.add('d-none');
        document.getElementById('approveStatus').classList.add('d-none');
        document.getElementById('disapprovedStatus').classList.add('d-none');

    } else if (status == 3) {
        
        document.getElementById('homeCol').classList.remove('ms-auto');
        document.getElementById('home').classList.add('mx-auto');
        document.getElementById('home').classList.remove('ms-auto', 'me-xl-5', 'me-2');
        document.getElementById('home').classList.add('btn-primary');
        document.getElementById('home').classList.remove('btn-secondary');
        document.getElementById('editStatus').classList.add('d-none');
        document.getElementById('approveStatus').classList.add('d-none');
        document.getElementById('disapprovedStatus').classList.add('d-none');

    }else if (status == 4) {
        box.classList.remove('d-none');
        topic[0].innerText = 'สาเหตุที่ไม่อนุมัติ โดย';
        document.getElementById('homeCol').classList.remove('ms-auto');
        document.getElementById('home').classList.add('mx-auto');
        document.getElementById('home').classList.remove('ms-auto', 'me-xl-5', 'me-2');
        document.getElementById('home').classList.add('btn-primary');
        document.getElementById('home').classList.remove('btn-secondary');
        document.getElementById('editStatus').classList.add('d-none');
        document.getElementById('approveStatus').classList.add('d-none');
        document.getElementById('disapprovedStatus').classList.add('d-none');
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
    document.getElementById('cke_1_top').classList.add('d-none');
    alert();
</script>
</body>
<?php

$conn = null;
?>
</html>