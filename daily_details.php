<?php
require_once 'db.php';
$m = $_GET['m']; $y = $_GET['y'];

$stmt = $db->prepare("SELECT DATE(created_at) as day, SUM(total_price) as total FROM rentals WHERE MONTH(created_at)=? AND YEAR(created_at)=? GROUP BY DATE(created_at) ORDER BY day ASC");
$stmt->execute([$m, $y]);
$days = $stmt->fetchAll();

// تحضير البيانات للرسم البياني
$labels = []; $data = [];
foreach($days as $d) {
    $labels[] = $d['day'];
    $data[] = $d['total'];
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
    <link rel="stylesheet" href="print.css" media="print">
<head>
    <meta charset="UTF-8">
    <title>تحليل شهر <?php echo $m; ?></title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f8f9fa; padding: 20px; }
        .dashboard { max-width: 900px; margin: auto; background: white; padding: 30px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
        canvas { margin-top: 30px; }
        .stats-card { text-align: center; padding: 20px; background: #0984e3; color: white; border-radius: 15px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2>📊 تفاصيل الحجوزات</h2>
    <button onclick="window.print()" class="btn-print" style="background: #e67e22; color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer;">
        🖨️ طباعة التقرير
    </button>
</div>
<button onclick="history.back()" style="
    background-color: #34495e; 
    color: white; 
    padding: 10px 20px; 
    border: none; 
    border-radius: 8px; 
    cursor: pointer; 
    font-weight: bold;
    margin-bottom: 20px;">
    ⬅ رجوع للخلف
</button>
<div class="dashboard">
    <div class="stats-card">
        <h1>تقرير شهر <?php echo "$m / $y"; ?></h1>
        <p>نظرة تحليلية على الأداء المالي اليومي</p>
    </div>

    <canvas id="profitChart"></canvas>

    <table style="width:100%; margin-top:30px; border-collapse: collapse;">
        <tr style="background:#f1f1f1;"><th style="padding:10px;">التاريخ</th><th>الأرباح</th></tr>
        <?php foreach($days as $d): ?>
            <tr><td style="padding:10px; border-bottom:1px solid #ddd;"><?php echo $d['day']; ?></td>
            <td style="padding:10px; border-bottom:1px solid #ddd;"><?php echo number_format($d['total'], 2); ?> ج.م</td></tr>
        <?php endforeach; ?>
    </table>
</div>
<div style="margin-bottom: 20px;">
    <a href="reports.php" style="
        background-color: #6c757d; 
        color: white; 
        padding: 10px 20px; 
        text-decoration: none; 
        border-radius: 8px; 
        font-weight: bold; 
        display: inline-flex; 
        align-items: center;
        transition: 0.3s;">
        ⬅ عودة إلى التقارير
    </a>
</div>
<script>
    const ctx = document.getElementById('profitChart').getContext('2d');
    new Chart(ctx, {
        type: 'line', // نوع الرسم (خط منحني)
        data: {
            labels: <?php echo json_encode($labels); ?>,
            datasets: [{
                label: 'الأرباح اليومية (جنيه)',
                data: <?php echo json_encode($data); ?>,
                borderColor: '#0984e3',
                backgroundColor: 'rgba(9, 132, 227, 0.2)',
                fill: true,
                tension: 0.4 // لجعل الخط منحني وناعم
            }]
        },
        options: { responsive: true }
    });
</script>
<div class="print-signature" style="display: none; margin-top: 50px;">
    <div style="display: flex; justify-content: space-around; text-align: center;">
        <div>
            <p>توقيع المسؤول:</p>
            <p>____________________</p>
        </div>
        <div>
            <p>ختم المعرض:</p>
            <p>____________________</p>
        </div>
    </div>
    <br>
    <button onclick="history.back()" style="padding: 10px; cursor: pointer;">⬅️ رجوع</button>

</div>

</body>
</html>