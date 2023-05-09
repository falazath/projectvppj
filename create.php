<?php
session_start();
if (!isset($_SESSION['id'])) {
    header('location:login.php');
}
include('header.html');
include("connect.php");
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
        $assign = $_SESSION['id'];
        
    } else if ($_SESSION['status'] == 2) {
        $status = 1;
        $id = $_SESSION['id'];
        $assign = $_SESSION['id'];;
    }
    $stmt = $conn->prepare("INSERT INTO itoss_form VALUES ('', ?, ?, ?, ?, ?, ?, ?, now(), ?)");
    $stmt->bindParam(1, $_SESSION['date']);
    $stmt->bindParam(2, $_POST["Form_Name"]);
    $stmt->bindParam(3, $_POST["Agency_id"]);
    $stmt->bindParam(4, $_POST["Form_Phone"]);
    $stmt->bindParam(5, $_POST["Form_Work"]);
    $stmt->bindParam(6, $status);
    $stmt->bindParam(7, $id);
    $stmt->bindParam(8, $assign);
    $stmt->execute();
    $stmt = $conn->query("SELECT * FROM itoss_form");
    while ($row = $stmt->fetch()) {
        $Form_id = $row['Form_id'];
    }
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
    if ($_POST["Agency_id"] == 0) {
        $stmt = $conn->prepare("INSERT INTO other_agency VALUES ('', ? , ?)");
        $stmt->bindParam(1, $_POST["other_agency"]);
        $stmt->bindParam(2, $Form_id);
        $stmt->execute();
    }
    include("message.php");

    if ($_SESSION['status'] == 1) {
        echo '<script language="javascript">';
        echo 'toastr.success("สร้างคำขอปฏิบัติการเรียบร้อย");';
        echo 'location.href="indexAdmin.php"';
        echo '</script>';
    } else if ($_SESSION['status'] == 2) {
        $_SESSION['ch'] = 1;
       echo '<script>location.href = "indexUser.php";</script>';
    }
}

?>
<main>
    <div class="row justify-content-center mt-5 ">
        <div class="col col-lg-3 col-lg-3 d-block mx-auto ">
            <p class="text-dark text-center fhead fw-bold">สร้างคำขอปฏิบัติงาน</p>
        </div>
    </div>
    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
        <div class="row justify-content-start mb-2" id="dsk">
            <div class="col-12 col-lg-4 mb-2">
                <label class="ftitle fw-bold form-label mb-0">ชื่อผู้ติดต่อ</label>
                <input type="text" class="form-control" name="Form_Name" placeholder="กรอกชื่อผู้ติดต่อ" required>
            </div>
            <div class="col-12 col-lg-4 mb-2">
                <p class="ftitle fw-bold mb-0">หน่วยงาน</p>
                <select class="form-select" id="Agency_id" name="Agency_id" required>
                    <option selected disabled value="">เลือกหน่วยงาน</option>
                    <?php
                    for ($i = 1; $i < count($filter[0]); $i++) {

                        echo '<option value="' . $filter[0][$i]['Agency_id'] . '">' . $filter[0][$i]['Agency_Name'] . '</option>';
                    }
                    ?>
                    <option value="0">อื่นๆ</option>
                </select>
                <input class="d-none form-control mt-1" type="text" name="other_agency" id="other_agency" value="" placeholder="กรอกข้อมูลอื่นๆ">
            </div>
            <div class="col-12 col-lg-4 mb-0">
                <p class="ftitle fw-bold mb-0">เบอร์โทรศัพท์</p>
                <input type="number" class="form-control" name="Form_Phone" placeholder="กรอกเบอร์โทรศัพท์">
            </div>
        </div>
        <div class="row mb-0">
            <div class="col-12 col-lg-6 mb-0">
                <p class="ftitle fw-bold mb-0">ประเภทงาน</p>
            </div>
        </div>
        <div class="row mb-lg-3">
            <?php
            for ($i = 1; $i < count($filter[2]); $i++) { ?>
                <div class="col-4 col-lg-2 mb-0">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input my-0 required" name="Jobtype_id[]" id="name<?= $filter[2][$i]['Jobtype_id'] ?>" value="<?= $filter[2][$i]['Jobtype_id'] ?>" onclick="deRequireCb('required')" required>
                        <label class="form-check-label ms-1 my-0" for="name<?= $filter[2][$i]['Jobtype_id'] ?>">
                            <?= $filter[2][$i]['Jobtype_name'] ?>
                        </label>
                    </div>
                </div>
            <?php
            }
            ?>
            <div class="col-12 col-lg-2 mb-0">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input my-0 required" name="Jobtype_id[]" id="name0" value="0" onclick="deRequireCb('required')" required>
                    <label class="form-check-label ms-1 my-0" for="name" id="labelOther">
                        อื่น ๆ
                    </label>
                </div>
                <input class="d-none form-control mt-1" type="text" name="Jobtype_orther_name" id="other_job" placeholder="กรอกประเภทงาน">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-11 col-lg-12">
                <p class="ftitle fw-bold mb-0">รายละเอียดงาน</p>
                <textarea class="form-control" name="Form_Work" id="detail" cols="30" rows="10" required></textarea>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col-12 col-lg-3 mx-lg-auto">
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
        <div class="row justify-content-around mb-5 mt-lg-5">
            <div class="col col-lg-4 ms-auto">
                <a class="col-lg-3 btn btn-secondary d-block ms-auto me-2 me-lg-5 ftitle" href="indexUser.php" id="home">ยกเลิก</a>
            </div>
            <div class="col col-lg-4 me-auto">
                <button class="btn btn-primary d-block me-auto ms-2 ms-lg-5" type="submit" name="save" value="<?= $Form_id ?>">บันทึก</button>
            </div>
        </div>
    </form>
</main>
<script>
    var editor = CKEDITOR.replace('detail');
    editor.on('required', function(evt) {
        toastr.warning("กรุณากรอก รายละเอียดงาน");
        evt.cancel();
    });

    function otherCheck() {
        var check = document.getElementById('other');
        if (check.checked == true) {
            document.getElementById('Task_orther_name').classList.remove('d-none');
        } else {
            document.getElementById('Task_orther_name').classList.add('d-none');
        }
    }

    $('#name0').click(function() {
        var check = document.getElementById('name0');
        if (check.checked) {
            document.getElementById('other_job').classList.remove('d-none');
            document.getElementById('other_job').required = true;

        } else {
            document.getElementById('other_job').classList.add('d-none');
            document.getElementById('other_job').required = false;

        }
    })


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
            document.getElementById('other_job').required = true;
        } else {
            $('#other_agency').addClass('d-none');
            document.getElementById('other_job').required = false;
        }
    });

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
</script>
</body>

</html>