<?php include ("connect.php");session_start();
    $stmt = $conn->prepare("SELECT * FROM itoss_form 
    INNER JOIN itoss_jobtype ON itoss_form.Jobtype_id = itoss_jobtype.Jobtype_id 
    INNER JOIN itoss_user ON itoss_form.User_id = itoss_user.User_id 
    INNER JOIN itoss_status_form ON itoss_form.Status_form_id = itoss_status_form.Status_form_id
    INNER JOIN itoss_agency ON itoss_form.Agency_id = itoss_agency.Agency_id 
    WHERE Form_id = ?");
    $stmt->bindParam(1, $_GET["Form_id"]);
    $stmt->execute(); // 3. เริ่มค้นหา
    $row = $stmt->fetch();

    $Form_id = $_GET["Form_id"];
    $stmt1 = $conn->query("SELECT * FROM itoss_jobtype_orther where Form_id = '$Form_id'");
    $row1 = $stmt1->fetch();
    
    $stmt2 = $conn->query("SELECT * FROM itoss_task_orther where Form_id = '$Form_id'");
    $row2 = $stmt2->fetch();

    $stmt3 = $conn->query("SELECT * FROM itoss_text where Form_id = '$Form_id' ORDER BY Text_id DESC");
    $row3 = $stmt3->fetch();

    $stmt4 = $conn->query("SELECT * FROM itoss_sign INNER JOIN itoss_user ON itoss_sign.User_id = itoss_user.User_id where itoss_sign.User_id = 1");
    $row4 = $stmt4->fetch();

?>
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
    <script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>

<!-- jQuery library -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.3/dist/jquery.slim.min.js"></script>

</head>

<body>
    <main>
        <p id="show"></p>
        <div class="row justify-content-center mt-5 ">
            <div class="col col-sm-3 col-xl-3 d-block mx-auto ">
                <p class="text-dark text-center fhead fw-bold">คำขอปฏิบัติงาน</p>
            </div>
        </div>

        <div class="row">
            <div class="col">

            </div>
        </div>
        <form action="" method="post">
            <div class="row mb-0 mb-xl-3 mb-xl-0 d-none" id="editBox">
                <div class="col-11 col-xl-12 mb-3">
                    <p class="ftitle fw-bold mb-1">รายละเอียดการแก้ไขงาน</p>
                    <div class="form-control text-light" id="Detail" cols="30" rows="10">
                        
                    </div>
                </div>
                <hr>
            </div>
            
            <div class="row justify-content-start mb-0 mb-xl-3" id="dsk">
                <div class="col-10 col-xl-4 mb-3 mb-xl-0">
                    <p class="ftitle fw-bold mb-1" id="demo">ชื่อผู้ติดต่อ</p>
                    <input type="hidden" name="Form_date" value="<?=$row['Form_date']?>">
                    <input type="text" class="data form-control ftitle" name="Form_Name" id="contact" value="<?=$row["Form_Name"]?>"  disabled>
                    <input type="hidden" name="Status_form_id" value="1">
                </div>
                <div class="col-10 col-xl-4 mb-3 mb-xl-0">
                    <p class="ftitle fw-bold mb-1">หน่วยงาน</p>
                        <select class="form-select data form-control ftitle" id="Agency_id" name="Agency_id" disabled>
                        <option selected value="<?=$row["Agency_id"]?>"><?=$row["Agency_Name"]?></option>
                        <option value="1">KAVE TOWN ISLAND</option>
                        <option value="2">KAVE SEED KASET</option>
                        <option value="3">Atmoz Minburi</option>
                        <option value="4">Origin Plug&Play Srinakarin</option>
                        <option value="5">Blue Phahonyothin 35</option>
                        </select>
                </div>
                <div class="col-10 col-xl-4 mb-3 mb-xl-0">
                    <p class="ftitle fw-bold mb-1">เบอร์โทรศัพท์</p>
                    <input type="text" class="data form-control ftitle" name="Form_Phone" value="<?=$row["Form_Phone"]?>"  disabled>
                </div>
            </div>
            <div class="row mb-xl-3">
                <div class="col-10 col-xl-4 mb-3 mb-xl-0">
                    <p class="ftitle fw-bold">ประเภทงาน</p>
                        <select class="form-select data form-control ftitle" id="Jobtype_id" name="Jobtype_id" disabled>
                        <option selected value="<?=$row["Jobtype_id"]?>"><?=$row["Jobtype_name"]?></option>
                        <option value="1">ติดตั้ง</option>
                        <option value="2">ซ่อมบำรุง</option>
                        <option value="3">บินโดรน</option>
                        <option value="4">อื่นๆ</option>
                        </select>
                        &nbsp;
                    <input type="text" class="d-none data form-control ftitle" name="Jobtype_orther_name" id="Jobtype_orther_name" value="<?=$row1['Jobtype_orther_name']?>"  disabled>
                </div>
                <div class="col-xl-6 mb-3 mb-xl-0">
                    <p class="ftitle fw-bold mb-1">หมวดงาน</p>
                    <div class="col-10 col-xl-12 align-items-center">
                        <div class="form-check form-check-inline">
                            <input class="data form-check-input my-3 me-4" type="checkbox" id="software" value="1"  disabled>
                            <label class="form-check-label my-3 me-4" for="software">ซอฟต์แวร์</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="data form-check-input my-3 me-4" type="checkbox" id="hardware" value="2"  checked disabled>
                            <label class="form-check-label my-3 me-4" for="hardware">ฮาร์ดแวร์</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="data form-check-input my-3 me-4" type="checkbox" id="other" onclick="otherCheck()" value="3" checked disabled>
                            <label class="form-check-label my-3 me-4" for="inlineCheckbox3" id="lab-other">อื่นๆ</label>
                          </div>
                          <div class="col-10 col-xl-8 mb-3">
                            <input class="d-none form-control" type="text" name="Task_orther_name" id="Task_orther_name" value="<?=$row2['Task_orther_name']?>">
                          </div>
                            <?php
                            //$stmt = $conn->query("SELECT * FROM itoss_task_format where Form_id = '$Form_id'");
                            //while($row1 = $stmt->fetch()){
                            //echo '
                            //    <input class="data form-check-input my-3 me-4" type="checkbox" id="software" value="'.$row1['Task_Format_id'].'"  checked disabled>
                            //    <label class="form-check-label my-3 me-4" for="software">'.$row1['Task_Format_name'].'</label>
                            //</div>';
                            //}?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-0 mb-xl-3 mb-xl-0">
                <div class="col-11 col-xl-12 mb-3">
                    <p class="ftitle fw-bold mb-1">รายละเอียดงาน</p>
                    <div class="data form-control text-light" name="Form_Work" id="show-detail" cols="30" rows="10">
                        <?=$row['Form_Work']?>
                    </div>
                    <textarea class="data form-control text-light d-none" name="Form_Work" id="detail" cols="30" rows="10">
                        <?=$row['Form_Work']?>
                    </textarea>
                </div>
            </div>
            <div class="row mb-5">
                <div class="col-10 col-xl-3 mx-xl-auto mb-3">
                    <p class="ftitle fw-bold mb-1 text-center">เจ้าหน้าที่ผู้รับผิดชอบ</p>
                    <input type="text" class="ftitle form-control text-center" id="name-user" name="User_Name" value="<?=$row['User_Name']?>" disabled>
                </div>
            </div>
            <div class="d-none" id="send-text">
                <div class="row">
                    <div class="col-6 mx-auto">
                        <p class="ftitle fw-bold mb-1 text-center"><?=$row4['User_Jop']?></p>
                    </div>
                </div>
                <div class="row mb-xl-5">
                    <div class="col-xl-6 mx-auto">
                    <a href="#" data-bs-target="#sendBox" data-bs-toggle="modal"><img src="data:<?=$row4['Sign_image']?>" class="d-block mb-3 mx-auto mb-xl-3 w-50 h-100 text-center" alt=""></a>
                    <input type="text" class="ftitle form-control text-center" id="name-user" name="User_Name" value="<?=$row4['User_Name']?>" disabled>
                    </div>
                    <div class="modal fade" id="sendBox" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <img class="d-block w-250 h-300 text-center" src="data:<?=$row4['Sign_image']?>"><br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row justify-content-around mb-5 mt-xl-5">
                <div class="col-6 col-xl-6 ms-auto" id="homeCol">
                    <a class="col-xl-3 btn btn-secondary d-block ms-auto me-2 me-xl-5 ftitle" href="indexUser.php"
                        id="home">กลับสู่หน้าหลัก</a>
                </div>
                <div class="col-6 col-xl-6 me-auto" id="saveCol">
                    <button class="btn btn-primary d-block me-auto ms-2 ms-xl-5 d-none" type="submit" name="save"
                        id="save">บันทึก</button>
                    <button class="btn btn-primary d-block me-auto ms-2 ms-xl-5 ftitle" type="button" id="edit"
                        onclick="disableFalse()">แก้ไข</button>
                </div>
            </div>
        </form>
    </main>

    <?php
        if (isset($_POST['save']))
        {  
            $stmt = $conn->prepare("UPDATE itoss_form SET Form_date=?, Form_Name=?, Agency_id=?, Form_Phone=?, Jobtype_id=?, Form_Work=?, Status_form_id=?, User_id=? WHERE Form_id=?"); // เตรยีมคา สง่ั SQL ส าหรบัแกไ้ข
            $stmt->bindParam(1, $_SESSION['date']);
            $stmt->bindParam(2, $_POST["Form_Name"]);
            $stmt->bindParam(3, $_POST["Agency_id"]);
            $stmt->bindParam(4, $_POST["Form_Phone"]);
            $stmt->bindParam(5, $_POST["Jobtype_id"]);
            $stmt->bindParam(6, $_POST["Form_Work"]);
            $stmt->bindParam(7, $_POST["Status_form_id"]);
            $stmt->bindParam(8, $_SESSION['id']);
            $stmt->bindParam(9, $Form_id);
            $stmt->execute();

            $stmt = $conn->prepare("UPDATE itoss_jobtype_orther SET Jobtype_orther_name=? WHERE Form_id=?"); // เตรยีมคา สง่ั SQL ส าหรบัแกไ้ข
            $stmt->bindParam(1, $_POST["Jobtype_orther_name"]);
            $stmt->bindParam(2, $Form_id);
            $stmt->execute();

            include ("message.php");
            
                echo '<script language="javascript">';
                echo 'alert("แก้ไขข้อมูลแล้ว"); location.href="requestUser.php?Form_id='.$Form_id.'"';
                echo '</script>';
                
        
                
        
        }
        $conn = null;
    ?>

    <script>
                $(document).ready(function(){
                    let a = $('#Jobtype_id').val();
                    if(a == "4"){
                        $('#Jobtype_orther_name').removeClass('d-none');
                        }
                    else{
                        $('#Jobtype_orther_name').addClass('d-none');
                    }
                });

        $(document).ready(function(){
            const status = <?php echo $row['Status_form_id']?>;
            if(status == 5){
                document.getElementById('send-text').classList.remove('d-none');
                document.getElementById('edit').remove();
                document.getElementById('save').classList.remove('d-none');
            }
        });

        function disableFalse(){
            var data = document.getElementsByClassName('data');
            var editbtn = document.getElementById('edit');
            var homebtn = document.getElementById('home');
            var savebtn = document.getElementById('save');
            for(var i=0;i<data.length;i++){
                data[i].disabled = false;
            }
            document.getElementById('show-detail').classList.add('d-none');
            document.getElementById('detail').classList.remove('d-none');

            editbtn.classList.add('d-none');
            homebtn.innerText = "ยกเลิก";
            savebtn.classList.remove('d-none');
            CKEDITOR.replace( 'detail' );
        }
    
        function otherCheck(){
            var check = document.getElementById('other');
            if(check.checked == true){
                document.getElementById('Task_orther_name').classList.remove('d-none');
            }else{
                document.getElementById('Task_orther_name').classList.add('d-none');
            }
        }
        const status = <?php echo $row3['Status_form_id']?>;
        const box = document.getElementById('editBox');
        const topic = box.getElementsByTagName('p');
        var str;
            if(status == 1){
                
                
            }else if(status == 2){
                box.classList.remove('d-none');
                topic[0].innerText = 'รายละเอียดที่ต้องการแก้ไข โดย ';
                document.getElementById('Detail').innerText="<?=$row3['Text_name']?>";
                
            }else if(status == 3){
                document.getElementById('homeCol').classList.remove('ms-auto');
                document.getElementById('home').classList.add('mx-auto');
                document.getElementById('home').classList.remove('ms-auto','me-xl-5','me-2');
                document.getElementById('home').classList.add('btn-primary');
                document.getElementById('home').classList.remove('btn-secondary');
                
                document.getElementById('saveCol').classList.add('d-none');
            }else if(status == 4){   
                box.classList.remove('d-none');
                topic[0].innerText = 'สาเหตุที่ไม่อนุมัติ โดย';
                document.getElementById('Detail').innerText="<?=$row3['Text_name']?>";
                document.getElementById('homeCol').classList.remove('ms-auto');
                document.getElementById('home').classList.add('mx-auto');
                document.getElementById('home').classList.remove('ms-auto','me-xl-5','me-2');
                document.getElementById('home').classList.add('btn-primary');
                document.getElementById('home').classList.remove('btn-secondary');
                
                document.getElementById('saveCol').classList.add('d-none');

            }
    </script>
</body>

</html>