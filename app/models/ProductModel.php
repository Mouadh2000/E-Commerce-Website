<?php
require_once 'database.php';
class ProductModel extends Database{
    
    public function createProduct($title, $description, $categoryId, $image, $price){
        try{
            $query = "INSERT INTO products (product_title, product_description, category_id, product_image, product_price)
            VALUES (?, ?, ?, ?, ?)";
            $statement = $this->connection->prepare($query);
            $statement->bindParam(1, $title);
            $statement->bindParam(2, $description);
            $statement->bindParam(3, $categoryId);
            $statement->bindParam(4, $image);
            $statement->bindParam(5, $price);
            $statement->execute();

            $rowCount = $statement->rowCount();
            return $rowCount >0;
        }  catch(PDOException $e){
            echo "Product registration failed: " . $e->getMessage();
            return false;
        }
    }

    public function deleteProduct($product_title){
        try {
            $query = "DELETE FROM products WHERE product_title = ?";
            $statement = $this->connection->prepare($query);
            $statement->bindParam(1, $product_title);
            $statement->execute();

            $rowCount = $statement->rowCount();

            return $rowCount > 0;
        } catch (PDOException $e) {
            return false;
        }
    }
    public function updateProduct($product_id, $newTitle, $newDescription, $newPrice, $newquantity, $newImage = null){
        try {
            $sql = "UPDATE products SET product_title = ?, product_description = ?, product_price = ?, stock_quantity = ?";
            $parameters = [$newTitle, $newDescription, $newPrice, $newquantity];
        
            if ($newImage !== null) {
                $sql .= ", product_image = ?";
                $parameters[] = $newImage;
            }
        
            $sql .= " WHERE product_id = ?";
            $parameters[] = $product_id;
        
            $stmt = $this->connection->prepare($sql);
        
            // Bind parameters
            $stmt->bindParam(1, $parameters[0]);
            $stmt->bindParam(2, $parameters[1]);
            $stmt->bindParam(3, $parameters[2]);
            $stmt->bindParam(4, $parameters[3]);
        
            // If newImage is provided, bind it as a BLOB
            if ($newImage !== null) {
                $stmt->bindParam(5, $newImage, PDO::PARAM_LOB);
                $stmt->bindParam(6, $product_id);
            }
            else {
                $stmt->bindParam(5, $product_id);
            }
            
            $stmt->execute();
        
            $rowCount = $stmt->rowCount();
        
            if ($rowCount > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return false;
        }
    }
    public function getProductsWithPagination($current_page, $products_per_page, $searchQuery = '') {
        $offset = ($current_page - 1) * $products_per_page;
        $query = "SELECT * FROM products";
        
        if (!empty($searchQuery)) {
            $query .= " WHERE product_title LIKE :searchQuery OR product_description LIKE :searchQuery";
        }
    
        $query .= " LIMIT :offset, :products_per_page";
    
        $statement = $this->connection->prepare($query);
        
        if (!empty($searchQuery)) {
            $searchParam = "%{$searchQuery}%";
            $statement->bindParam(':searchQuery', $searchParam, PDO::PARAM_STR);
        }
    
        $statement->bindParam(':offset', $offset, PDO::PARAM_INT);
        $statement->bindParam(':products_per_page', $products_per_page, PDO::PARAM_INT);
    
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getTotalProductsCount($searchQuery = '') {
        $query = "SELECT COUNT(*) as total FROM products";
    
        if (!empty($searchQuery)) {
            $query .= " WHERE product_title LIKE :searchQuery OR product_description LIKE :searchQuery";
        }
    
        $statement = $this->connection->prepare($query);
    
        if (!empty($searchQuery)) {
            $searchParam = "%{$searchQuery}%";
            $statement->bindParam(':searchQuery', $searchParam, PDO::PARAM_STR);
        }
    
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
    public function getTotalPages($itemsPerPage){
        $query = "SELECT COUNT(*) as total FROM products";
        $statement = $this->connection->prepare($query);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $totalItems = $result['total'];
        return ceil($totalItems / $itemsPerPage);
    }
    public function performSearch($searchQuery) {
        $query = "SELECT * FROM products WHERE product_title LIKE :searchQuery";
        $statement = $this->connection->prepare($query);
        $searchParam = "%{$searchQuery}%";
        $statement->bindParam(':searchQuery', $searchParam, PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}


