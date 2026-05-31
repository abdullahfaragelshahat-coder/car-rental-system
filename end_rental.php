<?php
session_start();
require_once 'db.php';

if (!isset($_GET['id'])) {
    header("Location: active_rentals.php");
    exit();
}

$rental_id = $_GET['id'];

try {
    // 1. جلب بيانات الحجز (عشان نعرف الـ car_id و الـ customer_id)
    $stmt = $db->prepare("SELECT car_id, customer_id, total_price FROM rentals WHERE id = ?");
    $stmt->execute([$rental_id]);
    $rental = $stmt->fetch();

    if ($rental) {
        // 2. تحديث حالة الحجز إلى 'completed'
        $db->prepare("UPDATE rentals SET status = 'completed' WHERE id = ?")->execute([$rental_id]);

        // 3. إعادة السيارة لحالتها 'available'
        $db->prepare("UPDATE cars SET status = 'available' WHERE id = ?")->execute([$rental['car_id']]);

        // 4. نظام النقاط: إضافة النقاط للعميل (1 نقطة لكل 100 جنيه)
        $points = floor($rental['total_price'] / 100);
        $db->prepare("UPDATE customers SET points = points + ? WHERE id = ?")->execute([$points, $rental['customer_id']]);

        // 5. تسجيل العملية في الإشعارات
        $msg = "تم إنهاء الحجز رقم $rental_id وإضافة $points نقطة للعميل.";
        $db->prepare("INSERT INTO notifications_log (message, created_at) VALUES (?, NOW())")->execute([$msg]);

        echo "<script>alert('✅ تم تسليم السيارة بنجاح وتحديث نقاط العميل!'); window.location='active_rentals.php';</script>";
    }
} catch (PDOException $e) {
    echo "<script>alert('❌ خطأ: " . $e->getMessage() . "');</script>";
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; background: #f4f7f6; }
        .message-box { background: white; padding: 40px; border-radius: 15px; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .spinner { border: 4px solid #f3f3f3; border-top: 4px solid #27ae60; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin: 20px auto; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
</head>
<body>
    <div class="message-box">
        <div class="spinner"></div>
        <h3>جاري معالجة تسليم السيارة...</h3>
    </div>
</body>
</html>