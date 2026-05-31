<?php
session_start();
require_once 'db.php'; // تأكد أن هذا الملف يربطك بقاعدة البيانات بشكل صحيح

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $emp_name = $_POST['emp_name'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $priority = $_POST['priority'];

    try {
        $stmt = $db->prepare("INSERT INTO support_tickets (emp_name, subject, message, priority) VALUES (?, ?, ?, ?)");
        $stmt->execute([$emp_name, $subject, $message, $priority]);

        echo "<script>alert('✅ تم إرسال طلب الدعم بنجاح!'); window.location='support.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('❌ حدث خطأ: " . $e->getMessage() . "');</script>";
    }
}
?>