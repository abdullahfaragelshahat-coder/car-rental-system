<?php
require_once 'db.php';

$new_password = 'password'; // كلمة المرور التي ستستخدمها للدخول
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
$username = 'admin';

$stmt = $db->prepare("UPDATE admins SET password = ? WHERE username = ?");
$stmt->execute([$hashed_password, $username]);

echo "تم تحديث كلمة المرور للمستخدم 'admin' لتصبح: password";
?>