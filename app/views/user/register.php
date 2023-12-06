<?php 
require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/index.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/app/views/common/header.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/app/controllers/UserController.php";
if (isset($_SESSION['user_id'])) {
  header('Location:/E-Commerce/');
}
$register = new Register();
if ($register->isValid()){
  echo '<script>alert("Registration Successfully")</script>';
}

?>
<section class="vh-100" style="background-color: #e1e1e1;">
  <div class="container py-4 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col col-xl-10">
        <div class="card" style="border-radius: 1rem;">
          <div class="row g-0">
            <div class="col-md-6 col-lg-5 d-none d-md-block">
              <img src="/E-Commerce/assets/images/login.jpg"style=";border-radius:10px;"
                alt="login form" height="562px" width="430px" style="border-radius: 1rem 0 0 1rem;" />
            </div>
            <div class="col-md-6 col-lg-7 d-flex align-items-center">
              <div class="card-body p-4 p-lg-5 text-black">

                <form action="/E-Commerce/user/register" method="POST">
                  <h5 style="margin-left:210px;" class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Sign Up</h5>
                  <div class="form-outline mb-4">
                    <input style="margin-left:13px;width: 440px;" type="text" name="fullname" class="form-control form-control-lg" placeholder="Full Name" autocomplete="off" />
                    
                  </div>
                  <div class="form-outline mb-4">
                    <input style="margin-left:13px;width: 440px;" type="text" name="username" class="form-control form-control-lg" placeholder="Username" autocomplete="off" />
                    
                  </div>

                  <div class="form-outline mb-4">
                    <input style="margin-left:13px;width: 440px;" type="email" name="email" class="form-control form-control-lg" placeholder="Email Address" autocomplete="off" />
                    
                  </div>

                  <div class="form-outline mb-4">
                    <input style="margin-left:13px;width: 440px;" type="password" name="password" class="form-control form-control-lg" placeholder="Password" />
    
                  </div>
                  <div class="form-outline mb-4">
                    <input style="margin-left:13px;width: 440px;" type="password" name="rpassword" class="form-control form-control-lg" placeholder="Repeat Password" />
    
                  </div>

                  <div class="pt-1 mb-0" >
                  <input type="hidden" name="form_type" value="register">
                    <button  style="margin-left:13px;width: 440px;" class="btn btn-dark btn-lg btn-block" type="submit">Sign Up</button>
                  </div>

                  
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




