<?php
header_remove("X-Powered-By");
header('Server: ');




$url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
$queryString = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
$routes = [
    '/E-Commerce/' => 'app/controllers/HomeController.php',
    '/E-Commerce/user/login' => 'app/views/user/login.php',
    '/E-Commerce/profile' => 'app/views/user/profile.php',
    '/E-Commerce/logout' => 'app/views/user/logout.php',
    '/E-Commerce/user/register' => 'app/views/user/register.php',
    '/E-Commerce/admin/login' => 'app/views/admin/login.php',
    '/E-Commerce/admin/logout' => 'app/views/admin/logout.php',
    '/E-Commerce/admin/dashboard' => 'app/views/admin/dashboard.php',
    '/E-Commerce/admin/edit_product' => 'app/views/admin/edit_product.php',
    '/E-Commerce/admin/edit_category' => 'app/views/admin/edit_category.php',
    '/E-Commerce/cart' => 'cart.php',
];

if (strpos($url, '/app') !== false) {
    // Handle 404 Not Found
    http_response_code(404);
    require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/app/views/errors/404.php";
    die();
} elseif (array_key_exists($url, $routes)) {
    
    require_once $routes[$url];
} elseif (strpos($url, '/E-Commerce/') === 0 && isset($_GET["add_to_cart"])) {
    $product_id = $_GET['add_to_cart']; 
    require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/app/controllers/HomeController.php";

}elseif (strpos($url, '/E-Commerce/') === 0 && isset($_GET["q"])) {
    require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/app/controllers/HomeController.php";

} elseif (strpos($url, '/E-Commerce/?page') === 0) {
    $pageNumber = substr($url, strlen('/E-Commerce/?page/'));
    require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/app/controllers/HomeController.php";
} elseif (strpos($url, '/E-Commerce/admin/get_categories.php?page') === 0) {
    $pageNumber = substr($url, strlen('/E-Commerce/admin/get_categories.php?page/'));
    require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/app/views/admin/get_categories.php";
}
elseif (strpos($url, '/E-Commerce/admin/get_products.php?page') === 0) {
    $pageNumber = substr($url, strlen('/E-Commerce/admin/get_products.php?page/'));
    require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/app/views/admin/get_products.php";
} 
elseif (strpos($url, '/E-Commerce/admin/edit_category?category_id') === 0) {
    require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/app/views/admin/edit_category.php";
}
elseif (strpos($url, '/E-Commerce/admin/edit_product?product_id') === 0) {
    require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/app/views/admin/edit_product.php";
}else {
    http_response_code(404);
    require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/app/views/errors/404.php";
}
?>
