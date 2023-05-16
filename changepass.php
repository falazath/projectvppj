<?php 
session_start();
include("header.html");
include("connect.php");
if (!isset($_SESSION['id'])) {
    header('location:index.php');
}
include($_SESSION['navbar']);
?>
    <div class="row justify-content-center">
        <div class="col-6 login align-self-center position-absolute top-50 start-50 translate-middle">
            <div class="row justify-content-center align-items-center g-2 my-4">
                <div class="col">
                    <p class="fhead fw-bold text-center">เปลี่ยนรหัสผ่าน</p>
                </div>
            </div>
            <?php
                $sql = $conn->prepare("SELECT * FROM itoss_user WHERE User_id = " . $_SESSION['id']);
                $sql->execute();
                $row = $sql->fetch();
                if (isset($_POST['save'])) {
                    if ($_POST['old_pwd'] == $row['User_Password']) {
                        if ($_POST['new_pwd'] == $row['User_Password']) {
                        echo '<script type="text/javascript">';
                        echo 'window.location.href = "changepass.php"; ';
                        echo "alert('กรุณาใส่รหัสผ่านใหม่');";
                        echo '</script>';
                        
                        
                        } else if (strlen($_POST['new_pwd']) < 6 || strlen($_POST['new_pwd']) > 20) {
                            echo '<script type="text/javascript">';
                            echo 'window.location.href = "changepass.php"; ';
                            echo "alert('กรุณาใส่รหัสอย่างน้อย 6 ตัว ไม่เกิน 20 ตัว');";
                            echo '</script>';
                            
                        } else if ($_POST['new_pwd'] == $_POST['confirm']) {
                            $stmt = $conn->prepare("UPDATE itoss_user SET itoss_user.User_Password = ? WHERE User_id LIKE ?;");
                            $stmt->bindParam(1, $_POST['new_pwd']);
                            $stmt->bindParam(2, $_SESSION['id']);
                            
                            if ($stmt->execute()) {
                                echo '<script type="text/javascript">';
                                echo 'window.location.href = "login.php"; ';
                                echo "alert('เปลี่่ยนรหัสผ่านสำเร็จ');";
                                echo '</script>';
                            }
                            
                        } else {
                            echo '<script type="text/javascript">';
                            echo 'window.location.href = "changepass.php"; ';
                            echo "alert('ยืนยันรหัสผ่านไม่ถูกต้อง');";
                            echo '</script>';
                            }
                    } else {
                        echo '<script type="text/javascript">';
                        echo 'window.location.href = "changepass.php"; ';
                        echo "alert('รหัสผ่านไม่ถูกต้อง');";
                        echo '</script>';
                    }
                }
            ?>
            <div class="row m-3 justify-content-around">
                <form action="" method="post">
                    <div class="col-xl-10 mx-auto mb-3">
                        <label for="pwd" class="form-label">รหัสผ่านเดิม</label>
                        <input type="password" class="form-control" name="old_pwd" id="pwd"
                            placeholder="กรอกรหัสผ่านใหม่..." required>
                    </div>
                    <div class="col-xl-10 mx-auto mb-3">
                        <label for="pwd" class="form-label">รหัสผ่านใหม่ (6-20 อักขระ)</label>
                        <input type="password" class="form-control" name="new_pwd" id="pwd"
                            placeholder="กรอกรหัสผ่านใหม่..." required>
                    </div>
                    <div class="col-xl-10 mx-auto mb-3">
                        <label for="confirm" class="form-label">ยืนยันรหัสผ่าน</label>
                        <input type="password" class="form-control" name="confirm" id="confirm"
                            placeholder="กรอกยืนยันรหัสผ่านใหม่..." required>
                    </div>
                    <div class="d-grid col-3 mx-auto mb-3 mt-4">
                        <input class="btn btn-primary" type="submit" name="save" value="บันทึก">
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>