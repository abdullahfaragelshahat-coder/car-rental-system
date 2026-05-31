<?php
// 1. استدعاء الاتصال بقاعدة البيانات
require_once 'db.php';

// 2. معالجة الطلب
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    if (isset($_FILES['car_image']) && $_FILES['car_image']['error'] == 0) {
        
        $image_name = time() . '_' . $_FILES['car_image']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . $image_name;
        
        if (move_uploaded_file($_FILES["car_image"]["tmp_name"], $target_file)) {
            
            // إضافة الأعمدة الجديدة: current_km و next_maintenance_km
            $stmt = $db->prepare("INSERT INTO cars 
                (model_name, purchase_price, price_per_day, car_image, status, current_km, next_maintenance_km) 
                VALUES (?, ?, ?, ?, 'available', ?, ?)");
            
            $stmt->execute([
                $_POST['model'], 
                $_POST['purchase'], 
                $_POST['rent'], 
                $image_name, 
                $_POST['current_km'], 
                $_POST['next_km']
            ]);
            
            $success_msg = "✅ تم إضافة السيارة بنجاح!";
        } else {
            $error_msg = "❌ فشل في رفع الصورة.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إضافة سيارة جديدة</title>
    <style>
        body{font-family:'Segoe UI',sans-serif; background:#f0f2f5; padding:30px;}
        .form-card{max-width:600px; margin:auto; background:#fff; padding:30px; border-radius:15px; box-shadow:0 4px 15px rgba(0,0,0,0.1);}
        input{display:block; width:100%; margin-bottom:15px; padding:12px; border:1px solid #ccc; border-radius:8px; box-sizing:border-box;}
        button{background-color:#27ae60; color:white; padding:15px; width:100%; border:none; border-radius:8px; font-weight:bold; cursor:pointer;}
        button:hover{background-color:#219150;}
    </style>
</head>
<body>

    <div class="form-card">
        <h2>إضافة سيارة جديدة</h2>
        
        <?php if(isset($success_msg)) echo "<p style='color:green;'>$success_msg</p>"; ?>
        <?php if(isset($error_msg)) echo "<p style='color:red;'>$error_msg</p>"; ?>

        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="model" placeholder="اسم الموديل" required>
            <input type="number" name="purchase" placeholder="سعر الشراء" required>
            <input type="number" name="rent" placeholder="سعر الإيجار اليومي" required>
            
            <input type="number" name="current_km" placeholder="عداد الكيلومترات الحالي" required>
            <input type="number" name="next_km" placeholder="مسافة الصيانة القادمة" required>
            
            <label>اختر صورة السيارة:</label>
            <input type="file" name="car_image" accept="image/*" required>
            
            <button type="submit">إضافة السيارة</button>
        </form>
        
        <br>
        <button onclick="history.back()" style="background: #95a5a6;">⬅️ رجوع للخلف</button>
    </div>

</body>
</html>