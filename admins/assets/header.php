<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@100..900&display=swap" rel="stylesheet">
    <style>
        /* Adjusted CSS for the navbar */
        .navbar {
            background: #fff;
            display: flex;
            flex-direction: row;
            justify-content: space-around;
            align-items: center;
            line-height: 60px;
            z-index: 100;
            padding: 0 20px;
        }
        .navbar .nav-logo {
            font-size: 25px;
            font-weight: 900;
        }
        .navbar .nav-logo a {
            text-decoration: none;
            color: #000;
        }
        .navbar .nav-links {
            display: flex;
            align-items: center;
        }
        .navbar .nav-links a {
            padding: 0 10px;
        }
        .navbar .navbtn {
            background-color: #4c44b6;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none; /* Remove underline */
        }
        .navbar .navbtn:hover {
            opacity: 0.82;
        }
        /* Applying the Noto Kufi Arabic font */
        body, h2, label, select, input, button, .car h3, .car p {
            font-family: 'Noto Kufi Arabic', sans-serif;
        }
    </style>
</head>
<body>
<div class="navbar">
    <div class="nav-logo">
        <p><a href="../Dashboard.php">E J A R - سيارة</a></p>
    </div>
    <div class="nav-links">
        <?php
        // التحقق من الصفحة الحالية
        $currentPage = basename($_SERVER['PHP_SELF']);

        // عرض الأزرار بناءً على الصفحة الحالية
        if ($currentPage == "Dashboard.php") {
            // عرض الأزرار الخاصة بالصفحة الرئيسية
            echo '
                <a href="./Pages/orders.php"><button class="navbtn">ادارة الطلبات</button></a>
                <a href="./Pages/cars.php"><button class="navbtn">تعديل وعرض السيارات</button></a>
                <a href="./Pages/add_cars.php"><button class="navbtn">إضافة سيارة</button></a>
                <a href="Pages/users.php"><button class="navbtn">عرض بيانات المستخدمين</button></a>
                <a href="./assets/logout.php"><button class="navbtn">تسجيل الخروج</button></a>
            ';
        } elseif ($currentPage == "users.php") {
            // عرض الأزرار الخاصة بصفحة عرض السيارات
            echo '
                <a href="../Dashboard.php"><button class="navbtn">العودة</button></a>
            ';
        } 
        elseif ($currentPage == "cars.php") {
            // عرض الأزرار الخاصة بصفحة اضافة السيارات
            echo '
                <a href="../Dashboard.php"><button class="navbtn">العودة</button></a>
            ';
        }
        elseif ($currentPage == "add_cars.php") {
            // عرض الأزرار الخاصة بصفحة اضافة السيارات
            echo '
                <a href="../Dashboard.php"><button class="navbtn">العودة</button></a>
            ';
        }
        elseif ($currentPage == "edit.php") {
            // عرض الأزرار الخاصة بصفحة عرض السيارات
            echo '
                <a href="../home.php"><button class="navbtn">الصفحة الرئيسية</button></a>
                <a href="./logout.php"><button class="navbtn">تسجيل الخروج</button></a>
            ';
        }
        elseif ($currentPage == "checkout.php") {
            // عرض الأزرار الخاصة بصفحة عرض السيارات
            echo '
                <a href="../home.php"><button class="navbtn">العودة</button></a>
                <a href="./logout.php"><button class="navbtn">تسجيل الخروج</button></a>
            ';
        }
        elseif ($currentPage == "orders.php") {
            // عرض الأزرار الخاصة بصفحة عرض السيارات
            echo '
                <a href="../Dashboard.php"><button class="navbtn">العودة</button></a>
            ';
        }
        ?>
    </div>
</div>
</body>
</html>
