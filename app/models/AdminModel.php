<?php
require_once 'database.php';

class AdminModel extends Database{
    
    public function checkLogin($email, $password) {
        try{
            $hashedPassword = md5($password);
            $query = "SELECT id, email, password FROM admin WHERE email = ? and password = ?";
            $statement = $this->connection->prepare($query);
            $statement->bindParam(1, $email);
            $statement->bindParam(2, $hashedPassword);
            $statement->execute();
            $user = $statement->fetch(PDO::FETCH_ASSOC);
            if ($user !== false) {
                return $user['id']; 
            }
            return false;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    public function totalOrders(){
        try{
            $query = "SELECT COUNT(*) FROM orders";
            $statement = $this->connection->prepare($query);
            $statement->execute();            
            $totalorders = $statement->fetch(PDO::FETCH_ASSOC);
            return $totalorders['COUNT(*)'];
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }

    }
    public function totalCustomers(){
        try{
            $query = "SELECT COUNT(*) FROM users";
            $stmt= $this->connection->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['COUNT(*)'];
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
        
    }
    public function viewCustomers(){
        try{
            $query = "SELECT * FROM users";
            $stmt= $this->connection->prepare($query);
            $stmt->execute();
            $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $customers;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    public function totalSales(){
        try{
            $query = "SELECT SUM(total_price) FROM orders";
            $statement = $this->connection->prepare($query);
            $statement->execute();            
            $totalorders = $statement->fetch(PDO::FETCH_ASSOC);
            return $totalorders['SUM(total_price)'];
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    public function getOrderDetails() {
        $query = "SELECT u.username, o.order_id, o.status, o.created_at, o.total_price
                  FROM orders o
                  JOIN users u ON o.user_id = u.id";
    
        try {
            $statement = $this->connection->prepare($query);
            $statement->execute();
    
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }
}

