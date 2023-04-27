<!--navbar-->
<body>
<nav class="navbar">
    <div class="container-fluid">

        <button class="navbar-toggler position-fixed bg-white opacity-100" style="left: 0; top: 0; margin:2px" type="button" data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasNavbarLight" aria-controls="offcanvasNavbarLight">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbarLight"
            aria-labelledby="offcanvasNavbarLightLabel">
            <div class="offcanvas-header">
                <a href="indexUser.php"><img src="./asset/Logo/VP.svg" alt="logo"></a>
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
                        <a href="login.html"><img src="./asset/icon/Password.svg"
                                class=" d-block float-end me-3"></a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 mb-2 mt-5">
                        <a class="btn ftitle d-block text-start" id="index" href="index.php">รายการคำขอปฏิบัติงาน</a>
                    </div>
                    <div class="col-12">
                        <a class="btn ftitle d-block text-start" id="department" href="sector.php">หน่วยงาน</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    var url_str = window.location.href;
    const index = document.getElementById('index');
    const department = document.getElementById('department');
    if(url_str.search('index')){
        index.classList.add('btn-primary');
        index.classList.remove('btn-secondary');

        department.classList.add('btn-secondary');
        department.classList.remove('btn-primary');
    }else if(url_str.search('department')){
        index.classList.add('btn-secondary');
        index.classList.remove('btn-primary');

        department.classList.add('btn-primary');
        department.classList.remove('btn-secondary');
    }
</script>
<!--navbar-->
