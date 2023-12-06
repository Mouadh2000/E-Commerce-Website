<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/app/controllers/CategoryController.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/app/controllers/ProductController.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/app/controllers/AdminController.php";


session_start();
if (!isset($_SESSION['admin'])) {
    // Redirect to the login page
    header("Location: /E-Commerce/admin/login");
    exit();
}

$dashboard = new Dashboard(new AdminModel());
$categoryModel = new CategoryModel();
$productModel = new ProductModel();

$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
$itemsPerPage = 1;
$productitemsPerPage = 2;
$categoryController = new CategoryController($categoryModel);
$productController = new ProductController($productModel, $categoryModel);

$products = $productModel->getProductsWithPagination($currentPage, $productitemsPerPage);
$productTotalPages = $productModel->getTotalPages($productitemsPerPage);

$orders = $dashboard->viewOrderDetails();



$categories = $categoryModel->getCategoriesWithPagination($currentPage, $itemsPerPage);
$totalPages = $categoryModel->getTotalPages($itemsPerPage);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the form was submitted
    if (isset($_POST['save_category'])) {
        
        $categoryController->create();
    }
    if (isset($_POST['delete_category'])){
        $categoryController->delete(trim($_POST['category_title']));
    }
    if (isset($_POST['save_product'])){
        $categoryModel = new CategoryModel();
        $productModel = new ProductModel();
        $productController = new ProductController($productModel, $categoryModel);
        $productController->create();
    
    }
    if (isset($_POST['delete_product'])){
        $categoryModel = new CategoryModel();
        $productModel = new ProductModel();
        $productController = new ProductController($productModel, $categoryModel);
        $productController->delete(trim($_POST['product_title']));
    }
    if (isset($_POST['send_confirmation_email'])){
        $order_id = trim($_POST['order_id']);
        $username = trim($_POST['username']);
        $dashboard->sendConfirmationEmail($username, $order_id);
        if($dashboard === 'true'){
            
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.css"
        />
        <link rel="stylesheet" href="/E-Commerce/includes/styles/style.css" />
        <title>Admin</title>
    </head>
    <body>
        <header class="header">
            <div
                class="header__logo dflex px-1 justify-content-start align-items-center"
            >
                <i class="header__collapse--btn fas fa-align-left"></i>
                <img
                    class="header__logo__img"
                    src="/E-Commerce/assets/logo.png"
                    alt="greatweb"
                />
            </div>
            <div
                class="header__profile px-1 mx-3 dflex justify-content-start align-items-center"
                style="margin-left:950px;"
            >
                <div class="header__profile__imgWrapper mr-1">
                    <img
                        class="header__profile__img"
                        src="/E-Commerce/assets/avatar.jpg"
                        alt=""
                    />
                </div>
                <p
                    class="header__profile__name text-white text-bold-500 relative"
                >
                    Admin
                </p>
            </div>
        </header>
        <div>
            <span class="header__profile__name--nav">
                <span class="header__profile__name--nav--pointer">
                    <i class="fas fa-sort-up"></i>
                </span>
                <ul class="header__profile__name--nav--list">
                    <li class="header__profile__name--nav--item">
                        <a class="header__profile__name--nav--link" href="dashboard"
                            >Profile</a
                        >
                    </li>
                
                    <li class="header__profile__name--nav--item">
                        <a class="header__profile__name--nav--link" href="/E-Commerce/admin/logout"
                            >Logout</a
                        >
                    </li>
                </ul>
            </span>
        </div>
        <nav class="nav">
            <div class="nav__wrapper">
                <span class="nav__close">
                    <i class="fas fa-window-close"></i>
                </span>
                <ul class="nav__list">
                    <li class="nav__item">
                        <a class="nav__link nav__active" href="dashboard">
                            <span class="nav__link--span--icon"
                                ><i class="fas fa-home nav__link--icon"> </i
                            ></span>
                            <span class="nav__link--span--navname">
                                Home
                            </span>
                        </a>
                    </li>
                    <li class="nav__item nav__showOrders">
                        <a class="nav__link" href="#">
                            <span class="nav__link--span--icon"
                                ><i class="fas fa-shopping-bag nav__link--icon">
                                </i
                            ></span>
                            <span class="nav__link--span--navname">
                                Orders
                            </span>
                        </a>
                    </li>
                    <ul class="sub__nav--list nav__orders--items">
                        <li class="sub__nav--item">
                            <a class="sub__nav--link" href="#" onclick="scrollToOrders()"> Orders</a>
                        </li>
                    </ul>
                    <li class="nav__item nav__showProducts">
                        <a class="nav__link" href="#">
                            <span class="nav__link--span--icon"
                                ><i class="fas fa-tags nav__link--icon"> </i
                            ></span>
                            <span class="nav__link--span--navname">
                                Products
                            </span>
                        </a>
                    </li>
                    <ul class="sub__nav--list nav__products--items">
                        <li class="sub__nav--item">
                            <a class="sub__nav--link" href="#" onclick="scrollToProducts()">All products</a>
                        </li>

                        <li class="sub__nav--item">
                            <a class="sub__nav--link" href="#" onclick="scrollToCategories()">Category</a>
                        </li>
                    </ul>
                    <li class="nav__item">
                        <a class="nav__link" href="#" onclick="scrollToCustomers()">
                            <span class="nav__link--span--icon"
                                ><i class="fas fa-user nav__link--icon"> </i
                            ></span>
                            <span class="nav__link--span--navname">
                                Customers
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        <main class="main">
            <div class="main__sideNav"></div>
            <div class="main__content">
                <div class="home">
                    <h2 class="my-3">Overview Dashboard</h2>
                    <div class="home__cards">
                        <div class="home__cards--item px-2 py-2 card">
                            <p class="home__cards--title">Total Sales</p>
                            <p class="home__cards--count">DT <?php echo number_format($dashboard->totalsales(),3); ?></p>
                            <hr />
                        </div>
                        <div class="home__cards--item px-2 py-2 card">
                            <p class="home__cards--title">Total Orders</p>
                            <p class="home__cards--count"><?php 
                            echo $dashboard->totalOrders();
                            ?></p>
                            <hr />
                        </div>
                        <div class="home__cards--item px-2 py-2 card">
                            <p class="home__cards--title">Total Customers</p>
                            <p class="home__cards--count">
                                <?php
                                    echo $dashboard->totalCustomers();
                                ?>
                            </p>
                            <hr /> 
                        </div>
                    </div>
                <div class="orders">
                    <h2 class="my-3">Orders</h2>
                    <div class="table card my-3">
                        
                        <div class="table--heading mt-2">
                            <p class="table--heading--col1">Order</p>
                            <p class="table--heading--col2">
                                Date
                            </p>
                            <p class="table--heading--col3">Customer</p>
                            <p class="table--heading--col4">
                                Payment
                            </p>
                            <p class="table--heading--col5">
                                Fulfillment
                            </p>
                            <p class="table--heading--col6">
                                <i class="fas fa-toggle-on"></i>
                            </p>
                            <p class="table--heading--col7">Total</p>
                            <p class="table--heading--col8">Confirmation</p>
                        </div>
                        <form action="dashboard" method="post">
                            <?php foreach ($orders as $order): ?>
                            <div class="table--items">
                                <a href="#" class="table--items--col1 table--items--transactionId">#<?= $order['order_id'] ?></a>
                                <p class="table--items--col2"><?= date('M d, g:ia', strtotime($order['created_at'])) ?></p>
                                <p class="table--items--col3"><?= $order['username'] ?></p>
                                <p class="table--items--col4">
                                    <span class="badge-paid px-1" style="padding-bottom:3px;">
                                        <?= $order['status']?>
                                    </span>
                                </p>
                                <p class="table--items--col5">Completed</p>
                                <p class="table--items--col6">
                                    
                                    <i
                                        class="table--items--indicator indicator-completed fas fa-circle"
                                    ></i>
                                </p>
                                <p class="table--items--col7"> DT <?= number_format($order['total_price'], 2) ?></p>
                                <div>
                                <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                                <input type="hidden" name="username" value="<?= $order['username'] ?>">
                                <button type="submit" name="send_confirmation_email" class="btn btn-secondary m-2 mt-1 table--items--col8">Send Confirmation</button>
                                </div>
                             </div>
                             <?php endforeach; ?>
                        </form>
                        <div
                            class="paginate dflex justify-content-between align-items-center mt-2"
                        >
                            <i class="paginate__icon fas fa-angle-left"></i>
                            <p class="paginate__text">Page 1 of 1</p>
                            <i class="paginate__icon fas fa-angle-right"></i>
                        </div>
                    </div>
                </div>
                <div class="collections-create">
                    <div
                        class="collections-create__titlebar dflex justify-content-between align-items-center"
                    >
                        <div class="collections-create__titlebar--item">
                            <h1 class="my-1">Create Category</h1>
                        </div>
                        
                    </div>
                <form action="dashboard" method="POST" enctype="multipart/form-data">
                    <div class="collections-create__cardWrapper mt-2">
                        <div class="collections-create__main">
                            <div
                                class="collections-create__main--addInfo card py-2 px-2 bg-white"
                            >
                                <p class="mb-1">Title</p>
                                <input
                                    type="text"
                                    placeholder="e.g. Desktop"
                                    class="input"
                                    name="category_title"
                                />
                                <p class="my-1">Description (optional)</p>
                                <textarea
                                    cols="30"
                                    rows="10"
                                    class="textarea"
                                    name="category_description"
                                ></textarea>
                            </div>
                        </div>
                        <div class="collections-create__sidebar">
                            <div class="card py-2 px-2 bg-white">
                                <h3>Category Image</h3>
                                <div
                                    class="collections-create__sidebar--form mt-2"
                                >
                                    <label
                                        class="collections-create__sidebar--form--label"
                                        for="myfile"
                                        >Add Image</label
                                    >
                                    <input
                                        class="collections-create__sidebar--form--input"
                                        type="file"
                                        id="myfile"
                                        name="category_image"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="collections__edit">
                    <div
                        class="collections__edit__titlebar dflex justify-content-between align-items-center"
                    >
                        
                        <div class="collections__edit__titlebar--item">
                            
                            <button class="btn btn-secondary m-2 mt-2" name="save_category">Save</button>
                        </div>
                    </div>
                </div>
            </form>
                <div class="categories">
                    <div
                        class="collections__titlebar dflex justify-content-between align-items-center"
                    >
                        <div class="collections__titlebar--item">
                            <h1 class="my-1">Category</h1>
                        </div>
                    </div>
                    <div class="table card my-3">
                        <div class="table--filter mb-2">
                            <span
                                class="table--filter--collapseBtn orders__table--filter--collapseBtn"
                            >
                                <i class="fas fa-ellipsis-h"></i>
                            </span>
                            <div
                                class="table--filter--listWrapper orders__table--filter--listWrapper"
                            >
                                <ul
                                    class="table--filter--list list-unstyled dflex justify-content-start"
                                >
                                    <li>
                                        <p
                                            class="table--filter--link table--filter--link--active"
                                        >
                                            All Category
                                        </p>
                                    </li>   
                                </ul>
                            </div>
                        </div>
                        <div class="table--heading collections__heading">
                            <p class="table--heading--col1">Title</p>
                            <p class="table--heading--col2 ">
                                Description
                            </p>
                            <p
                                class="table--heading--col3 collections__heading--count"
                            >
                                Image
                            </p>
                            <p class="table--heading--col4">Actions</p>
                        </div>
                        <div class="table--items collections__items">
                            <?php foreach ($categories as $category) : ?>
        
                        <p class="table--items--col3">
                            <?php echo $category['category_title'];  ?>
                        </p>
                        <p class="table--items--col2">
                            <?php echo substr($category['category_description'], 0, 100) . '...'; ?>
                        </p>
                    
                        <div class="collections__items--imgWrapper">
                        <?php
                            $imageData = base64_encode($category['category_image']);
                            echo '<img class="collections__items--img" src="data:image/jpeg;base64,' . $imageData . '" alt="' . $category['category_title'] . '" />';
                            ?>
                        </div>
                        <div>
                        <form action="dashboard" method="post">
                            
                            <a target="_blank" class="btn-icon btn-icon-success" href="edit_category?category_id=<?php echo $category['category_id']; ?>" ><i class="far fa-edit"></i>
                            </a>  
                            <button class="btn-icon btn-icon-danger" name="delete_category" type="submit">
                                <i class="far fa-trash-alt"></i></button>
                            </button>
                            <input type="hidden" name="category_title" value="<?php echo $category['category_title']; ?>">
                            <input type="text" name="new_category_title" class="edit-fields" placeholder="New Title" required style="display: none;">
                             <textarea name="new_category_description" class="edit-fields" placeholder="New Description" required style="display: none;"></textarea>
                        </form>
                    </div>
                    <?php endforeach; ?>
                            </div>
                        <div class="paginate dflex justify-content-between align-items-center mt-2">
                            <i class="paginate__icon fas fa-angle-left" id="prevPageIcon"></i>
                            <p class="paginate__text" id="currentPageDisplay">Page <?php echo $currentPage; ?> of <?php echo $totalPages; ?></p>
                            <i class="paginate__icon fas fa-angle-right" id="nextPageIcon"></i>
                <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
                
                <script>
                    $(document).ready(function () {
                        var currentPage = <?php echo $currentPage; ?>;
                        var totalPages = <?php echo $totalPages; ?>;

                        function updateContent(page) {
                            $.ajax({
                                url: 'get_categories.php',
                                method: 'GET',
                                data: { page: page },
                                success: function (data) {
                                    $('.table--items.collections__items').empty();

                                    $('.table--items.collections__items').append(data);

    totalPages = $('#totalPages').val();

    $('#currentPageDisplay').text('Page ' + page + ' of ' + totalPages);

    $('#prevPageIcon').prop('disabled', page === 1);
    $('#nextPageIcon').prop('disabled', page === totalPages);
                                },
                                error: function () {
                                    console.error('Error fetching data');
                                }
                            });
                        }

                        $('#prevPageIcon').on('click', function () {
                            if (currentPage > 1) {
                                currentPage--;
                                updateContent(currentPage);
                            }
                        });

                        $('#nextPageIcon').on('click', function () {
                            if (currentPage < totalPages) {
                                currentPage++;
                                updateContent(currentPage);
                            }
                        });

                        $('#firstPage').on('click', function () {
                            currentPage = 1;
                            updateContent(currentPage);
                        });

                        $('#lastPage').on('click', function () {
                            currentPage = totalPages;
                            updateContent(currentPage);
                        });
                    });
                </script>
            </div>
        </div>
    </div>
                <div class="products">
                    <div class="products__list">
                        <div
                            class="products__titlebar dflex justify-content-between align-items-center mt-2"
                        >
                            <div class="products__titlebar--item">
                                <h1 class="my-1">Products</h1>
                            </div>
                            
                        </div>
                        <div class="table card my-3">
                            <div class="table--filter mb-2">
                                <span
                                    class="table--filter--collapseBtn orders__table--filter--collapseBtn"
                                >
                                    <i class="fas fa-ellipsis-h"></i>
                                </span>
                                <div
                                    class="table--filter--listWrapper orders__table--filter--listWrapper"
                                >
                                    <ul
                                        class="table--filter--list list-unstyled dflex justify-content-start"
                                    >
                                        <li>
                                            <p
                                                class="table--filter--link table--filter--link--active"
                                            >
                                                All Products
                                            </p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="table--search my-3">
    <div class="table--search--wrapper">
        <select class="table--search--select py-1 px-2" name="filter_criteria" id="filter_criteria">
            <option class="table--search--option" value="">Filter</option>
            <option class="table--search--option" value="category">Category</option>
        </select>
        <span class="table--search--select--arrow">
            <i class="fas fa-caret-down"></i>
        </span>
    </div>
    <div class="relative">
        <i class="table--search--input--icon fas fa-search absolute"></i>
        <input class="table--search--input py-1 pl-4 pr-2" type="text" placeholder="Search Products" id="search_term" />
    </div>
</div>

                            <div
                                class="table--heading mt-2 products__list__heading"
                            >
                                <!-- <p class="table--heading--col1">&#32;</p> -->
                                <p class="table--heading--col1">Title</p>
                                <p class="table--heading--col2">
                                    Description
                                </p>
                                <p class="table--heading--col3">Category</p>
                                <p class="table--heading--col4">
                                    Image
                                </p>
                                <p class="table--heading--col5">Price</p>
                                <p class="table--heading--col6">actions</p>
                            </div>
                            
                            <div class="table--items products__list__item">
                                <?php foreach ($products as $product) : ?>
                                <p class="table--items--col1">
                                <?php echo $product['product_title'];  ?>
                                </p>
                                <p class="table--items--col2">
                                <?php echo $product['product_description'];  ?>
                                </p>

                                <p class="table--items--col3">  
                                <?php $cat_id = $product['category_id'];
                                    echo $categoryModel->getCategoryTitleById($cat_id);
                                      ?>

                                </p>
                                <div class="products__items--imgWrapper table--items--col4">
                                     <?php
                                    $imageData = base64_encode($product['product_image']);
                                    echo '<img class="products__items--img" style="width:50px;" src="data:image/jpeg;base64,' . $imageData . '" alt="' . $product['product_title'] . '" />';
                                    ?>
                                </div>
                                <p class="table--items--col5">DT
                                <?php echo $product['product_price'];  ?>
                                </p>
                                
                                <div class="table--items--col6">
                                    <form action="dashboard" method="post">
                                    <a target="_blank" href="edit_product?product_id=<?php $product['product_id'];?>" class="btn-icon btn-icon-success">
                                        <i class="far fa-edit"></i>
                                    </a>
                                    <button class="btn-icon btn-icon-danger" name="delete_product" type="submit">
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                    <input type="hidden" name="product_title" value="<?php $product['product_title']?>">
                                    </form>
                                    
                                </div>
                                <?php endforeach; ?>
                            </div> 
                            <div class="paginate dflex justify-content-between align-items-center mt-2">
                            <i class="paginate__icon fas fa-angle-left" id="pprevPageIcon"></i>
                            <p class="paginate__text" id="pcurrentPageDisplay">Page <?php echo $currentPage ?> of <?php echo $productTotalPages  ; ?></p>
                            <i class="paginate__icon fas fa-angle-right" id="pnextPageIcon"></i>
                <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
                <script>
                  $(document).ready(function () {
    var currentPage = <?php echo $currentPage; ?>;
    var totalPages = <?php echo $productModel->getTotalPages($productitemsPerPage); ?>;

    function updateContent(page) {
        $.ajax({
            url: 'get_products.php',
            method: 'GET',
            data: { page: page },
            success: function (data) {
                $('.table--items.products__list__item').empty();

                $('.table--items.products__list__item').append(data);

                totalPages = $('#totalPages').val();

                $('#pcurrentPageDisplay').text('Page ' + page + ' of ' + totalPages);
                $('#pprevPageIcon').prop('disabled', page === 1);
                $('#pnextPageIcon').prop('disabled', page === totalPages);
            },
            error: function () {
                console.error('Error fetching data');
            }
        });
    }

    $('#pprevPageIcon').on('click', function () {
        if (currentPage > 1) {
            currentPage--;
            updateContent(currentPage);
        }
    });

    $('#pnextPageIcon').on('click', function () {
        if (currentPage < totalPages) {
            currentPage++;
            updateContent(currentPage);
        }
    });

    $('#firstPage').on('click', function () {
        currentPage = 1;
        updateContent(currentPage);
    });

    $('#lastPage').on('click', function () {
        currentPage = totalPages;
        updateContent(currentPage);
    });
});
                </script>
                        </div>
                    </div>
                    <form action="dashboard" method="POST" enctype="multipart/form-data">
                    <div class="products__create">
                        <div
                            class="products__create__titlebar dflex justify-content-between align-items-center"
                        >
                            <div class="products__create__titlebar--item">
                                
                                <h1 class="my-1">Add Product</h1>
                            </div>
                        </div>
                        <div class="products__create__cardWrapper mt-2">
                            <div class="products__create__main">
                                <div
                                    class="products__create__main--addInfo card py-2 px-2 bg-white"
                                >
                                    <p class="mb-1">Title</p>
                                    <input
                                        type="text"
                                        placeholder="e.g. XPS 17"
                                        class="input"
                                        name="product_title"
                                    />
                                    <p class="my-1">Description</p>
                                    <textarea
                                        cols="30"
                                        rows="10"
                                        class="textarea"
                                        name="product_description"
                                    ></textarea>
                                </div>
                                <div class="collections-create__sidebar">
                            <div class="card py-2 px-2 bg-white">
                                <h3>Product Image</h3>
                                <div
                                    class="collections-create__sidebar--form mt-2"
                                >
                                    <label
                                        class="collections-create__sidebar--form--label"
                                        for="myfile"
                                        >Add Image</label
                                    >
                                    <input
                                        class="collections-create__sidebar--form--input"
                                        type="file"
                                        id="myfile"
                                        name="product_image"
                                    />
                                </div>
                            </div>
                        </div>
                                <div
                                    class="products__create__main--pricing card py-2 px-2 bg-white mt-2"
                                >
                                    <h3 class="mb-2">Pricing</h3>

                                    <div
                                        class="products__create__main--pricing--col"
                                    >
                                        <div>
                                            <label for="price">Price</label>
                                            <input
                                                id="price"
                                                type="text"
                                                name="product_price"
                                                class="input mt-1"
                                                placeholder="DT 1.000"
                                            />
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="products__create__sidebar">
                                <div class="card py-2 px-2 bg-white">
                                    <h3>Category</h3>

                                    <div class="my-3">
                                        <p style="margin-bottom: 15px;">Product Type</p>
                                        <select class="inputSelect" name="product_category"  id="product_category">
                                        <?php
                                        $categories = $categoryModel->getCategoriesWithPagination(1, 100);
                                        foreach ($categories as $category) {
                                        echo "<option value='{$category['category_id']}'>{$category['category_title']}</option>";
            
                                        }
                                        ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="products__edit">
                        <div
                            class="dflex justify-content-between align-items-center my-3"
                        >
                            
                            <button class="btn btn-secondary" name="save_product">Save</button>
                        </div>
                    </div>
                    </form>
                    <div class="products__variant__edit">
                        <div
                            class="products__variant__edit__titlebar dflex justify-content-between align-items-center"
                        >
                            <div
                                class="products__variant__edit__titlebar--item"
                            >
                                <div class="breadcramb mt-3" onclick="scrollToProducts()">
                                    <i class="fas fa-angle-left mr-1"></i>Back
                                    to product
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="customers">
                    <div
                        class="customers__titlebar dflex justify-content-between align-items-center"
                    >
                        <div class="customers__titlebar--item">
                            
                            <h1 class="my-1">Customers</h1>
                        </div>
                    </div>
                    <div class="table card my-3">
                        <div class="table--filter mb-2">
                            <span class="table--filter--collapseBtn">
                                <i class="fas fa-ellipsis-h"></i>
                            </span>
                            <div class="table--filter--listWrapper">
                                <ul
                                    class="table--filter--list list-unstyled dflex justify-content-start"
                                >
                                    <li>
                                        <p
                                            class="table--filter--link table--filter--link--active"
                                        >
                                            All
                                        </p>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="table--search my-3">
                            <div class="table--search--wrapper">
                                <select
                                    class="table--search--select py-1 px-2"
                                    name=""
                                    id=""
                                >
                                    <option
                                        class="table--search--option"
                                        value=""
                                        >Email Subscription</option
                                    >
                                    <option
                                        class="table--search--option"
                                        value=""
                                        >Subscribed</option
                                    >
                                    <option
                                        class="table--search--option"
                                        value=""
                                        >Pending Confirmation</option
                                    >
                                    <option
                                        class="table--search--option"
                                        value=""
                                        >Not Subscribed</option
                                    >
                                </select>
                                <span class="table--search--select--arrow">
                                    <i class="fas fa-caret-down"></i>
                                </span>
                            </div>
                            <div class="relative">
                                <i
                                    class="table--search--input--icon fas fa-search absolute"
                                ></i>
                                <input
                                    class="table--search--input py-1 pl-4 pr-2"
                                    type="text"
                                    placeholder="Search Customers"
                                />
                            </div>
                        </div>
                        <div class="table--heading customers__heading">
                            <p class="table--heading--col1">Name</p>
                        </div>
                        <?php
                        $customers = $dashboard->viewCustomers();
                        ?>
                        <?php 
                        foreach ($customers as $customer){
                        echo '
                        <div class="table--items customers__items">
                            <div class="">
                                <h3>' . $customer["full_name"] . '</h3>
                                <p>' . $customer["email"] . '</p>
                            </div>
                            
                        </div>';} ?>
                        <div
                            class="paginate dflex justify-content-between align-items-center"
                        >
                            <i class="paginate__icon fas fa-angle-left"></i>
                            <p class="paginate__text">Page 1 of 1</p>
                            <i class="paginate__icon fas fa-angle-right"></i>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="/E-Commerce/includes/js/script.js"></script>
        <script>
        function scrollToProducts() {
            // Find the products section and scroll to it
            const productsSection = document.querySelector('.products');
            if (productsSection) {
                productsSection.scrollIntoView({ behavior: 'smooth' });
            }
        }
        function scrollToCategories() {
            // Find the products section and scroll to it
            const productsSection = document.querySelector('.categories');
            if (productsSection) {
                productsSection.scrollIntoView({ behavior: 'smooth' });
            }
        }
        function scrollToOrders() {
            // Find the products section and scroll to it
            const productsSection = document.querySelector('.orders');
            if (productsSection) {
                productsSection.scrollIntoView({ behavior: 'smooth' });
            }
        }
        function scrollToCustomers() {
            // Find the products section and scroll to it
            const productsSection = document.querySelector('.customers');
            if (productsSection) {
                productsSection.scrollIntoView({ behavior: 'smooth' });
            }
        }
        </script>
    </body>
</html>