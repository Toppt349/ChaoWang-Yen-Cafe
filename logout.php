<?php
session_start(); // เริ่มต้น Session

// ลบตัวแปรทั้งหมดที่เก็บใน Session
$_SESSION = array();

// หากต้องการลบคุกกี้ Session ด้วย (สำคัญสำหรับการออกจากระบบที่สมบูรณ์)
// ตรวจสอบว่ามีการใช้คุกกี้สำหรับ Session หรือไม่
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// ทำลาย Session บนเซิร์ฟเวอร์
session_destroy();

// เปลี่ยนเส้นทางผู้ใช้กลับไปหน้า Login
header("Location: login.html");
exit();
?>