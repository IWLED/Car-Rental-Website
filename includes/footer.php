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
    exit("لم يتم تسجيل الدخول.");
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        .footer {
            background: #4c44b6;
            color: #fff;
            padding: 20px 0;
            text-align: center;
            margin-top: 30px;
            font-family: 'Noto Kufi Arabic', sans-serif;
        }
        .footer a {
            color: #fff;
            margin: 0 10px;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .footer a:hover {
            color: #d4d4d4;
        }
        .footer .social-icons {
            margin-top: 10px;
        }
        .footer .social-icons img {
            width: 30px;
            margin: 0 5px;
            transition: transform 0.3s ease;
        }
        .footer .social-icons img:hover {
            transform: scale(1.1);
        }
    </style>
</head>
<body>
    <div class="footer">
        <p>&copy; 2024 E J A R - سيارة. جميع الحقوق محفوظة.</p>
        <div>
            <a href="../../Rent_Cars/home.php">الصفحة الرئيسية</a>
            <a href="../../Rent_Cars/about.php">عن الموقع</a>
            <a href="../../Rent_Cars/contact.php">اتصل بنا</a>
            <a href="../../Rent_Cars/privacy.php">سياسة الخصوصية</a>
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
