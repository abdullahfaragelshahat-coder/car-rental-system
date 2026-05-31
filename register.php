<?php
session_start();
require_once 'db.php';

// حماية الصفحة: للمدير فقط
if (!isset($_SESSION['admin_logged']) || $_SESSION['role'] !== 'admin') {
    die("<div style='text-align:center; padding:50px;'>❌ غير مسموح بالدخول لغير المديرين!</div>");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $db->prepare("INSERT INTO admins (username, password, role) VALUES (?, ?, ?)");
    $stmt->execute([$username, $password, $role]);
    $msg = "✅ تم إضافة الموظف بنجاح!";
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إضافة موظف جديد</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .container { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 400px; }
        h2 { text-align: center; color: #333; }
        input, select { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        button:hover { background: #218838; }
        .msg { color: green; text-align: center; font-weight: bold; margin-bottom: 10px; }
    </style>
</head>
<body>

    <div class="container">
        <h2>👥 إضافة موظف جديد</h2>
        <?php if(isset($msg)) echo "<div class='msg'>$msg</div>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="اسم المستخدم" required>
            <input type="password" name="password" placeholder="كلمة المرور" required>
            <select name="role">
                <option value="receptionist">موظف استقبال</option>
                <option value="accountant">محاسب</option>
                <option value="admin">مدير عام</option>
            </select>
            <button type="submit">حفظ الموظف</button>
        </form>
        <a href="admin.php" style="display:block; text-align:center; margin-top:15px; color:#007bff; text-decoration:none;">← العودة للوحة التحكم</a>
    </div>

</body>
</html>