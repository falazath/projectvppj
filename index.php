<?php
include('connect.php');
include('header.html');
include('navbar.html');

$filter = $conn->prepare("SELECT * FROM itoss_agency,itoss_user,itoss_form,")

?>
    <main>

        <div class="row justify-content-center">
            <div class="col col-sm-3 col-xl-3 d-block mx-auto">
                <p class="text-dark text-center fhead fw-bold">รายการคำขอปฏิบัติงาน</p>
            </div>
        </div>
        <!--Desktop-->
        <!--ตัวกรอง-->
        <form action="" method="post">
            <div class="row justify-content-start mb-3" id="dsk">
                <div class="col-2 col-sm-2 col-xl-2">
                    <p class="ftitle">หน่วยงาน</p>
                    <select class="form-select" id="filterSector">
                        <option selected value="all">ทั้งหมด</option>
                        <option value="1">KAVE TOWN ISLAND</option>
                        <option value="2">KAVE SEED KASET</option>
                        <option value="3">Atmoz Minburi</option>
                        <option value="4">Origin Plug&Play Srinakarin</option>
                        <option value="5">Blue Phahonyothin 35</option>
                        <option value="6">อื่นๆ</option>
                    </select>
                </div>
                <div class="col-4 col-sm-2 col-xl-2">
                    <p class="ftitle element">ชื่อพนักงาน</p>
                    <select class="form-select" id="filterEmp">
                        <option selected value="all">ทั้งหมด</option>
                        <option value="1">คุณเอ</option>
                        <option value="2">คุณชาช่า</option>
                        <option value="3">คุณตั้ม</option>
                    </select>
                </div>
                <div class="col-2 col-sm-2 col-xl-2">
                    <p class="ftitle">ประเภทงาน</p>
                    <select class="form-select" id="filterType">
                        <option selected value="all">ทั้งหมด</option>
                        <option value="1">ติดตั้ง</option>
                        <option value="2">ซ่อมบำรุง</option>
                        <option value="3">บินโดรน</option>
                        <option value="4">อื่นๆ</option>
                    </select>
                </div>
                <div class="col-2 col-sm-2 col-xl-2">
                    <p class="ftitle">วันที่เริ่มต้น</p>
                    <input type="date" placeholder="dd-mm-yyyy" min="1997-01-01" max="2030-12-31" value="<?=date('Y-m-d')?>" class=" form-control" name="start-date" id="">
                </div>
                <div class="col-2 col-sm-2 col-xl-2">
                    <p class="ftitle">วันที่สิ้นสุด</p>
                    <input type="date" class=" form-control" name="end-date" id="">
                </div>
                <div class="col-2 col-sm-2 col-xl-2">
                    <p class="ftitle">สถานะ</p>
                    <select class="form-select" id="filterStatus">
                        <option selected value="">ทั้งหมด</option>
                        <option value="1">รออนุมัติ</option>
                        <option value="2">แก้ไข</option>
                        <option value="3">ยกเลิก</option>
                        <option value="4">ไม่อนุมัติ</option>
                        <option value="5">อนุมัติ</option>
                        <option value="6">ติดตามงาน</option>
                        <option value="7">รอตรวจสอบ</option>
                        <option value="8">เสร็จสิ้น</option>
                    </select>
                </div>
                <div class="row justify-content-center mt-3">
                    <div class="col-3 my-auto">
                        <button type="submit" class="btn btn-primary d-block mx-auto px-5" name="search">ค้นหา</button>
                    </div>
                </div>
            </div>
        </form>

        <form action="" method="post"><!--ตัวกรองPhone-->
            <div class="row justify-content-start" id="phone">
                <div class="col col-sm-2 col-xl-2">
                    <button class="btn btn-light border border-2 fsub" id="filterBtn" type="button" data-bs-toggle="offcanvas" data-bs-target="#department" aria-controls="offcanvasBottom"><img src="./asset/icon/Filterph.svg" class="h-100 w-100 d-block mx-auto" alt=""></button>
                    <div class="offcanvas offcanvas-bottom" tabindex="-1" id="department" aria-labelledby="offcanvasBottomLabel">
                        <div class="offcanvas-body small">
                            <div class="row mb-3">
                                <div class="col-6">
                                    <p class="ftitle fw-bold my-auto">เลือกพนักงาน</p>
                                </div>
                                <div class="col-11">
                                    <select class="form-select" aria-label="Default select example">
                                        <option selected disabled>เลือกพนักงาน</option>
                                        <option value="1">คุณตั้ม</option>
                                        <option value="2">คุณเอ</option>
                                        <option value="3">คุณชาช่า</option>
                                        <option value="4">คุณหน่อง</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <p class="ftitle fw-bold my-auto ">เลือกหน่วยงาน</p>
                                </div>
                                <div class="col-11">
                                    <select class="form-select" aria-label="Default select example">
                                        <option selected disabled>เลือกหน่วยงาน</option>
                                        <option value="1">KAVE TOWN ISLAND</option>
                                        <option value="2">KAVE SEED KASET</option>
                                        <option value="3">Atmoz Minburi</option>
                                        <option value="2">Origin Plug&Play Srinakarin</option>
                                        <option value="3">Blue Phahonyothin 35</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <p class="ftitle fw-bold my-auto ">เลือกประเภทงาน</p>
                                </div>
                                <div class="col-11">
                                    <select class="form-select" name="category" required>
                                        <option value="" selected disabled>เลือกประเภทงาน</option>
                                        <option value="1">ติดตั้ง</option>
                                        <option value="2">ซ่อมบำรุง</option>
                                        <option value="3">บินโดรน</option>
                                        <option value="4">อื่นๆ</option>
                                    </select>
                                </div>

                            </div>
                            <div class="row mb-3">
                                <div class="col-6 col-sm-2 col-xl-2">
                                    <label for="start-date">วันที่เริ่มต้น</label>
                                    <input type="date" class=" form-control" id="start-date" value="วันที่เริ่มต้น">
                                </div>
                                <div class="col-6 col-sm-2 col-xl-2">
                                    <label for="end-date">วันที่สิ้นสุด</label>
                                    <input type="date" class=" form-control" id="end-date" value="วันที่สิ้นสุด">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <p class="ftitle fw-bold my-auto">สถานะ</p>
                                    <select class="form-select" aria-label="Default select example">
                                        <option selected disabled>เลือกสถานะ</option>
                                        <option value="1">รออนุมัติ</option>
                                        <option value="2">แก้ไข</option>
                                        <option value="3">ยกเลิก</option>
                                        <option value="4">ไม่อนุมัติ</option>
                                        <option value="5">อนุมัติ</option>
                                        <option value="6">ติดตามงาน</option>
                                        <option value="7">รอตรวจสอบ</option>
                                        <option value="8">เสร็จสิ้น</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="offcanvas-footer">
                            <button type="submit" class="btn btn-primary d-block mx-auto my-2" name="submit">ค้นหา</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="row"><!--ปุ่มสร้างคำขอ-->
            <div class="col mb-3">
                <button type="button" class="btn btn-primary d-block me-xl-auto" data-bs-toggle="modal" data-bs-target="#create-date">สร้างคำขอปฏิบัติงาน</button>
            </div>
            <form action="create.html" method="post">
                <div class="modal fade" id="create-date" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <p class="ftitle fw-bold text-center">วันที่สร้างคำขอปฏิบัติงาน</p>
                                <div class="col-xl-10 mx-auto">
                                    <input type="date" class="form-control">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" name="create-date" class="btn btn-primary mx-auto">บันทึก</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="overflow-x-auto"><!--ตาราง-->
            <p id="demo"></p>

            <table class="table table-light table-bordered" >
                <thead>
                    <tr class="d-flex text-center fsub">
                        <th class="col-3 col-sm-1">วันที่</th>
                        <th class="col-4 col-sm-2">หน่วยงาน</th>
                        <th class="col-4 col-sm-2">เจ้าหน้าที่</th>
                        <th class="col-4 col-sm-1">ประเภทงาน</th>
                        <th class="col-8 col-sm-4">รายละเอียดงาน</th>
                        <th class="col-3 col-sm-1">สถานะ</th>
                        <th class="col-2 col-sm-1"></th>
                    </tr>
                </thead>
                <tbody>
                    <!--สถานะ:รออนุมัติ-->
                    <tr class="d-flex text-center fsub">
                        <td class="col-3 col-sm-1 date" id="1">24/03/2023</td>
                        <td class="col-4 col-sm-2 sector" id="1">Origin Plug&Play Srinakarin</td>
                        <td class="col-4 col-sm-2 user" id="1">คุณตั้ม</td>
                        <td class="col-4 col-sm-1 category" id="1">ติดตั้ง</td>
                        <td class="col-8 col-sm-4 text-start">
                            -ติดตั้งกล้องวงจรปิดหน้างาน 5 ตัว
                            <br>
                            -ติดตั้งกล้องวงจรปิดสำนักงาน 1 ตัว
                            <br>
                            -ติดตั้ง firewall และ set ระบบอินเตอร์เน็ต
                        </td>
                        <td class="col-3 col-sm-1 status" id="1">รออนุมัติ</td>
                        <td class="col-2 col-sm-1">
                            <a href="#" onclick="create_report(1)"><img src="./asset/icon/Paper.svg" alt=""></a>
                        </td>
                    </tr>
                    <!--สถานะ:แก้ไข-->
                    <tr class="d-flex text-center fsub">
                        <td class="col-3 col-sm-1 date" id="2">24/03/2023</td>
                        <td class="col-4 col-sm-2 sector" id="2">Origin Plug&Play Srinakarin</td>
                        <td class="col-4 col-sm-2 user" id="2">คุณตั้ม</td>
                        <td class="col-4 col-sm-1 category" id="2">ติดตั้ง</td>
                        <td class="col-8 col-sm-4 text-start">
                            -ติดตั้งกล้องวงจรปิดหน้างาน 5 ตัว
                            <br>
                            -ติดตั้งกล้องวงจรปิดสำนักงาน 1 ตัว
                            <br>
                            -ติดตั้ง firewall และ set ระบบอินเตอร์เน็ต
                        </td>
                        <td class="col-3 col-sm-1 status" id="2">แก้ไข</td>
                        <td class="col-2 col-sm-1">
                            <a href="#" onclick="create_report(2)"><img src="./asset/icon/Paper.svg" alt=""></a>
                        </td>
                    </tr>
                    <!--สถานะ:อนุมัติ-->
                    <tr class="d-flex text-center fsub">
                        <td class="col-3 col-sm-1 date" id="5">24/03/2023</td>
                        <td class="col-4 col-sm-2 sector" id="5">Origin Plug&Play Srinakarin</td>
                        <td class="col-4 col-sm-2 user" id="5">คุณตั้ม</td>
                        <td class="col-4 col-sm-1 cate-work" id="5">ติดตั้ง</td>
                        <td class="col-8 col-sm-4 text-start">
                            -ติดตั้งกล้องวงจรปิดหน้างาน 5 ตัว
                            <br>
                            -ติดตั้งกล้องวงจรปิดสำนักงาน 1 ตัว
                            <br>
                            -ติดตั้ง firewall และ set ระบบอินเตอร์เน็ต
                        </td>
                        <td class="col-3 col-sm-1 status" id="5">อนุมัติ</td>
                        <td class="col-2 col-sm-1">
                            <a href="#" onclick="create_report(5)"><img src="./asset/icon/Paper.svg" alt=""></a>
                        </td>
                    </tr>
                    <!--สถานะ:รอตรวจสอบ-->
                    <tr class="d-flex text-center fsub">
                        <td class="col-3 col-sm-1 date" id="5">24/03/2023</td>
                        <td class="col-4 col-sm-2 sector" id="5">Origin Plug&Play Srinakarin</td>
                        <td class="col-4 col-sm-2 user" id="5">คุณตั้ม</td>
                        <td class="col-4 col-sm-1 category" id="5">ติดตั้ง</td>
                        <td class="col-8 col-sm-4 text-start">
                            -ติดตั้งกล้องวงจรปิดหน้างาน 5 ตัว
                            <br>
                            -ติดตั้งกล้องวงจรปิดสำนักงาน 1 ตัว
                            <br>
                            -ติดตั้ง firewall และ set ระบบอินเตอร์เน็ต
                        </td>
                        <td class="col-3 col-sm-1 status" id="7">ติดตามงาน</td>
                        <td class="col-2 col-sm-1">
                            <a href="#" onclick="create_report(6)"><img src="./asset/icon/Paper.svg" alt=""></a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </main>
    <script>
        var phone = document.getElementById("phone");
        var dsk = document.getElementById("dsk");
        if (screen.width <= 576) {
            dsk.classList.add("d-none");

        } else if (screen.width >= 720) {
            phone.classList.add("d-none");
        }

        $('#filterStatus').change(function() {
            alert('input');
            
            if (a == "4") {
                $('#Jobtype_orther_name').removeClass('d-none');
            } else {
                $('#Jobtype_orther_name').addClass('d-none');
            }
        });

        function create_report(status) {
            let id = 5;
            if (status < 5) {
                location.href = "check_request.php?pid=" + id;
            } else if (status == 5) {
                location.href = "create_report.php?pid=" + id;
            } else {
                location.href = "check_report.php?pid=" + id;
            }
        }
    </script>
</body>

</html>