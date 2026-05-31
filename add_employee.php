<?php
require_once 'db.php'; // تأكد أن ملف الاتصال بقاعدة البيانات موجود

// معالجة البيانات عند إرسال النموذج
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['emp_name']);
    $role = $_POST['emp_role'];

    if (!empty($name) && !empty($role)) {
        $stmt = $db->prepare("INSERT INTO employees (name, role) VALUES (?, ?)");
        $stmt->execute([$name, $role]);
        
        echo "<script>alert('✅ تم إضافة الموظف بنجاح!'); window.location='admin.php';</script>";
    } else {
        echo "<script>alert('❌ يرجى ملء جميع الحقول!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إضافة موظف جديد</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f7f6; padding: 50px; }
        .form-container { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); max-width: 450px; margin: auto; }
        h2 { text-align: center; color: #2d3436; margin-bottom: 20px; }
        input, select { width: 100%; padding: 12px; margin: 10px 0; border: 2px solid #dfe6e9; border-radius: 8px; box-sizing: border-box; font-size: 16px; }
        button { background: linear-gradient(135deg, #00b894, #009432); color: white; padding: 12px; border: none; width: 100%; border-radius: 8px; cursor: pointer; font-weight: bold; font-size: 16px; margin-top: 10px; }
        button:hover { opacity: 0.9; }
        .back-link { display: block; text-align: center; margin-top: 15px; text-decoration: none; color: #636e72; }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>➕ إضافة موظف جديد</h2>
        <form method="POST">
            <input type="text" name="emp_name" placeholder="اسم الموظف بالكامل" required>
            
            <label style="font-weight: bold; color: #2d3436;">الوظيفة / الصلاحية:</label>
            <select name="emp_role" required>
                <option value="admin">مدير (Admin)</option>
                <option value="accountant">محاسب (Accountant)</option>
                <option value="reception">موظف استقبال (Reception)</option>
            </select>
            
            <button type="submit">حفظ بيانات الموظف</button>
        </form>
        <a href="admin.php" class="back-link">العودة للوحة الإدارة</a>
    </div>

</body>
</html>