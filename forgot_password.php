<?php
session_start();

// กำหนดข้อมูลการเชื่อมต่อฐานข้อมูล
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "register";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username_or_email = $_POST['username_or_email'];

    // ตรวจสอบว่ามี username นี้อยู่ในระบบหรือไม่
    $stmt = $conn->prepare("SELECT id, username FROM users WHERE username = ?");
    $stmt->bind_param("s", $username_or_email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id, $username_found);
    $stmt->fetch();

    if ($stmt->num_rows > 0) {
        // พบผู้ใช้! สร้างโทเค็นและบันทึกลงฐานข้อมูล
        $token = bin2hex(random_bytes(32)); // สร้างโทเค็นแบบสุ่ม 64 ตัวอักษร
        $expires = date("Y-m-d H:i:s", strtotime('+1 hour')); // โทเค็นหมดอายุใน 1 ชั่วโมง

        // ลบโทเค็นเก่าของผู้ใช้นี้ออกก่อน เพื่อไม่ให้มีหลายโทเค็นค้าง
        $delete_old_stmt = $conn->prepare("DELETE FROM password_resets WHERE username = ?");
        $delete_old_stmt->bind_param("s", $username_found);
        $delete_old_stmt->execute();
        $delete_old_stmt->close();

        // บันทึกโทเค็นใหม่ลงในตาราง password_resets
        $insert_stmt = $conn->prepare("INSERT INTO password_resets (username, token, expires_at) VALUES (?, ?, ?)");
        $insert_stmt->bind_param("sss", $username_found, $token, $expires);

        if ($insert_stmt->execute()) {
            // *** ตรงนี้คือส่วนที่คุณจะต้องใช้ระบบส่งอีเมลจริง ๆ ***
            // แต่เนื่องจากเราไม่ส่งอีเมลจริง เราจะแสดงลิงก์ให้เห็นแทน
            $reset_link = "http://localhost/reset_password.php?token=" . $token;
            $message = "<span style='color: green;'>เราได้สร้างลิงก์รีเซ็ตรหัสผ่านให้คุณแล้ว (ลิงก์หมดอายุใน 1 ชั่วโมง)<br></span>";
            $message .= "<span style='font-weight: bold;'>โปรดคัดลอกลิงก์นี้:</span> <br>";
            $message .= "<input type='text' value='" . htmlspecialchars($reset_link) . "' style='width: 90%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; margin-top: 10px;' onclick='this.select();' readonly><br><br>";
            $message .= "<a href='" . htmlspecialchars($reset_link) . "' class='action-btn' style='display: inline-block; margin-top: 10px;'>คลิกเพื่อรีเซ็ตรหัสผ่าน</a>";

        } else {
            $message = "<span style='color: red;'>เกิดข้อผิดพลาดในการสร้างลิงก์รีเซ็ต: " . $conn->error . "</span>";
        }
        $insert_stmt->close();
    } else {
        $message = "<span style='color: red;'>ไม่พบชื่อผู้ใช้ในระบบ.</span>";
    }
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ลืมรหัสผ่าน?</title>
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
        .form-group input[type="text"] {
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
        .action-btn { /* Style สำหรับปุ่มที่แสดงลิงก์รีเซ็ต */
            background-color: #28a745; /* สีเขียว */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s ease;
            margin-top: 15px;
        }
        .action-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>ลืมรหัสผ่าน?</h2>
        <?php if (!empty($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
        <form action="forgot_password.php" method="POST">
            <div class="form-group">
                <label for="username_or_email">ป้อนชื่อผู้ใช้ของคุณ:</label>
                <input type="text" id="username_or_email" name="username_or_email" required>
            </div>
            <button type="submit" class="btn-submit">ขอลิงก์รีเซ็ตรหัสผ่าน</button>
        </form>
        <div class="back-link">
            <a href="login.html">กลับไปหน้าเข้าสู่ระบบ</a>
        </div>
    </div>
</body>
</html>