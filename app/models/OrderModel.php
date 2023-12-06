<?php

require_once 'database.php';

class OrderModel extends Database{
    public function createOrder($userId, $cartContents, $totalPrice){
        $query = "INSERT INTO orders (user_id, total_price, status) VALUES (:user_id, :total_price, 'pending')";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':total_price', $totalPrice);
        $stmt->execute();

        $orderId = $this->connection->lastInsertId();

        foreach($cartContents as $cartItem){
            $this->insertOrderDetails($orderId, $cartItem['product_id'], $cartItem['quantity']);
        }
        return $orderId;
    }
    private function insertOrderDetails($orderId, $productId, $quantity){
        $query = "INSERT INTO order_details (order_id, product_id, quantity) VALUES (:order_id, :product_id, :quantity)";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':order_id', $orderId);
        $stmt->bindParam(':product_id', $productId);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->execute();
    }

    public function sendOrderDetailsToAdmin($orderId, $cartContents, $totalPrice){
        foreach ($cartContents as $cartItem) {
            $this->insertOrderDetails($orderId, $cartItem['product_id'], $cartItem['quantity']);
        }
    
    }

    public function getAllOrders(){
        // Implement the logic to retrieve all orders from the database
    }
    
}