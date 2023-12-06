<?php 
require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/index.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/app/views/common/header.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/app/controllers/UserController.php";
$usermodel = new UserModel();
$user = new Authentication($usermodel);
$user->logout();

