<?php
// =========================================================
// ไฟล์เชื่อมต่อฐานข้อมูลสำหรับเซิร์ฟเวอร์ Angsila (cs.buu.ac.th)
// =========================================================

$host     = "localhost";      // phpMyAdmin แสดงว่าเป็น Server: localhost
$username = "s67160214";      // รหัสนิสิต (ตามชื่อฐานข้อมูลในภาพ)
$password = "W5xPdkn9";  // ใส่รหัสผ่าน MySQL ของนิสิตที่ตั้งไว้
$dbname   = "s67160214";      // ชื่อฐานข้อมูลตามรูป phpMyAdmin
$charset  = "utf8mb4";

// ---------------------------------------------------------
// แบบที่ 1: เชื่อมต่อด้วย PDO (แนะนำ ปลอดภัย ป้องกัน SQL Injection)
// ---------------------------------------------------------
try {
    $dsn = "mysql:host={$host};dbname={$dbname};charset={$charset}";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    
    $pdo = new PDO($dsn, $username, $password, $options);
    // Uncomment บรรทัดด้านล่างเพื่อทดสอบการเชื่อมต่อ
    // echo "เชื่อมต่อฐานข้อมูล Angsila สำเร็จ (PDO)!";
    
} catch (PDOException $e) {
    die("เกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล (PDO): " . $e->getMessage());
}

/*
// ---------------------------------------------------------
// แบบที่ 2: เชื่อมต่อด้วย MySQLi (แบบ Procedural / OOP)
// ---------------------------------------------------------
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("เกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล (MySQLi): " . $conn->connect_error);
}

$conn->set_charset($charset);
// echo "เชื่อมต่อฐานข้อมูล Angsila สำเร็จ (MySQLi)!";
*/
?>
