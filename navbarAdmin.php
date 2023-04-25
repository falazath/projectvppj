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
                <a href="index.html"><img src="./asset/Logo/VP.svg" alt="logo"></a>
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
                    <div class="col-12 mb-2">
                        <a class="btn ftitle d-block text-start" id="sector" href="sector.php">จัดการหน่วยงาน</a>
                    </div>
                    <div class="col-12">
                        <a class="btn ftitle d-block text-start" id="user" href="manageUser.php">จัดการบัญชีผู้ใช้</a>
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

    if(url_str.search('index')){
        index.classList.add('btn-primary');
        index.classList.remove('btn-secondary');

        sector.classList.add('btn-secondary');
        sector.classList.remove('btn-primary');
        
        user.classList.add('btn-secondary');
        user.classList.remove('btn-primary');
    }else if(url_str.search('sector')){
        index.classList.add('btn-secondary');
        index.classList.remove('btn-primary');

        sector.classList.add('btn-primary');
        sector.classList.remove('btn-secondary');
        
        user.classList.add('btn-secondary');
        user.classList.remove('btn-primary');
    }else if(url_str.search('user')){
        index.classList.add('btn-secondary');
        index.classList.remove('btn-primary');

        sector.classList.add('btn-secondary');
        sector.classList.remove('btn-primary');

        user.classList.add('btn-primary');
        user.classList.remove('btn-secondary');
    }
</script>
<!--navbar-->