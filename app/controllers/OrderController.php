<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/app/models/OrderModel.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/app/models/UserModel.php";

class OrderController{
    private $orderModel;
    private $userModel;
    public function __construct(OrderModel $orderModel, UserModel $userModel){
        $this->orderModel = $orderModel;
        $this->userModel = $userModel;
    }
    public function processCheckout($userId, $cartContents, $totalPrice){
        $orderId = $this->orderModel->createOrder($userId, $cartContents, $totalPrice);

        $this->orderModel->sendOrderDetailsToAdmin($orderId, $cartContents, $totalPrice);


    }
    
}