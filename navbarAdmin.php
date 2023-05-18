<!--navbar-->
<body>
<nav class="navbar">
    <div class="container-fluid">
        <button class="navbar-toggler position-fixed bg-white opacity-100" style="left: 0; top: 0; margin:2px;z-index: 1;" type="button" data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasNavbarLight" aria-controls="offcanvasNavbarLight">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbarLight"
            aria-labelledby="offcanvasNavbarLightLabel">
            <div class="offcanvas-header">
                <a href="indexAdmin.php"><img src="./asset/Logo/VP.svg" alt="logo"></a>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div class="row">
                    <div class="col">
                        <p class="ftitle text-start">ยินดีต้อนรับ</p>
                    </div>
                    <div class="col-2">
                        <a href="logout.php" class="text-end"><img src="./asset/icon/Logout.svg"
                                class=" d-block float-end me-3" alt=""></a>
                    </div>
                </div>
                <div class="row">
                    <div class="col align-self-center">
                        <p class="fhead text-start my-auto">คุณ<?=$_SESSION['name']?></p>
                    </div>
                    <div class="col-2 align-self-center">
                        <a href="changepass.php"><img src="./asset/icon/Password.svg"
                                class=" d-block float-end me-3"></a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 mb-2 mt-5">
                        <a class="btn ftitle d-block text-start" id="index" href="indexAdmin.php">รายการคำขอปฏิบัติงาน</a>
                    </div>
                    <div class="col-12 mb-2">
                        <a class="btn ftitle d-block text-start" id="sector" href="department.php">จัดการหน่วยงาน</a>
                    </div>
                    <div class="col-12 mb-2">
                        <a class="btn ftitle d-block text-start" id="type" href="type.php">จัดการประเภทงาน</a>
                    </div>
                    <div class="col-12 mb-2">
                        <a class="btn ftitle d-block text-start" id="user" href="manageUser.php">จัดการบัญชีผู้ใช้</a>
                    </div>
                    <div class="col-12 mb-2">
                        <a class="btn ftitle d-block text-start" id="excel" href="exportExcel.php">รายงาน</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    var url_str = window.location.href;
    const index = document.getElementById('index');
    const sector = document.getElementById('sector');
    const user = document.getElementById('user');
    const type = document.getElementById('type');
    const excel = document.getElementById('excel');

    if(url_str.search('index') != -1){
        index.classList.add('btn-primary');
        index.classList.remove('btn-secondary');

        sector.classList.add('btn-secondary');
        sector.classList.remove('btn-primary');
        
        user.classList.add('btn-secondary');
        user.classList.remove('btn-primary');

        type.classList.add('btn-secondary');
        type.classList.remove('btn-primary');

        excel.classList.add('btn-secondary');
        excel.classList.remove('btn-primary');
    }else if(url_str.search('department') != -1){
        index.classList.add('btn-secondary');
        index.classList.remove('btn-primary');

        sector.classList.add('btn-primary');
        sector.classList.remove('btn-secondary');
        
        user.classList.add('btn-secondary');
        user.classList.remove('btn-primary');

        type.classList.add('btn-secondary');
        type.classList.remove('btn-primary');

        excel.classList.add('btn-secondary');
        excel.classList.remove('btn-primary');
    }else if(url_str.search('manage') != -1){
        index.classList.add('btn-secondary');
        index.classList.remove('btn-primary');

        sector.classList.add('btn-secondary');
        sector.classList.remove('btn-primary');
        
        user.classList.add('btn-primary');
        user.classList.remove('btn-secondary');

        type.classList.add('btn-secondary');
        type.classList.remove('btn-primary');

        excel.classList.add('btn-secondary');
        excel.classList.remove('btn-primary');
    }else if(url_str.search('type') != -1){
        index.classList.add('btn-secondary');
        index.classList.remove('btn-primary');

        sector.classList.add('btn-secondary');
        sector.classList.remove('btn-primary');
        
        user.classList.add('btn-secondary');
        user.classList.remove('btn-primary');

        type.classList.add('btn-primary');
        type.classList.remove('btn-secondary');

        excel.classList.add('btn-secondary');
        excel.classList.remove('btn-primary');
    }
    else if(url_str.search('export') != -1){
        index.classList.add('btn-secondary');
        index.classList.remove('btn-primary');

        sector.classList.add('btn-secondary');
        sector.classList.remove('btn-primary');
        
        user.classList.add('btn-secondary');
        user.classList.remove('btn-primary');

        type.classList.add('btn-secondary');
        type.classList.remove('btn-primary');

        excel.classList.add('btn-primary');
        excel.classList.remove('btn-secondary');
    }else{
        index.classList.add('btn-secondary');
        index.classList.remove('btn-primary');

        sector.classList.add('btn-secondary');
        sector.classList.remove('btn-primary');
        
        user.classList.add('btn-secondary');
        user.classList.remove('btn-primary');

        type.classList.add('btn-secondary');
        type.classList.remove('btn-primary');

        excel.classList.add('btn-secondary');
        excel.classList.remove('btn-primary');
    }
</script>
<?php
date_default_timezone_set("Asia/Bangkok");
?>
<!--navbar-->
