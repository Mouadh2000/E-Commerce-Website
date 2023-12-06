<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/app/models/CategoryModel.php";
class CategoryController{
    private $categoryModel;
    public function __construct(CategoryModel $categoryModel){
        $this->categoryModel = $categoryModel;
    }
    public function create(){
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $title = $_POST['category_title'];
            $description = $_POST['category_description'];
            $image = file_get_contents($_FILES['category_image']['tmp_name']);
            $insertStatus = $this->categoryModel->createCategory($title, $description, $image);
            if($insertStatus){
                echo '<script>alert("Category inserted successfully!");</script>';
            } else{
                echo '<script>alert("Category insertion failed!");</script>';
            }
        }
    }
    public function updateCategory($categoryId, $newTitle, $newDescription, $newImage = null) {
        return $resultUpdate = $this->categoryModel->updateCategory($categoryId, $newTitle, $newDescription, $newImage);
        
    }
    public function delete($categoryTitle){
        $categoryId = $this->categoryModel->getCategoryIdByTitle($categoryTitle);

        if ($categoryId !== null) {
            $deleteStatus = $this->categoryModel->deleteCategory($categoryId);

            if ($deleteStatus) {
                echo '<script>alert("Category deleted successfully!");</script>';
            } else {
                echo '<script>alert("Category deletion failed!");</script>';
            }
        } else {
            echo '<script>alert("Category not found!");</script>';
        }

    }
}