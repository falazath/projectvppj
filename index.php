<?php
include('connect.php');
include('header.html');
include('navbar.html');

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

//Data in Table
if (isset($_POST['search'])) {
    $data = $conn->prepare("SELECT * FROM itoss_form
    LEFT JOIN itoss_agency ON itoss_agency.Agency_id = itoss_form.Agency_id
    LEFT JOIN itoss_jobtype ON itoss_jobtype.Jobtype_id = itoss_form.Jobtype_id
    LEFT JOIN itoss_status_form ON itoss_status_form.Status_form_id = itoss_form.Status_form_id
    LEFT JOIN itoss_user ON itoss_user.User_id = itoss_form.User_id
    WHERE itoss_form.Agency_id LIKE ? OR itoss_form.User_id LIKE ? OR  itoss_form.Jobtype_id LIKE ?
    OR itoss_form.Form_date_id LIKE ? OR itoss_form.Form_date_end LIKE ? OR itoss_form.Status_form_id LIKE ?
    ;");
    $data->bindParam(1, $_POST['sector']);
    $data->bindParam(2, $_POST['user']);
    $data->bindParam(3, $_POST['type']);
    $data->bindParam(4, $_POST['start-date']);
    $data->bindParam(5, $_POST['end-date']);
    $data->bindParam(6, $_POST['status']);
    $data->execute();
    $row = $data->fetchAll();
} else {
    $sql_data = $conn->prepare("SELECT * FROM itoss_form,itoss_agency,itoss_jobtype,itoss_status_form,itoss_user
    WHERE itoss_form.Agency_id = itoss_agency.Agency_id AND itoss_form.Jobtype_id = itoss_jobtype.Jobtype_id 
    AND itoss_form.Status_form_id = itoss_status_form.Status_form_id AND itoss_form.User_id = itoss_user.User_id
    AND itoss_agency.state_id = 1 AND itoss_user.state_id = 1;");
    $sql_data->execute();
    $row = $sql_data->fetchAll();
}
//-Data in Table

?>
<main>

    <div class="row justify-content-center">
        <div class="col col-sm-3 col-xl-3 d-block mx-auto">
            <p class="text-dark text-center fhead fw-bold">รายการคำขอปฏิบัติงาน</p>
        </div>
    </div>
    <!--Desktop-->
    <!--ตัวกรอง-->
    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
        <div class="row justify-content-start mb-3" id="dsk">
            <div class="col-2 col-sm-2 col-xl-2">
                <p class="ftitle">หน่วยงาน</p>
                <select class="filter form-select" name="sector" onchange="disReq()" id="filterSector" required>
                    <option selected value="">ทั้งหมด</option>
                    <?php
                    for ($i = 0; $i < count($filter[0]); $i++) {

                        echo '<option value="' . $filter[0][$i]['Agency_id'] . '">' . $filter[0][$i]['Agency_Name'] . '</option>';
                    }
                    ?>
                    <option value="6">อื่นๆ</option>
                </select>
            </div>
            <div class="col-4 col-sm-2 col-xl-2">
                <p class="ftitle element">ชื่อพนักงาน</p>
                <select class="filter form-select" name="user" onchange="disReq()" id="filterEmp" required>
                    <option selected value="">ทั้งหมด</option>
                    <?php
                    for ($i = 0; $i < count($filter[1]); $i++) {

                        echo '<option value="' . $filter[1][$i]['User_id'] . '">' . $filter[1][$i]['User_Name'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="col-2 col-sm-2 col-xl-2">
                <p class="ftitle">ประเภทงาน</p>
                <select class="filter form-select" name="type" onchange="disReq()" id="filterType" required>
                    <option selected value="">ทั้งหมด</option>
                    <?php
                    for ($i = 0; $i < count($filter[2]); $i++) {

                        echo '<option value="' . $filter[2][$i]['Jobtype_id'] . '">' . $filter[2][$i]['Jobtype_name'] . '</option>';
                    }
                    ?>
                    <option value="4">อื่นๆ</option>
                </select>
            </div>
            <div class="col-2 col-sm-2 col-xl-2">
                <p class="ftitle">วันที่เริ่มต้น</p>
                <input type="date" class="filter form-control" onclick="disReq()" name="start-date" min="2000-01-01" value="" required>
            </div>
            <div class="col-2 col-sm-2 col-xl-2">
                <p class="ftitle">วันที่สิ้นสุด</p>
                <input type="date" class="filter form-control" onclick="disReq()" name="end-date" id="" min="2000-01-01" value="" required>
            </div>
            <div class="col-2 col-sm-2 col-xl-2">
                <p class="ftitle">สถานะ</p>
                <select class="filter form-select" name="status" onchange="disReq()" id="filterStatus" required>
                    <option selected value="">ทั้งหมด</option>
                    <?php
                    for ($i = 0; $i < count($filter[3]); $i++) {

                        echo '<option value="' . $filter[3][$i]['Status_form_id'] . '">' . $filter[3][$i]['Status_form_name'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="row justify-content-center mt-3">
                <div class="col-3 my-auto">
                    <button type="submit" class="btn btn-primary d-block mx-auto px-5" name="search">ค้นหา</button>
                </div>
            </div>
        </div>
    </form>

    <form action="" method="post"><!--ตัวกรองPhone-->
        <div class="row justify-content-start" id="phone">
            <div class="col col-sm-2 col-xl-2">
                <button class="btn btn-light border border-2 fsub" id="filterBtn" type="button" data-bs-toggle="offcanvas" data-bs-target="#filterPhone" aria-controls="offcanvasBottom"><img src="./asset/icon/Filterph.svg" class="h-100 w-100 d-block mx-auto" alt=""></button>
                <div class="offcanvas offcanvas-bottom" tabindex="-1" id="filterPhone" aria-labelledby="offcanvasBottomLabel">
                    <div class="offcanvas-body small">
                        <div class="row mb-3">
                            <div class="col">
                                <p class="ftitle fw-bold my-auto ">เลือกหน่วยงาน</p>
                            </div>
                            <div class="col-11">
                                <select class="form-select" aria-label="Default select example">
                                    <option selected disabled>เลือกหน่วยงาน</option>
                                    <?php
                                    for ($i = 0; $i < count($filter[0]); $i++) {

                                        echo '<option value="' . $filter[0][$i]['Agency_id'] . '">' . $filter[0][$i]['Agency_Name'] . '</option>';
                                    }
                                    ?>
                                    <option value="6">อื่นๆ</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <p class="ftitle fw-bold my-auto">เลือกพนักงาน</p>
                            </div>
                            <div class="col-11">
                                <select class="filter form-select" name="type" onchange="disReq()" id="filterType" required>
                                    <option selected value="">ทั้งหมด</option>
                                    <?php
                                    for ($i = 0; $i < count($filter[1]); $i++) {

                                        echo '<option value="' . $filter[1][$i]['User_id'] . '">' . $filter[1][$i]['User_Name'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <p class="ftitle fw-bold my-auto ">เลือกประเภทงาน</p>
                            </div>
                            <div class="col-11">
                                <select class="form-select" name="category" required>
                                    <option selected value="">ทั้งหมด</option>
                                    <?php
                                    for ($i = 0; $i < count($filter[2]); $i++) {

                                        echo '<option value="' . $filter[2][$i]['Jobtype_id'] . '">' . $filter[2][$i]['Jobtype_name'] . '</option>';
                                    }
                                    ?>
                                    <option value="4">อื่นๆ</option>
                                </select>
                            </div>

                        </div>
                        <div class="row mb-3">
                            <div class="col-6 col-sm-2 col-xl-2">
                                <label for="start-date">วันที่เริ่มต้น</label>
                                <input type="date" class=" form-control" id="start-date" value="วันที่เริ่มต้น">
                            </div>
                            <div class="col-6 col-sm-2 col-xl-2">
                                <label for="end-date">วันที่สิ้นสุด</label>
                                <input type="date" class=" form-control" id="end-date" value="วันที่สิ้นสุด">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-11">
                                <p class="ftitle fw-bold my-auto">สถานะ</p>
                                <select class="form-select" aria-label="Default select example">
                                    <option selected value="">ทั้งหมด</option>
                                    <?php
                                    for ($i = 0; $i < count($filter[3]); $i++) {

                                        echo '<option value="' . $filter[3][$i]['Status_form_id'] . '">' . $filter[3][$i]['Status_form_name'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="offcanvas-footer">
                        <button type="submit" class="btn btn-primary d-block mx-auto my-2" name="submit">ค้นหา</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="row"><!--ปุ่มสร้างคำขอ-->
        <div class="col mb-3">
            <button type="button" class="btn btn-primary d-block me-xl-auto" data-bs-toggle="modal" data-bs-target="#create-date">สร้างคำขอปฏิบัติงาน</button>
        </div>
        <form action="create.html" method="post">
            <div class="modal fade" id="create-date" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body">
                            <p class="ftitle fw-bold text-center">วันที่สร้างคำขอปฏิบัติงาน</p>
                            <div class="col-xl-10 mx-auto">
                                <input type="date" class="form-control" name="start_date">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="create-date" class="btn btn-primary mx-auto">บันทึก</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="overflow-x-auto"><!--ตาราง-->
        <p id="demo"></p>

        <table class="table table-light table-bordered">
            <thead>
                <tr class="d-flex text-center fsub">
                    <th class="col-3 col-sm-1">วันที่</th>
                    <th class="col-4 col-sm-2">หน่วยงาน</th>
                    <th class="col-4 col-sm-2">เจ้าหน้าที่</th>
                    <th class="col-4 col-sm-1">ประเภทงาน</th>
                    <th class="col-8 col-sm-4">รายละเอียดงาน</th>
                    <th class="col-3 col-sm-1">สถานะ</th>
                    <th class="col-2 col-sm-1"></th>
                </tr>
            </thead>
            <tbody>
                <?php
                for ($i = 0; $i < count($row); $i++) {

                ?>

                    <!--สถานะ:รออนุมัติ-->
                    <tr class="d-flex text-center fsub">
                        <td class="col-3 col-sm-1 date"><?= $row[$i]['Form_date'] ?></td>
                        <td class="col-4 col-sm-2 sector"><?= $row[$i]['Agency_Name'] ?></td>
                        <td class="col-4 col-sm-2 user"><?= $row[$i]['User_Name'] ?></td>
                        <?php
                        if ($row[$i]['Jobtype_id'] == 4) {
                            $sql_other = $conn->query("SELECT * FROM itoss_form,itoss_jobtype_orther 
                                WHERE itoss_jobtype_orther.Form_id = itoss_form.Form_id 
                                AND itoss_form.Form_id = " . $row[$i]['Form_id'] . ";");
                            if ($other = $sql_other->fetch())
                                echo '<td class="col-4 col-sm-1 category">' . $other['Jobtype_orther_name'] . '</td>';
                        } else {
                            echo '<td class="col-4 col-sm-1 category">' . $row[$i]['Jobtype_name'] . ' </td>';
                        }
                        ?>
                        <td class="col-8 col-sm-4 text-start">
                            <?= $row[$i]['Form_Work'] ?>
                        </td>
                        <td class="col-3 col-sm-1 status"><?= $row[$i]['Status_form_name'] ?></td>

                        <td class="col-2 col-sm-1">
                            <a href="#" onclick="create_report(<?= $row[$i]['Status_form_id'] ?>,<?= $row[$i]['Form_id'] ?>)"><img src="./asset/icon/Paper.svg" alt=""></a>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>

</main>
<script>
    var phone = document.getElementById("phone");
    var dsk = document.getElementById("dsk");
    if (screen.width <= 576) {
        dsk.classList.add("d-none");

    } else if (screen.width >= 720) {
        phone.classList.add("d-none");
    }

    function disReq() {
        var filter = document.getElementsByClassName('filter');
        for (i = 0; i < filter.length; i++) {
            filter[i].required = false;
        }
    }

    function create_report(status,id) {
        if (status < 5) {
            location.href = "check_request.php?pid=" + id;
        } else if (status == 5) {
            location.href = "create_report.php?pid=" + id;
        } else {
            location.href = "check_report.php?pid=" + id;
        }
    }
</script>
</body>

</html>