<?php
session_start(); // เริ่มต้น Session ทุกครั้งที่ต้องการใช้งาน

// ตรวจสอบว่าผู้ใช้ล็อกอินอยู่หรือไม่
// นี่คือมาตรการความปลอดภัยพื้นฐาน เพื่อให้แน่ใจว่ามีเพียงผู้ที่ล็อกอินแล้วเท่านั้นที่สามารถเข้าถึงหน้านี้ได้
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.html"); // ถ้ายังไม่ได้ล็อกอิน ให้เปลี่ยนเส้นทางกลับไปหน้า Login
    exit(); // หยุดการทำงานของสคริปต์ทันที
}

// ดึงชื่อผู้ใช้จาก Session เพื่อใช้ในการระบุตัวตนผู้ที่ต้องการแก้ไขรหัสผ่าน
$username = htmlspecialchars($_SESSION['username']); 
$message = ""; // ตัวแปรสำหรับเก็บข้อความแจ้งเตือนผู้ใช้ (เช่น "แก้ไขสำเร็จ", "รหัสผ่านไม่ถูกต้อง")

// ตรวจสอบว่ามีการส่งข้อมูลแบบ POST มาหรือไม่ (คือเมื่อผู้ใช้กดปุ่ม "บันทึกรหัสผ่านใหม่" ในฟอร์ม)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // กำหนดข้อมูลการเชื่อมต่อฐานข้อมูล
    $servername = "localhost";
    $username_db = "root";
    $password_db = "";
    $dbname = "register"; // ชื่อฐานข้อมูลที่เราใช้

    // สร้างการเชื่อมต่อกับฐานข้อมูล
    // พารามิเตอร์ที่ 4 ($dbname) ทำให้เชื่อมต่อและเลือกฐานข้อมูลได้ในคราวเดียว
    $conn = new mysqli($servername, $username_db, $password_db, $dbname);

    // ตรวจสอบการเชื่อมต่อว่าสำเร็จหรือไม่
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // รับค่าที่ผู้ใช้กรอกมาจากฟอร์มผ่านเมธอด POST
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_new_password = $_POST['confirm_new_password'];

    // 1. ตรวจสอบความถูกต้องเบื้องต้นของรหัสผ่านใหม่
    // ตรวจสอบว่ารหัสผ่านใหม่และยืนยันรหัสผ่านใหม่ตรงกันหรือไม่
    if ($new_password !== $confirm_new_password) {
        $message = "<span style='color: red;'>รหัสผ่านใหม่และยืนยันรหัสผ่านใหม่ไม่ตรงกัน!</span>";
    } 
    // ตรวจสอบความยาวของรหัสผ่านใหม่ (สามารถเพิ่มเงื่อนไขอื่นๆ ได้ เช่น ต้องมีตัวเลข, ตัวอักษรใหญ่เล็ก, ตัวอักษรพิเศษ)
    else if (strlen($new_password) < 6) { // ตัวอย่าง: รหัสผ่านต้องมีความยาวอย่างน้อย 6 ตัวอักษร
        $message = "<span style='color: red;'>รหัสผ่านใหม่ต้องมีความยาวอย่างน้อย 6 ตัวอักษร!</span>";
    }
    else {
        // 2. ดึงรหัสผ่านปัจจุบันที่ "เข้ารหัสแล้ว" จากฐานข้อมูล
        // ใช้ Prepared Statement เพื่อป้องกัน SQL Injection
        $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username); // ผูกค่า $username เข้ากับเครื่องหมาย ? (s คือ string)
        $stmt->execute(); // ประมวลผลคำสั่ง SQL
        $stmt->store_result(); // เก็บผลลัพธ์เพื่อตรวจสอบจำนวนแถว
        $stmt->bind_result($hashed_password_from_db); // ผูกผลลัพธ์กับตัวแปร
        $stmt->fetch(); // ดึงข้อมูลแถวเดียว

        // ตรวจสอบว่าพบผู้ใช้หรือไม่
        if ($stmt->num_rows > 0) {
            // 3. ตรวจสอบว่ารหัสผ่านปัจจุบันที่ผู้ใช้กรอกมาถูกต้องหรือไม่
            // ใช้ password_verify() เพื่อเปรียบเทียบรหัสผ่านที่ป้อนกับรหัสผ่านที่เข้ารหัสในฐานข้อมูล
            if (password_verify($current_password, $hashed_password_from_db)) {
                // 4. ถ้าทุกอย่างถูกต้อง ให้เข้ารหัสรหัสผ่านใหม่
                $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);

                // 5. อัปเดตรหัสผ่านใหม่ในฐานข้อมูล
                $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
                $update_stmt->bind_param("ss", $hashed_new_password, $username); // ผูกค่ารหัสผ่านใหม่และชื่อผู้ใช้

                if ($update_stmt->execute()) {
                    $message = "<span style='color: green;'>แก้ไขรหัสผ่านสำเร็จแล้ว!</span>";
                    // **ข้อแนะนำด้านความปลอดภัยเพิ่มเติม:**
                    // หลังจากเปลี่ยนรหัสผ่านสำเร็จ อาจจะทำลาย Session ปัจจุบัน
                    // และบังคับให้ผู้ใช้ล็อกอินใหม่ เพื่อป้องกันกรณีที่มี Session ค้างอยู่บนอุปกรณ์อื่น
                    // session_destroy();
                    // header("Location: login.html?msg=password_changed");
                    // exit();
                } else {
                    $message = "<span style='color: red;'>เกิดข้อผิดพลาดในการแก้ไขรหัสผ่าน: " . $conn->error . "</span>";
                }
                $update_stmt->close(); // ปิด Prepared Statement สำหรับการอัปเดต
            } else {
                $message = "<span style='color: red;'>รหัสผ่านปัจจุบันไม่ถูกต้อง!</span>";
            }
        } else {
            // กรณีนี้ไม่ควรเกิดขึ้นถ้าผู้ใช้ล็อกอินอยู่แล้ว เพราะ username มาจาก Session
            $message = "<span style='color: red;'>ไม่พบผู้ใช้ในระบบ. (ข้อผิดพลาดภายใน)</span>";
        }
        $stmt->close(); // ปิด Prepared Statement สำหรับการเลือกข้อมูล
    }
    $conn->close(); // ปิดการเชื่อมต่อฐานข้อมูล
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขรหัสผ่าน</title>
    <style>
        /* CSS สำหรับจัดหน้าเว็บ ยังคงเหมือนเดิม */
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
            max-width: 400px;
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
        <h2>แก้ไขรหัสผ่านสำหรับผู้ใช้: <?php echo $username; ?></h2>
        <?php if (!empty($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
        <form action="change_password.php" method="POST">
            <div class="form-group">
                <label for="current_password">รหัสผ่านปัจจุบัน:</label>
                <input type="password" id="current_password" name="current_password" required>
            </div>
            <div class="form-group">
                <label for="new_password">รหัสผ่านใหม่:</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>
            <div class="form-group">
                <label for="confirm_new_password">ยืนยันรหัสผ่านใหม่:</label>
                <input type="password" id="confirm_new_password" name="confirm_new_password" required>
            </div>
            <button type="submit" class="btn-submit">บันทึกรหัสผ่านใหม่</button>
        </form>
        <div class="back-link">
            <a href="dashboard.php">กลับสู่หน้าแดชบอร์ด</a>
        </div>
    </div>
</body>
</html>