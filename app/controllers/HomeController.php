<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/app/config/config.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/app/controllers/CartController.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/app/controllers/ProductController.php";
session_start();    
class HomeController
{
    private $productController;
    private $cartController;

    public function __construct()
    {
        $this->productController = new ProductController(new ProductModel(), new CategoryModel());
        $this->cartController = new CartController(new CartModel());
    }

    public function index()
    {
        $searchQuery = isset($_GET['q']) ? trim($_GET['q']) : '';
        $productsPerPage = 9;
        $page = isset($_GET['page']) ? $_GET['page'] : '1';

        if (!ctype_digit($page)) {
            // The 'page' parameter contains non-numeric characters
            die("Invalid 'page' parameter!");
        }
        

        $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($current_page - 1) * $productsPerPage;

        try {
            $products = $this->productController->getProductsWithPagination($current_page, $productsPerPage, $searchQuery);
            $totalProducts = $this->productController->getTotalProductsCount($searchQuery);
            $totalPages = ceil($totalProducts / $productsPerPage);
        } catch (PDOException $e) {
            die("Error fetching products: " . $e->getMessage());
        }

        if (isset($_GET["add_to_cart"])) {
            $product_id = $_GET['add_to_cart'];
            $quantity = 1;
            $this->cartController->addToCart($product_id, $quantity);
        }

        $this->renderView($products, $current_page, $totalPages);
    }

    private function renderView($products, $current_page, $totalPages)
    {
        include $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/app/views/common/header.php";

        echo '<div class="container">
                <div class="row">';
                
        foreach ($products as $product) {
            echo '<div class="col-md-4 mb-4 mt-4 d-flex">
                    <div class="card bg-dark p-4 " style="margin-left:10px;border-radius:15px;">
                        <div style="text-align: center;">';

            $imageData = base64_encode($product['product_image']);
            echo '<img style="width: 150px;height: 105px;" src="data:image/jpeg;base64,' . $imageData . '" data-blob="data:image/jpeg;base64,' . $imageData . '" class="card-img-top" alt="Product Image">
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 style="color: #0d6efd;text-align:center;" class="card-title">
                                <i class="fas fa-gem"></i> ' . $product['product_title'] . '
                            </h5>
                            <p style="color:white;font-family:cursive" class="card-text">
                                <span class="short-description">' . substr($product['product_description'], 0, 120) . '...</span>
                            </p>
                            <p class="mt-auto" style="color:#d5d113;font-family: monospace;font-size:18px;">
                                <i class="fas fa-tag"></i>
                                ' . number_format($product['product_price'], 3) . ' DT
                            </p>
                            <div style="display: flex; align-items: center;">
                                <button class="btn mt-auto view-more-btn" data-toggle="modal" data-target="#productModal' . $product['product_id'] . '" style="background-color:#0d6efd;color:white;">View More</button>
                                <a href="index.php?add_to_cart=' . $product['product_id'] . '" class="btn btn-success" style="margin-left:20px;"><i class="fa-solid fa-cart-shopping"></i> Add product</a>
                            </div>
                        </div>
                    </div>
                </div>';

            echo '<div class="modal fade" id="productModal' . $product['product_id'] . '" tabindex="-1" role="dialog" aria-labelledby="productModalLabel' . $product['product_id'] . '" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header text-center">
                                <h5 class="modal-title w-100" style="color: red;" id="productModalLabel' . $product['product_id'] . '">' . $product['product_title'] . '</h5>
                            </div>
                            <div class="modal-body">
                                <div style="text-align:center;">
                                    <img style="width: 50%; height: auto;" src="data:image/jpeg;base64,' . $imageData . '" alt="Product Image">
                                </div>
                                <p style="color: #0d6efd; font-family: cursive;">' . $product['product_description'] . '</p>
                                <p class="mt-auto" style="color:green;font-family: monospace;font-size:20px;">
                                    <i class="fas fa-tag"></i>
                                    ' . number_format($product['product_price'], 3) . ' DT
                                </p>
                            </div>
                        </div>
                    </div>
                </div>';
        }

        echo '</div>';
        
        // Pagination links
        echo '<nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">';
                
        for ($i = 1; $i <= $totalPages; $i++) {
            echo '<li class="page-item ' . (($i == $current_page) ? 'active' : '') . '">
                    <a class="page-link" href="?page=' . $i . '">' . $i . '</a>
                </li>';
        }

        echo '</ul>
            </nav>
        </div>';

        include $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/app/views/common/footer.php";
    }
}
$homeController = new HomeController();
$homeController->index();
echo '<script src="https://code.jquery.com/jquery-3.2.1.slim.js"></script>

<script>
    document.addEventListener(\'DOMContentLoaded\', function() {
        // JavaScript code to show the modal when the "View More" button is clicked
        var viewMoreBtns = document.querySelectorAll(\'.view-more-btn\');
        viewMoreBtns.forEach(function(btn) {
            btn.addEventListener(\'click\', function() {
                var productId = btn.getAttribute(\'data-target\').replace(\'#productModal\', \'\');
                $(\'#productModal\' + productId).modal(\'show\');
            });
        });
    });
</script>';
?>
