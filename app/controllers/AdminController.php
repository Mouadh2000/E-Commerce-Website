<?php 
require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/app/models/AdminModel.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/app/models/UserModel.php";


require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class Authentication{
    private $adminModel;

    public function __construct(AdminModel $adminModel)
    {
        $this->adminModel = $adminModel;
    }
    public function login(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){

            $email = $_POST['adminemail'];
            $password = $_POST['adminpassword'];
            $isLoggedIn = $this->adminModel->checkLogin($email, $password);
            if ($isLoggedIn) {
                session_start();
                $_SESSION['admin'] = $isLoggedIn;
                header("Location:/E-Commerce/admin/dashboard");
                exit();
            } else {
                echo '<script>alert("Invalid email or password")</script>';
            }
        }

    }
    public function logout(){
        session_start();
        if (isset($_SESSION['admin'])) {
            $_SESSION = array();
            session_destroy();
        }
        $this->redirectTologin();
    }
    private function redirectTologin(){
        header('Location: /E-Commerce/admin/login');
    }
    
}
class Dashboard{
    private $adminModel;
    public function __construct(AdminModel $adminModel)
    {
        $this->adminModel = $adminModel;
    }
    public function totalOrders(){
        return $totalOrders = $this->adminModel->totalOrders();
    }
    public function totalCustomers(){
        return $totalCustomers = $this->adminModel->totalCustomers();
    }
    public function viewCustomers(){
        return $customer = $this->adminModel->viewCustomers();;
    }
    public function totalsales(){
        return $totalsales = $this->adminModel->totalSales();
    }

    public function sendConfirmationEmail($username, $orderId) {
        
        return $status = $this->sendConfirmationEmailToUser($username, $orderId);

    }
    private function sendConfirmationEmailToUser($username, $orderId) {
        $usermodel = new UserModel();
        $userEmail = $usermodel->getUserEmail($username);
        $subject = 'Order Confirmation';
        $message = "Thank you for your order (Order ID: $orderId).\n";
        $mail = new PHPMailer(true);
        $SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.office365.com'; 
            $mail->SMTPAuth = true;
            $mail->Username = 'mouadh.project@outlook.com'; 
            $mail->Password = ''; 
            
            $mail->Port = 587;
            $mail->setFrom($mail->Username,);
            $mail->addAddress($userEmail); 
            $mail->isHTML(true);
            $mail->Subject = 'Order Confirmation';
            $mail->Body = "Thank you for your order (Order ID: $orderId).\nYour order will be delivered soon.";
            $mail->send();

            echo '<script>alert("Email sent successfully")</script>';
            return true;
        } catch (Exception $e) {
            echo '<script>alert("Error sending email: ' . $mail->ErrorInfo . '")</script>';
        }
    }
    public function viewOrderDetails() {
        return $orderDetails = $this->adminModel->getOrderDetails();

    }

}