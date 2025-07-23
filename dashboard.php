<?php
session_start(); // ต้องเรียกใช้เป็นบรรทัดแรกสุดเสมอเพื่อใช้งาน Session

// ตรวจสอบว่าผู้ใช้ล็อกอินอยู่หรือไม่
// ถ้าไม่มี $_SESSION['logged_in'] หรือค่าไม่เป็น true แสดงว่ายังไม่ได้ล็อกอิน
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // เปลี่ยนเส้นทางกลับไปหน้า login.html
    header("Location: login.html");
    exit(); // หยุดการทำงานของสคริปต์
}

// ถ้าถึงตรงนี้ได้ แสดงว่าผู้ใช้ล็อกอินอยู่แล้ว
// เราสามารถดึงชื่อผู้ใช้จาก Session มาแสดงได้
$username = htmlspecialchars($_SESSION['username']); // ใช้ htmlspecialchars เพื่อป้องกัน XSS
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หน้าแดชบอร์ด</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e6f7ff; /* สีฟ้าอ่อน */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            flex-direction: column; /* จัดองค์ประกอบแนวตั้ง */
        }
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 80%;
            max-width: 500px;
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
        p {
            color: #555;
            font-size: 1.1em;
        }
        .button-group { /* เพิ่ม Style สำหรับกลุ่มปุ่ม */
            margin-top: 25px;
            display: flex;
            gap: 15px; /* ระยะห่างระหว่างปุ่ม */
            justify-content: center;
            flex-wrap: wrap; /* ให้ปุ่มขึ้นบรรทัดใหม่ได้ถ้าหน้าจอเล็ก */
        }
        .logout-btn, .action-btn { /* รวม Style สำหรับปุ่ม Logout และปุ่ม Action อื่นๆ */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s ease;
        }
        .logout-btn {
            background-color: #dc3545; /* สีแดงสำหรับปุ่ม Logout */
        }
        .logout-btn:hover {
            background-color: #c82333;
        }
        .action-btn {
            background-color: #007bff; /* สีฟ้าสำหรับปุ่มแก้ไขรหัสผ่าน */
        }
        .action-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>ยินดีต้อนรับสู่แดชบอร์ดของคุณ, **<?php echo $username; ?>**!</h2>
        <p>คุณเข้าสู่ระบบสำเร็จแล้ว นี่คือหน้าสำหรับสมาชิกเท่านั้น</p>
        <p>คุณสามารถเพิ่มเนื้อหาหรือฟังก์ชันการทำงานอื่นๆ สำหรับผู้ใช้ที่ล็อกอินแล้วได้ที่นี่</p>
        
        <div class="button-group">
            <a href="change_password.php" class="action-btn">แก้ไขรหัสผ่าน</a>
            <a href="logout.php" class="logout-btn">ออกจากระบบ</a>
            </div>
    </div>
</body>
</html>