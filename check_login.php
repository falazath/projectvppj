<?php
include("connect.php");
session_start();
isset($_POST['User_Username']) ? $user = $_POST['User_Username'] : $user = NULL;
isset($_POST['User_Password']) ? $pass = $_POST['User_Password'] : $pass = NULL;
if ((isset($_POST['sign-in']))) {
  include("connect.php");
  $stmt = $conn->prepare("SELECT * FROM itoss_user WHERE User_Username LIKE ? AND User_Password LIKE ?");
  $stmt->bindParam(1, $user);
  $stmt->bindParam(2, $pass);
  $stmt->execute();
  if ($row = $stmt->fetch()) {
    session_regenerate_id();
    $_SESSION['loggedin'] = TRUE;
    $_SESSION['name'] = $row['User_Name'];
    $User_id = $row['User_id'];
    $_SESSION['id'] = $row['User_id'];
    $_SESSION['Status_id'] = $row['Status_id'];
    $stmt1 = $conn->query("SELECT * FROM itoss_sign where User_id = '$User_id'");
    $row1 = $stmt1->fetch();
    if($_SESSION['Status_id'] == 1){
      if($row1['Sign_image'] == NULL){
        header('location: sent.php');
      }
      else{
        $_SESSION['Sign_image'] = $row1['Sign_image'];
        header('location: indexAdmin.php');
      }
    }
    else if($_SESSION['Status_id'] == 2){
      if($row1['Sign_image'] == NULL){
        header('location: sent.php');
      }
      else{
        $_SESSION['Sign_image'] = $row1['Sign_image'];
        header('location: indexUser.php');
      }
    }
  } else {
    echo '<script type="text/javascript">';
    echo 'window.location.href = "login.php";';
    echo "alert('ชื่อผู้ใช้งาน หรือรหัสผ่านไม่ถูกต้อง');";
    echo '</script>';
  }
}
$conn = null;
?>