<?php
session_start();
if (!isset($_SESSION['id'])) {
    header('location:index.php');
}
include("header.html");
include("connect.php");
include($_SESSION['navbar']);

if(isset($_SESSION['ch'])){ //toastr
    switch($_SESSION['ch']){
        case 2:{
            echo '<script>toastr.success("ส่งรายละเอียดแก้ไขเรียบร้อย");</script>'; //if send edit success
            unset($_SESSION['ch']);
            break;
        }
        case 4:{
            echo '<script>toastr.success("ไม่อนุมัติคำขอปฏิบัติการเรียบร้อย");</script>'; //if edit success
            unset($_SESSION['ch']);
            break;
            
        }
        case 5:{
            echo '<script>toastr.success("อนุมัติคำขอปฏิบัติการเรียบร้อย");</script>'; //if edit success
            unset($_SESSION['ch']);
            break;
        }
        case 8:{
            echo '<script>toastr.success("การปฏิบัติงานเสร็จสิ้น");</script>'; //if edit success
            unset($_SESSION['ch']);
            break;
            
        }
    }
}

//Value filter
$sql = $conn->query("SELECT * FROM itoss_agency WHERE state_id =1;");
$filter[0] = $sql->fetchAll();
$sql = $conn->query("SELECT * FROM itoss_user WHERE state_id =1;");
$filter[1] = $sql->fetchAll();
$sql = $conn->query("SELECT * FROM itoss_jobtype WHERE state_id =1;");
$filter[2] = $sql->fetchAll();
$sql = $conn->query("SELECT * FROM itoss_status_form");
$filter[3] = $sql->fetchAll();
//-Value filter

