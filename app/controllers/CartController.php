<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/app/models/CartModel.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/app/controllers/OrderController.php";

class CartController
{
    private $cartModel;
    private $quantity;
    

    public function __construct(CartModel $cartModel)
    {
        $this->cartModel = $cartModel;
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

        if ($userId) {
            if (isset($_SESSION['visitor_cart'])) {
                $visitorCartContents = $_SESSION['visitor_cart'];
                foreach ($visitorCartContents as $item) {
                    $this->cartModel->addToCartForUser($item['product_id'], $item['quantity'], $userId);
                }
                unset($_SESSION['visitor_cart']);
            }
        }
    }

    public function addToCart($productId, $quantity) {
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    
        $isProductInCart = $this->cartModel->isProductInCart($productId, $userId);
    
        if ($isProductInCart) {
            echo '<script>alert("Product is already in the cart")</script>';
        } else {
            if ($userId) {
                if (isset($_SESSION['visitor_cart'])) {
                    $visitorCartContents = $_SESSION['visitor_cart'];
                    foreach ($visitorCartContents as $item) {
                        $this->cartModel->addToCartForUser($item['product_id'], $item['quantity'], $userId);
                    }
                    unset($_SESSION['visitor_cart']);
                }
    
                $this->cartModel->addToCartForUser($productId, $quantity, $userId);
            } else {
                $this->cartModel->addToVisitorCart($productId, $quantity);
            }
            echo '<script>alert("Product Added Successfully")</script>';
        }
    }
        
    public function removeFromCart($productId)
    {
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

        if ($userId) {
            $this->cartModel->removeFromCart($userId, $productId);
        } 
    }

   

    public function updateQuantity($productId, $quantity) {
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        $updatedCartContents = $this->cartModel->updateQuantity($userId, $productId, $quantity);
    
        return $updatedCartContents;
    }
    
    
    public function viewCart()
    {
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

        $cartContents = $this->cartModel->getCartContents($userId);
        return $cartContents;

    }
    
    public function processCheckout()
    {
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    if (!$userId) {
        echo '<script>alert("You Must Login to proceed")</script>';
        return;
    }

    $cartContents = $this->cartModel->getCartContents($userId);
    $subtotal = isset($_POST['subtotal']) ? floatval($_POST['subtotal']) : 0;
    $totalPrice = $subtotal + 6.99 + 30;
    // Call the OrderController to handle the checkout process
    $orderController = new OrderController(new OrderModel(), new UserModel());
    $orderController->processCheckout($userId, $cartContents, $totalPrice);

    }
}
