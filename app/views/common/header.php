<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/index.php";
?>  
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/E-Commerce/assets/css/bootstrap.css">
    <link rel="stylesheet" href="/E-Commerce/assets/css/backup.css">
    <link rel="stylesheet" href="/E-Commerce/assets/css/font-awesome.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.css" integrity="sha384..." crossorigin="anonymous">
    <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.css"
    />
    

    
    <title>DELL Shop</title>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiar bg-dark" style="padding-top: 18px; padding-bottom: 28px;">
  <div class="container-fluid">
    <img src="/E-Commerce/assets/images/dell-logo.png" alt="" height="50px" width="50px" style="margin-left:30px">
    <a class="navbar-brand " href="/E-Commerce/" style="font-family:Fantasy;padding-left:12px;color:#0d6efd;">DELL Shop</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse " id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
        <?php
            if (session_status() == PHP_SESSION_NONE) {
              session_start();
          }
            if (isset($_SESSION['user_id'])) {
                echo '<div class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: white;padding-left:675px;">
                    Welcome
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown" style="margin-left: 720px;background-color: #0d6efd;">
                    <a class="dropdown-item" href="/E-Commerce/profile" style="color: black;">Profile</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="/E-Commerce/logout" style="color: black;">Logout</a>
                </div>
              </div>';
            } else {
                echo '<a class="nav-link active" aria-current="page" href="/E-Commerce/user/login" style="color: white;padding-left:750px;">Login</a>';
            }
          ?>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/E-Commerce/cart" style="color: white;"> <i class="fas fa-shopping-cart" style="color: #0993ce;"></i></a>
        </li>
      </ul>
      <i class="fa fa-search se" aria-hidden="true"></i>
      <form class="d-flex" role="search" action="search" method="get" id="searchForm">
      <i class="fa-solid fa-magnifying-glass" style="color:#343a40;position: absolute;top:34px;left:1058px;"></i>
    <input class="form-control me-2" type="search" name="q" placeholder="Search" aria-label="Search" style="border-radius:25px;width:280px;text-indent: 20px;box-shadow:none; outline:none; border-color: transparent;" autocomplete="off">
</form>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function() {
        $("input[name='q']").on('input', function() {
            clearTimeout($(this).data('timer'));
            $(this).data('timer', setTimeout(function(){
                $("#searchForm").submit();
            }, 1000));
        });
    });
</script>

      
    </div>
  </div>
</nav>


