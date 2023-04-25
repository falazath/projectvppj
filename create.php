<?php
session_start();
include("connect.php");
include('header.html');
include("navbar.html");
$sql = $conn->query("SELECT * FROM itoss_agency WHERE state_id =1;");
$filter[0] = $sql->fetchAll();
$sql = $conn->query("SELECT * FROM itoss_user WHERE state_id =1;");
$filter[1] = $sql->fetchAll();
$sql = $conn->query("SELECT * FROM itoss_jobtype;");
$filter[2] = $sql->fetchAll();
$sql = $conn->query("SELECT * FROM itoss_status_form");
$filter[3] = $sql->fetchAll();
?>
<main>
    <div class="row justify-content-center mt-5 ">
        <div class="col col-sm-3 col-xl-3 d-block mx-auto ">
            <p class="text-dark text-center fhead fw-bold">สร้างคำขอปฏิบัติงาน</p>
        </div>
    </div>
    <form action="" method="post">
        <div class="row justify-content-start mb-3" id="dsk">
            <div class="col-12 col-xl-4">
                <p class="ftitle fw-bold">ชื่อผู้ติดต่อ</p>
                <input type="text" class="form-control" name="Form_Name" placeholder="กรอกชื่อผู้ติดต่อ" required>
                <input type="hidden" name="Status_form_id" value="1">
            </div>
            <div class="col-12 col-xl-4">
                <p class="ftitle fw-bold">หน่วยงาน</p>
                <select class="form-select" id="Agency_id" name="Agency_id" required>
                    <?php
                    for ($i = 1; $i < count($filter[0]); $i++) {

                        echo '<option value="' . $filter[0][$i]['Agency_id'] . '">' . $filter[0][$i]['Agency_Name'] . '</option>';
                    }
                    ?>
                    <option value="0">อื่นๆ</option>
                </select>
            </div>
            <div class="col-12 col-xl-4">
                <p class="ftitle fw-bold">เบอร์โทรศัพท์</p>
                <input type="text" class="form-control" name="Form_Phone" placeholder="กรอกเบอร์โทรศัพท์">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-12 col-xl-4 mb-3">
                <p class="ftitle fw-bold">ประเภทงาน</p>
                <select class="form-select" id="Jobtype_id" name="Jobtype_id" required>
                    <option selected value="all">ทั้งหมด</option>
                    <?php
                    for ($i = 1; $i < count($filter[2]); $i++) {

                        echo '<option value="' . $filter[2][$i]['Jobtype_id'] . '">' . $filter[2][$i]['Jobtype_name'] . '</option>';
                    }
                    ?>
                    <option value="0">อื่นๆ</option>
                </select>
                &nbsp;
                <input class="d-none form-control" type="text" name="Jobtype_orther_name" id="Jobtype_orther_name" placeholder="กรอกข้อมูลอื่นๆ">
            </div>
            <div class="col-12 col-xl-6">
                <p class="ftitle fw-bold">หมวดงาน</p>
                <div class="col-10 col-xl-12 mb-3">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input my-2 me-4" type="checkbox" id="inlineCheckbox1" name="Task_Format_name[]" value="ซอฟต์แวร์">
                        <label class="form-check-label my-2 me-4" for="inlineCheckbox1">ซอฟต์แวร์</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input my-2 me-4" type="checkbox" id="inlineCheckbox2" name="Task_Format_name[]" value="ฮาร์ดแวร์">
                        <label class="form-check-label my-2 me-4" for="inlineCheckbox2">ฮาร์ดแวร์</label>
                    </div>
                    <div class="form-check form-check-inline my-auto">
                        <input class="form-check-input my-2 me-4" type="checkbox" id="other" onclick="otherCheck()" name="Task_Format_name[]" value="อื่นๆ">
                        <label class="form-check-label my-2 me-4">อื่นๆ</label>
                    </div>
                </div>
                <div class="col-10 col-xl-8 mb-3">
                    <input class="d-none form-control" type="text" name="Task_orther_name" id="Task_orther_name" placeholder="กรอกข้อมูลอื่นๆ">
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-11 col-xl-12">
                <p class="ftitle fw-bold">รายละเอียดงาน</p>
                <textarea class="form-control" name="Form_Work" id="detail" cols="30" rows="10" required></textarea>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col-10 col-xl-3 mx-xl-auto">
                <p class="ftitle fw-bold">เจ้าหน้าที่ผู้รับผิดชอบ</p>
                <input type="text" class="ftitle form-control" id="name-user" style="background-color: #5F7769;" name="User_Name" value="<?= $_SESSION['name'] ?>" disabled>
            </div>
        </div>
        <div class="row justify-content-around mb-5 mt-xl-5">
            <div class="col col-xl-4 ms-auto">
                <a class="col-xl-3 btn btn-secondary d-block ms-auto me-2 me-xl-5 ftitle" href="indexUser.php" id="home">ยกเลิก</a>
            </div>
            <div class="col col-xl-4 me-auto">
                <button class="btn btn-primary d-block me-auto ms-2 ms-xl-5" type="submit" name="save">บันทึก</button>
            </div>
        </div>
    </form>

    <?php
    if (isset($_POST['save'])) {
        $stmt = $conn->prepare("INSERT INTO itoss_form VALUES ('', ?, ?, ?, ?, ?, ?, ?, ?, now())");
        $stmt->bindParam(1, $_SESSION['date']);
        $stmt->bindParam(2, $_POST["Form_Name"]);
        $stmt->bindParam(3, $_POST["Agency_id"]);
        $stmt->bindParam(4, $_POST["Form_Phone"]);
        $stmt->bindParam(5, $_POST["Jobtype_id"]);
        $stmt->bindParam(6, $_POST["Form_Work"]);
        $stmt->bindParam(7, $_POST["Status_form_id"]);
        $stmt->bindParam(8, $_SESSION['id']);
        $stmt->execute();

        $stmt = $conn->query("SELECT * FROM itoss_form");
        while ($row = $stmt->fetch()) {
            $_SESSION['Form_id'] = $row['Form_id'];
        }

        $stmt = $conn->prepare("INSERT INTO itoss_jobtype_orther VALUES ('', ? , ?)");
        $stmt->bindParam(1, $_POST["Jobtype_orther_name"]);
        $stmt->bindParam(2, $_SESSION['Form_id']);
        $stmt->execute();

        for ($i = 0; isset($_POST['Task_Format_name'][$i]); $i++) {
            $stmt = $conn->prepare("INSERT INTO itoss_task_format VALUES ('', ? , ?)");
            $stmt->bindParam(1, $_POST['Task_Format_name'][$i]);
            $stmt->bindParam(2, $_SESSION['Form_id']);
            $stmt->execute();
        }

        $stmt = $conn->prepare("INSERT INTO itoss_task_orther VALUES ('', ? , ?)");
        $stmt->bindParam(1, $_POST["Task_orther_name"]);
        $stmt->bindParam(2, $_SESSION['Form_id']);
        $stmt->execute();

        $User_id = $_SESSION['id'];
        include("message.php");
        echo '<script language="javascript">';
        echo 'alert("ข้อมูลฟอร์มถูกเพิ่มแล้ว"); location.href="indexUser.php?User_id=' . $User_id . '"';
        echo '</script>';
    }

    ?>

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
        if (a == "4") {
            $('#Jobtype_orther_name').removeClass('d-none');
        } else {
            $('#Jobtype_orther_name').addClass('d-none');
        }
    });
</script>
</body>

</html>