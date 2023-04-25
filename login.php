<?php
include('header.html');
?>
    <div class="row justify-content-center">
        <div class="col-6 login align-self-center position-absolute top-50 start-50 translate-middle">
            <img class="m-5 d-block mx-auto w-50 h-auto" src="./asset/Logo/VP.svg" alt="">
            <form action="check_login.php" method="post">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="User_Username" id="floatingInput" placeholder="name@example.com">
                <label for="floatingInput">Username</label>
              </div>
              <div class="form-floating">
                <input type="password" class="form-control" name="User_Password" id="floatingPassword" placeholder="Password">
                <label for="floatingPassword">Password</label>
              </div>
              <button class="btn btn-success d-block mx-auto my-5" type="submit" name="sign-in">เข้าสู่ระบบ</button>
            </form>
        </div>
    </div>
</body>
</html>