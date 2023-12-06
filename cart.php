<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/app/views/common/header.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/app/controllers/CartController.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/app/controllers/ProductController.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/app/controllers/CategoryController.php";


$subtotal = 0;
$cartController = new CartController(new CartModel());
$cartContents = $cartController->viewCart();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['action']) && $_POST['action'] === 'remove') {
      $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
      $productId = isset($_POST['productId']) ? $_POST['productId'] : null;
      $cartModel = new CartModel();
      $cartModel->removeFromCart($userId, $productId);
      header('Location: /E-Commerce/cart');
      exit();
  }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if(isset($_POST['update_product_quantity'])){
    $update_value = $_POST['update_quantity'];
    $product_id = $_POST['update_quantity_id'];
    $cartController->updateQuantity($product_id, $update_value);
    $cartContents = $cartController->viewCart();
  }
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
  if(isset($_POST['action']) && $_POST['action'] == "checkout"){
    $cartController->processCheckout();
  }
}

if (empty($cartContents)) {
    echo '<div class="container-fluid  p-5">
        <div class="row" style="text-align:center;">
        
           <div class="col-md-5 mx-auto">
           
            <div class="card" style="background-color:#f8f8f8;">
               <div class="card-header">
               <h5 style="color:#0993ce;">Cart</h5>
            </div>
            <div class="card-body cart" style="background-color:#f8f8f8;">
                <div class="col-sm-12 empty-cart-cls text-center">
                    <img src="https://i.imgur.com/dCdflKN.png" width="130" height="130" class="img-fluid mb-4 mr-3">
                    <h4 style="color:#e72323">Your Cart is Empty</h4>
                    <a href="/E-Commerce/" class="btn btn-primary cart-btn-transform m-3" style="background-color:#0993ce;" data-abc="true">Start Shopping</a>        
                </div>
                </div>
            </div>
           </div>
        </div>
       </div>';
} else {
    
    echo '<section class="h-100 h-custom">
  <div class="container h-100 py-5" >
    <div class="row d-flex justify-content-center align-items-center h-100" >
      <div class="col">

        <div class="table-responsive" style="border-radius:10px;">
          <table class="table">
            <thead>
              <tr>
                <th scope="col" class="h5">Shopping Bag</th>
                <th scope="col" style="padding-left:30px;">Quantity</th>
                <th scope="col">Price</th>
                <th scope="col">Action</th>
              </tr>
            </thead>';
            foreach($cartContents as $item){
              $imageData = base64_encode($item['product_image']);

            echo' 
            <tbody>
              <tr>
                <th scope="row">
                  <div class="d-flex align-items-center">
                    <img src="data:image/jpeg;base64,' . $imageData . '" data-blob="data:image/jpeg;base64,' . $imageData . '" class="img-fluid rounded-3"
                      style="width: 120px;" alt="' . $item['product_title'] . '">
                      <div class="flex-column ms-4">
                      <p class="mb-2">' . $item['product_title'] . '</p>
                      
                  </div>
                  </div>
                </th>
                <td class="align-middle">
                  <form action="cart" method="post">
                    <input type="hidden" value=" '. $item['product_id'].'" name="update_quantity_id" >
                    <div classs="quantity_box">
                      <input type="number" min="1" value="' . $item['quantity'] .'" style="width: 35px;" name="update_quantity">
                      <input type="submit" name="update_product_quantity" class="update_quantity" value="Update" style="width: 68px;background-color:#000027ed;color:white;">
                    </div> 
                  </form>
                </td>
                <td class="align-middle" style="font-weight: 750;color:green;">
                 <p class="mb-0" id="price_' . $item['product_id'] . '" data-price="' . $item['product_price'] . '">TND ' . number_format($item['product_price'] *= $item['quantity'], 3) . '</p>
                </td>';
                $subtotal += $item['product_price'];
                echo '<td class="align-middle">
                  <div>
                    <form action="cart" method="POST">
                      <input type="hidden" name="action" value="remove">
                      <input type="hidden" name="productId" value=" ' . $item['product_id'] . ' ">
                      <button type="submit" class="btn-icon btn-icon-danger">
                        <i class="far fa-trash-alt"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>';
              
            }
              echo '
            </tbody>
          </table>
        </div>
        <div class="card shadow-2-strong mb-5 mb-lg-0" style="border-radius: 16px;">
          <div class="card-body p-4">

            <div class="row">
              <div class="col-md-6 col-lg-4 col-xl-3 mb-4 mb-md-0">
                <form>
                  <div class="d-flex flex-row pb-3">
                    <div class="d-flex align-items-center pe-2">
                      <input class="form-check-input" type="radio" name="radioNoLabel" id="radioNoLabel1v"
                        value="" aria-label="..." checked />
                    </div>
                    <div class="rounded border w-100 p-3">
                      <p class="d-flex align-items-center mb-0">
                        <i class="fab fa-cc-mastercard fa-2x text-dark pe-2"></i>Credit
                        Card
                      </p>
                    </div>
                  </div>
                  <div class="d-flex flex-row pb-3">
                    <div class="d-flex align-items-center pe-2">
                      <input class="form-check-input" type="radio" name="radioNoLabel" id="radioNoLabel2v"
                        value="" aria-label="..." />
                    </div>
                    <div class="rounded border w-100 p-3">
                      <p class="d-flex align-items-center mb-0">
                        <i class="fab fa-cc-visa fa-2x fa-lg text-dark pe-2"></i>Debit Card
                      </p>
                    </div>
                  </div>
                  <div class="d-flex flex-row">
                    <div class="d-flex align-items-center pe-2">
                      <input class="form-check-input" type="radio" name="radioNoLabel" id="radioNoLabel3v"
                        value="" aria-label="..." />
                    </div>
                    <div class="rounded border w-100 p-3">
                      <p class="d-flex align-items-center mb-0">
                      <i class="fa-solid fa-truck"></i> Delivery
                      </p>
                    </div>
                  </div>
                </form>
              </div>
              <div class="col-md-6 col-lg-4 col-xl-6">
                <div class="row">
                  <div class="col-12 col-xl-6">
                    <div class="form-outline mb-4 mb-xl-5">
                      <input type="text" id="typeName" class="form-control form-control-lg" siez="17"
                        placeholder="John Smith" />
                      <label class="form-label" for="typeName">Name on card</label>
                    </div>

                    <div class="form-outline mb-4 mb-xl-5">
                      <input type="text" id="typeExp" class="form-control form-control-lg" placeholder="MM/YY"
                        size="7" id="exp" minlength="7" maxlength="7" />
                      <label class="form-label" for="typeExp">Expiration</label>
                    </div>
                  </div>
                  <div class="col-12 col-xl-6">
                    <div class="form-outline mb-4 mb-xl-5">
                      <input type="text" id="typeText" class="form-control form-control-lg" siez="17"
                        placeholder="1111 2222 3333 4444" minlength="19" maxlength="19" />
                      <label class="form-label" for="typeText">Card Number</label>
                    </div>

                    <div class="form-outline mb-4 mb-xl-5">
                      <input type="password" id="typeass" class="form-control form-control-lg"
                        placeholder="&#9679;&#9679;&#9679;" size="1" minlength="3" maxlength="3" />
                      <label class="form-label" for="typeText">Cvv</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 col-xl-3">
                <div class="d-flex justify-content-between" style="font-weight: 500;">
                  <p class="mb-2">Subtotal</p>
                  <p class="mb-2" id="subtotalValue">TND ' . number_format($subtotal, 3) . '</p>
                </div>

                <div class="d-flex justify-content-between" style="font-weight: 500;">
                  <p class="mb-0">Shipping</p>
                  <p class="mb-0">TND 6.99</p>
                </div>
                <hr class="my-4">
                <div class="d-flex justify-content-between mb-4" style="font-weight: 750;color:red;">
                  <p class="mb-2">Total (tax included)</p>
                  <p class="mb-2" id="totalValue">'.number_format($subtotal + 6.99 + 30,3) .'</p>
                </div>
                <form action="" method="post">
                <input type="hidden" name="action" value="checkout">
                <input type="hidden" name="subtotal" value="'.$subtotal.'">
                <button type="submit" class="btn btn-block btn-lg" style="background-color:#e4a11b;">
                  <div class="d-flex justify-content-between" style="color:white;">
                    <span>Checkout</span>
                    <span id="checkoutTotal">TND '. number_format($subtotal + 6.99 +30, 3) .'</span>
                  </div>
                </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>';
}
?>


<?php require_once "app/views/common/footer.php"; ?>

