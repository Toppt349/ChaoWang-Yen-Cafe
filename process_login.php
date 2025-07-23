<?php
session_start(); // **เพิ่มบรรทัดนี้เพื่อเริ่มต้น Session**

// กำหนดข้อมูลการเชื่อมต่อฐานข้อมูล
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "register";

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username_db, $password_db);

// ตรวจสอบการเชื่อมต่อ (ส่วนนี้ยังคงเดิม)
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// สร้างฐานข้อมูลและตารางหากยังไม่มี (ส่วนนี้ยังคงเดิม)
$sql_create_db = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql_create_db) === TRUE) {
    $conn->select_db($dbname);
    $sql_create_table = "CREATE TABLE IF NOT EXISTS users (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(30) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    if ($conn->query($sql_create_table) === TRUE) {
        $check_user_sql = "SELECT * FROM users WHERE username = 'admin'";
        $result_check = $conn->query($check_user_sql);
        if ($result_check->num_rows == 0) {
            $hashed_password = password_hash("password123", PASSWORD_DEFAULT);
            $insert_user_sql = "INSERT INTO users (username, password) VALUES ('admin', '$hashed_password')";
            $conn->query($insert_user_sql);
        }
    }
}

// ตรวจสอบว่ามีการส่งข้อมูลแบบ POST มาหรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($hashed_password_from_db);
    $stmt->fetch();

    if ($stmt->num_rows > 0) {
        if (password_verify($password, $hashed_password_from_db)) {
            // **ส่วนที่แก้ไข/เพิ่มเติม: เก็บข้อมูล Session และเปลี่ยนเส้นทาง**
            $_SESSION['username'] = $username; // เก็บชื่อผู้ใช้ไว้ใน Session
            $_SESSION['logged_in'] = true;     // ตั้งค่าสถานะว่าล็อกอินแล้ว
            
            // เปลี่ยนเส้นทางไปยังหน้า dashboard.php
            header("Location:shop.html"); 
            exit(); // **สำคัญ: ต้องเรียก exit() เสมอหลังจาก header()**
        } else {
            echo "<h2>ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง.</h2>";
        }
    } else {
        echo "<h2>ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง.</h2>";
    }

    $stmt->close();
} else {
    echo "<h2>กรุณากรอกข้อมูลเข้าสู่ระบบผ่านฟอร์ม.</h2>";
}

$conn->close();
?>