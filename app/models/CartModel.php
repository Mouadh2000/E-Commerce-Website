<?php

require_once 'database.php';

class CartModel extends Database
{
    public function addToCart($userId, $productsId, $quantity)
    {
        $existingCartItem = $this->getCartItem($userId, $productsId);

        if ($existingCartItem) {
            $this->updateQuantity($userId, $productsId, $quantity + $existingCartItem['quantity']);
        } else {
            $this->insertCartItem($userId, $productsId, $quantity);
        }

        
        header('Location: /E-Commerce/cart');
        exit();
    }
    public function addToVisitorCart($productId, $quantity){
    if (!isset($_SESSION['visitor_cart'])) {
        $_SESSION['visitor_cart'] = [];
    }

    $cartItemKey = array_search($productId, array_column($_SESSION['visitor_cart'], 'product_id'));

    if ($cartItemKey !== false) {
        $_SESSION['visitor_cart'][$cartItemKey]['quantity'] += $quantity;
    } else {
        $_SESSION['visitor_cart'][] = [
            'product_id' => $productId,
            'quantity' => $quantity,
        ];
    }

    return $_SESSION['visitor_cart'];
    }

    public function addToCartForUser($productId, $quantity, $userId) {
        $query = "INSERT INTO cart (product_id, quantity, user_id) VALUES (?, ?, ?) ";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(1, $productId);
        $stmt->bindParam(2, $quantity);
        $stmt->bindParam(3, $userId);
        $stmt->execute();
    }

    public function removeFromCart($userId, $productId){
    if ($userId) {
        $query = "DELETE FROM cart WHERE user_id = ? AND product_id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->execute([$userId, $productId]);
    } else {
        if (isset($_SESSION['visitor_cart'])) {
            $visitorCart = &$_SESSION['visitor_cart'];
            $cartItemKey = array_search($productId, array_column($visitorCart, 'product_id'));

            if ($cartItemKey !== false) {
                unset($visitorCart[$cartItemKey]);
                $visitorCart = array_values($visitorCart);
            }
        }
    }

    header('Location: /E-Commerce/cart');
    exit();
    }
    

    public function updateQuantity($userId, $productId, $quantity) {
        if ($userId) {
            $this->updateUserQuantity($userId, $productId, $quantity);
            $updatedCartContents = $this->getCartContents($userId);
        } else {
            $updatedCartContents = $this->updateVisitorQuantity($productId, $quantity);
            return $updatedCartContents;
        }
    
    }
    

    

    private function updateUserQuantity($userId, $productId, $quantity) {
        $query = "UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->execute([$quantity, $userId, $productId]);
        return $this->getCartContents($userId);

    }

    private function updateVisitorQuantity($productId, $quantity) {
        $visitorCart = isset($_SESSION['visitor_cart']) ? $_SESSION['visitor_cart'] : [];

        foreach ($visitorCart as &$item) {
            if ($item['product_id'] == $productId) {
                $item['quantity'] = $quantity;
                break;
            }
        }

        $_SESSION['visitor_cart'] = $visitorCart;
        return $visitorCart;

    }
    public function getCartContents($userId){
        $cartContents = [];
    
        if ($userId) {
            $query = "SELECT cart.product_id, products.product_title, products.product_price, products.product_image, categories.category_title, cart.quantity 
                      FROM cart 
                      INNER JOIN products ON cart.product_id = products.product_id 
                      INNER JOIN categories ON products.category_id = categories.category_id 
                      WHERE cart.user_id = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->execute([$userId]);
    
            $cartContents = array_merge($cartContents, $stmt->fetchAll(PDO::FETCH_ASSOC));
        }
    
        if (isset($_SESSION['visitor_cart'])) {
            $visitorCartContents = $_SESSION['visitor_cart'];
        
            $productIds = array_column($visitorCartContents, 'product_id');
        
            if (!empty($productIds)) {
                $productIds = array_map('intval', $productIds);
                $placeholders = implode(',', array_fill(0, count($productIds), '?'));
        
                $query = "SELECT product_id, product_title, product_price, product_image, category_id FROM products 
                          WHERE product_id IN ($placeholders)";
                $stmt = $this->connection->prepare($query);
                $stmt->execute($productIds);
        
                $dbResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
                foreach ($dbResults as &$resultItem) {
                    $cartItemKey = array_search($resultItem['product_id'], array_column($visitorCartContents, 'product_id'));
                    if ($cartItemKey !== false) {
                        $resultItem['quantity'] = $visitorCartContents[$cartItemKey]['quantity'];
                    }
                }
        
                $cartContents = array_merge($cartContents, $dbResults);
            }
        }
        
        return $cartContents;
        
    }

    public function hasItemsInCart($userId)
    {
        $query = "SELECT COUNT(*) FROM cart WHERE user_id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->execute([$userId]);

        return $stmt->fetchColumn() > 0;
    }

    private function insertCartItem($userId, $productsId, $quantity)
    {
        $query = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
        $stmt = $this->connection->prepare($query);
        $stmt->execute([$userId, $productsId, $quantity]);
    }

    private function getCartItem($userId, $productsId)
    {
        $query = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->execute([$userId, $productsId]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function isProductInCart($productId, $userId = null) {
        if ($userId) {
            $userCartContents = $this->getCartContents($userId);
            foreach ($userCartContents as $item) {
                if ($item['product_id'] == $productId) {
                    return true; 
                }
            }
        } else {
            if (isset($_SESSION['visitor_cart'])) {
                $visitorCartContents = $_SESSION['visitor_cart'];
                foreach ($visitorCartContents as $item) {
                    if ($item['product_id'] == $productId) {
                        return true; 
                    }
                }
            }
        }

        return false; 
    }

}
