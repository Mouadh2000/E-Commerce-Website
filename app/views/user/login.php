<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/index.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/app/views/common/header.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/app/controllers/UserController.php";

if (isset($_SESSION['user_id'])) {
  header('Location:/E-Commerce/');
}
$usermodel = new UserModel();
$user = new Authentication($usermodel);
$user->login();

?>
<section class="vh-100" style="background-color: #e1e1e1;">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col col-xl-10">
        <div class="card" style="border-radius: 1rem;">
          <div class="row g-0">
            <div class="col-md-6 col-lg-5 d-none d-md-block">
              <img src="/E-Commerce/assets/images/login.jpg"style="border-radius:10px;"
                alt="login form" height="455px" width="430px" style="border-radius: 1rem 0 0 1rem;" />
            </div>
            <div class="col-md-6 col-lg-7 d-flex align-items-center">
              <div class="card-body p-4 p-lg-5 text-black">

                <form action="/E-Commerce/user/login" method="POST">
                  <h5 style="margin-left:133px;" class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Sign into your account</h5>

                  <div class="form-outline mb-4">
                    <input style="margin-left:13px;width: 440px;" type="email" name="logemail" class="form-control form-control-lg" placeholder="Email Address" autocomplete="off" />
                    
                  </div>

                  <div class="form-outline mb-4">
                    <input style="margin-left:13px;width: 440px;" type="password" name="logpassword" class="form-control form-control-lg" placeholder="Password" autocomplete="off" />
    
                  </div>

                  <div class="pt-1 mb-4" >
                    <input type="hidden" name="form_type" value="login">
                    <button  style="margin-left:13px;width: 440px;" class="btn btn-dark btn-lg btn-block" type="submit">Log In</button>
                  </div>

                  <p style="margin-left:100px;" class="mb-5 pb-lg-2" style="color: #393f81;">Don't have an account? <a href="register"
                      style="color: #393f81;">Register here</a></p>
                </form>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/app/views/common/footer.php"; ?>