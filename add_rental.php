<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_role'])) {
    header("Location: login.php");
    exit();
}

// 1. جلب البيانات أولاً (قبل أي كود HTML)
$cars = $db->query("SELECT id, model_name FROM cars WHERE status = 'available'")->fetchAll();
$customers = $db->query("SELECT id, full_name FROM customers")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $car_id = $_POST['car_id'];
    $customer_id = $_POST['customer_id'];
    $total_price = $_POST['total_price'];
    $added_by = $_SESSION['user_name'];

    try {
        $db->beginTransaction(); // بدء عملية متكاملة

        // أ. إضافة الحجز
        $stmt = $db->prepare("INSERT INTO rentals (car_id, customer_id, total_price, added_by, status) VALUES (?, ?, ?, ?, 'active')");
        $stmt->execute([$car_id, $customer_id, $total_price, $added_by]);

        // ب. تحديث حالة السيارة
        $db->prepare("UPDATE cars SET status = 'rented' WHERE id = ?")->execute([$car_id]);

        // ج. إضافة النقاط
        $points = floor($total_price / 100);
        $db->prepare("UPDATE customers SET points = points + ? WHERE id = ?")->execute([$points, $customer_id]);

        // د. تسجيل الإشعار (جوه الـ try)
        $msg = "تم حجز السيارة $car_id للعميل رقم $customer_id";
        $db->prepare("INSERT INTO notifications_log (message, created_at) VALUES (?, NOW())")->execute([$msg]);

        $db->commit(); // حفظ كل العمليات
        echo "<script>alert('✅ تم الحجز بنجاح!'); window.location='admin.php';</script>";
    } catch (Exception $e) {
        $db->rollBack(); // تراجع عن كل شيء لو حصل خطأ
        echo "<script>alert('❌ خطأ: " . $e->getMessage() . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إضافة حجز جديد</title>
    </head>
<body>
    <div class="form-box">
        <h2>➕ إضافة حجز جديد</h2>
        <form method="POST">
            <label>اختر السيارة:</label>
            <select name="car_id" required>
                <?php foreach ($cars as $car): ?>
                    <option value="<?php echo $car['id']; ?>"><?php echo $car['model_name']; ?></option>
                <?php endforeach; ?>
            </select>

            <label>اختر العميل:</label>
            <select name="customer_id" required>
                <?php foreach ($customers as $c): ?>
                    <option value="<?php echo $c['id']; ?>"><?php echo $c['full_name']; ?></option>
                <?php endforeach; ?>
            </select>

            <input type="number" name="total_price" placeholder="السعر الكلي" required>
            <button type="submit">حفظ الحجز</button>
        </form>
    </div>
    <br>
    <button onclick="history.back()" style="padding: 10px; cursor: pointer;">⬅️ رجوع</button>
</body>
</html>
<style>
    /* تنسيق الصفحة العامة */
    body { 
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
        background-color: #f4f7f6; 
        margin: 0; 
        padding: 40px; 
    }

    /* صندوق الفورم */
    .form-box { 
        background: white; 
        padding: 30px; 
        border-radius: 12px; 
        max-width: 450px; 
        margin: auto; 
        box-shadow: 0 10px 20px rgba(0,0,0,0.1); 
    }

    /* العنوان */
    h2 { 
        text-align: center; 
        color: #2c3e50; 
        margin-bottom: 25px; 
    }

    /* تسميات الحقول */
    label { 
        display: block; 
        margin-bottom: 8px; 
        font-weight: bold; 
        color: #34495e; 
    }

    /* تنسيق المدخلات والقوائم */
    input, select { 
        width: 100%; 
        padding: 12px; 
        margin-bottom: 20px; 
        border: 2px solid #e1e1e1; 
        border-radius: 8px; 
        box-sizing: border-box; 
        font-size: 16px;
        transition: border-color 0.3s;
    }

    input:focus, select:focus { 
        border-color: #27ae60; 
        outline: none; 
    }

    /* زر الحفظ */
    button { 
        background: #27ae60; 
        color: white; 
        padding: 14px; 
        width: 100%; 
        border: none; 
        border-radius: 8px; 
        cursor: pointer; 
        font-size: 18px; 
        font-weight: bold;
        transition: background 0.3s;
    }

    button:hover { 
        background: #219150; 
    }
</style>