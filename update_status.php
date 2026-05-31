<?php
require_once 'db.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $new_status = $_POST['new_status'];
    
    // تحديث الحالة بناءً على اختيارك
    $stmt = $db->prepare("UPDATE support_tickets SET status = ? WHERE id = ?");
    $stmt->execute([$new_status, $id]);
    
    header("Location: admin_support.php");
    exit();
}
?>