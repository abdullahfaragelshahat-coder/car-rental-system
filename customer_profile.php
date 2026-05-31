<?php
session_start();
require_once 'db.php';

// التأكد من وجود ID للعميل في الرابط
if (!isset($_GET['id'])) {
    header("Location: customers_list.php");
    exit();
}

$customer_id = $_GET['id'];

// 1. جلب بيانات العميل
$stmt = $db->prepare("SELECT * FROM customers WHERE id = ?");
$stmt->execute([$customer_id]);
$customer = $stmt->fetch();

// 2. جلب تاريخ حجوزات العميل
$rentals = $db->prepare("
    SELECT rentals.*, cars.model_name 
    FROM rentals 
    JOIN cars ON rentals.car_id = cars.id 
    WHERE rentals.customer_id = ?
    ORDER BY rentals.id DESC
");
$rentals->execute([$customer_id]);
$history = $rentals->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>ملف العميل: <?php echo $customer['full_name']; ?></title>
    <style>
        body { font-family: sans-serif; background: #f4f7f6; padding: 20px; }
        .profile-card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; background: white; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: center; }
        th { background: #2c3e50; color: white; }
    </style>
</head>
<body>

    <div class="profile-card">
        <h2>👤 بيانات العميل: <?php echo $customer['full_name']; ?></h2>
        <p>📱 الهاتف: <?php echo $customer['phone']; ?></p>
        <p>⭐ إجمالي النقاط: <strong><?php echo $customer['points']; ?></strong></p>
    </div>

    <h3>📜 تاريخ الحجوزات:</h3>
    <table>
        <tr>
            <th>السيارة</th>
            <th>السعر الكلي</th>
            <th>التاريخ</th>
        </tr>
        <?php foreach ($history as $h): ?>
        <tr>
            <td><?php echo $h['model_name']; ?></td>
            <td><?php echo $h['total_price']; ?></td>
            <td><?php echo $h['created_at']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <br>
    <button onclick="history.back()" style="padding: 10px; cursor: pointer;">⬅️ رجوع للقائمة</button>

</body>
</html>