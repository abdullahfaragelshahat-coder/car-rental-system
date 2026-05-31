<?php
require_once 'db.php';
// بنجيب الحجوزات اللي حالتها 'active' بس
$rentals = $db->query("
    SELECT rentals.*, cars.model_name, customers.full_name 
    FROM rentals 
    JOIN cars ON rentals.car_id = cars.id 
    JOIN customers ON rentals.customer_id = customers.id 
    WHERE rentals.status = 'active'
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>السيارات المؤجرة حالياً</title>
    <style>
        body { font-family: sans-serif; background: #f4f7f6; padding: 20px; }
        table { width: 100%; background: white; border-collapse: collapse; }
        th, td { padding: 15px; border: 1px solid #ddd; text-align: center; }
        th { background: #e74c3c; color: white; }
    </style>
</head>
<body>
    <h2>🚗 السيارات المؤجرة حالياً</h2>
    <table>
        <tr>
            <th>السيارة</th>
            <th>العميل</th>
            <th>تاريخ الحجز</th>
            <th>الإجراء</th>
        </tr>
        <?php foreach ($rentals as $r): ?>
        <tr>
            <td><?php echo $r['model_name']; ?></td>
            <td><?php echo $r['full_name']; ?></td>
            <td><?php echo $r['created_at']; ?></td>
            <td>
                <a href="end_rental.php?id=<?php echo $r['id']; ?>" style="color: green; font-weight: bold;">إنهاء الحجز</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <a href="end_rental.php?id=<?php echo $r['id']; ?>" 
   onclick="return confirm('⚠️ هل أنت متأكد من إنهاء الحجز وتسليم السيارة؟');"
   style="
    background-color: #27ae60; 
    color: white; 
    padding: 8px 15px; 
    text-decoration: none; 
    border-radius: 5px; 
    font-weight: bold;
    display: inline-block;">
    ✅ إنهاء الحجز
</a>
<br>
    <button onclick="history.back()" style="padding: 10px; cursor: pointer;">⬅️ رجوع</button>
</body>
</html>