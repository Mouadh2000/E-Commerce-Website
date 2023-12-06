<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/app/controllers/CategoryController.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/app/controllers/ProductController.php";

$categoryModel = new CategoryModel();
$productModel = new ProductModel();

$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
$productitemsPerPage = 2;

$products = $productModel->getProductsWithPagination($currentPage, $productitemsPerPage);


foreach ($products as $product) {
    $cat_id = $product['category_id'];
    $cat_title = $categoryModel->getCategoryTitleById($cat_id);
    // Output HTML for each category
    $imageData = base64_encode($product['product_image']);
    echo '<p class=""table--items--col1">' . $product['product_title'] . '</p>';
    echo '<p class="table--items--col2">' . $product['product_description'] . '</p>';
    echo '<p class="table--items--col3">' . $cat_title . '</p>';
    echo '<div class="products__items--imgWrapper table--items--col4">';
    echo '<img class="products__items--img" style="width:50px;" src="data:image/jpeg;base64,' . $imageData . '" alt="' . $product['product_title'] . '" />';
    echo '</div>';
    echo '<p class="table--items--col5">DT
    '.$product['product_price'].'
    </p>';
    echo'<div class="table--items--col6">';
    echo '<form action="" method="POST">';
    echo '<a target="_blank" href="edit_product?product_id='.$product['product_id'].'" class="btn-icon btn-icon-success">
            <i class="far fa-edit"></i>
        </a>
        <button class="btn-icon btn-icon-danger" name="delete_product" type="submit">
        <i class="far fa-trash-alt"></i>
        </button>
        <input type="hidden" name="product_title" value=" '.$product['product_title'].'">
        </form>';
    echo '</div>';
    
    
}

?>


<?php
echo '<input type="hidden" style="display: none;" id="currentPage" value="' . $currentPage . '">';
echo '<input type="hidden" style="display: none;" id="totalPages" value="' . $productModel->getTotalPages($productitemsPerPage) . '">';
?>