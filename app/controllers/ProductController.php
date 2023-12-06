<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/app/models/ProductModel.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/app/models/CategoryModel.php";

class ProductController{
    private $productModel;
    private $categoryModel;
    public function __construct(ProductModel $productModel, CategoryModel $categoryModel){
        $this->productModel = $productModel;
        $this->categoryModel = $categoryModel;
    }
    public function create(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $title = $_POST['product_title'];
            $description = $_POST['product_description'];
            $_POST['product_category'];
            $categoryId = $_POST['product_category'];
            $image = file_get_contents($_FILES['product_image']['tmp_name']);
            $price = $_POST['product_price'];

            $insertStatus = $this->productModel->createProduct($title, $description, $categoryId, $image, $price);
            
            if ($insertStatus) {
                echo '<script>alert("Product inserted successfully!");</script>';
            } else {
                echo '<script>alert("Product insertion failed!");</script>';
            }
        }
    }
    public function updateProduct($product_id, $newTitle, $newDescription, $newPrice, $newquantity, $newImage = null){
        return $resultUpdate = $this->productModel->updateProduct($product_id, $newTitle, $newDescription, $newPrice, $newquantity, $newImage);
    }
    
    public function delete($product_title){
        $deleteStatus = $this->productModel->deleteProduct($product_title);
        if ($deleteStatus) {
            echo '<script>alert("Product deleted successfully!");</script>';
        } else {
            echo '<script>alert("Product deletion failed!");</script>';
        }
    } 
    public function getProductsWithPagination($current_page, $products_per_page, $searchQuery = '') {
        return $this->productModel->getProductsWithPagination($current_page, $products_per_page, $searchQuery);
    }
    
    
    public function getTotalProductsCount($searchQuery = '') {
        return $this->productModel->getTotalProductsCount($searchQuery);
    }
    
    public function performSearch($searchQuery) {
        return $this->productModel->performSearch($searchQuery);
    }

}