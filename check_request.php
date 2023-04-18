<?php
session_start();
$_SESSION['id'] = 2;
include('connect.php');
include('header.html');
include('navbar.html');

$sql = $conn->prepare("SELECT * FROM itoss_form");
$sql->execute();
$data = $sql->fetch();

?>
    <main>
        <div class="row justify-content-center mt-5 ">
            <div class="col col-sm-3 col-xl-3 d-block mx-auto ">
                <p class="text-dark text-center fhead fw-bold">คำขอปฏิบัติงาน</p>
            </div>
        </div>

        <form action="" method="get">
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
                    <div class="data form-control text-light" name="detail" id="showDetail" cols="30" rows="10">
                    <?=$data['Form_Work']?>
                    </div>
                    <textarea class="data form-control text-light d-none" name="detail" id="detail" cols="30" rows="10">
                    <?=$data['Form_Work']?>
                    </textarea>
                </div>
                
            </div>
            <div class="row mb-5">
                <div class="col-10 col-xl-3 mx-xl-auto mb-3">
                    <p class="ftitle fw-bold mb-1">เจ้าหน้าที่ผู้รับผิดชอบ</p>
                    <input type="text" class="ftitle form-control" id="name-user" value="คุณตั้ม" disabled>
                </div>
            </div>
            <div class="row justify-content-around mb-5 mt-xl-5">
                <div class="col-6 col-xl-6 ms-auto" id="homeCol">
                    <a class="col-xl-3 btn btn-secondary d-block ms-auto me-2 me-xl-5 ftitle" href="index.html"
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
    <script>
        function disableFalse() {
            var data = document.getElementsByClassName('data');
            var editbtn = document.getElementById('edit');
            var homebtn = document.getElementById('home');
            var savebtn = document.getElementById('save');

            for (var i = 0; i < data.length; i++) {
                data[i].disabled = false;
            }
            
            document.getElementById('showDetail').classList.add('d-none');
            document.getElementById('detail').classList.remove('d-none');
            /*var val = document.getElementById('detail');
            alert(val.textContent);
            var attribute = { };
             $.each($("#detail")[0].attributes, function(id, atr) {
                 attribute[atr.nodeName] = atr.nodeValue;
             });
             $("#detail").replaceWith(function () {
                 return $("<textarea />",
                     attribute).append($(this).contents());
             });*/

            editbtn.classList.add('d-none');
            homebtn.innerText = "ยกเลิก";
            savebtn.classList.remove('d-none');
            CKEDITOR.replace('detail');
        }

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
        var url_str = window.location.href;
        var url = new URL(url_str);
        var edit = url.searchParams.get("id");

        const status = 5; //ค่า status 
        const box = document.getElementById('editBox');
        const topic = box.getElementsByTagName('p');
        var str;
        if (status != null) {
            if(status == 2){
            box.classList.remove('d-none');
                topic[0].innerText = 'รายละเอียดที่ต้องการแก้ไข โดย ';
            }else if(status == 3){
            box.classList.remove('d-none');
                topic[0].innerText = 'สาเหตุที่ยกเลิก โดย ';
                document.getElementById('homeCol').classList.remove('ms-auto');
                document.getElementById('home').classList.add('mx-auto');
                document.getElementById('home').classList.remove('ms-auto','me-xl-5','me-2');
                document.getElementById('home').classList.add('btn-primary');
                document.getElementById('home').classList.remove('btn-secondary');
                
                document.getElementById('saveCol').classList.add('d-none');
            }else if(status == 4){   
                box.classList.remove('d-none');
                topic[0].innerText = 'สาเหตุที่ไม่อนุมัติ โดย';
                document.getElementById('homeCol').classList.remove('ms-auto');
                document.getElementById('home').classList.add('mx-auto');
                document.getElementById('home').classList.remove('ms-auto','me-xl-5','me-2');
                document.getElementById('home').classList.add('btn-primary');
                document.getElementById('home').classList.remove('btn-secondary');
                
                document.getElementById('saveCol').classList.add('d-none');

            }
        }else{
            
        }

    </script>
</body>

</html>