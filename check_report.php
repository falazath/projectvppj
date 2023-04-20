<?php
session_start();
$_SESSION['id'] = 2;
include('connect.php');
include('header.html');
include('navbar.html');

$sql = $conn->prepare("SELECT * FROM itoss_form WHERE  itoss_form.Form_id = " . $_GET['pid'] . " ");
$sql->execute();
$data = $sql->fetch();
$id = 5;
$detail = "<p>ติดตั้งกล้องวงจรปิดรอบโครงการ 5 ตัว</p><p>-ติดตั้งกล้องวงจรปิดสำนักงาน 1 ตัว</p><p>-ติดตั้ง firewall และ set ระบบอินเตอร์เน็ต</p>";
        $dateStart = '2023-03-24 8:00:00';
        $dateEnd = '2023-03-24 18:00:00';
        $status = 2;
        $followDate = '2023-03-25';
        $signUser = './asset/exSignature.png';
        $dateUser = '2023-03-24';
        $signClient = './asset/exSignature.png';
        $dateClient = '2023-03-24';
        $signManager = './asset/exSignature.png';
        $dateManager = '2023-03-27';
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
                <input type="text" class="form-control ftitle" name="contact" id="contact" value="<?= $data['Form_Name'] ?>" disabled>
            </div>
            <div class="col-10 col-xl-4 mb-3 mb-xl-0">
                <p class="ftitle fw-bold mb-1">หน่วยงาน</p>
                <input type="text" class="form-control ftitle" name="sector" value="Origin Plug&Play Srinakarin" disabled>
            </div>
            <div class="col-10 col-xl-4 mb-3 mb-xl-0">
                <p class="ftitle fw-bold mb-1">เบอร์โทรศัพท์</p>
                <input type="text" class="form-control ftitle" name="tel" value="-" disabled>
            </div>
        </div>
        <div class="row mb-xl-3">
            <div class="col-10 col-xl-4 mb-3 mb-xl-0">
                <p class="ftitle fw-bold mb-1">ประเภทงาน</p>
                <input type="text" class="form-control ftitle" name="cate-work" value="ติดตั้ง" disabled>
            </div>
            <div class="col-xl-6 mb-3 mb-xl-0">
                <p class="ftitle fw-bold mb-1">หมวดงาน</p>
                <div class="col-10 col-xl-12 align-items-center">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input my-0 me-1" type="checkbox" id="software" value="1" checked disabled>
                        <label class="form-check-label" for="software">ซอฟต์แวร์</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input my-0 me-1" type="checkbox" id="hardware" value="2" checked disabled>
                        <label class="form-check-label" for="software">ฮาร์ดแวร์</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input my-0" type="checkbox" id="other" onclick="otherCheck()" value="3" disabled>
                        <label class="form-check-label ms-1" for="inlineCheckbox3">อื่นๆ</label>
                        <input type="text" class="form-control d-none ms-1" name="other">
                    </div>
                    <div class="col">

                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-0 mb-xl-3 mb-xl-0">
            <div class="col-11 col-xl-12 mb-3">
                <p class="ftitle fw-bold mb-1">รายละเอียดงาน</p>
                <div class="form-control text-light" name="detail" id="showDetail" cols="30" rows="10">
                    <?= $data['Form_Work'] ?>
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

        <!--ส่วนรายงาน-->
        <div class="row justify-content-center mt-5 ">
            <div class="col col-sm-3 col-xl-3 d-block mx-auto ">
                <p class="text-dark text-center fhead fw-bold">รายงานการปฏิบัติงาน</p>
            </div>
        </div>
        <?php
        
        for ($i = 0; $i < 3; $i++) {
        ?>
            <div class="row mb-0 mb-xl-3 mb-xl-0">
                <div class="col-11 col-xl-12 mb-3">
                    <p class="ftitle fw-bold mb-1">รายละเอียดงาน</p>
                    <div class=" form-control text-light" name="" id="showDetail" cols="30" rows="10">
                        <?= $detail ?>
                    </div>
                    <textarea class="form-control text-light d-none" name="" id="detail" cols="30" rows="10">
                    <?= $detail ?>
                    </textarea>
                </div>
            </div>
            <div class="row mb-5 mb-xl-5">
                <div class="col-xl-4">
                    <p class="ftilte fw-bold">เวลาเริ่มดำเนินงาน</p>
                    <input class=" form-control" type="datetime" name="start-time" value="<?= date('d-m-Y H:i', strtotime($dateStart)) ?>" disabled>
                </div>
                <div class="col-xl-4">
                    <p class=" ftilte fw-bold">เวลาเสร็จสิ้นการดำเนินงาน</p>
                    <input class=" form-control" type="datetime" name="end-time" value="<?= date('d-m-Y H:i', strtotime($dateEnd)) ?>" disabled>
                </div>
                <div class="col-xl-3">
                    <p class="ftilte fw-bold">สถานะ:</p>
                    <div class="row">

                        <div class="col-6 col-xl-6 form-check">
                            <input class="finish form-check-input mx-auto me-2" type="radio" onclick="otherCheck()" name="status<?= $i ?>" id="finish" disabled>
                            <label class="form-check-label ftitle" for="finish">
                                ปิดงาน
                            </label>
                        </div>
                        <div class="col-6 col-xl-6 form-check">
                            <input class="follow form-check-input me-2" type="radio" onclick="otherCheck()" name="status<?= $i ?>" id="follow" disabled>
                            <label class="form-check-label ftitle" for="follow" id="lab-other">
                                ติดตามงาน
                            </label>
                        </div>
                        <div class="col-xl-12">
                            <input type="date" class="form-control d-none mt-xl-2" name="in" id="inp-other<?=$i?>" value="<?=$followDate?>" disabled>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="row">
                        <div class="col-6 mx-auto">
                            <p class="ftilte fw-bold text-center">เจ้าหน้าที่</p>
                        </div>
                    </div>
                    <div class="row mb-xl-5">
                        <div class="col-xl-6 mx-auto">
                            <img src="<?= $signUser ?>" class="d-block mb-3 mx-auto mb-xl-3 w-100 h-100">
                            <!--กดบันทึกลายเซ็นเสร็จให้ลายเซ็นขึ้นตรงนี้-->

                        </div>
                        <div class="modal fade" id="signatureBox1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <!--ใส่ลายเซ็น-->
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" name="create-date" onclick="" class="btn btn-primary mx-auto">บันทึก</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-3 col-xl-6 me-0 align-self-center">
                            <label class="ftilte fw-bold text-end mb-0 mt-0" for="start">วันที่</label>
                            <input class=" form-control ms-0  col-xl-1" type="date" name="start-time" id="start" value="<?= $dateUser ?>" disabled>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="row">
                        <div class="col-6 mx-auto">
                            <p class="ftilte fw-bold text-center">ผู้ใช้บริการ</p>
                        </div>
                    </div>
                    <div class="row mb-xl-5">
                        <div class="col-xl-6 mx-auto">
                            <img src="<?= $signClient ?>" class="d-block mb-3 mx-auto mb-xl-3 w-100 h-100" id="picSig">
                            <!--กดบันทึกลายเซ็นเสร็จให้ลายเซ็นขึ้นตรงนี้-->

                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-3 col-xl-6 me-0 align-self-center">
                            <label class="ftilte fw-bold text-end mb-0 mt-0" for="start">วันที่</label>
                            <input class=" form-control ms-0  col-xl-1" type="date" name="start-time" id="start" value="<?= $dateClient ?>" disabled>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="my-5">
        <?php
        }
        ?>
        <div class="row justify-content-around mb-5 mt-xl-5">
            <div class="col-6 col-xl-6 ms-auto" id="homeCol">
                <a class="col-xl-3 btn btn-secondary d-block ms-auto me-2 me-xl-5 ftitle" href="index.html" id="home">กลับสู่หน้าหลัก</a>
            </div>
            <div class="col-6 col-xl-6 me-auto" id="saveCol">
                <button class="btn btn-primary d-block me-auto ms-2 ms-xl-5 d-none" type="submit" name="save" id="save">บันทึก</button>
                <?php
                if ($status == 1) {
                    ?>
                    <button class="btn btn-primary d-block me-auto ms-2 ms-xl-5 ftitle" type="button" id="edit" onclick="disableFalse()">แก้ไข</button>
                <?php
                } else {
                    ?>
                    <a href="create_report.php?pid=<?=$id?>" class="btn btn-primary col-3 d-block me-auto ms-2 ms-xl-5 ftitle" type="button" id="continue" >ดำเนินงานต่อ</a>
                <?php
                }
                ?>

            </div>
        </div>

    </form>

