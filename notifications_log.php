<?php
require_once 'db.php';
$logs = $db->query("SELECT * FROM notifications_log ORDER BY created_at DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>سجل الإشعارات</title>
    <style>
        body { font-family: sans-serif; background: #f4f7f6; padding: 20px; }
        .log-box { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .log-item { padding: 10px; border-bottom: 1px solid #eee; }
    </style>
</head>
<body>
    <div class="log-box">
        <h2>🔔 سجل الإشعارات والعمليات</h2>
        <?php foreach ($logs as $log): ?>
            <div class="log-item">
                <strong><?php echo $log['created_at']; ?>:</strong> <?php echo $log['message']; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <div style="margin-top: 20px;">
    <button onclick="window.location='admin.php'" style="background: #34495e; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
        ⬅️ العودة للوحة التحكم
    </button>
</div>
</body>
</html>