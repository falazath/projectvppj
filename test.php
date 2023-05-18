<?php
session_start();
if (!isset($_SESSION['id'])) {
    header('location:index.php');
}
include("header.html");
include("connect.php");

include($_SESSION['navbar']);

//Value filter
$sql = $conn->query("SELECT * FROM itoss_agency WHERE state_id =1;");
$filter[0] = $sql->fetchAll();
$sql = $conn->query("SELECT * FROM itoss_user WHERE state_id =1;");
$filter[1] = $sql->fetchAll();
$sql = $conn->query("SELECT * FROM itoss_jobtype WHERE state_id =1;;");
$filter[2] = $sql->fetchAll();
$sql = $conn->query("SELECT * FROM itoss_status_form");
$filter[3] = $sql->fetchAll();
//-Value filter
isset($_SESSION['sector']) ?: $_SESSION['sector'] = 'all';
isset($_SESSION['user']) ?: $_SESSION['user'] = 'all';
isset($_SESSION['type']) ?: $_SESSION['type'] = 'all';
isset($_SESSION['start-date']) ?: $_SESSION['start-date'] = '';
isset($_SESSION['end-date']) ?: $_SESSION['end-date'] = '';
isset($_SESSION['inpstatus']) ?: $_SESSION['inpstatus'] = 'all';

