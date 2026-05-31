<?php
require_once 'db.php';

// 1. عدد السيارات المتاحة
$available_cars = $db->query("SELECT COUNT(*) FROM cars WHERE status = 'available'")->fetchColumn();

// 2. عدد السيارات المؤجرة حالياً
$rented_cars = $db->query("SELECT COUNT(*) FROM cars WHERE status = 'rented'")->fetchColumn();

// 3. إجمالي الحجوزات اليوم (سواء انتهت أو لا)
$today_rentals = $db->query("SELECT COUNT(*) FROM rentals WHERE DATE(created_at) = CURDATE()")->fetchColumn();

// 4. إجمالي الإيرادات اليوم (افتراضياً الحقل اسمه total_price)
$daily_revenue = $db->query("SELECT SUM(total_price) FROM rentals WHERE DATE(created_at) = CURDATE()")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>التقرير اليومي</title>
    <style>
        body { font-family: sans-serif; background: #f4f7f6; padding: 20px; }
        .stats-container { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; }
        .card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center; }
        .card h3 { color: #2c3e50; }
        .number { font-size: 24px; font-weight: bold; color: #27ae60; }
    </style>
</head>
<body>

    <h2>📊 ملخص التقرير اليومي</h2>
    
    <div class="stats-container">
        <div class="card">
            <h3>السيارات المتاحة</h3>
            <p class="number"><?php echo $available_cars; ?></p>
        </div>
        <div class="card">
            <h3>المؤجرة حالياً</h3>
            <p class="number"><?php echo $rented_cars; ?></p>
        </div>
        <div class="card">
            <h3>حجوزات اليوم</h3>
            <p class="number"><?php echo $today_rentals; ?></p>
        </div>
        <div class="card">
            <h3>إيرادات اليوم</h3>
            <p class="number"><?php echo ($daily_revenue ?: 0); ?> ج.م</p>
        </div>
    </div>

    <br>
    <button onclick="history.back()" style="padding: 10px; cursor: pointer;">⬅️ رجوع</button>

</body>
</html>