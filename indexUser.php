<?php
session_start();
if (!isset($_SESSION['id'])) {
    header('location:login.php');
}
include("header.html");
include("connect.php");
include($_SESSION['navbar']);
if (isset($_SESSION['ch'])) { //toastr
    switch ($_SESSION['ch']) {
        case 1: {
                echo '<script>toastr.success("สร้างคำขอเรียบร้อย");</script>'; //if create success
                unset($_SESSION['ch']);
                break;
            }
        case 3: {
                echo '<script>toastr.success("ยกเลิกคำขอเรียบร้อย");</script>'; //if cancel success
                unset($_SESSION['ch']);
                break;
            }
        case 6: {
                echo '<script>toastr.success("สร้างรายงานเรียบร้อย:กำลังติดตามงาน");</script>'; //if edit report success
                unset($_SESSION['ch']);
                break;
            }
        case 7: {
                echo '<script>toastr.success("สร้างรายงานเรียบร้อย:รอตรวจสอบ");</script>'; //if edit report success
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
$sql = $conn->query("SELECT * FROM itoss_jobtype;");
$filter[2] = $sql->fetchAll();
$sql = $conn->query("SELECT * FROM itoss_status_form");
$filter[3] = $sql->fetchAll();
//-Value filter

if (isset($_POST['search'])) {
    $inpAgency = $_POST['sector'];
    $inpUser = $_POST['user'];
    $inpType = $_POST['type'];
    $inpStart = $_POST['start-date'];
    $inpEnd = $_POST['end-date'];
    $inpStatus = $_POST['status'];
    $idJob = array();
    $sql = "SELECT DISTINCT itoss_form.Form_id FROM itoss_form
         INNER JOIN itoss_agency ON itoss_agency.Agency_id = itoss_form.Agency_id
         INNER JOIN itoss_status_form ON itoss_status_form.Status_form_id = itoss_form.Status_form_id
         INNER JOIN itoss_user ON itoss_user.User_id = itoss_form.User_id ";
    $condition = array();
    $dateSql;
    if (!empty(strcmp('all', $inpAgency))) {
        $condition[] = "itoss_form.Agency_id LIKE '$inpAgency'";
    }
    if (!empty(strcmp('all', $inpUser))) {
        $condition[] = "itoss_form.User_id LIKE '$inpUser'";
    }
    if (!empty(strcmp('all', $inpType))) {
        $sql_job = $conn->query("SELECT itoss_jobtype.Jobtype_name,itoss_form.Form_id FROM itoss_job,itoss_form,itoss_jobtype WHERE itoss_job.Form_id = itoss_form.Form_id AND 
                           itoss_job.Jobtype_id = '$inpType' AND itoss_job.Jobtype_id = itoss_jobtype.Jobtype_id");
        while ($row = $sql_job->fetch()) {
            array_push($idJob, $row['Form_id']);
        };
    }
    if (!empty(strcmp('', $inpStart)) && !empty(strcmp('', $inpEnd))) {
        $condition[] = "itoss_form.Form_date BETWEEN '$inpStart' AND '$inpEnd'";
    }
    if (!empty(strcmp('all', $inpStatus))) {
        $condition[] = "itoss_form.Status_form_id LIKE '$inpStatus'";
    }
    if (count($condition) > 0) {
        $sql .= "WHERE " . implode(' AND ', $condition) . " ORDER BY itoss_form.Form_date DESC,itoss_form.Form_id DESC;";
    }
    $idForm = array();
    $query = $conn->query($sql);
    $in;
    if (!empty($idJob)) { //ถ้ามี input ประเภทงาน
        while ($data = $query->fetch()) {
            array_push($idForm, $data['Form_id']);
        }
        if (!empty($idForm)) {
            $data = array_intersect($idJob, $idForm);
            print_r(array_key_last($data));
            $in = "(";
            for ($i = 0; $i <= array_key_last($data); $i++) {
                if (!empty($data[$i])) {
                    echo $data[$i];
                    $in .= "'" . $data[$i] . "'";
                    if ($i != array_key_last($data)) {
                        $in .= ",";
                    }
                }
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
                WHERE itoss_form.Form_id IN $in");
        $row = $sql_in->fetchAll();
    } else {
        $sql_in = $conn->query("SELECT * FROM itoss_form
                WHERE itoss_form.Form_id IN ('')");
        $row = $sql_in->fetchAll();
    }
} else {
    $data = $conn->prepare("SELECT * FROM itoss_form,itoss_agency,itoss_status_form,itoss_user
    WHERE itoss_form.Agency_id = itoss_agency.Agency_id
    AND itoss_form.Status_form_id = itoss_status_form.Status_form_id AND itoss_form.User_id = itoss_user.User_id
    AND itoss_agency.state_id = 1 AND itoss_user.state_id = 1 ORDER BY itoss_form.Form_date DESC,itoss_form.Form_id DESC; ");
    $data->execute();
    $row = $data->fetchAll();
}
if (!empty($_SESSION['check'])) {
?>
    <script>
        $(document).ready(function() {
            $("#notiWorkToday").modal("show");
        });
    </script>
<?php
}

?>
<main>
    <!-- Modal -->
    <div class="modal fade" id="notiWorkToday" tabindex="-1" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitleId">รายการคำขอปฏิบัติงานของคุณวันนี้</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <?php
                        for ($i = 0; $i < count(isset($_SESSION['check']) ? $_SESSION['check'] : array()); $i++) {
                        ?>
                            <div class="row">
                                <div class="col">
                                    <p class="ftitle fw-bold"><?= $_SESSION['check'][$i]['Agency_Name'] ?></p>
                                    <p class="ftitle">รายละเอียด: </p>
                                    <?= $_SESSION['check'][$i]['Form_Work'] ?>
                                </div>
                            </div>
                        <?php
                        }
                        unset($_SESSION['check']);
                        ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>

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
                                        if (!is_null($_POST['sector']) && $_POST['sector'] != 'all') {
                                            if ($_POST['sector'] == $filter[0][$i]['Agency_id']) {
                                                echo '<option selected value="' . $filter[0][$i]['Agency_id'] . '">' . $filter[0][$i]['Agency_Name'] . '</option>';
                                                $_POST['sector'] = null;
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
                                            if ($_POST['user'] == $filter[1][$i]['User_id']) {
                                                echo '<option selected value="' . $filter[1][$i]['User_id'] . '">' . $filter[1][$i]['User_Name'] . '</option>';
                                                $_POST['user'] = null;
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
                                        if (!is_null($_POST['type']) && $_POST['Jobtype_id'] != 'all') {
                                            if ($_POST['type'] == $filter[2][$i]['Jobtype_id']) {
                                                echo '<option selected value="' . $filter[2][$i]['Jobtype_id'] . '">' . $filter[2][$i]['Jobtype_name'] . '</option>';
                                                $_POST['type'] = null;
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
                                if (isset($_POST['start-date'])) { ?>
                                    <input type="date" class="filter form-control" name="start-date" id="start-date" min="2000-01-01" value="<?= $_POST['start-date'] ?>" onchange="requireDate()">
                                <?php
                                } else {
                                    echo '<input type="date" class="filter form-control" name="start-date" id="start-date" min="2000-01-01" value="" onchange="requireDate()">';
                                }
                                ?>
                            </div>
                            <div class="col-12 col-sm-2 col-xl-2 mb-2"> <!--เลือกวันที่สิ้นสุด-->
                                <p class="ftitle mb-0">วันที่สิ้นสุด</p>
                                <?php
                                if (isset($_POST['end-date'])) { ?>
                                    <input type="date" class="filter form-control" name="end-date" id="end-date" min="2000-01-01" value="<?= $_POST['end-date'] ?>" onchange="requireDate()">

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
                                        if (!is_null($_POST['status']) && $_POST['Status_form_id'] != 'all') {
                                            if ($_POST['status'] == $filter[3][$i]['Status_form_id']) {
                                                echo '<option selected value="' . $filter[3][$i]['Status_form_id'] . '">' . $filter[3][$i]['Status_form_name'] . '</option>';
                                                $_POST['status'] = null;
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
                                <input type="date" name="Form_date" id="Form_date" class="form-control">
                                <input type="text" name="Show_date" id="Show_date" class="form-control">
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
                    if ($row[$j]['Status_form_id'] == 1 || ($row[$j]['Status_form_id']) == 5  || ($row[$j]['Status_form_id']) == 7) {
                        $bg = 'table-success';
                    } else if ($row[$j]['Status_form_id'] == 2 || ($row[$j]['Status_form_id']) == 6) {
                        $bg = 'table-warning';
                    } else if ($row[$j]['Status_form_id'] == 3 || ($row[$j]['Status_form_id']) == 4) {
                        $bg = 'table-danger';
                    } else if ($row[$j]['Status_form_id'] == 8) {
                        $bg = 'table-secondary';
                    }
                    for ($k = 0; $k < count($filter[0]); $k++) {
                        if ($row[$j]['Agency_id'] == $filter[0][$k]['Agency_id']) {
                            $Agency_Name = $filter[0][$k]['Agency_Name'];
                        }
                    }
                    for ($k = 0; $k < count($filter[1]); $k++) {
                        if ($row[$j]['User_id'] == $filter[1][$k]['User_id']) {
                            $User_Name = $filter[1][$k]['User_Name'];
                        }
                    }
                    for ($k = 0; $k < count($filter[3]); $k++) {
                        if ($row[$j]['Status_form_id'] == $filter[3][$k]['Status_form_id']) {
                            $Status_form_name = $filter[3][$k]['Status_form_name'];
                        }
                    }
                    $Status_form_id = $row[$j]['Status_form_id'];
                    $Form_date = $row[$j]['Form_date'];
                    $Form_Work = $row[$j]['Form_Work'];
                    $Form_id = $row[$j]['Form_id'];
                    echo '<tr class="d-flex text-center fsub">
                                <td class="col-3 col-sm-1" id="date">' . date("d/m/Y", strtotime($Form_date)) . '</td>';
                    if ($row[$j]['Agency_id'] == 0) {
                        $sql = $conn->query("SELECT * FROM other_agency WHERE Form_id = '$Form_id'");
                        $agency = $sql->fetch();
                        echo  '<td class="col-4 col-sm-2" id="sector">' . $agency['name'] . '</td>';
                    } else {
                        echo  '<td class="col-4 col-sm-2" id="sector">' . $Agency_Name . '</td>';
                    }
                    echo '<td class="col-4 col-sm-2" id="user">' . $User_Name . '</td>';
                    echo  '<td class="col-4 col-sm-1" id="cate-work">'; // column ประเภทงาน
                    $sql = $conn->query("SELECT * FROM itoss_job,itoss_form,itoss_jobtype WHERE itoss_job.Form_id = itoss_form.Form_id AND 
                           itoss_form.Form_id = '$Form_id' AND itoss_job.Jobtype_id = itoss_jobtype.Jobtype_id ORDER BY itoss_job.Job_id ASC");
                    $job = $sql->fetchAll();
                    for ($i = 0; $i < count($job); $i++) {
                        if ($i != 0) {
                            echo '/';
                        }
                        if ($job[$i]['Jobtype_id'] == 0) {
                            echo $job[$i]['name_other'];
                        } else {
                            echo $job[$i]['Jobtype_name'];
                        }
                    }
                    echo '</td>';
                    echo    '<td class="col-8 col-sm-4 text-start">
                                ' . $Form_Work . '
                                </td>
                                <td class="col-3 col-sm-1 ' . $bg . '" id="status">' . $Status_form_name . '</td>
                                <td class="col-2 col-sm-1">';
                    if ($row[$j]['User_id'] == $_SESSION['id']) {
                        if ($Status_form_id < 5) {
                            echo '<a href="requestUser.php?Form_id=' . $Form_id . '"><img src="./asset/icon/Paper.svg" alt=""></a>';
                        } else if ($Status_form_id > 5) {
                            echo '<a href="check_report.php?Form_id=' . $Form_id . '"><img src="./asset/icon/Paper.svg" alt=""></a>';
                        } else if ($Status_form_id == 5) {
                            echo '<a href="create_report.php?Form_id=' . $Form_id . '"><img src="./asset/icon/Paper.svg" alt=""></a>';
                        }
                    } else {
                        if ($Status_form_id <= 5) {
                            echo '<a href="requestUser.php?Form_id=' . $Form_id . '"><img src="./asset/icon/Paper.svg" alt=""></a>';
                        } else if ($Status_form_id > 5) {
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
    $(document).ready(function() {
        $("#Show_date").hide();
        $("#Form_date").val("<?= date("Y-m-d") ?>");
        var date = new Date($('#Form_date').val());
        var options = {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        };
        var formattedDate = date.toLocaleDateString('th-TH',
            options);
        $("#Show_date").val(formattedDate);
        $("#Form_date").hide();
        $("#Show_date").show();

        $("#Form_date").change(function() {
            var date = new Date($(this).val());
            var options = {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            };
            var formattedDate = date.toLocaleDateString('th-TH',
                options);
            $("#Show_date").val(formattedDate);
            $("#Form_date").hide();
            $("#Show_date").show();
        });

        $("#Show_date").click(function() {
            $("#Show_date").hide();
            $("#Form_date").show();
        });
    });
    // Get the current date
    let currentDate = new Date();

    // Get the current year, month, and day
    let year = currentDate.getFullYear() + 543;
    let month = currentDate.getMonth() + 1; // Month is 0-indexed, so add 1
    let day = currentDate.getDate();

    // Pad the day with a leading zero if necessary
    day = day.toString().padStart(2, '0');

    // Get the abbreviated name of the month
    let monthNames = ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.',
        'ธ.ค.'
    ];
    let monthAbbr = monthNames[month - 1]; // Month is 1-indexed, so subtract 1

    // Get the date in the format "DD-MMM-YYYY"
    let dateString = `${day} ${monthAbbr} ${year}`;
    let dateElement = document.getElementById('date');
    dateElement.innerHTML = dateString;

    function requireDate() {
        document.getElementById('start-date').required = true;
        document.getElementById('end-date').required = true;
    }
    var status = document.getElementById('status')
</script>
</body>

</html>