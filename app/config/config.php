<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/E-Commerce/index.php";
?>
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dellshop";
try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
?>