if (isset($_POST['search']) || isset($_POST['colFilter'])) {

    isset($_POST['sector']) ? $_SESSION['sector'] = $_POST['sector'] : $_SESSION['sector'];
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
        $condition[] = "itoss_form.Agency_id LIKE '" . $_SESSION['sector'] . "'";
    }
    if (!empty(strcmp('all', $_SESSION['user']))) {
        $condition[] = "itoss_form.User_id LIKE '" . $_SESSION['user'] . "'";
    }
    if (!empty(strcmp('all', $_SESSION['type']))) {
        $sql_job = $conn->query("SELECT itoss_jobtype.Jobtype_name,itoss_form.Form_id FROM itoss_job,itoss_form,itoss_jobtype WHERE itoss_job.Form_id = itoss_form.Form_id AND 
                           itoss_job.Jobtype_id = '" . $_SESSION['type'] . "' AND itoss_job.Jobtype_id = itoss_jobtype.Jobtype_id");
        while ($row = $sql_job->fetch()) {
            array_push($idJob, $row['Form_id']);
        };
    } else {
        $idJob = null;
    }
    if (!empty(strcmp('', $_SESSION['start-date'])) && !empty(strcmp('', $_SESSION['end-date']))) {
        $condition[] = "itoss_form.Form_date BETWEEN '" . $_SESSION['start-date'] . "' AND '" . $_SESSION['end-date'] . "'";
    }
    if (!empty(strcmp('all', $_SESSION['inpstatus']))) {
        $condition[] = "itoss_form.Status_form_id LIKE '" . $_SESSION['inpstatus'] . "'";
    }
    if (count($condition) > 0) {
        $sql .= "WHERE " . implode(' AND ', $condition) . " ORDER BY itoss_form.Form_date DESC,itoss_form.Form_id DESC;";
    } else {
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
            foreach ($data as $key => $value) {

                $in .= "'" . $value . "'";
                if ($i != $max - 1) {
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
        $sql_in = $conn->query("SELECT * FROM itoss_form,itoss_agency,itoss_status_form,itoss_user
        WHERE itoss_form.Agency_id = itoss_agency.Agency_id
        AND itoss_form.Status_form_id = itoss_status_form.Status_form_id
        AND itoss_form.User_id = itoss_user.User_id
        AND itoss_agency.state_id = 1 AND itoss_user.state_id = 1 AND itoss_form.Form_id IN $in ORDER BY itoss_form.Form_date DESC,itoss_form.Form_id DESC;");
        $row = $sql_in->fetchAll();
    } else {
        $sql_in = $conn->query("SELECT * FROM itoss_form
                WHERE itoss_form.Form_id IN ('')");
        $row = $sql_in->fetchAll();
    }
} else {

    $data = $conn->prepare("SELECT * FROM itoss_form,itoss_agency,itoss_status_form,itoss_user
    WHERE itoss_form.Agency_id = itoss_agency.Agency_id
    AND itoss_form.Status_form_id = itoss_status_form.Status_form_id
    AND itoss_form.User_id = itoss_user.User_id
    AND itoss_agency.state_id = 1 AND itoss_user.state_id = 1 ORDER BY itoss_form.Form_date DESC,itoss_form.Form_id DESC; ");
    $data->execute();
    $row = $data->fetchAll();
    unset($_SESSION['sector'], $_SESSION['user'], $_SESSION['type'], $_SESSION['start-date'], $_SESSION['end-date'], $_SESSION['inpstatus']);
}

function convertDate($date)
{
    $dd = date('d', strtotime($date));
    $mm = date('m', strtotime($date));
    $yy = date('Y', strtotime($date));
    switch ($mm) {
        case 1:
            $mm = "ม.ค";
            break;
        case 2:
            $mm = "ก.พ";
            break;
        case 3:
            $mm = "มี.ค";
            break;
        case 4:
            $mm = "เม.ย";
            break;
        case 5:
            $mm = "พ.ค";
            break;
        case 6:
            $mm = "มิ.ย";
            break;
        case 7:
            $mm = "ก.ค";
            break;
        case 8:
            $mm = "ส.ค";
            break;
        case 9:
            $mm = "ก.ย";
            break;
        case 10:
            $mm = "ต.ค";
            break;
        case 11:
            $mm = "พ.ย";
            break;
        case 12:
            $mm = "ธ.ค";
            break;
    }
    $date = $dd . " " . $mm . " " . ($yy + 543);
    return $date;
}

function colspanCheckRq()
{
    $colspan = 0;
    isset($_POST['reqDate']) ? $colspan++ : '';
    isset($_POST['reqSector']) ? $colspan++ : '';
    isset($_POST['reqUser']) ? $colspan++ : '';
    isset($_POST['reqType']) ? $colspan++ : '';
    isset($_POST['reqDetail']) ? $colspan++ : '';
    isset($_POST['reqAssign']) ? $colspan++ : '';
    isset($_POST['reqStatus']) ? $colspan++ : '';
    if (!empty($colspan)) {
        return $colspan;
    } else {
        return 7;
    }
}
function colspanCheckRp()
{
    $colspan = 0;
    isset($_POST['repDetail']) ? $colspan++ : '';
    isset($_POST['repTime']) ? $colspan += 2 : '';
    isset($_POST['repStatus']) ? $colspan += 2 : '';
    isset($_POST['repUser']) ? $colspan++ : '';
    isset($_POST['repClient']) ? $colspan++ : '';
    isset($_POST['repAssign']) ? $colspan++ : '';
    if (!empty($colspan)) {
        return $colspan;
    } else {
        return 8;
    }
}
?>

<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
    <div class="navbar navbar-expand-lg" style="z-index: -1;">
        <div class="container-fluid">
            <button class="navbar-toggler btn btn-success border border-2 fsub position-fixed bottom-0 end-0 bg-white m-2" id="filterBtn" type="button" data-bs-toggle="offcanvas" data-bs-target="#filterPhone" aria-controls="filterPhone" aria-label="Toggle navigation">
                <img src="./asset/icon/Filterph.svg" class="h-100 w-100 d-block mx-auto" alt="">
            </button>
            <div class="offcanvas offcanvas-bottom" tabindex="-1" id="filterPhone" aria-labelledby="offcanvasBottomLabel">
                <div class="offcanvas-body small mx-xl-auto mt-xl-5">
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
                                    if (!is_null($_SESSION['user']) && $_SESSION['user'] != 'all') {
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
<div class="row mb-xl-2 mb-2">
    <div class="dropdown col-sm-auto col-xl-2 ms-auto">
        <button type="button" class="btn btn-secondary dropdown-toggle d-block ms-xl-auto" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
            เลือกคอลัมน์
        </button>
        <form class="dropdown-menu p-4" method="post">
            <div class="mb-3">
                <div class="form-check mb-xl-2">
                    <input type="checkbox" class="checkbox form-check-input my-0 request" name="reqDate" id="req-date" onclick="deRequireCb('request')" required>
                    <label class="form-check-label ms-1 my-0" for="req-date">
                        วันที่
                    </label>
                </div>
                <div class="form-check mb-xl-2">
                    <input type="checkbox" class="checkbox form-check-input my-0 request" name="reqSector" id="req-sector" onclick="deRequireCb('request')" required>
                    <label class="form-check-label ms-1 my-0" for="req-sector">
                        หน่วยงาน
                    </label>
                </div>
                <div class="form-check mb-xl-2">
                    <input type="checkbox" class="checkbox form-check-input my-0 request" name="reqUser" id="req-user" onclick="deRequireCb('request')" required>
                    <label class="form-check-label ms-1 my-0" for="req-user">
                        เจ้าหน้าที่
                    </label>
                </div>
                <div class="form-check mb-xl-2">
                    <input type="checkbox" class="checkbox form-check-input my-0 request" name="reqType" id="req-type" onclick="deRequireCb('request')" required>
                    <label class="form-check-label ms-1 my-0" for="req-type">
                        ประเภทงาน
                    </label>
                </div>
                <div class="form-check mb-xl-2">
                    <input type="checkbox" class="checkbox form-check-input my-0 request" name="reqDetail" id="req-detail" onclick="deRequireCb('request')" required>
                    <label class="form-check-label ms-1 my-0" for="req-detail">
                        รายละเอียดคำขอ
                    </label>
                </div>
                <div class="form-check mb-xl-2">
                    <input type="checkbox" class="checkbox form-check-input my-0 request" name="reqAssign" id="req-assign" onclick="deRequireCb('request')" required>
                    <label class="form-check-label ms-1 my-0" for="req-assign">
                        ผู้มอบหมาย
                    </label>
                </div>
                <div class="form-check mb-xl-2">
                    <input type="checkbox" class="checkbox form-check-input my-0 request" name="reqStatus" id="req-status" onclick="deRequireCb('request')" required>
                    <label class="form-check-label ms-1 my-0" for="req-status">
                        สถานะคำขอ
                    </label>
                </div>
                <hr>
                <div class="form-check mb-xl-2">
                    <input type="checkbox" class="checkbox form-check-input my-0 report" name="repDetail" id="rep-detail" onclick="deRequireCb('report')" required>
                    <label class="form-check-label ms-1 my-0" for="rep-detail">
                        รายละเอียดรายงาน
                    </label>
                </div>
                <div class="form-check mb-xl-2">
                    <input type="checkbox" class="checkbox form-check-input my-0 report" name="repTime" id="rep-time" onclick="deRequireCb('report')" required>
                    <label class="form-check-label ms-1 my-0" for="rep-time">
                        เวลาดำเนินงาน
                    </label>
                </div>
                <div class="form-check mb-xl-2">
                    <input type="checkbox" class="checkbox form-check-input my-0 report" name="repStatus" id="rep-status" onclick="deRequireCb('report')" required>
                    <label class="form-check-label ms-1 my-0" for="rep-statuts">
                        สถานะ
                    </label>
                </div>
                <div class="form-check mb-xl-2">
                    <input type="checkbox" class="checkbox form-check-input my-0 report" name="repUser" id="rep-user" onclick="deRequireCb('report')" required>
                    <label class="form-check-label ms-1 my-0" for="rep-user">
                        เจ้าหน้าที่
                    </label>
                </div>
                <div class="form-check mb-xl-2">
                    <input type="checkbox" class="checkbox form-check-input my-0 report" name="repClient" id="rep-client" onclick="deRequireCb('report')" required>
                    <label class="form-check-label ms-1 my-0" for="rep-client">
                        ผู้ตรวจสอบ
                    </label>
                </div>
                <div class="form-check mb-xl-2">
                    <input type="checkbox" class="checkbox form-check-input my-0 report" name="repAssign" id="rep-assign" onclick="deRequireCb('report')" required>
                    <label class="form-check-label ms-1 my-0" for="rep-assign">
                        ผู้อนุมัติ
                    </label>
                </div>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary " name="colFilter">เลือกคอลัมน์</button>
            </div>
        </form>
    </div>
    <!-- ปุ่มกด export Excel -->
    <div class="col-auto align-self-end">
        <button class="btn btn-primary" type="button" id="exportBtn1">Export</button>
    </div>
    <div class="row">
        <div class="overflow-x-auto">
            <table style="width:150%">
                <thead class="text-center">
                    <tr>
                        <th class="text-center border border-dark w-auto" colspan="<?= colspanCheckRq() ?>">คำขอปฏิบัติงาน</th>
                        <th class="text-center border border-dark w-auto" colspan="<?= colspanCheckRp() ?>">รายงานการปฏิบัติงาน</th>
                    </tr>
                    <tr class="border border-dark" rowspan="2">
                        <th class="border border-dark" rowspan="2">วันที่</th>
                        <th class="border border-dark" rowspan="2">หน่วยงาน</th>
                        <th class="border border-dark" rowspan="2">เจ้าหน้าที่</th>
                        <th class="border border-dark" rowspan="2">ประเภทงาน</th>
                        <th class="border border-dark" rowspan="2">รายละเอียดคำขอ</th>
                        <th class="border border-dark" rowspan="2">ผู้มอบหมาย</th>
                        <th class="border border-dark" rowspan="2">สถานะคำขอ</th>
                        <th class="border border-dark" rowspan="2">รายละเอียดรายงาน</th>
                        <th class="border border-dark" colspan="2">เวลาดำเนินงาน</th>
                        <th class="border border-dark" colspan="2">สถานะรายงาน</th>
                        <th class="border border-dark" rowspan="2">เจ้าหน้าที่</th>
                        <th class="border border-dark" rowspan="2">ผู้ตรวจสอบ</th>
                        <th class="border border-dark" rowspan="2">ผู้อนุมัติ</th>
                    </tr>
                    <tr class="border border-dark">
                        <th class="border border-dark">เริ่ม</th>
                        <th class="border border-dark">เสร็จสิ้น</th>
                        <th class="border border-dark">ปิดงาน</th>
                        <th class="border border-dark">ติดตามงาน</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    for ($i = 0; $i < count($row); $i++) {
                        $sql = $conn->query("SELECT * FROM other_agency WHERE Form_id = '" . $row[$i]['Form_id'] . "' ORDER BY id DESC LIMIT 1");
                        $data = $sql->fetch();
                        $agency = isset($data['name']) ? $data['name'] : $row[$i]['Agency_Name'];
                        $sql_report = $conn->query("SELECT * FROM itoss_report WHERE Form_id = '" . $row[$i]['Form_id'] . "'");
                        $report = $sql_report->fetchAll();
                        if (empty(count($report))) {
                            $count = 1;
                        } else {
                            $count = count($report);
                        }
                        for ($j = 0; $j < $count; $j++) {
                    ?>
                            <tr>
                                <td class="border border-dark text-center" style="width: 5.5%;"><?= convertDate($row[$i]['Form_date']) ?></td>
                                <td class="border border-dark text-center text-break" style="width: 7%;"><?= $agency ?></td>
                            </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>

</html>