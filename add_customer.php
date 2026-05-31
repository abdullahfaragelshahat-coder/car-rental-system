<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // إضافة العميل الجديد لقاعدة البيانات
    $stmt = $db->prepare("INSERT INTO customers (full_name, phone, email) VALUES (?, ?, ?)");
    $stmt->execute([$_POST['full_name'], $_POST['phone'], $_POST['email']]);
    
    echo "<script>alert('✅ تم إضافة العميل بنجاح!'); window.location='add_rental.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إضافة عميل جديد</title>
    <style>
        body { font-family: sans-serif; background: #f4f7f6; padding: 20px; }
        .form-box { background: white; padding: 30px; border-radius: 10px; max-width: 400px; margin: auto; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        button { background: #27ae60; color: white; padding: 10px; width: 100%; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>
    <div class="form-box">
        <h2>👤 إضافة عميل جديد</h2>
        <form method="POST">
            <input type="text" name="full_name" placeholder="اسم العميل" required>
            <input type="text" name="phone" placeholder="رقم الهاتف" required>
            <input type="email" name="email" placeholder="البريد الإلكتروني">
            <button type="submit">إضافة العميل</button>
        </form>
    </div>
    <br>
    <button onclick="history.back()" style="padding: 10px; cursor: pointer;">⬅️ رجوع</button>
</body>
</html>