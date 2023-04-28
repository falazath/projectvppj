<?php
session_start();
if (!isset($_SESSION['id'])) {
    header('location:login.php');
}
include("connect.php");
include('header.html');

include($_SESSION['navbar']);


$sql = $conn->query("SELECT * FROM itoss_agency WHERE state_id =1;");
$filter[0] = $sql->fetchAll();
$sql = $conn->query("SELECT * FROM itoss_user WHERE state_id =1 and Status_id = 2;");
$filter[1] = $sql->fetchAll();
$sql = $conn->query("SELECT * FROM itoss_jobtype;");
$filter[2] = $sql->fetchAll();
$sql = $conn->query("SELECT * FROM itoss_status_form");
$filter[3] = $sql->fetchAll();

if (isset($_POST['save'])) {
    if ($_SESSION['status'] == 1) {
        $status = 5;
        $id = $_POST['User_id'];
    } else if ($_SESSION['status'] == 2) {
        $status = 1;
        $id = $_SESSION['id'];
    }
    $stmt = $conn->prepare("INSERT INTO itoss_form VALUES ('', ?, ?, ?, ?, ?, ?, ?, ?, now())");
    $stmt->bindParam(1, $_SESSION['date']);
    $stmt->bindParam(2, $_POST["Form_Name"]);
    $stmt->bindParam(3, $_POST["Agency_id"]);
    $stmt->bindParam(4, $_POST["Form_Phone"]);
    $stmt->bindParam(5, $_POST["Jobtype_id"]);
    $stmt->bindParam(6, $_POST["Form_Work"]);
    $stmt->bindParam(7, $status);
    $stmt->bindParam(8, $id);
    $stmt->execute();
    $stmt = $conn->query("SELECT * FROM itoss_form");
    while ($row = $stmt->fetch()) {
        $Form_id = $row['Form_id'];
    }
    if ($_POST["Jobtype_id"] == 0) {
        $stmt = $conn->prepare("INSERT INTO itoss_jobtype_orther VALUES ('', ? , ?)");
        $stmt->bindParam(1, $_POST["Jobtype_orther_name"]);
        $stmt->bindParam(2, $Form_id);
        $stmt->execute();
    }
    if ($_POST["Agency_id"] == 0) {
        $stmt = $conn->prepare("INSERT INTO other_agency VALUES ('', ? , ?)");
        $stmt->bindParam(1, $_POST["other_agency"]);
        $stmt->bindParam(2, $Form_id);
        $stmt->execute();
    }
    include("message.php");

    if ($_SESSION['status'] == 1) {
        echo '<script language="javascript">';
        echo 'location.href="indexAdmin.php"';
        echo '</script>';
    } else if ($_SESSION['status'] == 2) {
        echo '<script language="javascript">';

        echo 'location.href="indexUser.php"';
        echo '</script>';
    }
}

?>
<main>
    <div class="row justify-content-center mt-5 ">
        <div class="col col-sm-3 col-xl-3 d-block mx-auto ">
            <p class="text-dark text-center fhead fw-bold">สร้างคำขอปฏิบัติงาน</p>
        </div>
    </div>
    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
        <div class="row justify-content-start mb-2" id="dsk">
            <div class="col-12 col-xl-4 mb-2">
                <label class="ftitle fw-bold form-label mb-0">ชื่อผู้ติดต่อ</label>
                <input type="text" class="form-control" name="Form_Name" placeholder="กรอกชื่อผู้ติดต่อ" required>
            </div>
            <div class="col-12 col-xl-4 mb-2">
                <p class="ftitle fw-bold mb-0">หน่วยงาน</p>
                <select class="form-select" id="Agency_id" name="Agency_id" required>
                    <option selected disabled>เลือกหน่วยงาน</option>
                    <?php
                    for ($i = 1; $i < count($filter[0]); $i++) {

                        echo '<option value="' . $filter[0][$i]['Agency_id'] . '">' . $filter[0][$i]['Agency_Name'] . '</option>';
                    }
                    ?>
                    <option value="0">อื่นๆ</option>
                </select>
                <input class="d-none form-control mt-1" type="text" name="other_agency" id="other_agency" value="" placeholder="กรอกข้อมูลอื่นๆ">
            </div>
            <div class="col-12 col-xl-4 mb-0">
                <p class="ftitle fw-bold mb-0">เบอร์โทรศัพท์</p>
                <input type="number" class="form-control" name="Form_Phone" placeholder="กรอกเบอร์โทรศัพท์">
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-12 col-xl-4">
                <p class="ftitle fw-bold mb-0">ประเภทงาน</p>
                <select class="form-select" id="Jobtype_id" name="Jobtype_id" required>
                    <option selected disabled>เลือกประเภทงาน</option>
                    <?php
                    echo $_SESSION['id'] . "kbjbljknknknkllknk";
                    for ($i = 1; $i < count($filter[2]); $i++) {

                        echo '<option value="' . $filter[2][$i]['Jobtype_id'] . '">' . $filter[2][$i]['Jobtype_name'] . '</option>';
                    }
                    ?>
                    <option value="0">อื่นๆ</option>
                </select>
                <input class="d-none form-control mt-1" type="text" name="Jobtype_orther_name" id="Jobtype_orther_name" value="" placeholder="กรอกข้อมูลอื่นๆ">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-11 col-xl-12">
                <p class="ftitle fw-bold mb-0">รายละเอียดงาน</p>
                <textarea class="form-control" name="Form_Work" id="detail" cols="30" rows="10" required></textarea>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col-12 col-xl-3 mx-xl-auto">
                <p class="ftitle fw-bold mb-0">เจ้าหน้าที่ผู้รับผิดชอบ</p>
                <?php
                if ($_SESSION['status'] == 1) { ?>
                    <select class="form-select" id="User_id" name="User_id" required>
                        <option selected disabled>เลือกเจ้าหน้าที่รับผิดชอบ</option>
                        <?php
                        for ($i = 0; $i < count($filter[1]); $i++) {

                            echo '<option value="' . $filter[1][$i]['User_id'] . '">' . $filter[1][$i]['User_Name'] . '</option>';
                        }
                        ?>
                    </select>
                <?php } else { ?>
                    <input type="text" class="ftitle form-control" id="name-user" style="background-color: #5F7769;" name="User_Name" value="<?= $_SESSION['name'] ?>" disabled>
                <?php } ?>
            </div>
        </div>
        <div class="row justify-content-around mb-5 mt-xl-5">
            <div class="col col-xl-4 ms-auto">
                <a class="col-xl-3 btn btn-secondary d-block ms-auto me-2 me-xl-5 ftitle" href="indexUser.php" id="home">ยกเลิก</a>
            </div>
            <div class="col col-xl-4 me-auto">
                <button class="btn btn-primary d-block me-auto ms-2 ms-xl-5" type="submit" name="save" value="<?= $Form_id ?>">บันทึก</button>
            </div>
        </div>
    </form>
</main>
<script>
    CKEDITOR.replace('detail');

    function otherCheck() {
        var check = document.getElementById('other');
        if (check.checked == true) {
            document.getElementById('Task_orther_name').classList.remove('d-none');
        } else {
            document.getElementById('Task_orther_name').classList.add('d-none');
        }
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
</body>

</html>