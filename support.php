<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/support.css">
    <title>نموذج الدعم الفني</title>
</head>
<body>

<div class="support-form-container">
    <h2>🛠️ تذكرة دعم فني جديدة</h2>
    <form action="save_support.php" method="POST">
        
        <label>اسم الموظف:</label>
        <input type="text" name="emp_name" placeholder="أدخل اسمك الكريم" required>

        <label>عنوان المشكلة:</label>
        <input type="text" name="subject" placeholder="مثال: تعليق في نظام الحجز" required>

        <label>تفاصيل المشكلة:</label>
        <textarea name="message" placeholder="اشرح المشكلة بالتفصيل..." required></textarea>

        <label>الأهمية (السعر/الأولوية):</label>
        <select name="priority">
            <option value="low">منخفضة</option>
            <option value="medium">متوسطة</option>
            <option value="high">عاجلة جداً</option>
        </select>

        <button type="submit">إرسال التذكرة</button>
    </form>
</div>
<br>
    <button onclick="history.back()" style="padding: 10px; cursor: pointer;">⬅️ رجوع</button>
</body>
</html>
<style>
    .support-form-container {
    max-width: 500px;
    margin: 40px auto;
    background: #ffffff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    border-top: 5px solid #e67e22; /* لون برتقالي مميز للدعم */
}

.support-form-container h2 { text-align: center; color: #333; margin-bottom: 20px; }

label { display: block; margin-top: 15px; font-weight: bold; color: #555; }

input, textarea, select {
    width: 100%;
    padding: 12px;
    margin-top: 5px;
    border: 1px solid #ddd;
    border-radius: 5px;
    box-sizing: border-box; /* مهم جداً عشان الحقول متخرجش بره الصندوق */
}

button {
    background: #e67e22;
    color: white;
    padding: 15px;
    width: 100%;
    border: none;
    border-radius: 5px;
    margin-top: 20px;
    font-size: 16px;
    cursor: pointer;
}
</style>

