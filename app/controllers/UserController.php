<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/app/models/UserModel.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/app/controllers/CartController.php";

class Register{
    private $registrationData = [];
    
    public function fullName($fullname){
        if (!preg_match('/^[a-zA-Z]/', $fullname)) {
            echo '<script>alert("fullname should only have letters")</script>';
            return false;
        }
        if (str_contains($fullname, ' ') == false) {
            echo '<script>alert("fullname should have a space")</script>';
            return false;
        }
        return true;
    }
    public function username($username){
        if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
            echo '<script>alert("username should only have letters or number")</script>';
            return false;
        }
        return true;
    }
    public function email($email){
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    public function password($password){
        if (strlen($password) < 8){
            echo '<script>alert("password length should be bigger than 8")</script>'; 
            return false;
        }
        if(preg_match('/[A-Z]/', $password) < 1){
            echo '<script>alert("password should have at least one uppercase character")</script>';
            return false;
        }
        if(preg_match('/[0-9]/', $password) <1){
            echo 'script>alert("password should have at least one number")</script>';
            return false;
        }
        if(preg_match('/[^a-zA-Z0-9]/', $password) < 1){
            echo '<script>alert("password should have at least one special characters")</script>';
            return false;
        }
        return true;
    }
    public function handleRegistration() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['form_type'] == 'register') {
            $username = $_POST['username'] ?? '';
            $fullname = $_POST['fullname'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $rpassword = $_POST['rpassword'] ?? '';

            $this->registrationData = [
                'username' => $username,
                'fullname' => $fullname,
                'email' => $email,
                'password' => $password,
                'rpassword' => $rpassword,
            ];
            
            if ($this->registrationData['password'] !== $this->registrationData['rpassword']){
                echo '<script>alert("password do not match")</script>';
                return false; 
            }
            if ($this->isEmailUsed($this->registrationData['email'])) {
                echo '<script>alert("Email is already used")</script>';
                return false;
            }
            if(Register::fullName($this->registrationData['fullname']) && Register::username($this->registrationData['username'])
            && Register::email($this->registrationData['email']) && Register::password($this->registrationData['password'])){
                return true;
            } else {
                return false;
            }
        }
    }
    public function isValid(){
        if (Register::handleRegistration()){
            $usermodel = new UserModel();
            $usermodel->saveUser($this->registrationData['fullname'], $this->registrationData['username'],
            $this->registrationData['email'], $this->registrationData['password'] );
            echo '<script>
            alert("User Successfully Registered");
            window.location.href = "/E-Commerce/user/login";
        </script>';
            return false;
        }
    }
    private function isEmailUsed($email) {
        $userModel = new UserModel();
        return $userModel->isEmailUsed($email); 
    
    }
   
}
    
class Authentication{
        private $userModel;
        public function __construct(UserModel $userModel) {
            $this->userModel = $userModel;

        }
        public function login() {
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['form_type'] == 'login') {
                $email = $_POST['logemail'];
                $password = $_POST['logpassword'];
    
                if ($this->isBruteForceAttack($email)) {
                    echo '<script>alert("Too many login attempts. Try again later.")</script>';
                    return;
                }
    
                $isLoggedIn = $this->userModel->checkLogin($email, $password);
    
                if ($isLoggedIn) {
                    $this->resetLoginAttempts($email);
    
                    session_start();
                    $_SESSION['user_id'] = $isLoggedIn;
                    $this->redirectTohome();
                    exit();
                } else {
                   
                    $this->incrementLoginAttempts($email);
    
                    echo '<script>alert("Invalid email or password")</script>';
                }
            }
        }
        public function logout(){
            session_start();
            if (isset($_SESSION['user_id'])) {
                $_SESSION = array();
                session_destroy();
            }
            $this->redirectTologin();
        }
        private function redirectTologin(){
            header('Location: /E-Commerce/user/login');
        }
        private function redirectTohome(){
            header('Location:/E-Commerce/');
        }
        private function isBruteForceAttack($email) {
            
    
            $maxAttempts = 5; 
            $lockoutDuration = 60; 
    
            $loginAttempts = $this->userModel->getLoginAttempts($email);
    
            if ($loginAttempts >= $maxAttempts) {
                $lastAttemptTime = $this->userModel->getLastAttemptTime($email);
    
                if (time() - $lastAttemptTime < $lockoutDuration) {
                    return true; 
                } else {
                    $this->resetLoginAttempts($email);
                }
            }
    
            return false;
        }
        private function incrementLoginAttempts($email) {
            $this->userModel->incrementLoginAttempts($email);
        }
    
        private function resetLoginAttempts($email) {
            $this->userModel->resetLoginAttempts($email);
        }
        public function profile(){
            
            if(isset($_SESSION['user_id'])){
                $userId = $_SESSION['user_id'];
                return $this->userModel->getUserById($userId);
                
            } else {
            $this->redirectTohome();
            }
        }
        public function getLoggedInUserId() {
            session_start();
            return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : false;
        }

}