if (isset($_POST['search']) || isset($_GET['page'])) {
    isset($_POST['sector']) ? $_SESSION['sector'] = $_POST['sector']: $_SESSION['sector'] ;
    isset($_POST['user']) ? $_SESSION['user'] = $_POST['user'] : $_SESSION['user'];
    isset($_POST['type']) ? $_SESSION['type'] = $_POST['type'] : $_SESSION['type'];
    isset($_POST['start-date']) ? $_SESSION['start-date'] = $_POST['start-date'] : $_SESSION['start-date'];
    isset($_POST['end-date']) ? $_SESSION['end-date'] = $_POST['end-date'] : $_SESSION['end-date'];
    isset($_POST['status']) ? $_SESSION['inpstatus'] = $_POST['status'] : $_SESSION['inpstatus'];

    $idJob = array();
    $sql = "SELECT DISTINCT itoss_form.Form_id FROM itoss_form
         INNER JOIN itoss_agency ON itoss_agency.Agency_id = itoss_form.Agency_id
         INNER JOIN itoss_status_form ON itoss_status_form.Status_form_id = itoss_form.Status_form_id
         INNER JOIN itoss_user ON itoss_user.User_id = itoss_form.User_id ";
    $condition = array();
    $dateSql;
    if (!empty(strcmp('all', $_SESSION['sector']))) {
        $condition[] = "itoss_form.Agency_id LIKE ".$_SESSION['sector']."";
    }
    if (!empty(strcmp('all', $_SESSION['user']))) {
        $condition[] = "itoss_form.User_id LIKE ".$_SESSION['user']."";
    }
    if (!empty(strcmp('all', $_SESSION['type']))) {
        $sql_job = $conn->query("SELECT itoss_jobtype.Jobtype_name,itoss_form.Form_id FROM itoss_job,itoss_form,itoss_jobtype WHERE itoss_job.Form_id = itoss_form.Form_id AND 
                           itoss_job.Jobtype_id = ".$_SESSION['type']." AND itoss_job.Jobtype_id = itoss_jobtype.Jobtype_id");
        while ($row = $sql_job->fetch()) {
            array_push($idJob, $row['Form_id']);
        };
    }else{
        $idJob = null;
    }
    if (!empty(strcmp('', $_SESSION['start-date'])) && !empty(strcmp('', $_SESSION['end-date']))) {
        $condition[] = "itoss_form.Form_date BETWEEN '".$_SESSION['start-date']."' AND '".$_SESSION['end-date']."'";
    }
    if (!empty(strcmp('all', $_SESSION['inpstatus']))) {
        $condition[] = "itoss_form.Status_form_id LIKE ".$_SESSION['inpstatus']."";
    }
    if (count($condition) > 0) {
        $sql .= "WHERE " . implode(' AND ', $condition) . " ORDER BY itoss_form.Form_date DESC,itoss_form.Form_id DESC;";
    }else{
        $sql .= " ORDER BY itoss_form.Form_date DESC,itoss_form.Form_id DESC;";
    }
    $idForm = array();
    $query = $conn->query($sql);
    $in;
    if (!is_null($idJob)) { //ถ้ามี input ประเภทงาน
        while ($data = $query->fetch()) {
            array_push($idForm, $data['Form_id']);
        }
        if (!empty($idForm)) {
            $data = array_intersect($idJob, $idForm);
            $in = "(";
            $max = count($data);
            $i = 0;
            foreach($data as $key => $value){
                            
                            $in .= "'" . $value . "'";
                            if ($i != $max-1) {
                                $in .= ",";
                            }
                            $i++;
            }
            $in .= ")";
        }
    } else { //ถ้าไม่มี input ประเภทงาน
        $data = $query->fetchAll();
        $in = "(";
        for ($i = 0; $i < count($data); $i++) {
            $in .= "'" . $data[$i]['Form_id'] . "'";
            if ($i != (count($data) - 1)) {
                $in .= ",";
            }
        }
        $in .= ")";
    }

    if (!empty($data)) {
        $sql_in = $conn->query("SELECT * FROM itoss_form
                WHERE Form_id IN $in");
        $row = $sql_in->fetchAll();

        $results_per_page = 10;
        $number_of_result = count($row);
        $number_of_page = ceil($number_of_result / $results_per_page);
        if (!isset ($_GET['page']) ) {
            $page = 1;
        } else {
            $page = $_GET['page'];
        }
        $page_first_result = ($page-1)*$results_per_page;

        $sql_in = $conn->query("SELECT * FROM itoss_form
                WHERE Form_id IN $in ORDER BY itoss_form.Form_date DESC,itoss_form.Form_id DESC LIMIT $page_first_result,$results_per_page  ");
        $row = $sql_in->fetchAll();
    } else {
        $sql_in = $conn->query("SELECT * FROM itoss_form
                WHERE Form_id IN ('')");
        $row = $sql_in->fetchAll();
        $results_per_page = 10;
        $number_of_result = count($row);
        $number_of_page = ceil($number_of_result / $results_per_page);
        if (!isset ($_GET['page']) ) {
            $page = 1;
        } else {
            $page = $_GET['page'];
        }
        $page_first_result = ($page-1)*$results_per_page;
        $sql_in = $conn->query("SELECT * FROM itoss_form
                WHERE Form_id IN ('') ORDER BY itoss_form.Form_date DESC,itoss_form.Form_id DESC LIMIT $page_first_result,$results_per_page ");
        $row = $sql_in->fetchAll();
    }
} else {
    unset($_SESSION['sector'],$_SESSION['user'],$_SESSION['type'],$_SESSION['start-date'],$_SESSION['end-date'],$_SESSION['inpstatus']);
    $data = $conn->prepare("SELECT * FROM itoss_form,itoss_agency,itoss_status_form,itoss_user
    WHERE itoss_form.Agency_id = itoss_agency.Agency_id
    AND itoss_form.Status_form_id = itoss_status_form.Status_form_id AND itoss_form.User_id = itoss_user.User_id
    AND itoss_agency.state_id = 1 AND itoss_user.state_id = 1 ORDER BY itoss_form.Form_date DESC,itoss_form.Form_id DESC; ");
    $data->execute();
    $row = $data->fetchAll();

    $results_per_page = 10;
    $number_of_result = count($row);
    $number_of_page1 = ceil($number_of_result / $results_per_page);
    if (!isset ($_GET['page1']) ) {
        $page1 = 1;
    } else {
        $page1 = $_GET['page1'];
    }
    $page_first_result = ($page1-1)*$results_per_page;
    $data = $conn->prepare("SELECT * FROM itoss_form,itoss_agency,itoss_status_form,itoss_user
    WHERE itoss_form.Agency_id = itoss_agency.Agency_id
    AND itoss_form.Status_form_id = itoss_status_form.Status_form_id AND itoss_form.User_id = itoss_user.User_id
    AND itoss_agency.state_id = 1 AND itoss_user.state_id = 1 ORDER BY itoss_form.Form_date DESC,itoss_form.Form_id DESC LIMIT $page_first_result,$results_per_page;");
    $data->execute();
    $row = $data->fetchAll();
}
    function convertDate($date){
        $dd = date('d',strtotime($date));
        $mm = date('m',strtotime($date));
        $yy = date('Y',strtotime($date));
        switch($mm){
            case 1: $mm = "ม.ค";break;
            case 2: $mm = "ก.พ";break;
            case 3: $mm = "มี.ค";break;
            case 4: $mm = "เม.ย";break;
            case 5: $mm = "พ.ค";break;
            case 6: $mm = "มิ.ย";break;
            case 7: $mm = "ก.ค";break;
            case 8: $mm = "ส.ค";break;
            case 9: $mm = "ก.ย";break;
            case 10: $mm = "ต.ค";break;
            case 11: $mm = "พ.ย";break;
            case 12: $mm = "ธ.ค";break;
        }
        $date = $dd." ".$mm." ".($yy+543);
        return $date;
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
    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
        <div class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <button class="navbar-toggler btn btn-success border border-2 fsub position-fixed bottom-0 end-0 bg-white m-2" id="filterBtn" type="button" data-bs-toggle="offcanvas" data-bs-target="#filterPhone" aria-controls="filterPhone" aria-label="Toggle navigation">
                    <img src="./asset/icon/Filterph.svg" class="h-100 w-100 d-block mx-auto" alt="">
                </button>
                <div class="offcanvas offcanvas-bottom" tabindex="-1" id="filterPhone" aria-labelledby="offcanvasBottomLabel">
                    <div class="offcanvas-body small mx-xl-auto">
                        <div class="row justify-content-start mb-3">
                            <div class="col-12 col-sm-2 col-xl-2 mb-2"><!--เลือกหน่วยงาน-->
                                <p class="ftitle mb-0">หน่วยงาน</p>
                                <select class="filter form-select" name="sector" id="filterSector">
                                    <option selected value="all">ทั้งหมด</option>
                                    <?php
                                    for ($i = 1; $i < count($filter[0]); $i++) {
                                        if (!is_null($_SESSION['sector']) && $_SESSION['sector'] != 'all') {
                                            if ($_SESSION['sector'] == $filter[0][$i]['Agency_id']) {
                                                echo '<option selected value="' . $filter[0][$i]['Agency_id'] . '">' . $filter[0][$i]['Agency_Name'] . '</option>';
                                            } else {
                                                echo '<option value="' . $filter[0][$i]['Agency_id'] . '">' . $filter[0][$i]['Agency_Name'] . '</option>';
                                            }
                                        } else {
                                            echo '<option value="' . $filter[0][$i]['Agency_id'] . '">' . $filter[0][$i]['Agency_Name'] . '</option>';
                                        }
                                    }
                                    ?>
                                    <option value="0">อื่นๆ</option>
                                </select>
                            </div>

                            <div class="col-12 col-sm-2 col-xl-2 mb-2"> <!--เลือกชื่อพนักงาน-->
                                <p class="ftitle mb-0">ชื่อพนักงาน</p>
                                <select class="filter form-select" name="user" id="filterEmp">
                                    <option selected value="all">ทั้งหมด</option>
                                    <?php
                                    for ($i = 0; $i < count($filter[1]); $i++) {
                                        if (!is_null($_POST['user']) && $_POST['user'] != 'all') {
                                            if ($_SESSION['user'] == $filter[1][$i]['User_id']) {
                                                echo '<option selected value="' . $filter[1][$i]['User_id'] . '">' . $filter[1][$i]['User_Name'] . '</option>';
                                            } else {
                                                echo '<option value="' . $filter[1][$i]['User_id'] . '">' . $filter[1][$i]['User_Name'] . '</option>';
                                            }
                                        } else {
                                            echo '<option value="' . $filter[1][$i]['User_id'] . '">' . $filter[1][$i]['User_Name'] . '</option>';
                                        }
                                    }
                                    ?>

                                </select>
                            </div>

                            <div class="col-12 col-sm-2 col-xl-2 mb-2"> <!--เลือกประเภทงาน-->
                                <p class="ftitle mb-0">ประเภทงาน</p>
                                <select class="filter form-select" name="type" id="filterType">
                                    <option selected value="all">ทั้งหมด</option>
                                    <?php
                                    for ($i = 1; $i < count($filter[2]); $i++) {
                                        if (!is_null($_SESSION['type']) && $_SESSION['type'] != 'all') {
                                            if ($_SESSION['type'] == $filter[2][$i]['Jobtype_id']) {
                                                echo '<option selected value="' . $filter[2][$i]['Jobtype_id'] . '">' . $filter[2][$i]['Jobtype_name'] . '</option>';
                                            } else {
                                                echo '<option value="' . $filter[2][$i]['Jobtype_id'] . '">' . $filter[2][$i]['Jobtype_name'] . '</option>';
                                            }
                                        } else {
                                            echo '<option value="' . $filter[2][$i]['Jobtype_id'] . '">' . $filter[2][$i]['Jobtype_name'] . '</option>';
                                        }
                                    }
                                    ?>
                                    <option value="0">อื่นๆ</option>
                                </select>
                            </div>

                            <div class="col-12 col-sm-2 col-xl-2 mb-2"> <!--เลือกวันที่เริ่มต้น-->
                                <p class="ftitle mb-0">วันที่เริ่มต้น</p>
                                <?php
                                if (isset($_SESSION['start-date'])) { ?>
                                    <input type="date" class="filter form-control" name="start-date" id="start-date" min="2000-01-01" value="<?= $_SESSION['start-date'] ?>" onchange="requireDate()">
                                <?php
                                } else {
                                    echo '<input type="date" class="filter form-control" name="start-date" id="start-date" min="2000-01-01" value="" onchange="requireDate()">';
                                }
                                ?>
                            </div>
                            <div class="col-12 col-sm-2 col-xl-2 mb-2"> <!--เลือกวันที่สิ้นสุด-->
                                <p class="ftitle mb-0">วันที่สิ้นสุด</p>
                                <?php
                                if (isset($_SESSION['end-date'])) { ?>
                                    <input type="date" class="filter form-control" name="end-date" id="end-date" min="2000-01-01" value="<?= $_SESSION['end-date'] ?>" onchange="requireDate()">

                                <?php
                                } else {
                                    echo '<input type="date" class="filter form-control" name="end-date" id="end-date" min="2000-01-01" value="" onchange="requireDate()">';
                                }
                                ?>

                            </div>

                            <div class="col-12 col-sm-2 col-xl-2 mb-2"> <!--เลือกสถานะ-->
                                <p class="ftitle mb-0">สถานะ</p>
                                <select class="filter form-select" name="status" id="filterStatus">
                                    <option selected value="all">ทั้งหมด</option>
                                    <?php
                                    for ($i = 0; $i < count($filter[3]); $i++) {
                                        if (!is_null($_SESSION['inpstatus']) && $_SESSION['inpstatus'] != 'all') {
                                            if ($_SESSION['inpstatus'] == $filter[3][$i]['Status_form_id']) {
                                                echo '<option selected value="' . $filter[3][$i]['Status_form_id'] . '">' . $filter[3][$i]['Status_form_name'] . '</option>';
                                            } else {
                                                echo '<option value="' . $filter[3][$i]['Status_form_id'] . '">' . $filter[3][$i]['Status_form_name'] . '</option>';
                                            }
                                        } else {
                                            echo '<option value="' . $filter[3][$i]['Status_form_id'] . '">' . $filter[3][$i]['Status_form_name'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="row justify-content-center mt-3">
                                <div class="col-auto my-auto ">
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
                for ($j = 0; $j < count($row); $j++) {
                    if($row[$j]['Status_form_id']==1 || ($row[$j]['Status_form_id']) == 5  || ($row[$j]['Status_form_id'])==7){
                        $bg = 'table-success';
                    }else if($row[$j]['Status_form_id']== 2 || ($row[$j]['Status_form_id']) == 6){
                        $bg = 'table-warning';
                    }else if($row[$j]['Status_form_id']== 3 || ($row[$j]['Status_form_id']) == 4){
                        $bg = 'table-danger';
                    }else if($row[$j]['Status_form_id']== 8){
                        $bg = 'table-secondary';
                    }
                    for($k=0;$k<count($filter[0]);$k++){
                        if($row[$j]['Agency_id'] == $filter[0][$k]['Agency_id']){
                            $Agency_Name = $filter[0][$k]['Agency_Name'];
                        }
                    }
                    for($k=0;$k<count($filter[1]);$k++){
                        if($row[$j]['User_id'] == $filter[1][$k]['User_id']){
                            $User_Name = $filter[1][$k]['User_Name'];
                        }
                    }
                    for($k=0;$k<count($filter[3]);$k++){
                        if($row[$j]['Status_form_id'] == $filter[3][$k]['Status_form_id']){
                            $Status_form_name = $filter[3][$k]['Status_form_name'];
                        }
                    }
                    $Status_form_id = $row[$j]['Status_form_id'];
                    $Form_date = $row[$j]['Form_date'];
                    $Form_Work = $row[$j]['Form_Work'];
                    $Form_id = $row[$j]['Form_id'];
                    echo '<tr class="d-flex text-center fsub">
                                <td class="col-3 col-sm-1" id="date">' . convertDate($Form_date) . '</td>';
                    if ($row[$j]['Agency_id'] == 0) {
                        $sql = $conn->query("SELECT * FROM other_agency WHERE Form_id = '$Form_id'");
                        $agency = $sql->fetch();
                        echo  '<td class="col-4 col-sm-2 text-break" id="sector">' . $agency['name'] . '</td>';
                    } else {
                        echo  '<td class="col-4 col-sm-2 text-break" id="sector">' . $Agency_Name . '</td>';
                    }
                    echo '<td class="col-4 col-sm-2 text-break" id="user">' . $User_Name . '</td>';
                    echo  '<td class="col-4 col-sm-1 text-break" id="cate-work">'; // column ประเภทงาน
                    $sql = $conn->query("SELECT * FROM itoss_job,itoss_form,itoss_jobtype WHERE itoss_job.Form_id = itoss_form.Form_id AND 
                           itoss_form.Form_id = '$Form_id' AND itoss_job.Jobtype_id = itoss_jobtype.Jobtype_id");
                    $job = $sql->fetchAll();
                    for($i=0;$i<count($job);$i++){
                        if($i != 0){
                            echo '/';
                        }
                        if ($job[$i]['Jobtype_id'] == 0) {
                            echo $job[$i]['name_other'];
                        } else {
                            echo $job[$i]['Jobtype_name'];
                        }
                        
                    }
                    echo '</td>';
                    echo    '<td class="col-8 col-sm-4 text-start text-break">
                                ' . $Form_Work . '
                                </td>
                                <td class="col-3 col-sm-1 '.$bg.'" id="status">' . $Status_form_name . '</td>
                                <td class="col-2 col-sm-1">';
                                if ($Status_form_id <= 5) { //ปุ่ม
                                    echo '<a href="requestAdmin.php?Form_id=' . $Form_id . '"><img src="./asset/icon/Paper.svg" alt=""></a>';
                                } else if ($Status_form_id > 5) {
                                    echo '<a href="check_report.php?Form_id=' . $Form_id . '"><img src="./asset/icon/Paper.svg" alt=""></a>';
                                }

                    echo ' </td>
                            </tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php if (isset($_POST['search']) || isset($_GET['page'])) {?>
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                    <li class="page-item"><a class="page-link link-light" href="indexUser.php?page=1">หน้าแรก</a></li> 
                <?php for($page = 1; $page<= $number_of_page; $page++) {?>
                    <li class="page-item"><a class="page-link link-light" href="indexUser.php?page=<?=$page?>"><?=$page?></a></li> 
                <?php } ?>
                    <li class="page-item"><a class="page-link link-light" href="indexUser.php?page=<?=$number_of_page?>">หน้าสุดท้าย</a></li>
            </ul>
        </nav>
        <?php }else{?>
        <nav aria-label="Page navigation example ">
            <ul class="pagination justify-content-center ">
                    <li class="page-item"><a class="page-link link-light" href="indexUser.php?page1=1">หน้าแรก</a></li> 
                <?php for($page1 = 1; $page1<= $number_of_page1; $page1++) {?>
                    <li class="page-item"><a class="page-link link-light" href="indexUser.php?page1=<?=$page1?>"><?=$page1?></a></li> 
                <?php } ?>
                    <li class="page-item"><a class="page-link link-light" href="indexUser.php?page1=<?=$number_of_page1?>">หน้าสุดท้าย</a></li>
            </ul>
        </nav>
        <?php }?>

</main>
<script>
    function clear(){
        alert();
        const filter = document.getElementsByClassName('filter');
        for(i=0;i<filter.length;i++){
            filter[i].selectedIndex = 0;
            filter[i].value = '';
        }
    }
</script>
</body>

</html>