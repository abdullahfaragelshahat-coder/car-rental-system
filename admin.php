<?php
session_start();
require_once 'db.php';

// حماية الصفحة
if (!isset($_SESSION['admin_logged'])) {
    header("Location: login.php");
    exit();
}

include 'navbar.php';

// حساب التقارير
$today = date('Y-m-d');

$daily = $db->query("
    SELECT SUM(total_price)
    FROM rentals
    WHERE DATE(created_at) = '$today'
")->fetchColumn();

$monthly = $db->query("
    SELECT SUM(total_price)
    FROM rentals
    WHERE DATE_FORMAT(created_at, '%Y-%m') = '".date('Y-m')."'
")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>لوحة تحكم الوكالة</title>
    <style>
        body { font-family: sans-serif; background: #f4f7f6; padding: 20px; }
        .stats { display: flex; gap: 20px; margin-bottom: 20px; }
        .card { background: #007bff; color: white; padding: 20px; border-radius: 10px; flex: 1; }
        table { width: 100%; border-collapse: collapse; background: white; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: center; }
        th { background: #333; color: white; }
    </style>
</head>
<body>
    <a href="active_rentals.php" style="
    display: inline-block; 
    background-color: #e74c3c; 
    color: white; 
    padding: 15px 25px; 
    text-decoration: none; 
    border-radius: 8px; 
    font-weight: bold; 
    margin: 10px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
    🚗 السيارات المؤجرة حالياً
</a>
<a href="daily_report.php" style="
    display: inline-block; 
    background-color: #27ae60; 
    color: white; 
    padding: 15px 25px; 
    text-decoration: none; 
    border-radius: 8px; 
    font-weight: bold; 
    margin: 10px;">
    📊 التقرير اليومي
</a>

<div class="stats">
    <div class="card"><h3>أرباح اليوم: <?php echo $daily ?: 0; ?> جنيه</h3></div>
    <div class="card" style="background: #28a745;"><h3>أرباح الشهر: <?php echo $monthly ?: 0; ?> جنيه</h3></div>
</div>
<div class="admin-actions" style="margin: 20px 0; text-align: center;">
    <a href="add_employee.php" style="background-color: #27ae60; color: white; padding: 12px 25px; text-decoration: none; border-radius: 8px; font-weight: bold;">
        ➕ إضافة موظف جديد
    </a>
<a href="add_rental.php" style="background: #e67e22; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-right: 10px;">
    ➕ حجز جديد
</a>
</div>
<table>
    <tr>
        <th>الموديل</th>
        <th>سعر الإيجار</th>
        <th>الإجراءات</th>
    </tr>
    <?php
    $cars = $db->query("SELECT * FROM cars");
    while ($row = $cars->fetch()) {
        echo "<tr>
            <td>{$row['model_name']}</td>
            <td>{$row['rent_price']} جنيه</td>
            <td>";
           if ($_SESSION['user_role'] !== 'accountant') {
    echo "<a href='rent_car.php?car_id={$row['id']}' style='color:blue;'>تأجير</a>";
} else {
    echo "غير مسموح";
}
        echo "</td></tr>";
    }
    ?>
</table>
<a href="reports.php" style="background: #e67e22; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: bold;">
    📊 عرض التقارير المالية
</a>
<a href="customers_list.php" style="
    display: inline-block; 
    background-color: #3498db; 
    color: white; 
    padding: 15px 25px; 
    text-decoration: none; 
    border-radius: 8px; 
    font-weight: bold; 
    margin: 10px;">
    👥 عرض قائمة العملاء
</a>
<a href="add_customer.php" style="
    display: inline-block; 
    background-color: #e67e22; 
    color: white; 
    padding: 15px 25px; 
    text-decoration: none; 
    border-radius: 8px; 
    font-weight: bold; 
    margin: 10px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
    👤 إضافة عميل جديد
</a>
<a href="notifications_log.php" style="
    display: inline-block; 
    background-color: #9b59b6; 
    color: white; 
    padding: 15px 25px; 
    text-decoration: none; 
    border-radius: 8px; 
    font-weight: bold; 
    margin: 10px;">
    🔔 سجل الإشعارات
</a>
<div style="margin: 20px;">
    <a href="admin_support.php" style="background: #2c3e50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
        📂 عرض تذاكر الدعم الفني
    </a>
    <a href="view_employees.php" style="background: #8e44ad; color: white; padding: 12px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;">
    👥 عرض قائمة الموظفين
</a>
</div>
<div style="position: fixed; bottom: 20px; left: 20px;">
    <a href="support.php" style="background: #e67e22; color: white; padding: 15px 25px; border-radius: 50px; text-decoration: none; font-weight: bold; box-shadow: 0 4px 6px rgba(0,0,0,0.2);">
        🛠️ الدعم الفني
    </a>
</div>

</body>
</html>