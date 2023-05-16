<?php
session_start();
include('header.html');
?>
    <div class="row justify-content-center">
        <div class="col-10 col-xl-6 login align-self-center position-absolute top-50 start-50 translate-middle">
            <img class="m-5 d-block mx-auto w-75 h-auto" src="./asset/Logo/VP.svg" alt="">
            <div class="row justify-content-center">
              <div class="col col-sm-4 col-xl-8 d-block mx-auto">
                  <p class="text-dark text-center fhead fw-bold">It Onsite Service </p>
              </div>
            </div>
            <form action="check_login.php" method="post">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="User_Username" id="floatingInput" placeholder="name@example.com">
                <label class="ftitle" for="floatingInput">Username</label>
              </div>
              <div class="form-floating">
                <input type="password" class="form-control" name="User_Password" id="floatingPassword" placeholder="Password">
                <label class="ftitle" for="floatingPassword">Password</label>
              </div>
              <button class="btn btn-success d-block mx-auto my-5" type="submit" name="sign-in">เข้าสู่ระบบ</button>
            </form>
        </div>
    </div>
</body>
</html>