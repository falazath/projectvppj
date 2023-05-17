<?php
session_start();
include("header.html");
if (!isset($_SESSION['id'])) {
  header('location:index.php');
}
include($_SESSION['navbar']);
include("connect.php");

if (isset($_POST['createdp'])) {
  $sql = $conn->query("SELECT * FROM itoss_agency WHERE Agency_Name = '".$_POST["Agency_Name"]."'");
  $check = $sql->fetch();
  if(empty($check)){
    $stmt = $conn->prepare("INSERT INTO itoss_agency VALUES ('', ?, 1)");
    $stmt->bindParam(1, $_POST["Agency_Name"]);
    $stmt->execute();
  
    echo '<script language="javascript">';
    echo 'toastr.success("เพิ่มหน่วยงานเรียบร้อย");';
    echo '</script>';
  }else{
    echo '<script language="javascript">';
    echo 'toastr.warning("ชื่อหน่วยงานซ้ำ");';
    echo '</script>';
  }
} else if (isset($_POST['editname'])) {
  $sql = $conn->query("SELECT * FROM itoss_agency WHERE Agency_Name = '".$_POST["Agency_Name"]."'");
  $check = $sql->fetch();
  if(empty($check)){
    $stmt = $conn->prepare("UPDATE itoss_agency SET Agency_Name = ? where Agency_id = ?");
    $stmt->bindParam(1, $_POST["Agency_Name"]);
    $stmt->bindParam(2, $_POST["editname"]);
    $stmt->execute();
  
    echo '<script language="javascript">';
    echo 'toastr.success("เปลี่ยนชื่อหน่วยงานเรียบร้อย")';
    echo '</script>';
  }else{
    echo '<script language="javascript">';
    echo 'toastr.warning("ชื่อนี้ถูกใช้งานอยู่")';
    echo '</script>';
  }
} else if (isset($_POST['delete'])) {
  if($_POST['state_id'] == 0){
    $stmt = $conn->query("SELECT * FROM itoss_form WHERE Agency_id = " . $_POST['delete'] . "");
    $del = $stmt->fetch();
  
    if (!empty($del)) {
      echo '<script language="javascript">';
      echo 'toastr.warning("ไม่สามารถปิดได้ หน่วยงานนี้ยังใช้งานอยู่")';
      echo '</script>';
    } else {
      $stmt = $conn->prepare("UPDATE itoss_agency SET state_id = ? where Agency_id = ?");
      $stmt->bindParam(1, $_POST["state_id"]);
      $stmt->bindParam(2, $_POST["delete"]);
  
      $stmt->execute();
  
      echo '<script language="javascript">';
      echo 'toastr.success("ปิดหน่วยงานเรียบร้อย")';
      echo '</script>';
    }
  }else{
    $stmt = $conn->prepare("UPDATE itoss_agency SET state_id = ? where Agency_id = ?");
    $stmt->bindParam(1, $_POST["state_id"]);
    $stmt->bindParam(2, $_POST["delete"]);
    $stmt->execute();
    echo '<script language="javascript">';
    echo 'toastr.success("เปิดหน่วยงานเรียบร้อย")';
    echo '</script>';
  }
  
}
?>
<main>
  <div class="row justify-content-center mt-5 ">
    <div class="col col-sm-3 col-xl-3 d-block mx-auto ">
      <p class="text-dark text-center fhead fw-bold">หน่วยงาน</p>
    </div>
  </div>
  <div class="row">
    
    <form method="post">
      <div class="modal fade" id="create-dp" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-body">
              <p class="ftitle fw-bold text-center">เพิ่มหน่วยงาน</p>
              <div class="col-xl-10 mx-auto">
                <p class="ftitle fw-bold mb-1">ชื่อหน่วยงาน</p>
                <input type="text" class="data form-control ftitle" name="Agency_Name" placeholder="กรอกข้อมูลหน่วยงาน">
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
  <div class="overflow-x-auto col-sm-8 col-xl-6 mx-auto">
  <div class="col mb-3">
      <button type="button" class="btn btn-primary d-block me-xl-auto" data-bs-toggle="modal" data-bs-target="#create-dp">เพิ่มหน่วยงาน</button>
    </div>
    <!--ตาราง-->
    <table class="table table-light table-bordered">
      <thead>
        <tr class="d-flex text-center fsub">
          <th class="col-6 col-sm-7">หน่วยงาน</th>
          <th class="col-2 col-sm-3">สถานะ</th>
          <th class="col-4 col-sm-2"></th>
        </tr>
      </thead>
      <tbody>
        <!--สถานะ:รออนุมัติ-->
        <?php
        $stmt = $conn->query("SELECT * FROM itoss_agency INNER JOIN state ON itoss_agency.state_id = state.id WHERE  NOT itoss_agency.Agency_id = 0 ;");
        while ($row = $stmt->fetch()) { ?>
          <tr class="d-flex text-center fsub">
            <td class="col-6 col-sm-7" id="date"><?= $row['Agency_Name'] ?></td>
            <?php if ($row['state_id'] == 1) { ?>
              <td class="col-2 col-sm-3" id="sector">เปิด</td>
            <?php } else if ($row['state_id'] == 0) { ?>
              <td class="col-2 col-sm-3" id="sector" style="background: rgba(234, 67, 53, 0.5);">ปิด</td>
            <?php }
            ?>
            <td class="col-2 col-sm-1" id="user"><img data-bs-toggle="modal" data-bs-target="#edit-name<?= $row['Agency_id'] ?>" src="./asset/icon/Edit.svg" alt=""></td>
            <form method="post">
              <div class="modal fade" id="edit-name<?= $row['Agency_id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-body">
                      <p class="ftitle fw-bold text-center">แก้ไขชื่อของหน่วยงาน</p>
                      <div class="col-xl-10 mx-auto">
                        <input type="text" class="data form-control ftitle" name="Agency_Name" value="<?= $row['Agency_Name'] ?>">
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="submit" name="editname" value="<?= $row['Agency_id'] ?>" class="btn btn-primary mx-auto">บันทึก</button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
            <td class="col-2 col-sm-1" id="user"><img data-bs-toggle="modal" data-bs-target="#edit-status<?= $row['Agency_id'] ?>" src="./asset/icon/Setting.svg" alt=""></td>
            <form method="post">
              <div class="modal fade" id="edit-status<?= $row['Agency_id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                  <div class="modal-body">
                          <p class="ftitle fw-bold text-center">เปลี่ยนสถานะของหน่วยงาน</p>
                          <div class="col-xl-10 mx-auto">
                            <div class="row">
                              <div class="col-6 col-xl form-check">
                                <input class="form-check-input mx-auto me-2 open" type="radio" name="state_id" value="1" id="status<?= $row['Agency_id'] ?>1">
                                <label class="form-check-label ftitle" for="flexRadioDefault1">
                                  เปิด
                                </label>
                              </div>
                              <div class="col-6 col-xl form-check">
                                <input class="form-check-input me-2 close" type="radio" name="state_id" id="status<?= $row['Agency_id'] ?>2" value="0">
                                <label class="form-check-label ftitle" for="flexRadioDefault2">
                                  ปิด
                                </label>
                              </div>
                            </div>
                          </div>
                        </div>
                    <div class="modal-footer justify-content-around">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                      <button type="submit" name="delete" value="<?= $row['Agency_id'] ?>" class="btn btn-primary">ยืนยัน</button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </tr>
          <script>
            if (<?= $row['state_id'] ?> == 1) {
              document.getElementById('status<?= $row['Agency_id'] ?>1').checked = true
            } else if (<?= $row['state_id'] ?> == 0) {
              document.getElementById('status<?= $row['Agency_id'] ?>2').checked = true;
            }
          </script>
        <?php
        }
        ?>
      </tbody>
    </table>
  </div>
</main>
</body>

</html>