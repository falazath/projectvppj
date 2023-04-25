<?php
session_start();

if (!isset($_SESSION['id'])) {
    echo '<script language="javascript">location.href="login.php"</script>';
}
include('header.html');
include("connect.php");
include("navbar.html");

//Value filter
$sql = $conn->query("SELECT * FROM itoss_agency WHERE state_id =1;");
$filter[0] = $sql->fetchAll();
$sql = $conn->query("SELECT * FROM itoss_user WHERE state_id =1;");
$filter[1] = $sql->fetchAll();
$sql = $conn->query("SELECT * FROM itoss_jobtype;");
$filter[2] = $sql->fetchAll();
$sql = $conn->query("SELECT * FROM itoss_status_form");
$filter[3] = $sql->fetchAll();
//-Value filter

if (isset($_POST['save'])) {
    $stmt = $conn->prepare("INSERT INTO itoss_form VALUES ('', ?, ?, ?, ?, ?, ?, 1, ?, now())");
    $stmt->bindParam(1, $_SESSION['date']);
    $stmt->bindParam(2, $_POST["Form_Name"]);
    $stmt->bindParam(3, $_POST["sector"]);
    $stmt->bindParam(4, $_POST["Form_Phone"]);
    $stmt->bindParam(5, $_POST["type"]);
    $stmt->bindParam(6, $_POST["Form_Work"]);
    $stmt->bindParam(7, $_SESSION['id']);
    $stmt->execute();

    $stmt = $conn->query("SELECT * FROM itoss_form");
    while ($row = $stmt->fetch()) {
        $_SESSION['Form_id'] = $row['Form_id'];
    }
    if ($_POST["sector"] == '0') {
        $sql = $conn->query("INSERT INTO other_agency VALUES (''," . $_POST['other_sector'] . " ," . $_SESSION['Form_id'] . ")");
    }
    if ($_POST["type"] == '0') {
        $stmt = $conn->prepare("INSERT INTO itoss_jobtype_orther VALUES ('', ? , ?)");
        $stmt->bindParam(1, $_POST["Jobtype_orther_name"]);
        $stmt->bindParam(2, $_SESSION['Form_id']);
        $stmt->execute();
    }

    include("message.php");
    echo '<script language="javascript">';
    echo 'alert("ข้อมูลฟอร์มถูกเพิ่มแล้ว"); location.href="index.php"';
    echo '</script>';
}

?>
<main>
    <div class="row justify-content-center mt-5 ">
        <div class="col col-sm-3 col-xl-3 d-block mx-auto ">
            <p class="text-dark text-center fhead fw-bold">สร้างคำขอปฏิบัติงาน</p>
        </div>
    </div>
    <form action="" method="post" novalidate>
        <div class="row justify-content-start mb-3" id="dsk">
            <div class="col-10 col-xl-4">
                <p class="ftitle fw-bold">ชื่อผู้ติดต่อ</p>
                <input type="text" class="form-control" name="Form_Name" placeholder="กรอกชื่อผู้ติดต่อ" required>
            </div>
            <div class="col-10 col-xl-4">
                <p class="ftitle fw-bold">หน่วยงาน</p>
                <select class="filter form-select" name="sector" id="filterSector" required>
                    <option selected disabled>เลือกหน่วยงาน</option>
                    <?php
                    for ($i = 1; $i < count($filter[0]); $i++) {
                        echo '<option value="' . $filter[0][$i]['Agency_id'] . '">' . $filter[0][$i]['Agency_Name'] . '</option>';
                    }
                    ?>
                    <option value="0">อื่นๆ</option>
                </select>
                &nbsp;
                <input class="d-none form-control" type="text" name="other_sector" id="other_sector" placeholder="กรอกหน่วยงาน">
            </div>

            <div class="col-10 col-xl-4 mb-3">
                <p class="ftitle fw-bold">ประเภทงาน</p>
                <select class="filter form-select" name="type" id="filterType" required>
                    <option selected disabled>เลือกประเภทงาน</option>
                    <?php
                    for ($i = 1; $i < count($filter[2]); $i++) {
                        echo '<option value="' . $filter[2][$i]['Jobtype_id'] . '">' . $filter[2][$i]['Jobtype_name'] . '</option>';
                    }
                    ?>
                    <option value="0">อื่นๆ</option>
                </select>
                &nbsp;
                <input class="d-none form-control" type="text" name="Jobtype_orther_name" id="Jobtype_orther_name" placeholder="กรอกประเภทงาน">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-10 col-xl-4">
                <p class="ftitle fw-bold">เบอร์โทรศัพท์</p>
                <input type="text" class="form-control" name="Form_Phone" placeholder="กรอกเบอร์โทรศัพท์">
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



</main>
<script>
    CKEDITOR.replace('detail');

    $('#filterType').change(function() {
        let a = $('#filterType').val();
        if (a == "0") {
            $('#Jobtype_orther_name').removeClass('d-none');
            document.getElementById('Jobtype_orther_name').required = true;
        } else {
            $('#Jobtype_orther_name').addClass('d-none');
        }
    });

    $('#filterSector').change(function() {
        let a = $('#filterSector').val();
        if (a == "0") {
            $('#other_sector').removeClass('d-none');
            document.getElementById('other_sector').required = true;
        } else {
            $('#other_sector').addClass('d-none');
        }
    });
</script>
</body>

</html>