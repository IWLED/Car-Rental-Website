<?php
include 'config.php';

$id = $_SESSION['id'] ?? null;
if ($id) {
    $query = mysqli_query($conn, "SELECT * FROM users WHERE Id=$id");
    if ($query) {
        $result = mysqli_fetch_assoc($query);
        if ($result) {
            $res_Uname = htmlspecialchars($result['Username'], ENT_QUOTES, 'UTF-8');
            $res_Email = htmlspecialchars($result['Email'], ENT_QUOTES, 'UTF-8');
            $res_id = $result['Id'];
        } else {
            exit("لا يوجد بيانات للمستخدم.");
        }
    } else {
        exit("خطأ في استعلام SQL: " . mysqli_error($conn));
    }
} else {
    
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الصفحة الرئيسية</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@100..900&display=swap" rel="stylesheet">
    <style>
        body, h2, label, select, input, button, .car h3, .car p {
            font-family: 'Noto Kufi Arabic', sans-serif;
        }
        .nav {
            background: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .logo {
            font-size: 25px;
            font-weight: 900;
        }
        .logo a {
            text-decoration: none;
            color: #000;
        }
        .right-links {
            display: flex;
            align-items: center;
        }
        .right-links a {
            margin: 0 10px;
        }
        .btn {
            background-color: #4c44b6;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            opacity: 0.82;
        }
        .userIcon {
            width: 50px;
            height: 50px;
            cursor: pointer;
            border-radius: 50%;
            margin-left: 20px;
        }
        .popover {
            display: none;
            position: absolute;
            top: 60px;
            right: 10px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 10px;
            z-index: 200;
            text-align: center;
        }
        .popover p {
            margin: 5px 0;
        }
    </style>
    <script>
        function togglePopover() {
            var popover = document.getElementById('userPopover');
            popover.style.display = (popover.style.display === 'block') ? 'none' : 'block';
        }
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("userIcon").addEventListener("click", togglePopover);
        });
    </script>
</head>
<body>
<div class="nav">
    <div class="logo">
        <a href="../../Rent_Cars/home.php">E J A R - سيارة</a>
    </div>
    <div class="right-links">
        <?php
        // التحقق من الصفحة الحالية
        $currentPage = basename($_SERVER['PHP_SELF']);

        // عرض الأزرار بناءً على الصفحة الحالية
        if ($currentPage == "home.php") {
            // عرض الأزرار الخاصة بالصفحة الرئيسية
            echo '
                <a href="Pages/show_cars.php"><button class="btn">معرض السيارات</button></a>
                <a href="Pages/order.php"><button class="btn">الطلبات</button></a>
                <a href="Pages/edit.php"><button class="btn">تعديل الملف الشخصي</button></a>
                <a href="./logout.php"><button class="btn">تسجيل الخروج</button></a>
                <img src="./images/image-removebg-preview.png" alt="user" id="userIcon" class="userIcon">
                <div id="userPopover" class="popover">
                    <p>' . $res_Uname . '</p>
                    <p>' . $res_Email . '</p>
                </div>
            ';
        } elseif ($currentPage == "show_cars.php") {
            // عرض الأزرار الخاصة بصفحة عرض السيارات
            echo '
                <a href="../home.php"><button class="btn">العودة</button></a>
                <a href="/Rent_Cars/logout.php"><button class="btn">تسجيل الخروج</button></a>
            ';
        } elseif ($currentPage == "edit.php") {
            // عرض الأزرار الخاصة بصفحة تعديل الملف الشخصي
            echo '
                <a href="../home.php"><button class="btn">العودة</button></a>
                <a href="../logout.php"><button class="btn">تسجيل الخروج</button></a>
            ';
        } elseif ($currentPage == "checkout.php") {
            // عرض الأزرار الخاصة بصفحة الدفع
            echo '
                <a href="../home.php"><button class="btn">العودة</button></a>
                <a href="/Rent_Cars/logout.php"><button class="btn">تسجيل الخروج</button></a>
            ';
        } elseif ($currentPage == "payment.php") {
            // عرض الأزرار الخاصة بصفحة الدفع
            echo '
                <a href=".././home.php"><button class="btn">الصفحة الرئيسية</button></a>
                <a href="/Rent_Cars/logout.php"><button class="btn">تسجيل الخروج</button></a>
            ';
        } elseif ($currentPage == "order.php") {
            // عرض الأزرار الخاصة بصفحة الطلبات
            echo '
                <a href="../home.php"><button class="btn">العودة</button></a>
                <a href="/Rent_Cars/logout.php"><button class="btn">تسجيل الخروج</button></a>
            ';
        }
        ?>
    </div>
</div>
</body>
</html>
