<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หน้าเข้าสู่ระบบ</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        .login-container h2 {
            margin-bottom: 20px;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        .form-group input[type="text"],
        .form-group input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .form-group input[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        .form-group input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .links-container { /* เพิ่ม CSS สำหรับคอนเทนเนอร์ของลิงก์ */
            margin-top: 20px;
            font-size: 14px;
            display: flex;
            flex-direction: column; /* จัดเรียงลิงก์ในแนวตั้ง */
            gap: 10px; /* ระยะห่างระหว่างลิงก์ */
        }
        .links-container a {
            color: #007bff;
            text-decoration: none;
        }
        .links-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>เข้าสู่ระบบ</h2>
        <form action="process_login.php" method="POST">
            <div class="form-group">
                <label for="username">ชื่อผู้ใช้:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">รหัสผ่าน:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <input type="submit" value="เข้าสู่ระบบ">
            </div>
        </form>
        
        <div class="links-container">
            <a href="register.html">สมัครสมาชิกใหม่</a>
            <a href="change_password.php">แก้ไขหัสผ่าน <a href="forgot_password.php">ลืมรหัสผ่าน?</a><a href="logout.php">ออกจากระบบ</a></a>
				
        </div>
        </div>
</body>
</html>
