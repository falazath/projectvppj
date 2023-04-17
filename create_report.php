<?php
session_start();
$_SESSION['id'] = 2;
include('connect.php');
include('header.html');
include('navbar.html');

$sql = $conn->prepare("SELECT * FROM itoss_form WHERE  itoss_form.Form_id = ".$_GET['pid']." ");
$sql->execute();
$data = $sql->fetch();
?>
    <main>
        <div class="row justify-content-center mt-5 ">
            <div class="col col-sm-3 col-xl-3 d-block mx-auto ">
                <p class="text-dark text-center fhead fw-bold">คำขอปฏิบัติงาน</p>
            </div>
        </div>

        <form action="" method="post">
            <div class="row mb-0 mb-xl-3 mb-xl-0 d-none" id="editBox">
                <div class="col-11 col-xl-12 mb-3">
                    <p class="ftitle fw-bold mb-1 d-inline"></p>
                    <p class="ftitle fw-bold d-inline">พี่หน่อง</p>
                    <div class="form-control text-light" name="editDetail" id="Detail" cols="30" rows="10">
                        <p>-ติดตั้งกล้องวงจรปิดหน้างาน 5 ตัว</p>
                        <p>-ติดตั้งกล้องวงจรปิดสำนักงาน 1 ตัว</p>
                        <p>-ติดตั้ง firewall และ set ระบบอินเตอร์เน็ต</p> 
                    </div>
                </div>
                <hr>
            </div>

            <div class="row justify-content-start mb-0 mb-xl-3" id="dsk">
                <div class="col-10 col-xl-4 mb-3 mb-xl-0">
                    <p class="ftitle fw-bold mb-1" id="demo">ชื่อผู้ติดต่อ</p>
                    <input type="text" class="data form-control ftitle" name="contact" id="contact" value="<?=$data['Form_Name']?>"
                        disabled>
                </div>
                <div class="col-10 col-xl-4 mb-3 mb-xl-0">
                    <p class="ftitle fw-bold mb-1">หน่วยงาน</p>
                    <input type="text" class="data form-control ftitle" name="sector"
                        value="Origin Plug&Play Srinakarin" disabled>
                </div>
                <div class="col-10 col-xl-4 mb-3 mb-xl-0">
                    <p class="ftitle fw-bold mb-1">เบอร์โทรศัพท์</p>
                    <input type="text" class="data form-control ftitle" name="tel" value="-" disabled>
                </div>
            </div>
            <div class="row mb-xl-3">
                <div class="col-10 col-xl-4 mb-3 mb-xl-0">
                    <p class="ftitle fw-bold mb-1">ประเภทงาน</p>
                    <input type="text" class="data form-control ftitle" name="cate-work" value="ติดตั้ง" disabled>
                </div>
                <div class="col-xl-6 mb-3 mb-xl-0">
                    <p class="ftitle fw-bold mb-1">หมวดงาน</p>
                    <div class="col-10 col-xl-12 align-items-center">
                        <div class="form-check form-check-inline">
                            <input class="data form-check-input my-0 me-1" type="checkbox" id="software" value="1"
                                checked disabled>
                            <label class="form-check-label" for="software">ซอฟต์แวร์</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="data form-check-input my-0 me-1" type="checkbox" id="hardware" value="2"
                                checked disabled>
                            <label class="form-check-label" for="software">ฮาร์ดแวร์</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="data form-check-input my-0" type="checkbox" id="other" onclick="otherCheck()"
                                value="3" disabled>
                            <label class="form-check-label ms-1" for="inlineCheckbox3" id="lab-other">อื่นๆ</label>
                            <input type="text" class="form-control d-none ms-1" id="inp-other" name="other">
                        </div>
                        <div class="col">

                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-0 mb-xl-3 mb-xl-0">
                <div class="col-11 col-xl-12 mb-3">
                    <p class="ftitle fw-bold mb-1">รายละเอียดงาน</p>
                    <div class="data form-control text-light" name="detail" id="detail" cols="30" rows="10">
                    <?=$data['Form_Work']?>
                    </div>
                </div>
            </div>
            <div class="row mb-5">
                <div class="col-10 col-xl-3 mx-xl-auto mb-3">
                    <p class="ftitle fw-bold mb-1">เจ้าหน้าที่ผู้รับผิดชอบ</p>
                    <input type="text" class="ftitle form-control" id="name-user" value="คุณตั้ม" disabled>
                </div>
            </div>
