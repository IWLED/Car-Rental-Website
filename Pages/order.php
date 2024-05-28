<?php

session_start();
// تحميل ملفات الهيدر والتكوين
include("../includes/header.php");
include("../includes/config.php");

// التحقق من صحة الجلسة
if (!isset($_SESSION['valid'])) {
    header("Location: login.php");
    exit;
}

// استخراج معرف المستخدم من الجلسة
$id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>قائمة الطلبات</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@100..900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Noto Kufi Arabic', sans-serif;
            margin: 0;
            padding: 0;
            background: #e4e9f7;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        h2 {
            text-align: center;
            margin-top: 20px;
        }
        body, h2, label, select, input, button, .car h3, .car p {
            font-family: 'Noto Kufi Arabic', sans-serif;
        }
    </style>
</head>
<body>
    <?php
    // التحقق من وجود معرف المستخدم
    if ($id) {
        // استعلام SQL لاسترداد جميع الطلبات الخاصة بالمستخدم
        $query = mysqli_query($conn, "SELECT * FROM orders WHERE user_id=$id");

        // التحقق من نجاح الاستعلام
        if ($query) {
            // التحقق من وجود نتائج
            if (mysqli_num_rows($query) > 0) {
                // عرض الطلبات في حالة وجودها
                echo "<h2>قائمة الطلبات الخاصة بك</h2>";
                // عرض الطلبات في جدول
                echo "<table>";
                echo "<tr><th>رقم الطلب</th><th>تاريخ الاستلام</th><th>تاريخ التسليم</th><th>مدينة الاستلام</th><th>مدينة التسليم</th><th>اسم السيارة</th><th>الموديل</th><th>اللون</th><th>السنة</th><th>المبلغ الاجمالي</th></tr>";
                // عرض كل صف من النتائج
                while ($row = mysqli_fetch_assoc($query)) {
                    echo "<tr>";
                    echo "<td>".$row['id']."</td>";
                    echo "<td>".$row['rental_date']."</td>";
                    echo "<td>".$row['return_date']."</td>";
                    echo "<td>".$row['pickup_city']."</td>";
                    echo "<td>".$row['delivery_city']."</td>";

                    // استعلام لاسترداد بيانات السيارة المرتبطة برقم السيارة
                    $car_id = $row['car_id'];
                    $car_query = mysqli_query($conn, "SELECT * FROM cars WHERE id=$car_id");
                    $car_data = mysqli_fetch_assoc($car_query);
                    echo "<td>".$car_data['company']."</td>";
                    echo "<td>".$car_data['model']."</td>";
                    echo "<td>".$car_data['color']."</td>";
                    echo "<td>".$car_data['year']."</td>";
                    echo "<td>".$row['total_price']."</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                // عرض رسالة إذا لم تكن هناك طلبات
                echo "<h2>لا توجد طلبات حاليًا.</h2>";
            }
        } else {
            // عرض رسالة خطأ إذا فشل الاستعلام
            echo "<p>خطأ في استعلام SQL: " . mysqli_error($conn) . "</p>";
        }
    } else {
        // عرض رسالة إذا لم يتم تحديد معرف المستخدم
        echo "<p>المتغير \$_SESSION['id'] غير معرف.</p>";
    }

    // إغلاق اتصال قاعدة البيانات
    mysqli_close($conn);
    ?>
</body>
</html>