</main>
<script>
    for (i = 0; i < document.getElementsByClassName('finish').length; i++) {
        if (<?= $status ?> == 1) {
            document.getElementsByClassName('finish')[i].checked = true
        } else {
            document.getElementsByClassName('follow')[i].checked = true;
            document.getElementById('inp-other'+i).classList.remove('d-none');

        }
    }

    function otherCheck() {
        var finish = document.getElementById('finish');
        var follow = document.getElementById('follow')
        if (follow.checked == true) {
            document.getElementById('inp-other').classList.remove('d-none');
        } else if (finish.checked == true) {
            document.getElementById('inp-other').classList.add('d-none');
        }
    }
    if (follow.checked == true) {
        document.getElementById('inp-other').classList.remove('d-none');
    } else if (finish.checked == true) {
        document.getElementById('inp-other').classList.add('d-none');
    }

    function disableFalse() {
        var data = document.getElementsByClassName('data');
        var editbtn = document.getElementById('edit');
        var homebtn = document.getElementById('home');
        var savebtn = document.getElementById('save');
        for (var i = 0; i < data.length; i++) {
            [i].disabled = false;
        }

        document.getElementById('showDetail').classList.add('d-none');
        document.getElementById('detail').classList.remove('d-none');
        document.getElementById('default').id = 'signatureBox';
        editbtn.classList.add('d-none');
        homebtn.innerText = "ยกเลิก";
        savebtn.classList.remove('d-none');
        CKEDITOR.replace('detail');
    }
    CKEDITOR.replace('detail-report');
</script>
</body>

</html>