<hr>
<div class="row justify-content-center mt-5 ">
    <div class="col col-sm-3 col-xl-3 d-block mx-auto ">
        <p class="text-dark text-center fhead fw-bold">รายงานการปฏิบัติงาน</p>
    </div>
</div>
<div class="row mb-0 mb-xl-3 mb-xl-0">
    <div class="col-11 col-xl-12 mb-3">
        <p class="ftitle fw-bold mb-1">รายละเอียดงาน</p>
        <textarea class="data form-control" name="detail" id="detail-report" cols="30" rows="10">                    </textarea>
    </div>
</div>
<div class="row mb-5 mb-xl-5">
    <div class="col-xl-4">
        <p class="ftilte fw-bold">เวลาเริ่มดำเนินงาน</p>
        <input class=" form-control" type="date" name="start-time">
    </div>
    <div class="col-xl-4">
        <p class="ftilte fw-bold">เวลาเสร็จสิ้นการดำเนินงาน</p>
        <input class=" form-control" type="date" name="end-time">
    </div>
    <div class="col-xl-3">
        <p class="ftilte fw-bold">สถานะ:</p>
        <div class="row">
            <div class="col-6 col-xl form-check">
                <input class="form-check-input mx-auto me-2" type="radio" name="status" id="status1">
                <label class="form-check-label ftitle" for="flexRadioDefault1">
                    ปิดงาน
                </label>
            </div>
            <div class="col-6 col-xl form-check">
                <input class="form-check-input me-2" type="radio" name="status" id="status2" checked>
                <label class="form-check-label ftitle" for="flexRadioDefault2">
                    ติดตามงาน
                </label>
            </div>
        </div>

    </div>
</div>

<div class="row">
    <div class="col-6 mx-auto">
        <p class="ftilte fw-bold text-center">ผู้ใช้บริการ</p>
    </div>
    </div>
    <div class="row mb-0 mb-xl-3">
        <div class="col-xl-6 mx-auto">
            <img src="./asset/signature.png" class="d-block mb-3 mx-auto mb-xl-3" width="300px" height="300px" alt="">
            <!--กดบันทึกลายเซ็นเสร็จให้ลายเซ็นขึ้นตรงนี้-->
            <button type="button" class="btn btn-primary d-block mx-auto mb-3" data-bs-toggle="modal" data-bs-target="#signatureBox"> ลายเซ็น </button>
        </div>
        <div class="modal fade" id="signatureBox" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <!--ใส่ลายเซ็น-->
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="create-date" class="btn btn-primary mx-auto">บันทึก</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center mb-5">
        <div class="col-3 col-xl-1 me-0 align-self-center">
            <p class="ftilte fw-bold text-end mb-0 mt-0">วันที่</p>
        </div>
        <div class="col-6 col-xl-3 ms-0">
            <input class="form-control ms-0" type="date" name="start-time">
        </div>
    </div>
    <div class="row justify-content-around mb-5 mt-xl-5">
                <div class="col-6 col-xl-6 ms-auto" id="homeCol">
                    <a class="col-xl-3 btn btn-secondary d-block ms-auto me-2 me-xl-5 ftitle" href="index.html"
                        id="home">กลับสู่หน้าหลัก</a>
                </div>
                <div class="col-6 col-xl-6 me-auto" id="saveCol">
                    <button class="btn btn-primary d-block me-auto ms-2 ms-xl-5" type="submit" name="create-report"
                        id="save">บันทึก</button>
                </div>
            </div>
        </form>
        
    </main>
    <script>
        function otherCheck() {
            var check = document.getElementById('other');
            if (check.checked == true) {
                document.getElementById('lab-other').classList.add('d-none');
                document.getElementById('inp-other').classList.remove('d-none');
            } else {
                document.getElementById('lab-other').classList.remove('d-none');
                document.getElementById('inp-other').classList.add('d-none');
            }
        }
        CKEDITOR.replace('detail-report');

        
    </script>
</body>

</html>