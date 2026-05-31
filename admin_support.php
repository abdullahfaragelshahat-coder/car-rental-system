<?php
session_start();
require_once 'db.php'; 

// حماية: لا يدخلها إلا أنت
if ($_SESSION['user_role'] !== 'admin') { die("غير مصرح لك بالدخول"); }

$tickets = $db->query("SELECT * FROM support_tickets ORDER BY created_at DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إدارة الدعم الفني</title>
    <style>
        table { width: 90%; margin: 20px auto; border-collapse: collapse; background: white; }
        th, td { padding: 15px; border: 1px solid #ddd; text-align: center; }
        th { background: #34495e; color: white; }
    </style>
</head>
<body>
    <h1 style="text-align: center;">📥 طلبات الدعم الفني</h1>
    <table>
    <tr>
        <th>اسم الموظف</th>
        <th>المشكلة</th>
        <th>الحالة</th> <th>الإجراء</th> </tr>
    <?php foreach ($tickets as $t): ?>
    <tr>
        <td><?php echo $t['emp_name']; ?></td>
        <td><?php echo $t['subject']; ?></td>
        
        <td>
            <span style="color: <?php echo ($t['status'] == 'تم الحل') ? 'green' : 'red'; ?>">
                <?php echo $t['status']; ?>
            </span>
        </td>

        <td>
            <?php if($t['status'] != 'تم الحل'): ?>
                <a href="update_status.php?id=<?php echo $t['id']; ?>" 
                   style="background: #27ae60; color: white; padding: 5px 10px; text-decoration: none; border-radius: 4px;">
                   تحديد كـ "تم الحل"
                </a>
            <?php else: ?>
                ✅
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
<td>
    <form action="update_status.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $t['id']; ?>">
        
        <select name="new_status">
            <option value="قيد المراجعة">قيد المراجعة</option>
            <option value="تم الحل">تم الحل</option>
            <option value="مرفوضة/غير واضحة">غير واضحة</option>
        </select>
        
        <button type="submit">تحديث</button>
    </form>
</td>
</table>
<br>
    <button onclick="history.back()" style="padding: 10px; cursor: pointer;">⬅️ رجوع</button>
</body>
</html>