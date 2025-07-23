<?php
// กำหนดข้อมูลการเชื่อมต่อฐานข้อมูล
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "register"; // ต้องเป็นฐานข้อมูลเดียวกับที่ใช้ใน process_login.php

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username_db, $password_db);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// สร้างฐานข้อมูลหากยังไม่มี (โค้ดซ้ำจาก process_login.php เพื่อให้มั่นใจว่ามีฐานข้อมูล)
$sql_create_db = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql_create_db) === TRUE) {
    // เลือกฐานข้อมูลที่เราเพิ่งสร้างหรือมีอยู่แล้ว
    $conn->select_db($dbname);

    // สร้างตาราง users หากยังไม่มี (โค้ดซ้ำจาก process_login.php เพื่อให้มั่นใจว่ามีตาราง)
    $sql_create_table = "CREATE TABLE IF NOT EXISTS users (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(30) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";

    if ($conn->query($sql_create_table) === TRUE) {
        // echo "Database and table checked/created successfully.<br>";
    } else {
        echo "Error creating table: " . $conn->error . "<br>";
    }
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}


// ตรวจสอบว่ามีการส่งข้อมูลแบบ POST มาหรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // ตรวจสอบว่ารหัสผ่านตรงกันหรือไม่
    if ($password !== $confirm_password) {
        echo "<h2>รหัสผ่านและการยืนยันรหัสผ่านไม่ตรงกัน.</h2>";
        $conn->close();
        exit(); // หยุดการทำงาน
    }

    // ตรวจสอบว่าชื่อผู้ใช้ซ้ำหรือไม่
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<h2>ชื่อผู้ใช้นี้มีอยู่ในระบบแล้ว. กรุณาเลือกชื่อผู้ใช้อื่น.</h2>";
    } else {
        // เข้ารหัสรหัสผ่านก่อนบันทึกลงฐานข้อมูล
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // เตรียมคำสั่ง SQL สำหรับเพิ่มผู้ใช้ใหม่
        $stmt_insert = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt_insert->bind_param("ss", $username, $hashed_password);

        if ($stmt_insert->execute()) {
            echo "<h2>สมัครสมาชิกสำเร็จ!</h2>";
            echo "<p><a href='login.html'>กลับไปหน้าเข้าสู่ระบบ</a></p>";
        } else {
            echo "<h2>เกิดข้อผิดพลาดในการสมัครสมาชิก: " . $stmt_insert->error . "</h2>";
        }
        $stmt_insert->close();
    }
    $stmt->close();
} else {
    echo "<h2>กรุณากรอกข้อมูลสมัครสมาชิกผ่านฟอร์ม.</h2>";
}

$conn->close();
?>