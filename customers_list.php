<?php
session_start();
require_once 'db.php';

// جلب كل العملاء من قاعدة البيانات
$customers = $db->query("SELECT * FROM customers ORDER BY points DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>قائمة العملاء</title>
    <style>
        body { font-family: sans-serif; background: #f4f7f6; padding: 20px; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        th, td { padding: 15px; text-align: center; border-bottom: 1px solid #ddd; }
        th { background-color: #2c3e50; color: white; }
        .btn-details { background: #3498db; color: white; padding: 5px 15px; border-radius: 5px; text-decoration: none; }
    </style>
</head>
<body>

    <h2>👥 قائمة العملاء المميزين</h2>
    <table>
        <tr>
            <th>الاسم</th>
            <th>الهاتف</th>
            <th>النقاط</th>
            <th>الإجراءات</th>
        </tr>
        <?php foreach ($customers as $c): ?>
        <<tr>
    <td><?php echo $c['full_name']; ?></td>
    <td><?php echo $c['phone']; ?></td>
    <td><strong><?php echo $c['points']; ?></strong></td>
    
    <td>
        <a href="customer_profile.php?id=<?php echo $c['id']; ?>" 
           style="background-color: #3498db; color: white; padding: 8px 15px; text-decoration: none; border-radius: 5px; font-size: 14px;">
           🔍 عرض التفاصيل
        </a>
    </td>
</tr>
        <?php endforeach; ?>
    </table>

    <br>
    <button onclick="history.back()" style="padding: 10px; cursor: pointer;">⬅️ رجوع</button>

</body>
</html>