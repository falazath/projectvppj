<!-- use script  -->
<?php
session_start();
if (!isset($_SESSION['id'])) {
    header('location:login.php');
}
include("header.html");
include("connect.php");
include($_SESSION['navbar']);
?>

<!-- Body -->

        <!-- ปุ่มกด export Excel -->
        <div style="width:100%; height:50px;  margin:auto; margin-top:3vh; text-align:center; ">
            <input type="button" value="download.excel"
                style="font-size:1vw; float:right; margin-right:2%; margin-top:5px; background-color: #4B785E;color:#ffffff;width: 10%;height:5vh; border: 0px; border-radius: 15px; "
                id="exportBtn1">
        </div>

        <!-- ข้อมูลใน ไฟล์ excel -->
        <div class="overflow-x-auto">
        <!--ตาราง-->
        <table class="table table-bordered border-dark text-center" id="tableExport" style="  margin-top:-1px; width:100%; ">
                <tr>
                    <th class="table-primary" colspan="6">คำขอปฏิบัติงาน</th>
                    <th class="table-success" colspan="8">รายงานการปฏิบัติงาน</th>
                </tr>
                <tr rowspan="2">
                    <th class="table-primary" rowspan="2">วันที่</th>
                    <th class="table-primary" rowspan="2">หน่วยงาน</th>
                    <th class="table-primary" rowspan="2">เจ้าหน้าที่</th>
                    <th class="table-primary" rowspan="2">ประเภทงาน</th>
                    <th class="table-primary" rowspan="2">รายละเอียดงาน</th>
                    <th class="table-primary" rowspan="2">ผู้มอบหมายงาน</th>
                    <th class="table-success" rowspan="2">รายละเอียดการปฏิบัติงาน</th>
                    <th class="table-success" colspan="2">เวลาดำเนินงาน</th>
                    <th class="table-success" colspan="2">สถานะ</th>
                    <th class="table-success" rowspan="2">เจ้าหน้าที่</th>
                    <th class="table-success" rowspan="2">ชื่อผู้ใช้บริการ</th>
                    <th class="table-success" rowspan="2">ตรวจสอบ</th>
                </tr>
                <tr >
                    <th class="table-success" >เริ่ม</th>
                    <th class="table-success" >เสร็จสิ้น</th>
                    <th class="table-success" >ปิดงาน</th>
                    <th class="table-success" >ติดตามงาน</th>
                </tr>
                <?php $stmt = $conn->query("SELECT itoss_form.Form_id,itoss_form.Form_date,itoss_agency.Agency_Name,itoss_user.User_Name,itoss_form.Form_Work,itoss_report.Report_Detail,itoss_report.Report_Start_Date,itoss_report.Report_Stop_Date,itoss_report.Report_Status,itoss_form.Form_Name,itoss_form.assign_id FROM itoss_form 
                    INNER JOIN itoss_agency ON itoss_form.Agency_id = itoss_agency.Agency_id 
                    INNER JOIN itoss_user ON itoss_form.User_id = itoss_user.User_id
                    INNER JOIN itoss_report ON itoss_form.Form_id = itoss_report.Form_id ORDER BY itoss_form.Form_id ASC");
                    while($row = $stmt->fetch()){?>
                <tr>
                    <td><?= $row['Form_date']?></td>
                    <td><?= $row['Agency_Name']?></td>
                    <td>คุณ<?= $row['User_Name']?></td>
                    <td> 
                    <?php $sql = $conn->query("SELECT * FROM itoss_job,itoss_form,itoss_jobtype WHERE itoss_job.Form_id = itoss_form.Form_id AND itoss_form.Form_id = ".$row['Form_id']." AND itoss_job.Jobtype_id = itoss_jobtype.Jobtype_id");
                        $job = $sql->fetchAll();
                        for($i=0;$i<count($job);$i++){
                            if($i != 0){
                                echo '/';
                            }
                            if ($job[$i]['Jobtype_id'] == 0) {
                                echo $job[$i]['name_other'];
                            } else {
                                echo $job[$i]['Jobtype_name'];
                            }
                            
                        }
                    ?></td>
                    <td><?= $row['Form_Work']?></td>
                    <?php $stmt1 = $conn->query("SELECT itoss_user.User_Name,itoss_form.assign_id,itoss_form.Form_id FROM itoss_form 
                    INNER JOIN itoss_user ON itoss_form.assign_id = itoss_user.User_id where itoss_form.Form_id = ".$row['Form_id']."");
                    $row1 = $stmt1->fetch(); ?>
                    <td>คุณ<?= $row1['User_Name']?></td>
                    <td><?= $row['Report_Detail']?></td>
                    <td><?= $row['Report_Start_Date']?></td>
                    <td><?= $row['Report_Stop_Date']?></td>
                    <?php if($row['Report_Status'] == 7){?>
                    <td>ปิดงาน</td>
                    <td></td>
                    <?php }else if($row['Report_Status'] == 6){?>
                    <td></td>
                    <td>ติดตามงาน</td>
                    <?php } ?>
                    <td>คุณ<?= $row['User_Name']?></td>
                    <td>คุณ<?= $row['Form_Name']?></td>
                    <td>คุณ<?= $row1['User_Name']?></td>
                </tr>
                <?php }?>

</table>
    </div>

<!-- script -->
<script type="text/javascript"></script>
<script>
$("#exportBtn1").on('click', function() {

    TableToExcel.convert(document.getElementById("tableExport"), {
        name: "ข้อมูลการขอปฏิบัติหน้าที่นอกสถานที่.xlsx",
        sheet: {
            name: "Sheet1"
        }
    });
    setTimeout(() => {
        window.close();
    }, 1000);

});
</script>