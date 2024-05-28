<?php
require_once "../includes/config.php";

session_start();
// التحقق مما إذا تم إرسال النموذج
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // التحقق مما إذا تم تقديم جميع الحقول المطلوبة
    if (isset($_POST['car_id'], $_POST['begin_date'], $_POST['end_date'], $_POST['pickup_city'], $_POST['delivery_city'], $_POST['total_price'], $_POST['card_number'], $_POST['expiry_date'], $_POST['cvv'], $_POST['card_holder_name'])) {
        // استخراج البيانات من POST
        $car_id = $_POST['car_id'];
        $begin_date = $_POST['begin_date'];
        $end_date = $_POST['end_date'];
        $pickup_city = $_POST['pickup_city'];
        $delivery_city = $_POST['delivery_city'];
        $total_price = $_POST['total_price'];
        $card_number = $_POST['card_number'];
        $expiry_date = $_POST['expiry_date'];
        $cvv = $_POST['cvv'];
        $card_holder_name = $_POST['card_holder_name'];

        // إدراج الطلب في جدول الطلبات
        $sql = "INSERT INTO orders (user_id, car_id, rental_date, return_date, pickup_city, delivery_city, total_price) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);

        // التحقق من نجاح الاستعلام المُعد 
        if ($stmt) {
            // ربط المتغيرات ببيانات الاستعلام
            mysqli_stmt_bind_param($stmt, "iissssd", $_SESSION['id'], $car_id, $begin_date, $end_date, $pickup_city, $delivery_city, $total_price);

            // تنفيذ الاستعلام
            if (mysqli_stmt_execute($stmt)) {
                // تحديث عدد الكمية في جدول السيارات
                $update_sql = "UPDATE cars SET quantity = quantity - 1 WHERE id = ?";
                $update_stmt = mysqli_prepare($conn, $update_sql);
                mysqli_stmt_bind_param($update_stmt, "i", $car_id);
                mysqli_stmt_execute($update_stmt);

                $message = "تم حفظ الطلب بنجاح.";
            } else {
                $error = "حدث خطأ أثناء حفظ الطلب: " . mysqli_error($conn);
            }

            // إغلاق الاستعلام
            mysqli_stmt_close($stmt);
        } else {
            $error = "حدث خطأ أثناء إعداد الاستعلام: " . mysqli_error($conn);
        }
    } else {
        $error = "يجب اكمال الحقول المطلوبة.";
    }
} else {
    $error = "طلب غير صالح.";
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الدفع</title>
    <style>
        /* تنسيقات CSS */
        body {
            font-family: 'Noto Kufi Arabic', sans-serif;
            background: #e4e9f7;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            direction: rtl; /* تأكيد الاتجاه من اليمين إلى اليسار */
            unicode-bidi: embed; /* تثبيت النص داخل المربع */
        }
        h1, h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .message-box {
            text-align: center;
            margin-bottom: 20px;
        }
        .message-box p {
            color: blue;
        }
        .car-info {
            text-align: center;
            margin-bottom: 20px;
        }
        .car-info img {
            width: 200px;
            height: auto;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .car-details p {
            text-align: right;
            margin: 5px 0;
        }
        .total-price {
            text-align: center;
            margin-bottom: 20px;
        }
        .total-price p {
            font-weight: bold;
        }
        .payment-form label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            color: #555;
        }
        .payment-form input[type="text"],
        .payment-form input[type="number"],
        .payment-form input[type="date"] {
            width: calc(100% - 22px);
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        .payment-form input[type="text"]:focus,
        .payment-form input[type="number"]:focus,
        .payment-form input[type="date"]:focus {
            outline: none;
            border-color: #4CAF50;
        }
        .payment-form .btn-pay {
            width: 100%;
            padding: 15px;
            background-color: #4CAF50;
            color: white;
            text-align: center;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .payment-form .btn-pay:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?> <!-- استيراد شريط التنقل -->

    <div class="container">
        <h1>الدفع</h1>
        <div class="message-box">
            <?php if (isset($message)) : ?>
                <p><?php echo $message; ?></p>
            <?php elseif (isset($error)) : ?>
                <p><?php echo $error; ?></p>
            <?php endif; ?>
        </div>

        <?php
        // التحقق من وجود معرف السيارة وتواريخ الاستئجار والسعر المحدد
        if (isset($_POST['car_id'], $_POST['begin_date'], $_POST['end_date'], $_POST['price_per_day'])) {
            $car_id = $_POST['car_id'];
            $begin_date = $_POST['begin_date'];
            $end_date = $_POST['end_date'];
            $price_per_day = $_POST['price_per_day'];

            // الاستعلام عن تفاصيل السيارة
            $query = "SELECT * FROM cars WHERE id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "i", $car_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result && mysqli_num_rows($result) > 0) {
                $car = mysqli_fetch_assoc($result);
                $total_days = (strtotime($end_date) - strtotime($begin_date)) / (60 * 60 * 24);
                $total_price = $total_days * $price_per_day;
        ?>
                <div class="car-info">
                    <img src="<?php echo $car['image_path']; ?>" alt="صورة السيارة">
                    <h2>السيارة المختارة</h2>
                    <div class="car-details">
                        <p><strong> الشركة: </strong><?php echo $car['company']; ?></p>
                        <p><strong> الموديل: </strong><?php echo $car['model']; ?></p>
                        <p><strong> اللون: </strong><?php echo $car['color']; ?></p>
                        <p><strong> السنة: </strong><?php echo $car['year']; ?></p>
                        <p><strong> السعر باليوم: </strong><?php echo $car['price']; ?> ريال </p>
                    </div>
                </div>

                <div class="car-details">
                    <h2>تفاصيل الإيجار</h2>
                    <p><strong> تاريخ البدء: </strong><?php echo $begin_date; ?></p>
                    <p><strong> تاريخ الانتهاء: </strong><?php echo $end_date; ?></p>
                    <p><strong> مدينة الاستلام: </strong><?php echo $_POST['pickup_city']; ?></p>
                    <p><strong> مدينة التسليم: </strong><?php echo $_POST['delivery_city']; ?></p>
                </div>

                <div class="total-price">
                    <p><strong>السعر الإجمالي </strong><?php echo $total_price; ?> ريال</p>
                </div>

                <!-- نموذج الدفع -->
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="payment-form">
                    <input type="hidden" name="car_id" value="<?php echo $car_id; ?>">
                    <input type="hidden" name="begin_date" value="<?php echo $begin_date; ?>">
                    <input type="hidden" name="end_date" value="<?php echo $end_date; ?>">
                    <input type="hidden" name="pickup_city" value="<?php echo $_POST['pickup_city']; ?>">
                    <input type="hidden" name="delivery_city" value="<?php echo $_POST['delivery_city']; ?>">
                    <input type="hidden" name="total_price" value="<?php echo $total_price; ?>">

                    <h2>بيانات بطاقة الائتمان</h2>
                    <label for="card_number">رقم البطاقة:</label>
                    <input type="text" id="card_number" name="card_number" placeholder="أدخل رقم البطاقة" required>
                    
                    <label for="expiry_date">تاريخ الانتهاء (MM/YYYY):</label>
                    <input type="text" id="expiry_date" name="expiry_date" pattern="\d{2}/\d{4}" placeholder="MM/YYYY" required>

                    <label for="cvv">CVV (3 أرقام):</label>
                    <input type="text" id="cvv" name="cvv" pattern="\d{3}" placeholder="أدخل CVV" required>

                    <label for="card_holder_name">اسم صاحب البطاقة:</label>
                    <input type="text" id="card_holder_name" name="card_holder_name" placeholder="أدخل اسم صاحب البطاقة" required>
                    
                    <button type="submit" class="btn-pay">ادفع الآن</button>
                </form>
        <?php
            } else {
                echo "<p>تفاصيل السيارة غير موجودة.</p>";
            }
        } 
        ?>
    </div>
</body>
</html>
