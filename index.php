<?php
require_once 'db.php';

// تجهيز متغيرات الفلترة والبحث
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter_status = isset($_GET['filter_status']) ? trim($_GET['filter_status']) : '';

// بناء الاستعلام الديناميكي حسب اختيار الزبون
$sql = "SELECT * FROM cars WHERE status != 'sold'";
$params = [];

if ($search !== '') {
    $sql .= " AND model_name LIKE ?";
    $params[] = "%$search%";
}

if ($filter_status === 'available') {
    $sql .= " AND status = 'available'";
} elseif ($filter_status === 'rented') {
    $sql .= " AND status = 'rented'";
}

$sql .= " ORDER BY id DESC";

$stmt_cars = $db->prepare($sql);
$stmt_cars->execute($params);
$cars = $stmt_cars->fetchAll();

// معالجة طلب الحجز وإرسال الإيميل
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $car_id = filter_var($_POST['car_id'], FILTER_VALIDATE_INT);
    $customer_name = trim(htmlspecialchars($_POST['customer_name'], ENT_QUOTES, 'UTF-8'));
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $price_per_day = filter_var($_POST['price_per_day'], FILTER_VALIDATE_FLOAT);

    $time_diff = strtotime($end_date) - strtotime($start_date);
    $total_days = $time_diff / (60 * 60 * 24);
    $total_price = $total_days * $price_per_day;

    if (!$car_id || !$price_per_day) {
        echo "<script>alert('❌ بيانات غير صحيحة!');</script>";
    } elseif (empty($customer_name) || strlen($customer_name) < 3) {
        echo "<script>alert('❌ يرجى كتابة اسم صحيح!');</script>";
    } elseif ($total_days <= 0) {
        echo "<script>alert('❌ خطأ في التواريخ!');</script>";
    } else {
        // فحص تضارب المواعيد
        $check_stmt = $db->prepare("SELECT COUNT(*) AS overlap_count FROM rentals WHERE car_id = ? AND NOT (end_date <= ? OR start_date >= ?)");
        $check_stmt->execute([$car_id, $start_date, $end_date]);
        if ($check_stmt->fetch()['overlap_count'] > 0) {
            echo "<script>alert('❌ هذه السيارة محجوزة في هذه التواريخ بالفعل!');</script>";
        } else {
            // حفظ الحجز
            $insert_stmt = $db->prepare("INSERT INTO rentals (car_id, customer_name, start_date, end_date, total_price) VALUES (?, ?, ?, ?, ?)");
            $insert_stmt->execute([$car_id, $customer_name, $start_date, $end_date, $total_price]);
            $last_rental_id = $db->lastInsertId();

            $update_stmt = $db->prepare("UPDATE cars SET status = 'rented' WHERE id = ?");
            $update_stmt->execute([$car_id]);

            // 📧 تفعيل إشعار الإيميل تلقائياً للمدير بعد الحجز
            $to_admin = "admin@yourcompany.com"; // اكتب إيميل شركتك هنا
            $subject = "🚨 حجز جديد بانتظارك في السيستم #$last_rental_id";
            $message = "مرحباً يا مدير، تم تسجيل حجز جديد حالاً:\n\n" .
                       "اسم العميل: $customer_name\n" .
                       "من تاريخ: $start_date\n" .
                       "إلى تاريخ: $end_date\n" .
                       "الإجمالي المحصل: $total_price جنيه\n\n" .
                       "يرجى الدخول للوحة التحكم لمراجعة الحجز وتسليم السيارة.";
            $headers = "From: noreply@yourcompany.com\r\n" . "Reply-To: noreply@yourcompany.com\r\n" . "Content-Type: text/plain; charset=UTF-8";
            
            // إرسال الإيميل عبر سيرفر الميجريشن (أو محلياً عبر XAMPP Sendmail)
            @mail($to_admin, $subject, $message, $headers);

            echo "<script>alert('✅ تم الحجز بنجاح وإرسال إشعار فوري لإيميل الإدارة!'); window.location='invoice.php?rental_id=$last_rental_id';</script>";
            exit();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>معرض السيارات الذكي</title>
    <style>
    /* تحسين الخطوط والخلفية */
    body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 20px; background-color: #f8f9fa; color: #2d3436; }
    
    h1 { text-align: center; color: #2d3436; margin-bottom: 10px; font-weight: 700; }
    .sub-title { text-align: center; color: #636e72; margin-bottom: 30px; font-size: 1.1em; }

    /* شريط البحث */
    .search-box { 
        background: white; padding: 25px; border-radius: 15px; 
        box-shadow: 0 10px 25px rgba(0,0,0,0.05); 
        margin-bottom: 40px; display: flex; gap: 15px; 
        justify-content: center; align-items: center; 
        flex-wrap: wrap;
    }
    .search-box input, .search-box select { 
        padding: 12px 18px; border: 2px solid #e1e1e1; 
        border-radius: 10px; font-size: 15px; transition: 0.3s;
    }
    .search-box input:focus { border-color: #0984e3; outline: none; }
    
    .btn-search { background-color: #0984e3; color: white; border: none; padding: 12px 25px; cursor: pointer; border-radius: 10px; font-weight: 600; transition: 0.3s; }
    .btn-search:hover { background-color: #74b9ff; }

    /* شبكة السيارات (الكروت) */
    .car-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; justify-items: center; max-width: 1200px; margin: auto; }
    
    .car-card { 
        background: white; padding: 20px; border-radius: 20px; 
        box-shadow: 0 15px 30px rgba(0,0,0,0.1); 
        transition: transform 0.3s ease; width: 100%; max-width: 320px;
    }
    .car-card:hover { transform: translateY(-10px); }
    
    .car-img { width: 100%; height: 200px; object-fit: cover; border-radius: 15px; margin-bottom: 15px; }
    
    .rental-form input { width: 100%; padding: 12px; margin-bottom: 12px; border: 1px solid #dfe6e9; border-radius: 8px; box-sizing: border-box; }
    
    .btn-rent { 
        background: linear-gradient(135deg, #00b894, #009432); 
        color: white; border: none; padding: 15px; width: 100%; 
        cursor: pointer; border-radius: 10px; font-weight: bold; font-size: 16px;
    }
    
    .btn-nav { background-color: #6c5ce7; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; }
</style>
        
</head>
<body>

    <h1>🚗 معرض السيارات الفاخرة ونظام الحجز الذكي</h1>
    <div class="sub-title">ابحث عن سيارتك وفلتر النتائج للحجز الفوري</div>

    <div class="top-bar">
        <div>
            
            <a href="admin.php" class="btn-nav">🔑 لوحة الإدارة</a>
        </div>
    </div>

    <!-- 🔍 شريط البحث والفلترة الذكي المطور -->
    <form method="GET" class="search-box">
        <input type="text" name="search" placeholder="🔍 اكتب اسم أو ماركة السيارة..." value="<?php echo htmlspecialchars($search); ?>" style="width: 300px;">
        
        <select name="filter_status">
            <option value="">كل السيارات (المتاحة والمستأجرة)</option>
            <option value="available" <?php if($filter_status == 'available') echo 'selected'; ?>>🟢 السيارات المتاحة بالجراج حالياً فقط</option>
            <option value="rented" <?php if($filter_status == 'rented') echo 'selected'; ?>>⏰ السيارات المستأجرة بالخارج حالياً</option>
        </select>
        
        <button type="submit" class="btn-search">تطبيق الفلتر والبحث 🚀</button>
        <?php if($search !== '' || $filter_status !== ''): ?>
            <a href="index.php" class="btn-reset">إلغاء البحث ✖</a>
        <?php endif; ?>
    </form>

    <!-- عرض الكروت -->
    <div class="car-grid">
        <?php if(count($cars) > 0): ?>
            <?php foreach($cars as $car): ?>
                <div class="car-card">
                    <img src="uploads/<?php echo $car['car_image']; ?>" class="car-img" onerror="this.src='https://placehold.co/310x170?text=No+Image';">
                    <h3 style="text-align:center; margin:0 0 10px 0;"><?php echo htmlspecialchars($car['model_name']); ?></h3>
                    <p style="margin-bottom:8px;">السعر اليومي: <strong style="color:#28a745;"><?php echo number_format($car['price_per_day'], 2); ?> جنيه</strong></p>
                    <p>الحالة: <?php echo ($car['status'] == 'available') ? '<span style="color:green;font-weight:bold;">متاحة 🟢</span>' : '<span style="color:orange;font-weight:bold;">مستأجرة ⏰</span>'; ?></p>
                    
                    <form class="rental-form" method="POST">
                        <input type="hidden" name="car_id" value="<?php echo $car['id']; ?>">
                        <input type="hidden" name="price_per_day" value="<?php echo $car['price_per_day']; ?>">
                        <input type="text" name="customer_name" placeholder="اكتب اسمك بالكامل" required>
                        <label style="font-size:12px; font-weight:bold;">من تاريخ:</label>
                        <input type="date" name="start_date" required>
                        <label style="font-size:12px; font-weight:bold;">إلى تاريخ:</label>
                        <input type="date" name="end_date" required>
                        <button type="submit" class="btn-rent">تأكيد الحجز الفوري</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align:center; font-weight:bold; font-size:18px; color:#dc3545; width:100%;">❌ عذراً، لا توجد سيارات تطابق بحثك الحالي!</p>
        <?php endif; ?>
    </div>

</body>
</html>