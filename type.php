<?php
session_start();
include("header.html");
if (!isset($_SESSION['id'])) {
    header('location:index.php');
}
include($_SESSION['navbar']);

include("connect.php");
if (isset($_POST['createdp'])) {
    $stmt = $conn->prepare("INSERT INTO itoss_jobtype VALUES ('', ?,1)");
    $stmt->bindParam(1, $_POST["inp_name"]);
    $stmt->execute();

    echo '<script language="javascript">';
    echo 'location.href="type.php"';
    echo '</script>';
} else if (isset($_POST['delete'])) {
    $stmt = $conn->query("SELECT * FROM itoss_form WHERE Agency_id = " . $_POST['delete'] . "");
    $del = $stmt->fetch();

    if (empty($del)) {
        echo '<script language="javascript">';
        echo 'toastr.warning("มีข้อมูลหน่วยงานนี้ที่ยังใช้งานอยู่ ไม่สามารถลบได้");';
        echo '</script>';
    } else {
        $stmt = $conn->prepare("UPDATE itoss_jobtype SET state_id = 0 WHERE Jobtype_id = ?");
        $stmt->bindParam(1, $_POST["delete"]);
        $stmt->execute();
        echo '<script language="javascript">';
        echo 'toastr.success("ลบประเภทงานเรียบร้อย");';
        echo '</script>';
    }
} else if (isset($_POST['edit'])) {
    $sql = $conn->query("SELECT * FROM itoss_jobtype WHERE Jobtype_name = '".$_POST["Jobtype_name"]."'");
    $check = $sql->fetch();
    if(empty($check)){
        $stmt = $conn->prepare("UPDATE itoss_jobtype SET Jobtype_name = ? WHERE Jobtype_id = ?");
        $stmt->bindParam(1, $_POST["Jobtype_name"]);
        $stmt->bindParam(2, $_POST["edit"]);
        $stmt->execute();
        echo '<script language="javascript">';
        echo 'toastr.success("แก้ไขประเภทงานเรียบร้อย");';
        echo '</script>';
    }else{
        echo '<script language="javascript">';
        echo 'toastr.warning("ชื่อประเภทงานนี้ถูกใช้งานแล้ว");';
        echo '</script>';
    }
}
?>
<main>
    <div class="row justify-content-center mt-5 ">
        <div class="col col-sm-3 col-xl-3 d-block mx-auto ">
            <p class="text-dark text-center fhead fw-bold">ประเภทงาน</p>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-8 col-xl-6 mx-auto my-0">
            <div class="row">
                <div class="col mb-3">
                    <button type="button" class="btn btn-primary d-block me-xl-auto" data-bs-toggle="modal" data-bs-target="#create-dp">เพิ่มประเภทงาน</button>
                </div>
                <form method="post">
                    <div class="modal fade" id="create-dp" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <p class="ftitle fw-bold text-center">เพิ่มประเภทงาน</p>
                                    <div class="col-xl-10 mx-auto">
                                        <p class="ftitle fw-bold mb-1">ชื่อประเภทงาน</p>
                                        <input type="text" class="data form-control ftitle" name="inp_name" placeholder="กรอกประเภทงาน">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" name="createdp" class="btn btn-primary mx-auto">บันทึก</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="overflow-x-auto">
                <!--ตาราง-->
                <table class="table table-light table-bordered">
                    <thead>
                        <tr class="d-flex text-center fsub">
                            <th class="col-6 col-sm-8">ประเภทงาน</th>
                            <th class="col-4 col-sm-2"></th>
                            <th class="col-4 col-sm-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <!--สถานะ:รออนุมัติ-->
                        <?php
                        $stmt = $conn->query("SELECT * FROM itoss_jobtype WHERE state_id = 1 AND NOT Jobtype_id = 0 ;");
                        while ($row = $stmt->fetch()) { ?>
                            <tr class="d-flex text-center fsub">
                                <td class="col-6 col-sm-8" id="name"><?= $row['Jobtype_name'] ?></td>
                                <td class="col-2 col-sm-2" id="user">
                                    <button class="btn" type="button" data-bs-toggle="modal" data-bs-target="#delete<?= $row['Jobtype_id'] ?>">
                                        <img src="./asset/icon/Delete.svg" alt="">
                                    </button>
                                </td>
                                <td class="col-2 col-sm-2" id="user">
                                    <button class="btn my-0" type="button" data-bs-toggle="modal" data-bs-target="#edit<?= $row['Jobtype_id'] ?>">
                                        <img src="./asset/icon/Setting.svg" alt="">
                                    </button>
                                </td>

                                <form method="post">
                                    <div class="modal fade" id="edit<?= $row['Jobtype_id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <p class="ftitle fw-bold text-center">แก้ไขชื่อประเภทงาน</p>
                                                    <div class="col-xl-10 mx-auto">
                                                        <input type="text" class="data form-control ftitle" name="Jobtype_name" value="<?= $row['Jobtype_name'] ?>">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" name="edit" value="<?= $row['Jobtype_id'] ?>" class="btn btn-primary mx-auto">บันทึก</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <form method="post">
                                    <div class="modal fade" id="delete<?= $row['Jobtype_id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <p class="modal-title fhead fw-bold text-center">ยืนยันการลบ</p>
                                                </div>
                                                <div class="modal-body my-3 my-xl-3 text-center">
                                                    <p class="ftitle text-center d-inline">คุณต้องการลบประเภทงาน </p>
                                                    <p class="ftitle text-center fw-bold d-inline"> <?= $row['Jobtype_name'] ?> </p>
                                                    <p class="ftitle text-center d-inline">หรือไม่</p>
                                                </div>
                                                <div class="modal-footer justify-content-center">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                                                    <button type="submit" class="btn btn-primary" name="delete" value="<?= $row['Jobtype_id'] ?>" class="btn btn-primary mx-auto">ยืนยัน</button>
                                                </div>
                                            </div>
                                        </div>
                                </form>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</main>
</body>

</html>