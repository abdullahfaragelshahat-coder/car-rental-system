<?php
session_start();
require_once 'db.php';

// حماية الصفحة
if (!isset($_SESSION['admin_logged'])) {
    die("❌ غير مسموح بالدخول!");
}

$car_id = isset($_GET['car_id']) ? intval($_GET['car_id']) : 0;

// جلب بيانات السيارة المباعة
$stmt = $db->prepare("SELECT * FROM cars WHERE id = ? AND status = 'sold'");
$stmt->execute([$car_id]);
$car = $stmt->fetch();

if (!$car) {
    die("❌ خطأ: هذه السيارة غير مباعة أو غير موجودة بالسيستم حالياً!");
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>عقد بيع ونقل ملكية سيارة رسمية</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background-color: #fff; color: #000; line-height: 1.6; }
        .contract-container { border: 4px double #000; padding: 30px; border-radius: 8px; max-width: 800px; margin: 0 auto; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 15px; margin-bottom: 30px; }
        .row { display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 16px; }
        .section-title { font-weight: bold; text-decoration: underline; margin-top: 25px; margin-bottom: 10px; font-size: 18px; }
        .signatures { display: flex; justify-content: space-between; margin-top: 60px; text-align: center; }
        .btn-print { background-color: #28a745; color: white; padding: 10px 20px; border: none; cursor: pointer; border-radius: 5px; font-weight: bold; display: block; margin: 20px auto; font-size: 16px; }
        @media print { .btn-print { display: none; } body { margin: 10px; } .contract-container { border: none; } }
    </style>
</head>
<body>

    <div class="contract-container">
        <div class="header">
            <h2>📜 عقد بيع سيارة ونقل الملكية النهائي</h2>
            <p>صادر عن نظام إدارة وكالة السيارات المحاسبي</p>
        </div>

        <p>إنه في يوم المبيعات الموافق: <strong><?php echo $car['sale_date']; ?></strong>، تم الاتفاق والتعاقد بين كل من:</p>
        
        <p><strong>الطرف الأول (البائع):</strong> وكالة السيارات الفاخرة المعتمدة.</p>
        <p><strong>الطرف الثاني (المشتري):</strong> العميل المسجل في فاتورة النظام المحاسبية.</p>

        <div class="section-title">⚖️ بند أولاً: موضوع البيع</div>
        <p>باع وأسقط وتنازل الطرف الأول بكافة الضمانات القانونية إلى الطرف الثاني القابل لذلك السيارة المبينة مواصفاتها بالسيستم كالتالي:</p>
        
        <div class="row">
            <div>📌 كود السيارة بالشركة: <strong>#<?php echo $car['id']; ?></strong></div>
            <div>🚗 ماركة وموديل السيارة: <strong><?php echo htmlspecialchars($car['model_name']); ?></strong></div>
        </div>

        <div class="section-title">⚖️ بند ثانياً: المقابل المالي والأرباح</div>
        <p>تم هذا البيع نظير مبلغ إجمالي وقدره: <strong style="font-size: 18px; color: green;"><?php echo number_format($car['selling_price'], 2); ?> جنيه مصري لا غير</strong>، ودخل الخزينة الرئيسية للشركة فوراً.</p>

        <div class="section-title">⚖️ بند ثالثاً: المعاينة والاستلام</div>
        <p>يقر الطرف الثاني (المشتري) بأنه عاين السيارة المذكورة المعاينة التامة النافية للجهالة، وقبلها بحالتها الراهنة، وتسلمها وأصبحت في حيازته الكاملة من تاريخ هذا العقد.</p>

        <div class="signatures">
            <div>
                <p>✍️ <strong>توقيع الطرف الأول (الوكالة):</strong></p>
                <p>.........................................</p>
            </div>
            <div>
                <p>✍️ <strong>توقيع الطرف الثاني (المشتري):</strong></p>
                <p>.........................................</p>
            </div>
        </div>
    </div>

    <button onclick="window.print();" class="btn-print">🖨️ طباعة العقد الرسمي الآن</button>

</body>
</html>