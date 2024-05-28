<?php 
include("./assets/header.php");
session_start();
include(".././includes/config.php");

if(!isset($_SESSION['valid'])){
    header("Location: /admin_login.php");
}

// Query to get users count
$sql_users_count = "SELECT COUNT(*) AS users_count FROM users";
$result_users_count = mysqli_query($conn, $sql_users_count);
$row_users_count = mysqli_fetch_assoc($result_users_count);
$users_count = $row_users_count['users_count'];

// Query to get orders count
$sql_orders_count = "SELECT COUNT(*) AS orders_count FROM orders";
$result_orders_count = mysqli_query($conn, $sql_orders_count);
$row_orders_count = mysqli_fetch_assoc($result_orders_count);
$orders_count = $row_orders_count['orders_count'];

// Query to get cars count
$sql_cars_count = "SELECT COUNT(*) AS cars_count FROM cars";
$result_cars_count = mysqli_query($conn, $sql_cars_count);
$row_cars_count = mysqli_fetch_assoc($result_cars_count);
$cars_count = $row_cars_count['cars_count'];

// Query to get comments count
$sql_comments_count = "SELECT COUNT(*) AS comments_count FROM comments";
$result_comments_count = mysqli_query($conn, $sql_comments_count);
$row_comments_count = mysqli_fetch_assoc($result_comments_count);
$comments_count = $row_comments_count['comments_count'];
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@100..900&display=swap" rel="stylesheet">
    <title>لوحة التحكم</title>
    <style>
        body {
            font-family: 'Noto Kufi Arabic', sans-serif;
            margin: 0;
            padding: 0;
            background: #e4e9f7;
        }
        main {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 60px;
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
        }
        .main-box {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            margin-bottom: 40px;
        }
        .box, .right-box {
            background: #fff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 45%;
            margin: 10px; /* Add margin to separate the boxes */
        }
        .box {
            text-align: center;
            margin-top: 90px;
        }
        .right-box {
            text-align: right;
        }
        .box img {
            width: 150px;
            margin: 0 auto;
            display: block;
        }
        .box p {
            margin: 10px 0;
            font-size: 18px;
        }
        .statistics-box {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap; /* Allow the items to wrap */
            width: 100%;
            margin-top: 40px;
        }
        .statistics-box div {
            background: #fff;
            padding: 10px;
            border-radius: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 20%;
            text-align: center;
            margin: 10px; /* Add margin to separate the statistics boxes */
        }
        .statistics-box h2 {
            font-size: 22px;
            color: #4c44b6;
            margin-bottom: 10px;
        }
        .statistics-box h1 {
            font-size: 36px;
            color: #333;
        }
    </style>
</head>
<body>

    <?php
        $id = $_SESSION['id'];
        $query = mysqli_query($conn, "SELECT * FROM admins WHERE Id = $id");
        while($result = mysqli_fetch_assoc($query)){
            $res_Uname = $result['username'];
            $res_Email = $result['email'];
            $res_id = $result['id'];
        }
    ?>

    <main>
        <div class="main-box">
            <div class="box">
                <img src=".././images/image-removebg-preview.png" alt="admin">
                <p><b><?php echo $res_Email ?></b> :البريد الالكتروني</p>
                <p><b><?php echo $res_Uname ?></b> :اسم المستخدم</p>
            </div>
            <div class="right-box">
                <h1>لوحة تحكم المشرفين |<br>E J A R - سيارة |</br></h1>
                
            
            </div>
        </div>

        <div class="statistics-box">
            <div>
                <h2>عدد المستخدمين </h2>
                <h1><?php echo $users_count; ?></h1>
            </div>
            <div>
                <h2>عدد الطلبات</h2>
                <h1><?php echo $orders_count; ?></h1>
            </div>
            <div>
                <h2>عدد السيارات</h2>
                <h1><?php echo $cars_count; ?></h1>
            </div>
            <div>
                <h2>عدد التعليقات</h2>
                <h1><?php echo $comments_count; ?></h1>
            </div>
        </div>
    </main>

</body>
</html>
