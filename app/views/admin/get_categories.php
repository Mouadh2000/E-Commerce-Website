<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/app/controllers/CategoryController.php";

    
$categoryModel = new CategoryModel();
$categoryController = new CategoryController($categoryModel);
$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
$itemsPerPage = 1;

$categories = $categoryModel->getCategoriesWithPagination($currentPage, $itemsPerPage);


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_category'])) {
        
    if (isset($_POST['delete_category'])){
            
        $categoryController->delete($_POST['category_title']);
    }

}

foreach ($categories as $category) {
    $imageData = base64_encode($category['category_image']);
    echo '<p class="table--items--col1">' . $category['category_title'] . '</p>';
    echo '<p class="table--items--col2">' . substr($category['category_description'], 0, 100) . '...</p>';
    echo '<div class="products__items--imgWrapper table--items--col3">';
    echo '<img class="collections__items--img" src="data:image/jpeg;base64,' . $imageData . '" alt="' . $category['category_title'] . '" />';
    echo '</div>';
    echo'<div class="table--items--col4">';
    echo '<form action="" method="POST">';
    echo '<a target="_blank" href="edit_category?category_id='.$category['category_id'].'" class="btn-icon btn-icon-success">
            <i class="far fa-edit"></i>
        </a>
        <button class="btn-icon btn-icon-danger" name="delete_category" type="submit">
        <i class="far fa-trash-alt"></i>
        </button>
        <input type="hidden" name="category_title" value=" '.$category['category_title'].'">
        </form>';
    
    
    echo '</div>';
    }

?>

<?php
echo '<input type="hidden" style="display: none;" id="currentPage" value="' . $currentPage . '">';
echo '<input type="hidden" style="display: none;" id="totalPages" value="' . $categoryModel->getTotalPages($itemsPerPage) . '">';
echo '<input type="text" name="new_category_title" class="edit-fields" placeholder="New Title" required style="display: none;">
    <textarea name="new_category_description" class="edit-fields" placeholder="New Description" required style="display: none;"></textarea>
    <input type="file" name="new_category_image" class="edit-fields" style="display: none;">';
?>


