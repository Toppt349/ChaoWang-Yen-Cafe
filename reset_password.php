<?php
session_start();

// กำหนดข้อมูลการเชื่อมต่อฐานข้อมูล
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "test_db";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";
$valid_token = false;
$username_for_reset = "";

// ตรวจสอบว่ามี token ใน URL หรือไม่
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // ดึงข้อมูลโทเค็นจากฐานข้อมูล
    $stmt = $conn->prepare("SELECT username, expires_at FROM password_resets WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($username_for_reset, $expires_at);
    $stmt->fetch();

    if ($stmt->num_rows > 0) {
        // ตรวจสอบว่าโทเค็นยังไม่หมดอายุ
        if (strtotime($expires_at) > time()) {
            $valid_token = true; // โทเค็นถูกต้องและยังไม่หมดอายุ
        } else {
            $message = "<span style='color: red;'>ลิงก์รีเซ็ตรหัสผ่านหมดอายุแล้ว. โปรดขอลิงก์ใหม่.</span>";
        }
    } else {
        $message = "<span style='color: red;'>ลิงก์รีเซ็ตรหัสผ่านไม่ถูกต้อง.</span>";
    }
    $stmt->close();
} else {
    $message = "<span style='color: red;'>ไม่พบโทเค็นสำหรับการรีเซ็ตรหัสผ่าน.</span>";
}

// ถ้าโทเค็นถูกต้อง และมีการส่งข้อมูลจากฟอร์มเพื่อตั้งรหัสผ่านใหม่
if ($valid_token && $_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST['new_password'];
    $confirm_new_password = $_POST['confirm_new_password'];

    if ($new_password !== $confirm_new_password) {
        $message = "<span style='color: red;'>รหัสผ่านใหม่และยืนยันรหัสผ่านใหม่ไม่ตรงกัน!</span>";
    } 
    else if (strlen($new_password) < 6) {
        $message = "<span style='color: red;'>รหัสผ่านใหม่ต้องมีความยาวอย่างน้อย 6 ตัวอักษร!</span>";
    }
    else {
        // เข้ารหัสรหัสผ่านใหม่
        $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);

        // อัปเดตรหัสผ่านในตาราง users
        $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
        $update_stmt->bind_param("ss", $hashed_new_password, $username_for_reset);

        if ($update_stmt->execute()) {
            // ลบโทเค็นออกจากตาราง password_resets เพื่อไม่ให้ใช้ซ้ำ
            $delete_token_stmt = $conn->prepare("DELETE FROM password_resets WHERE token = ?");
            $delete_token_stmt->bind_param("s", $token);
            $delete_token_stmt->execute();
            $delete_token_stmt->close();

            $message = "<span style='color: green;'>รีเซ็ตรหัสผ่านสำเร็จแล้ว! คุณสามารถเข้าสู่ระบบด้วยรหัสผ่านใหม่ได้ทันที.</span>";
            // เราอาจจะเปลี่ยนสถานะ valid_token เป็น false เพื่อไม่ให้ฟอร์มแสดงซ้ำ
            $valid_token = false; 

            // เปลี่ยนเส้นทางไปหน้า Login หลังรีเซ็ตสำเร็จ (ตัวเลือก)
            // header("Refresh: 5; url=login.html"); // เปลี่ยนเส้นทางใน 5 วินาที
            // exit();
        } else {
            $message = "<span style='color: red;'>เกิดข้อผิดพลาดในการรีเซ็ตรหัสผ่าน: " . $conn->error . "</span>";
        }
        $update_stmt->close();
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รีเซ็ตรหัสผ่าน</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            flex-direction: column;
        }
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 90%;
            max-width: 450px;
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: bold;
        }
        .form-group input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1em;
        }
        .message {
            margin-bottom: 15px;
            font-weight: bold;
        }
        .btn-submit {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.2s ease;
            width: 100%;
        }
        .btn-submit:hover {
            background-color: #0056b3;
        }
        .back-link {
            margin-top: 20px;
        }
        .back-link a {
            color: #007bff;
            text-decoration: none;
        }
        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>รีเซ็ตรหัสผ่าน</h2>
        <?php if (!empty($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>

        <?php if ($valid_token): // แสดงฟอร์มเฉพาะเมื่อโทเค็นถูกต้อง ?>
            <p>กรุณาป้อนรหัสผ่านใหม่สำหรับผู้ใช้: <strong><?php echo htmlspecialchars($username_for_reset); ?></strong></p>
            <form action="reset_password.php?token=<?php echo htmlspecialchars($token); ?>" method="POST">
                <div class="form-group">
                    <label for="new_password">รหัสผ่านใหม่:</label>
                    <input type="password" id="new_password" name="new_password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_new_password">ยืนยันรหัสผ่านใหม่:</label>
                    <input type="password" id="confirm_new_password" name="confirm_new_password" required>
                </div>
                <button type="submit" class="btn-submit">ตั้งรหัสผ่านใหม่</button>
            </form>
        <?php endif; ?>

        <div class="back-link">
            <a href="login.html">กลับไปหน้าเข้าสู่ระบบ</a>
        </div>
    </div>
</body>
</html>