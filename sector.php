<?php
session_start();
if (!isset($_SESSION['id'])) {
  header('location:index.php');
}else if($_SESSION['status']==1){
  header('location:indexAdmin.php');
}
include('header.html');
include('navbar.php');
include('connect.php');

$sql = $conn->prepare("SELECT * FROM itoss_agency WHERE state_id = 1 AND Agency_id != 0");
$sql->execute();
$data = $sql->fetchAll();
?>
<main>
  <div class="row justify-content-center mt-5 ">
    <div class="col col-sm-3 col-xl-3 d-block mx-auto ">
      <p class="text-dark text-center fhead fw-bold">หน่วยงาน</p>
    </div>
  </div>

  <div class="row">
    <div class="col-8 mx-auto">
      <table class="table table-bordered">
        <thead>
          <tr class="d-flex ftitle text-light text-center" style="background-color:#4B785E">
            <th class="col-12">ชื่อหน่วยงาน</th>
          </tr>
        </thead>
        <tbody class="bg-light text-center">
          <?php
          for ($i = 0; $i < count($data); $i++) {
          ?>
            <tr class="d-flex fsub">
              <td class="col-12"><?=$data[$i]['Agency_Name']?></td>
            </tr>
          <?php
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>

</main>


</body>

</html>