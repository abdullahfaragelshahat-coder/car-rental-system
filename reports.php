<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['admin_logged'])) {
    header("Location: login.php");
    exit();
}

// الآن الاستعلامات هتشوف الـ $db المفتوح من db.php
$stmt_cars = $db->query("SELECT c.model_name, COUNT(r.id) as rental_count FROM rentals r JOIN cars c ON r.car_id = c.id GROUP BY c.model_name ORDER BY rental_count DESC LIMIT 5");
$top_cars = $stmt_cars->fetchAll(PDO::FETCH_ASSOC);

$stmt_customers = $db->query("SELECT customer_name, COUNT(*) as total_rentals FROM rentals GROUP BY customer_name ORDER BY total_rentals DESC LIMIT 5");
$top_customers = $stmt_customers->fetchAll(PDO::FETCH_ASSOC);

$stmt_stagnant = $db->query("SELECT model_name FROM cars WHERE id NOT IN (SELECT DISTINCT car_id FROM rentals)");
$stagnant_cars = $stmt_stagnant->fetchAll(PDO::FETCH_ASSOC);

$year = date('Y');
$stmt = $db->prepare("SELECT MONTH(created_at) AS month, SUM(total_price) AS total FROM rentals WHERE YEAR(created_at) = ? GROUP BY MONTH(created_at)");
$stmt->execute([$year]);
$monthly_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

$report = [];
foreach ($monthly_data as $row) {
    $report[$row['month']] = $row['total'];
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
<meta charset="UTF-8">
<title>التقارير المالية</title>

<link rel="stylesheet" href="print.css" media="print">

<style>

body{
    font-family:'Segoe UI',sans-serif;
    background:#f0f2f5;
    padding:30px;
}

.report-card{
    max-width:1000px;
    margin:auto;
    background:#fff;
    padding:30px;
    border-radius:20px;
    box-shadow:0 10px 25px rgba(0,0,0,.1);
}

.top-bar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
}

.btn-back{
    background:#6c757d;
    color:white;
    padding:10px 20px;
    text-decoration:none;
    border-radius:8px;
}

.btn-back:hover{
    background:#5a6268;
}

.btn-print{
    background:#e67e22;
    color:white;
    border:none;
    padding:10px 20px;
    border-radius:8px;
    cursor:pointer;
}

.btn-print:hover{
    opacity:.9;
}

.title{
    text-align:center;
    margin-bottom:25px;
}

.summary{
    background:#27ae60;
    color:white;
    padding:20px;
    border-radius:10px;
    text-align:center;
    margin-bottom:20px;
    font-size:20px;
    font-weight:bold;
}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
}

th{
    background:#34495e;
    color:white;
    padding:15px;
}

td{
    padding:15px;
    border-bottom:1px solid #ddd;
    text-align:center;
}

tr:hover{
    background:#f8f9fa;
}

.btn-details{
    background:#27ae60;
    color:white;
    padding:8px 15px;
    border-radius:5px;
    text-decoration:none;
}

.btn-details:hover{
    background:#219150;
}

.total-row{
    background:#ecf0f1;
    font-weight:bold;
    font-size:18px;
}

</style>

</head>

<body>

<div class="report-card">

    <div class="top-bar">

        <a href="admin.php" class="btn-back">
            ⬅ العودة للوحة الإدارة
        </a>

        <button onclick="window.print()" class="btn-print">
            🖨️ طباعة التقرير
        </button>

    </div>

    <div class="title">
        <h1>📊 تقرير الأرباح السنوي (<?php echo $year; ?>)</h1>
    </div>
<hr>
<div style="display: flex; gap: 20px; flex-wrap: wrap; margin-top: 30px;">
    
    <div style="flex: 1; min-width: 300px;">
        <h3>🚗 السيارات الأكثر طلباً</h3>
        <table border="1" style="width: 100%;">
            <?php foreach($top_cars as $car) { ?>
            <tr><td><?php echo $car['model_name']; ?></td><td><?php echo $car['rental_count']; ?> مرات</td></tr>
            <?php } ?>
        </table>
    </div>

    <div style="flex: 1; min-width: 300px;">
        <h3>🚫 السيارات الراكدة</h3>
        <ul style="background: #fee; padding: 20px; border-radius: 10px;">
            <?php foreach($stagnant_cars as $car) { ?>
            <li><?php echo $car['model_name']; ?></li>
            <?php } ?>
        </ul>
    </div>
</div>
    <?php
    $sum_year = array_sum($report);
    ?>

    <div class="summary">
        إجمالي أرباح السنة:
        <?php echo number_format($sum_year,2); ?>
        ج.م
    </div>

    <table>

        <tr>
            <th>الشهر</th>
            <th>الأرباح</th>
            <th>التفاصيل</th>
        </tr>

        <?php

        for($m=1;$m<=12;$m++) {

            $val = isset($report[$m]) ? $report[$m] : 0;

            echo "
            <tr>
                <td>شهر $m</td>

                <td>"
                    . number_format($val,2) .
                " ج.م</td>

                <td>
                    <a class='btn-details'
                       href='daily_details.php?m=$m&y=$year'>
                       عرض الأيام
                    </a>
                </td>
            </tr>";
        }

        ?>

        <tr class="total-row">
            <td>الإجمالي السنوي</td>
            <td colspan="2">
                <?php echo number_format($sum_year,2); ?> ج.م
            </td>
        </tr>

    </table>

</div>

</body>
</html>