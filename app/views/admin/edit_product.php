<?php 
require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/index.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/app/controllers/CategoryController.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/app/controllers/ProductController.php";
session_start();
if (!isset($_SESSION['admin'])) {
    // Redirect to the login page
    header("Location: /E-Commerce/admin/login");
    exit();
}

$productController = new ProductController(new ProductModel(), new CategoryModel());



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/E-Commerce/assets/css/bootstrap.css">
    <link rel="stylesheet" href="/E-Commerce/assets/css/backup.css">
    <link rel="stylesheet" href="/E-Commerce/assets/css/font-awesome.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.css" integrity="sha384..." crossorigin="anonymous">
    <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.css"
    />
    

    
    <title>DELL Shop</title>
</head>
<body>
<h1 class="text-center  mt-5 mb-2 py-3">Edit Product </h1>

    <div class="container">
        <div class="row">
            <div class="col-8 mx-auto">
            <?php 
if (isset($_POST['update_product'])) {
  $product_id = $_GET['product_id'];
  $newTitle = $_POST['new_product_title'];
  $newPrice = $_POST['new_product_price'];
  $newDescription = $_POST['new_product_description'];
  $newquantity = $_POST['new_product_quantity'];

  $newImage = null;
  if (!empty($_FILES['new_product_image']['tmp_name'])) {
      $newImage = file_get_contents($_FILES['new_product_image']['tmp_name']);
      
  }

  if($productController->updateProduct($product_id, $newTitle, $newDescription, $newPrice, $newquantity, $newImage)){


                    echo '<h3 class="alert alert-success text-center">Product Updated Successfully</h3>';
  }
               else{
                    echo'<h3 class="alert alert-danger text-center">Product Update faill</h3>';
               }
              }         
        ?>
                <form class="p-5 border mb-5" method="POST" action="" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" required value="" name="new_product_title" class="form-control" id="name" autocomplete="off" >
                    </div>
                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="text" required class="form-control" value="" name="new_product_price" id="price">
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <input type="text" required class="form-control" value="" name="new_product_description" id="description">
                    </div>

                    <div class="form-group">
                        <label for="qty">Stock Quantity</label>
                        <input type="number" required class="form-control" name="new_product_quantity" id="qty">
                    </div>
            
                    <input type="file" class="custom-file-input mt-2" id="imageUpload" name="new_product_image">
                    <button type="submit" name="update_product" class="btn btn-primary mt-5">Submit</button>
                </form>
                            
            </div>
        </div>
    </div>

    </body>
<script src="/E-Commerce/assets/js/bootstrap.js"></script>
<script src="/E-Commerce/assets/js/bootstrap.bundle.js"></script>
<script type="module" src="/E-Commerce/assets/js/bootstrap.esm.js"></script>

</html>
<style>
    /* Custom styling for the file input */
    .custom-file-input {
      color: transparent;
    }

    .custom-file-input::-webkit-file-upload-button {
      visibility: hidden;
    }

    .custom-file-input::before {
      content: 'Choose Image';
      display: inline-block;
      background: #007bff;
      border: 1px solid #007bff;
      color: #fff;
      border-radius: 5px;
      padding: 8px 12px;
      outline: none;
      white-space: nowrap;
      cursor: pointer;
    }

    .custom-file-input:hover::before {
      border-color: #0056b3;
    }

    .custom-file-input:active::before {
      background: #0056b3;
    }
  </style>