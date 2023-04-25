<?php
session_start();
include("connect.php");
include("header.html");

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

if (isset($_POST['search']) && !($_POST['sector'] == 'all' && $_POST['user'] == 'all' && $_POST['type'] && $_POST['start-date'] == '' && $_POST['end-date'] == '' && $_POST['status'] == 'all')) {

    $data = $conn->prepare("SELECT * FROM itoss_form
    LEFT JOIN itoss_agency ON itoss_agency.Agency_id = itoss_form.Agency_id
    LEFT JOIN itoss_jobtype ON itoss_jobtype.Jobtype_id = itoss_form.Jobtype_id
    LEFT JOIN itoss_status_form ON itoss_status_form.Status_form_id = itoss_form.Status_form_id
    LEFT JOIN itoss_user ON itoss_user.User_id = itoss_form.User_id
    WHERE itoss_form.Agency_id LIKE ? OR itoss_form.User_id LIKE ? OR  itoss_form.Jobtype_id LIKE ?
    OR itoss_form.Form_date LIKE ? OR itoss_form.Form_date_end LIKE ? OR itoss_form.Status_form_id LIKE ?
    ORDER BY itoss_form.Form_date DESC;");
    $data->bindParam(1, $_POST['sector']);
    $data->bindParam(2, $_POST['user']);
    $data->bindParam(3, $_POST['type']);
    $data->bindParam(4, $_POST['start-date']);
    $data->bindParam(5, $_POST['end-date']);
    $data->bindParam(6, $_POST['status']);
    $data->execute();
    $row = $data->fetchAll();
} else {
    $data = $conn->prepare("SELECT * FROM itoss_form,itoss_agency,itoss_jobtype,itoss_status_form,itoss_user
    WHERE itoss_form.Agency_id = itoss_agency.Agency_id AND itoss_form.Jobtype_id = itoss_jobtype.Jobtype_id 
    AND itoss_form.Status_form_id = itoss_status_form.Status_form_id AND itoss_form.User_id = itoss_user.User_id
    AND itoss_agency.state_id = 1 AND itoss_user.state_id = 1 ORDER BY itoss_form.Form_date DESC ");
    $data->execute();
    $row = $data->fetchAll();

    include($_SESSION['navbar']);

}
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
        <div class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <button class="navbar-toggler btn btn-success border border-2 fsub position-fixed bottom-0 end-0 bg-white m-2" id="filterBtn" type="button" data-bs-toggle="offcanvas" data-bs-target="#filterPhone" aria-controls="filterPhone" aria-label="Toggle navigation">
                    <img src="./asset/icon/Filterph.svg" class="h-100 w-100 d-block mx-auto" alt="">
                </button>
                <div class="offcanvas offcanvas-bottom" tabindex="-1" id="filterPhone" aria-labelledby="offcanvasBottomLabel">
                    <div class="offcanvas-body small">
                        <div class="row justify-content-start mb-3">
                            <div class="col-12 col-sm-2 col-xl-2 mb-2"><!--เลือกหน่วยงาน-->
                                <p class="ftitle mb-0">หน่วยงาน</p>
                                <select class="filter form-select" name="sector" id="filterSector" >
                                    <option selected value="all">ทั้งหมด</option>
                                    <?php
                                    for ($i = 1; $i < count($filter[0]); $i++) {

                                        echo '<option value="' . $filter[0][$i]['Agency_id'] . '">' . $filter[0][$i]['Agency_Name'] . '</option>';
                                    }
                                    ?>
                                    <option value="0">อื่นๆ</option>
                                </select>
                            </div>

                            <div class="col-12 col-sm-2 col-xl-2 mb-2"> <!--เลือกชื่อพนักงาน-->
                                <p class="ftitle mb-0">ชื่อพนักงาน</p>
                                <select class="filter form-select" name="user" id="filterEmp" >
                                    <option selected value="all">ทั้งหมด</option>
                                    <?php
                                    for ($i = 0; $i < count($filter[1]); $i++) {

                                        echo '<option value="' . $filter[1][$i]['User_id'] . '">' . $filter[1][$i]['User_Name'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-12 col-sm-2 col-xl-2 mb-2"> <!--เลือกประเภทงาน-->
                                <p class="ftitle mb-0">ประเภทงาน</p>
                                <select class="filter form-select" name="type" id="filterType" >
                                    <option selected value="all">ทั้งหมด</option>
                                    <?php
                                    for ($i = 1; $i < count($filter[2]); $i++) {

                                        echo '<option value="' . $filter[2][$i]['Jobtype_id'] . '">' . $filter[2][$i]['Jobtype_name'] . '</option>';
                                    }
                                    ?>
                                    <option value="0">อื่นๆ</option>
                                </select>
                            </div>

                            <div class="col-12 col-sm-2 col-xl-2 mb-2"> <!--เลือกวันที่เริ่มต้น-->
                                <p class="ftitle mb-0">วันที่เริ่มต้น</p>
                                <input type="date" class="filter form-control" name="start-date" min="2000-01-01" value="" >
                            </div>
                            <div class="col-12 col-sm-2 col-xl-2 mb-2"> <!--เลือกวันที่สิ้นสุด-->
                                <p class="ftitle mb-0">วันที่สิ้นสุด</p>
                                <input type="date" class="filter form-control" name="end-date" id="" min="2000-01-01" value="" >
                            </div>
                            
                            <div class="col-12 col-sm-2 col-xl-2 mb-2"> <!--เลือกสถานะ-->
                                <p class="ftitle mb-0">สถานะ</p>
                                <select class="filter form-select" name="status" id="filterStatus" >
                                    <option selected value="all">ทั้งหมด</option>
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
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!--ปุ่มสร้างคำขอ-->
    <div class="row">
        <div class="col mb-3">
            <button type="button" class="btn btn-primary d-block me-xl-auto" data-bs-toggle="modal" data-bs-target="#create-date">สร้างคำขอปฏิบัติงาน</button>
        </div>
        <form method="post">
            <div class="modal fade" id="create-date" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body">
                            <p class="ftitle fw-bold text-center">วันที่สร้างคำขอปฏิบัติงาน</p>
                            <div class="col-xl-10 mx-auto">
                                <input type="date" name="Form_date" class="form-control" value="<?= date("Y-m-d") ?>">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="create-date" class="btn btn-primary mx-auto">บันทึก</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <?php
        if (isset($_POST['create-date'])) {
            $_SESSION['date'] = $_POST["Form_date"];
            echo '<script language="javascript">';
            echo 'location.href="create.php"';
            echo '</script>';
        }
        ?>
    </div>
    <div class="overflow-x-auto">
        <!--ตาราง-->
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
                <!--สถานะ:รออนุมัติ-->
                <?php
                for($j=0;$j<count($row);$j++) {
                    $Agency_Name = $row[$j]['Agency_Name'];
                    $Jobtype_name = $row[$j]['Jobtype_name'];
                    $User_Name = $row[$j]['User_Name'];
                    $Status_form_id = $row[$j]['Status_form_id'];
                    $Status_form_name = $row[$j]['Status_form_name'];
                    $Form_date = $row[$j]['Form_date'];
                    $Form_Work = $row[$j]['Form_Work'];
                    $Form_id = $row[$j]['Form_id'];
                    echo '<tr class="d-flex text-center fsub">
                                <td class="col-3 col-sm-1" id="date">' . $Form_date . '</td>';
                                if($row[$j]['Agency_id'] == 0){
                                    $sql = $conn->query("SELECT * FROM other_agency WHERE Form_id = '$Form_id'");
                                    $agency = $sql->fetch();
                                    echo  '<td class="col-4 col-sm-2" id="sector">' . $agency['name'] . '</td>';
                                }else{
                                    echo  '<td class="col-4 col-sm-2" id="sector">' . $Agency_Name . '</td>';
                                }
                           echo '<td class="col-4 col-sm-2" id="user">' . $User_Name . '</td>';
                           if($row[$j]['Jobtype_id'] == 0){
                                    $sql = $conn->query("SELECT * FROM itoss_jobtype_orther WHERE Form_id = '$Form_id'");
                                    $job = $sql->fetch();
                               echo  '<td class="col-4 col-sm-1" id="cate-work">' . $job['Jobtype_orther_name'] . '</td>';
                               
                        }else{
                            echo  '<td class="col-4 col-sm-1" id="cate-work">' . $Jobtype_name . '</td>';

                        }
                            echo    '<td class="col-8 col-sm-4 text-start">
                                ' . $Form_Work . '
                                </td>
                                <td class="col-3 col-sm-1" id="status">' . $Status_form_name . '</td>
                                <td class="col-2 col-sm-1">';
                                if($row[$j]['User_id'] == $_SESSION['id']){
                                    if ($Status_form_id < 5) {
                        echo '<a href="requestUser.php?Form_id=' . $Form_id . '"><img src="./asset/icon/Paper.svg" alt=""></a>';
                    } else if ($Status_form_id > 5 ) {
                        echo '<a href="check_report.php?Form_id=' . $Form_id . '"><img src="./asset/icon/Paper.svg" alt=""></a>';
                    } else if ($Status_form_id == 5 ){
                        echo '<a href="create_report.php?Form_id=' . $Form_id . '"><img src="./asset/icon/Paper.svg" alt=""></a>';
                    }
                                }else{
                                    if ($Status_form_id <= 5) {
                                        echo '<a href="requestUser.php?Form_id=' . $Form_id . '"><img src="./asset/icon/Paper.svg" alt=""></a>';
                                    } else if ($Status_form_id > 5 ) {
                                        echo '<a href="check_report.php?Form_id=' . $Form_id . '"><img src="./asset/icon/Paper.svg" alt=""></a>';
                                    }
                                }
                    

                    echo ' </td>
                            </tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

</main>
<script>
</script>
</body>

</html>