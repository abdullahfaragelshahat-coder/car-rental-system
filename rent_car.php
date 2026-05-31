<?php
require_once 'db.php';

if (isset($_GET['id'])) {
    $car_id = $_GET['id'];
    // هنا المفروض يفتح صفحة بيانات التأجير
    echo "جاري تجهيز عملية تأجير السيارة رقم: " . $car_id;
} else {
    echo "خطأ: لم يتم تحديد سيارة!";
}
?>