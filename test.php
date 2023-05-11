<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <?php
  session_start();
  include('connect.php');

  $sql = $conn->query("SELECT * FROM itoss_form,itoss_agency,itoss_status_form,itoss_user
  WHERE itoss_form.Agency_id = itoss_agency.Agency_id
  AND itoss_form.Status_form_id = itoss_status_form.Status_form_id AND itoss_form.User_id = itoss_user.User_id
  AND itoss_agency.state_id = 1 AND itoss_user.state_id = 1 ORDER BY itoss_form.Form_date DESC,itoss_form.Form_id DESC;");
  $data = $sql->fetchAll();
  $sql = $conn->query("SELECT * FROM itoss_job,itoss_jobtype WHERE itoss_job.Jobtype_id = itoss_jobtype.Jobtype_id");
  $job = $sql->fetchAll();
  if (isset($_POST['submit'])) {
    $all = array();
    $arrAgency = array();
    $arrUser = array();
    $arrType = array();
    $arrDate = array();
    $arrStatus = array();
    for ($i = 0; $i < count($data); $i++) {
      array_push($all, $data[$i]['Form_id']);
      
      if ($_POST['agency'] == $data[$i]['Agency_id']) {
        array_push($arrAgency, $data[$i]['Form_id']);
      }
      if ($_POST['user'] == $data[$i]['User_id']) {
        array_push($arrUser, $data[$i]['Form_id']);
      }
      if (strtotime($_POST['start-date']) <= strtotime($data[$i]['Form_date']) && strtotime($_POST['end-date']) >= strtotime($data[$i]['Form_date'])) {    
        array_push($arrDate, $data[$i]['Form_id']);
      }
      if ($_POST['status'] == $data[$i]['Status_form_id']) {
        array_push($arrStatus, $data[$i]['Form_id']);
      }
    }
    for ($i = 0; $i < count($job); $i++) {
      if ($_POST['type'] == $job[$i]['Jobtype_id']) {
        array_push($arrType, $job[$i]['Form_id']);
      }
    }
    print_r($arrAgency);
    print_r($arrUser);
    print_r($arrDate);
    print_r($arrStatus);
    print_r($arrType);

    $row=array_intersect($all,$arrAgency,$arrUser,$arrType,$arrDate,$arrStatus);
    // $row=array_intersect($all,$arrAgency,$arrUser);
    print_r($row);
  }
  $_SESSION['data'] = array(['agency'], ['user'], ['type'], ['date'], ['status'], ['id'], ['status_id'], ['status_name'])
  ?>
  <form action="" method="post">
    <input type="text" name="agency" value="all">
    <input type="text" name="user" value="all">
    <input type="text" name="type" value="all">
    <input type="date" name="start-date" value="">
    <input type="date" name="end-date" value="">
    <input type="text" name="status" value="all">
    <button type="submit" name="submit">submit</button>
  </form>
  <script>
    document.getElementsByTagName
  </script>
</body>

</html>