<?php
require_once 'db.php';

if (!isset($_GET['rental_id'])) {
    die("خطأ: لم يتم تحديد الحجز!");
}

$stmt = $db->prepare("SELECT rentals.*, cars.model_name, cars.price_per_day 
                      FROM rentals 
                      JOIN cars ON rentals.car_id = cars.id 
                      WHERE rentals.id = ?");
$stmt->execute([$_GET['rental_id']]);
$rental = $stmt->fetch();

if (!$rental) {
    die("خطأ: الحجز غير موجود!");
}

$days = (strtotime($rental['end_date']) - strtotime($rental['start_date'])) / (60 * 60 * 24);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>فاتورة حجز رقم <?php echo $rental['id']; ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; background: #fff; color: #333; }
        .invoice-box { border: 1px solid #ddd; padding: 25px; max-width: 600px; margin: 0 auto; box-shadow: 0 0 10px rgba(0,0,0,0.05); }
        .invoice-header { display: flex; justify-content: space-between; border-bottom: 2px solid #007BFF; padding-bottom: 15px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        th { background: #f8f9fa; }
        .total { font-size: 20px; font-weight: bold; color: #28a745; text-align: left; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print" style="text-align:center; margin-bottom:20px;">
        <button onclick="window.print();" style="background:#28a745; color:white; padding:10px 20px; border:none; border-radius:5px; cursor:pointer; font-weight:bold;">🖨️ طباعة الفاتورة الآن</button>
        <a href="index.php" style="margin-right:10px; color:#007BFF;">العودة للمعرض</a>
    </div>

    <div class="invoice-box">
        <div class="invoice-header">
            <div>
                <h2>🧾 فاتورة حجز سيارة</h2>
                <p>رقم الفاتورة: #<?php echo $rental['id']; ?></p>
            </div>
            <div style="text-align: left;">
                <h3>شركة السيارات الذكية</h3>
                <p>تاريخ الفاتورة: <?php echo date('Y-m-d'); ?></p>
            </div>
        </div>

        <p><strong>اسم العميل:</strong> <?php echo htmlspecialchars($rental['customer_name']); ?></p>
        
        <table>
            <thead>
                <tr>
                    <th>السيارة</th>
                    <th>فترة الحجز</th>
                    <th>عدد الأيام</th>
                    <th>السعر اليومي</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>🚗 <?php echo $rental['model_name']; ?></td>
                    <td>من <?php echo $rental['start_date']; ?><br>إلى <?php echo $rental['end_date']; ?></td>
                    <td><?php echo $days; ?> أيام</td>
                    <td><?php echo $rental['price_per_day']; ?> جنيه</td>
                </tr>
            </tbody>
        </table>

        <div class="total">الإجمالي المدفوع: <?php echo $rental['total_price']; ?> جنيه</div>
    </div>
</body>
</html>