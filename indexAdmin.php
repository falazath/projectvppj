<?php include ("connect.php");
session_start();
if(isset($_POST['search'])){
    $filter = $conn->prepare("SELECT * FROM itoss_form
    LEFT JOIN itoss_agency ON itoss_agency.Agency_id = itoss_form.Agency_id
    LEFT JOIN itoss_jobtype ON itoss_jobtype.Jobtype_id = itoss_form.Jobtype_id
    LEFT JOIN itoss_status_form ON itoss_status_form.Status_form_id = itoss_form.Status_form_id
    LEFT JOIN itoss_user ON itoss_user.User_id = itoss_form.User_id
    WHERE itoss_form.Agency_id LIKE ? OR itoss_form.User_id LIKE ? OR  itoss_form.Jobtype_id LIKE ?
    OR itoss_form.Form_date_id LIKE ? OR itoss_form.Form_date_end LIKE ? OR itoss_form.Status_form_id LIKE ?
    ;");
    $filter->bindParam(1 , $_POST['sector']);
    $filter->bindParam(2 , $_POST['user']);
    $filter->bindParam(3 , $_POST['type']);
    $filter->bindParam(4 , $_POST['start-date']);
    $filter->bindParam(5 , $_POST['end-date']);
    $filter->bindParam(6 , $_POST['status']);
    $filter->execute();
    $row = $filter->fetchAll();
}else{
    $filter = $conn->prepare("SELECT * FROM itoss_form,itoss_agency,itoss_jobtype,itoss_status_form,itoss_user 
    WHERE itoss_form.Agency_id = itoss_agency.Agency_id AND itoss_form.Jobtype_id = itoss_jobtype.Jobtype_id 
    AND itoss_form.Status_form_id = itoss_status_form.Status_form_id AND itoss_form.User_id = itoss_user.User_id
    AND itoss_agency.state_id = 1 AND itoss_user.state_id = 1;");
    $filter->execute();
    $row = $filter->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title>Document</title>
    <link href="./dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="./dist/css/bootstrap.css" rel="stylesheet">
    <script src="./dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="./style.css">
</head>

<body>
<?php include ("navbar.php");?>
    <main>

        <div class="row justify-content-center">
            <div class="col col-sm-3 col-xl-3 d-block mx-auto">
                <p class="text-dark text-center fhead fw-bold">รายการคำขอปฏิบัติงาน</p>
            </div>
        </div>
        <!--Desktop-->
        <!--ตัวกรอง-->
                <!--ตัวกรอง-->
                <form action="" method="post">
            <div class="row justify-content-start mb-3" id="dsk">
                <div class="col-2 col-sm-2 col-xl-2">
                    <p class="ftitle">หน่วยงาน</p>
                    <select class="form-select" aria-label="Default select example" name="sector">
                        <option selected disabled>เลือกหน่วยงาน</option>
                        <option value="1">KAVE TOWN ISLAND</option>
                        <option value="2">KAVE SEED KASET</option>
                        <option value="3">Atmoz Minburi</option>
                        <option value="4">Origin Plug&Play Srinakarin</option>
                        <option value="5">Blue Phahonyothin 35</option>
                        <option value="6">อื่นๆ</option>
                    </select>
                </div>
                <div class="col-4 col-sm-2 col-xl-2">
                    <p class="ftitle element">ชื่อพนักงาน</p>
                    <select class="form-select" aria-label="Default select example" name="user">
                        <option selected disabled>เลือกพนักงาน</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                    </select>
                </div>
                <div class="col-2 col-sm-2 col-xl-2">
                    <p class="ftitle">ประเภทงาน</p>
                    <select class="form-select" aria-label="Default select example" name="type">
                        <option selected disabled>เลือกประเภทงาน</option>
                        <option value="1">ติดตั้ง</option>
                        <option value="2">ซ่อมบำรุง</option>
                        <option value="3">บินโดรน</option>
                        <option value="4">อื่นๆ</option>
                    </select>
                </div>
                <div class="col-2 col-sm-2 col-xl-2">
                    <p class="ftitle">วันที่เริ่มต้น</p>
                    <input type="date" placeholder="dd-mm-yyyy" min="1997-01-01" max="2030-12-31" value=""
                        class=" form-control" name="start-date" id="">
                </div>
                <div class="col-2 col-sm-2 col-xl-2">
                    <p class="ftitle">วันที่สิ้นสุด</p>
                    <input type="date" class=" form-control" name="end-date" id="">
                </div>
                <div class="col-2 col-sm-2 col-xl-2">
                    <p class="ftitle">สถานะ</p>
                    <select class="form-select" aria-label="Default select example" name="status">
                        <option selected disabled>เลือกสถานะ</option>
                        <option value="1">รออนุมัติ</option>
                        <option value="2">แก้ไข</option>
                        <option value="3">ยกเลิก</option>
                        <option value="4">ไม่อนุมัติ</option>
                        <option value="5">อนุมัติ</option>
                        <option value="6">ติดตามงาน</option>
                        <option value="7">รอตรวจสอบ</option>
                        <option value="8">เสร็จสิ้น</option>
                    </select>
                </div>
                <div class="row justify-content-center mt-3">
                    <div class="col-3 my-auto">
                        <button type="submit" class="btn btn-primary d-block mx-auto px-5" name="search">ค้นหา</button>
                    </div>
                </div>
            </div>
        </form>

        <!--Phone-->
        <!--ตัวกรอง-->
        <form action="" method="post">
            <div class="row justify-content-start" id="phone">
                <div class="col col-sm-2 col-xl-2">
                    <button class="btn btn-light border border-2 fsub" id="filter" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#department" aria-controls="offcanvasBottom"><img src="./asset/icon/Filterph.svg" class="h-100 w-100 d-block mx-auto" alt=""></button>
                    <div class="offcanvas offcanvas-bottom" tabindex="-1" id="department"
                        aria-labelledby="offcanvasBottomLabel">
                        <div class="offcanvas-body small">
                        <div class="row mb-3">
                                <div class="col">
                                    <p class="ftitle fw-bold my-auto ">เลือกหน่วยงาน</p>
                                </div>
                                <div class="col-11">
                                    <select class="form-select" aria-label="Default select example" name="sector">
                                        <option selected disabled>เลือกหน่วยงาน</option>
                                        <option value="1">KAVE TOWN ISLAND</option>
                                        <option value="2">KAVE SEED KASET</option>
                                        <option value="3">Atmoz Minburi</option>
                                        <option value="2">Origin Plug&Play Srinakarin</option>
                                        <option value="3">Blue Phahonyothin 35</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-6">
                                    <p class="ftitle fw-bold my-auto">เลือกพนักงาน</p>
                                </div>
                                <div class="col-11">
                                    <select class="form-select" aria-label="Default select example" name="user">
                                        <option selected disabled>เลือกพนักงาน</option>
                                        <option value="1">คุณตั้ม</option>
                                        <option value="2">คุณเอ</option>
                                        <option value="3">คุณชาช่า</option>
                                        <option value="4">คุณหน่อง</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <p class="ftitle fw-bold my-auto ">เลือกประเภทงาน</p>
                                </div>
                                <div class="col-11">
                                    <select class="form-select" aria-label="Default select example" name="type">
                                        <option selected disabled>เลือกประเภทงาน</option>
                                        <option value="1">ติดตั้ง</option>
                                        <option value="2">ซ่อมบำรุง</option>
                                        <option value="3">บินโดรน</option>
                                        <option value="4">อื่นๆ</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-6 col-sm-2 col-xl-2">
                                    <label for="start-date">วันที่เริ่มต้น</label>
                                    <input type="date" class=" form-control" id="start-date" name="start-date" value="วันที่เริ่มต้น">
                                </div>
                                <div class="col-6 col-sm-2 col-xl-2">
                                    <label for="end-date">วันที่สิ้นสุด</label>
                                    <input type="date" class=" form-control" id="end-date" name="end-date" value="วันที่สิ้นสุด">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <p class="ftitle fw-bold my-auto">สถานะ</p>
                                    <select class="form-select" aria-label="Default select example" name="status">
                                        <option selected disabled>เลือกสถานะ</option>
                                        <option value="1">รออนุมัติ</option>
                                        <option value="2">แก้ไข</option>
                                        <option value="3">ยกเลิก</option>
                                        <option value="4">ไม่อนุมัติ</option>
                                        <option value="5">อนุมัติ</option>
                                        <option value="6">ติดตามงาน</option>
                                        <option value="7">รอตรวจสอบ</option>
                                        <option value="8">เสร็จสิ้น</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="offcanvas-footer">
                            <button type="submit" class="btn btn-primary d-block mx-auto my-2"
                                name="search">ค้นหา</button>
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
                        <input type="date" name="Form_date" class="form-control" value="<?=date("Y-m-d")?>">
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
                if (isset($_POST['create-date']))
                {   
                    $stmt = $conn->prepare("INSERT INTO itoss_form_date VALUES ('', ?)");
                    $stmt->bindParam(1, $_POST["Form_date"]);
                    $stmt->execute();
                
                    $stmt = $conn->query("SELECT * FROM itoss_form_date");
                    while($row = $stmt->fetch()){
                        $_SESSION["date_id"] = $row['Form_date_id'];
                        $_SESSION['date'] = $row['Form_date'];
                    }

                    echo '<script language="javascript">';
                    echo 'alert("บันทึกวันที่แล้ว"); location.href="create.php"';
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
                        $stmt = $conn->query("SELECT * FROM itoss_form INNER JOIN itoss_jobtype ON itoss_form.Jobtype_id = itoss_jobtype.Jobtype_id INNER JOIN itoss_user ON itoss_form.User_id = itoss_user.User_id INNER JOIN itoss_status_form ON itoss_form.Status_form_id = itoss_status_form.Status_form_id  INNER JOIN itoss_agency ON itoss_form.Agency_id = itoss_agency.Agency_id ORDER BY Form_id DESC;");
                        while($row = $stmt->fetch()){
                            $Agency_Name = $row['Agency_Name'];
                            $Jobtype_name = $row['Jobtype_name'];
                            $User_Name = $row['User_Name'];
                            $Status_form_id = $row['Status_form_id'];
                            $Status_form_name = $row['Status_form_name'];
                            $Form_date = $row['Form_date'];
                            $Form_Work = $row['Form_Work'];
                            $Form_id = $row['Form_id'];
                        echo '<tr class="d-flex text-center fsub">
                                <td class="col-3 col-sm-1" id="date">'.$Form_date.'</td>
                                <td class="col-4 col-sm-2" id="sector">'.$Agency_Name.'</td>
                                <td class="col-4 col-sm-2" id="user">'.$User_Name.'</td>
                                <td class="col-4 col-sm-1" id="cate-work">'.$Jobtype_name.'</td>
                                <td class="col-8 col-sm-4 text-start">
                                '.$Form_Work.'
                                </td>
                                <td class="col-3 col-sm-1" id="status">'.$Status_form_name.'</td>
                                <td class="col-2 col-sm-1">';
                                if($Status_form_id < 5){
                                    echo '<a href="requestAdmin.php?Form_id='.$Form_id.'"><img src="./asset/icon/Paper.svg" alt=""></a>';
                                }
                                else if ($Status_form_id > 5){
                                    echo '<a href="check_report.php?Form_id='.$Form_id.'"><img src="./asset/icon/Paper.svg" alt=""></a>';
                                }
                                else{
                                    echo '<a href="create_report.php?Form_id='.$Form_id.'"><img src="./asset/icon/Paper.svg" alt=""></a>';
                                }
                    echo '     </td> 
                            </tr>';
                        }
                        ?>
                </tbody>
            </table>
        </div>

    </main>
    <script>

        function create_report(status) {
            let id = 5;
            if (status < 5) {
                location.href = "check_request.php?pid=" + id;
            } else if (status == 5) {
                location.href = "create_report.php?pid=" + id;
            } else {
                location.href = "check_report.php?pid=" + id;
            }
        }

        var phone = document.getElementById("phone");
        var dsk = document.getElementById("dsk");
        if (screen.width <= 576) {
            dsk.classList.add("d-none");

        } else if (screen.width >= 720) {
            phone.classList.add("d-none");
        }  
    </script>
</body>

</html>