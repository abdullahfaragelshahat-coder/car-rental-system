<?php
require_once 'db.php';
$employees = $db->query("SELECT * FROM employees")->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>قائمة الموظفين</title>
    <style>
        .emp-card { background: white; padding: 15px; margin: 10px; border-right: 5px solid #8e44ad; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <h1 style="text-align: center;">👥 الموظفون المسجلون</h1>
    
<?php foreach ($employees as $emp): ?>
    <div class="emp-card">
        <h3>الاسم: <?php echo htmlspecialchars($emp['name']); ?></h3>
        
        <p>الوظيفة/الرتبة: 
            <strong>
                <?php echo isset($emp['role']) ? htmlspecialchars($emp['role']) : 'موظف عام'; ?>
            </strong>
        </p>
        
        <p>البريد الإلكتروني: <?php echo isset($emp['email']) ? htmlspecialchars($emp['email']) : 'غير متاح'; ?></p>
    </div>
<?php endforeach; ?>
    
    <div style="text-align: center; margin-top: 20px;">
        <a href="admin.php">⬅️ عودة للرئيسية</a>
    </div>
</body>
</html>
<style>
    /* تنسيق الصفحة العامة */
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f7f6;
        padding: 20px;
        direction: rtl;
    }

    /* حاوية الموظفين */
    .employees-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        max-width: 1000px;
        margin: 0 auto;
    }

    /* شكل الكارت لكل موظف */
    .emp-card {
        background: #ffffff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        border-top: 5px solid #8e44ad; /* لون تمييز للوظيفة */
        transition: transform 0.3s ease;
    }

    .emp-card:hover {
        transform: translateY(-5px);
    }

    .emp-card h3 {
        margin-top: 0;
        color: #2c3e50;
    }

    /* تنسيق شارة الوظيفة */
    .role-badge {
        display: inline-block;
        padding: 5px 10px;
        background: #e8f6f3;
        color: #16a085;
        border-radius: 20px;
        font-size: 0.9em;
        font-weight: bold;
        margin-bottom: 10px;
    }

    /* تنسيق زر العودة */
    .btn-back {
        display: block;
        width: 200px;
        margin: 30px auto;
        text-align: center;
        background: #34495e;
        color: white;
        padding: 10px;
        text-decoration: none;
        border-radius: 5px;
    }
</style>