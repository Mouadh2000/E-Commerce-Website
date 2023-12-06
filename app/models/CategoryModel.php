<?php
require_once 'database.php';
class CategoryModel extends Database{
    
    public function getCategoryIdByTitle($categoryTitle) {
        try {
            $query = "SELECT category_id FROM categories WHERE category_title = ?";
            $statement = $this->connection->prepare($query);
            $statement->bindParam(1, $categoryTitle);
            $statement->execute();

            $result = $statement->fetch(PDO::FETCH_ASSOC);

            return ($result) ? $result['category_id'] : null;
        } catch (PDOException $e) {
            return null;
        }
    }
    public function getCategoryTitleById($categoryId){
        try {
            $query = "SELECT category_title FROM categories WHERE category_id = ?";
            $statement = $this->connection->prepare($query);
            $statement->bindParam(1, $categoryId);
            $statement->execute();

            $result = $statement->fetch(PDO::FETCH_ASSOC);

            return ($result) ? $result['category_title'] : null;
        } catch (PDOException $e) {
            return null;
        }
    }
    public function getCategoriesWithPagination($currentpage, $itemsPerPage){
        $offset = ($currentpage - 1) * $itemsPerPage;
        $query = "SELECT * FROM categories LIMIT :offset, :itemsPerPage";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(':offset', $offset, PDO::PARAM_INT);
        $statement->bindParam(':itemsPerPage', $itemsPerPage, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getTotalPages($itemsPerPage){
        $query = "SELECT COUNT(*) as total FROM categories";
        $statement = $this->connection->prepare($query);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $totalItems = $result['total'];
        return ceil($totalItems / $itemsPerPage);
    }
    public function createCategory($title, $description, $image) {
        try {
            $query = "INSERT INTO categories (category_title, category_description, category_image) VALUES (?, ?, ?)";
            $statement = $this->connection->prepare($query);
            $statement->bindParam(1, $title);
            $statement->bindParam(2, $description);
            $statement->bindParam(3, $image, PDO::PARAM_LOB);
            $statement->execute();
            $rowCount = $statement->rowCount();
            if($rowCount > 0){
                return true;
            }else{
                return false;
            }
        } catch (PDOException $e) {
            echo "Category registration failed: " . $e->getMessage();
        }
        
    }
    public function updateCategory($categoryId, $newTitle, $newDescription, $newImage = null) {
        try {
            $sql = "UPDATE categories SET category_title = ?, category_description = ?";
            $parameters = [$newTitle, $newDescription];
        
            if ($newImage !== null) {
                $sql .= ", category_image = ?";
                $parameters[] = $newImage;
            }
        
            $sql .= " WHERE category_id = ?";
            $parameters[] = $categoryId;
        
            $stmt = $this->connection->prepare($sql);
        
            // Bind parameters
            $stmt->bindParam(1, $parameters[0]);
            $stmt->bindParam(2, $parameters[1]);
        
            if ($newImage !== null) {
                $stmt->bindParam(3, $parameters[2], PDO::PARAM_LOB);
                $stmt->bindParam(4, $categoryId);
            }
            else {
                $stmt->bindParam(3, $categoryId);
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
    public function deleteCategory($categoryId) {
        try {
            $query = "DELETE FROM categories WHERE category_id = ?";
            $statement = $this->connection->prepare($query);
            $statement->bindParam(1, $categoryId);
            $statement->execute();

            $rowCount = $statement->rowCount();

            return $rowCount > 0;
        } catch (PDOException $e) {
            return false;
        }
    }
}

