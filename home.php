<?php
  session_start();
// تحميل ملفات الهيدر والتكوين
include("includes/header.php");
include("includes/config.php");

// التحقق من صحة الجلسة
if (!isset($_SESSION['valid'])) {
    header("Location: login.php");
    exit;
}

// استرجاع بيانات المستخدم من قاعدة البيانات
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
    exit("المتغير \$_SESSION['id'] غير معرف.");
}

// التحقق من وجود بيانات POST لإضافة التعليقات
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['comment']) && !empty($_POST['comment']) && isset($_POST['car_id'])) {
    // استرجاع معرف المستخدم من الجلسة
    $user_id = $_SESSION['id'] ?? null;

    // التحقق من وجود معرف المستخدم
    if ($user_id) {
        // التحضير لاستخدام البيانات بشكل آمن في الاستعلام
        $comment_text = mysqli_real_escape_string($conn, $_POST['comment']);
        $car_id = mysqli_real_escape_string($conn, $_POST['car_id']);

        // إنشاء استعلام SQL لإدخال التعليق في جدول الـ comments
        $sql = "INSERT INTO comments (user_id, comment_text, car_id) VALUES ('$user_id', '$comment_text', '$car_id')";

        // تنفيذ الاستعلام
        if (mysqli_query($conn, $sql)) {
            // عملية الإدخال ناجحة، إعادة توجيه المستخدم لتجنب إعادة إرسال النموذج
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            echo "حدث خطأ أثناء حفظ التعليق: " . mysqli_error($conn);
        }
    } else {
        echo "يبدو أن هناك مشكلة في استرجاع معرف المستخدم.";
    }
}

// حذف التعليق عند الضغط على الزر
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id']) && isset($_POST['user_id']) && isset($_POST['delete'])) {
    // استرجاع معرف التعليق ومعرف صاحب التعليق من النموذج
    $comment_id = mysqli_real_escape_string($conn, $_POST['id']);
    $comment_user_id = mysqli_real_escape_string($conn, $_POST['user_id']);

    // التحقق مما إذا كان معرف صاحب التعليق متطابقًا مع معرف المستخدم الحالي في الجلسة
    if ($comment_user_id == $id) {
        // إنشاء استعلام SQL لحذف التعليق
        $delete_sql = "DELETE FROM comments WHERE id = $comment_id";

        // تنفيذ الاستعلام لحذف التعليق
        if (mysqli_query($conn, $delete_sql)) {
            // عملية الحذف ناجحة، إعادة توجيه المستخدم لتجنب إعادة إرسال النموذج
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            echo "حدث خطأ أثناء حذف التعليق: " . mysqli_error($conn);
        }
    } else {
        echo "لا يمكنك حذف هذا التعليق لأنه لم يتم إنشاؤه بواسطتك.";
    }
}
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
    <title>Home</title>
    <style>
        /* General Styles */
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
        }
        .main-box {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            width: 90%;
            max-width: 1500px;
            margin: 0 auto;
        }
        .box, .add-comment-box {
            background: #fdfdfd;
            padding: 20px;
            border-radius: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            direction: rtl;
            unicode-bidi: embed;
            margin: 10px;
            text-align: center;
            width: calc(50% - 20px);
            box-sizing: border-box;
        }
        .comment-section {
            width: 100%;
            margin-top: 20px;
        }
        .comment-box {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-end;
            gap: 10px;
        }
        .comment-card {
            text-align: center;
            background: #fff;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            direction: rtl;
            unicode-bidi: embed;
            width: calc(25% - 20px);
            box-sizing: border-box;
        }
        h1, h2 {
            margin: 0;
            text-align: center;
        }
        .subtle-text {
            margin-top: 10px;
            text-align: center;
        }
        .image-grid {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }
        .grid-item img {
            width: 100%;
            height: auto;
            display: block;
        }
        textarea, select {
            width: calc(100% - 22px);
            max-width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 10px;
            border: 1px solid #ccc;
            box-sizing: border-box;
            resize: none;
        }
        input[type="submit"] {
            width: calc(100% - 22px);
            max-width: 100%;
            padding: 10px;
            border-radius: 10px;
            border: none;
            background-color: #423B94;
            color: white;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #5E67C7;
        }
        .delete-btn {
            background-color: #ff5252;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .delete-btn:hover {
            background-color: #d22f2f;
        }
    </style>
</head>
<body>
<main>
    <div class="main-box">
        <div class="box">
            <h1>E J A R - سيارة</h1>
            <p class="subtle-text">موقع ايجار سيارة منصة متخصصة في خدمة تأجير السيارات، سواء بشكل يومي أو لفترات زمنية محددة. يمكنك استئجار سيارة في أي وقت ومن أي مكان بكل سهولة.</p>
            <div class="image-grid">
                <div class="grid-item">
                    <img src="./images/toyota_cars.png" alt="Cars">
                </div>
            </div>
        </div>
        <div class="add-comment-box">
            <h2>اضف تعليقك</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <select name="car_id" required>
                    <option value="" disabled selected>اختر السيارة</option>
                    <?php
                    $car_query = mysqli_query($conn, "SELECT DISTINCT cars.id, cars.company, cars.model FROM cars INNER JOIN orders ON cars.id = orders.car_id WHERE orders.user_id = $id");
                    while ($car_row = mysqli_fetch_assoc($car_query)) {
                        $car_id = $car_row['id'];
                        $car_name = htmlspecialchars($car_row['company'] . ' ' . $car_row['model'], ENT_QUOTES, 'UTF-8');
                        echo "<option value=\"$car_id\">$car_name</option>";
                    }
                    ?>
                </select>
                <textarea name="comment" rows="4" placeholder="أضف تعليقك هنا..." required></textarea>
                <br>
                <input type="submit" value="إرسال">
            </form>
        </div>
    </div>
    <div class="comment-section">
        <div class="comment-box">
            <?php
            $sql = "SELECT * FROM comments";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    $comment_user_id = $row["user_id"];
                    $comment_id = $row["id"];
                    $comment_text = $row["comment_text"];
                    $car_id = $row["car_id"];

                    $user_query = mysqli_query($conn, "SELECT * FROM users WHERE Id=$comment_user_id");
                    $user_result = mysqli_fetch_assoc($user_query);
                    $comment_user_name = $user_result["Username"];

                    $car_query = mysqli_query($conn, "SELECT company, model FROM cars WHERE id=$car_id");
                    $car_result = mysqli_fetch_assoc($car_query);
                    $car_name = htmlspecialchars($car_result['company'] . ' ' . $car_result['model'], ENT_QUOTES, 'UTF-8');

                    echo "<div class='comment-card'>";
                    echo "<p>المستخدم: <b>$comment_user_name</b></p>";
                    echo "<p>السيارة: <b>$car_name</b></p>";
                    echo "<p>التعليق: <b>$comment_text</b></p>";

                    // تحقق إذا كان المستخدم الحالي هو صاحب التعليق لعرض زر الحذف
                    if ($comment_user_id == $_SESSION['id']) {
                        echo "<form action='' method='POST'>
                                <input type='hidden' name='id' value='$comment_id'>
                                <input type='hidden' name='user_id' value='$comment_user_id'>
                                <button type='submit' name='delete' class='delete-btn'>حذف التعليق</button>
                              </form>";
                    }
                    echo "</div>";
                }
            } else {
                echo "<p>لا توجد تعليقات بعد.</p>";
            }
            ?>
        </div>
    </div>
</main>
<footer>

</footer>
</body>
</html>
