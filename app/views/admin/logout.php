<?php 
require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/index.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/app/views/common/header.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/app/controllers/AdminController.php";
$adminmodel = new AdminModel();
$admin = new Authentication($adminmodel);
$admin->logout();

