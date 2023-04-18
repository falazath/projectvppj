<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link href="./dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="./dist/css/bootstrap.css" rel="stylesheet">
  <script src="./dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="./style.css">
</head>

<body>
  <?php
  include('connect.php');
  $sql = $conn->prepare("SELECT * FROM itoss_form");
  $sql->execute();
  $data = $sql->fetch();
  ?>
  <div class="row mb-0 mb-xl-3 mb-xl-0 d-none" id="editBox">
    <div class="col-11 col-xl-12 mb-3">
      <p class="ftitle fw-bold mb-1 d-inline"></p>
      <p class="ftitle fw-bold d-inline">พี่หน่อง</p>
      <div class="form-control text-light" name="editDetail" id="Detail" cols="30" rows="10">
      </div>
    </div>
    <hr>
  </div>

  <script>
    const status = <?= $data['Status_form_id'] ?>;
    if (status == 1) {
      document.getElementById("Detail").textContent = "<?= $data['Form_id'] ?>";
    } else if (status == 2) {
      document.getElementById("Detail").innerText = "<?= $data['Form_date_id'] ?>";
    } else if (status == 3) {
      document.getElementById("Detail").innerText = "<?= $data['Form_Name'] ?>";
    } else if (status == 4) {
      document.getElementById("Detail").innerText = "<?= $data['Agency_id'] ?>";
    } else if (status == 5) {
      alert(status);
      document.getElementById("Detail").innerHTML = "<?= $data['Form_Phone'] ?>";
    }
  </script>
</body>

</html>