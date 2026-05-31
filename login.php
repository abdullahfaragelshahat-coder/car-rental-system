<?php
session_start();
require_once 'db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = trim($_POST['name']);
    $password = trim($_POST['password']);

    $stmt = $db->prepare("SELECT * FROM employees WHERE LOWER(name) = LOWER(?)");
    $stmt->execute([$name]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {

        if ($password == $user['password']) {

            // حفظ بيانات المستخدم في Session
            $_SESSION['admin_logged'] = true;
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_name'] = $user['name'];

            header("Location: admin.php");
            exit();

        } else {
            $error = "❌ كلمة المرور غير صحيحة";
        }

    } else {
        $error = "❌ اسم المستخدم غير موجود";
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تسجيل دخول النظام</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background-color: #f4f7f6; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { background: #fff; padding: 40px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); width: 100%; max-width: 350px; text-align: center; }
        h2 { color: #333; margin-bottom: 25px; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #27ae60; border: none; color: white; border-radius: 6px; font-size: 16px; cursor: pointer; transition: 0.3s; }
        button:hover { background: #219150; }
        .error { color: #e74c3c; margin-bottom: 15px; font-weight: bold; }
    </style>
</head>
<body>

<div class="login-box">
    <h2>🔑 تسجيل الدخول</h2>
    <?php if($error) echo "<div class='error'>$error</div>"; ?>
    <form method="POST">
        <input type="text" name="name" placeholder="اسم المستخدم" required>
        <input type="password" name="password" placeholder="كلمة المرور" required>
        <button type="submit">دخول</button>
    </form>
</div>

</body>
</html>