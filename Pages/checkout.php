<?php
require_once "../includes/header.php";

session_start();

// Include config file
require_once "../includes/config.php";

// Check if car ID is provided
if (isset($_GET['id'])) {
    $car_id = $_GET['id'];

    // Fetch car details
    $query = "SELECT * FROM cars WHERE id = ?";
    
    // Prepare the statement
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        // Bind parameters
        mysqli_stmt_bind_param($stmt, "i", $car_id);

        // Execute the statement
        mysqli_stmt_execute($stmt);

        // Get result
        $result = mysqli_stmt_get_result($stmt);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $car = mysqli_fetch_assoc($result);
            // Store car details in session
            $_SESSION['car_details'] = $car;
        } else {
            echo "<p>السيارة غير موجودة.</p>";
            exit;
        }
    } else {
        echo "<p>فشلت عملية تحضير البيان.</p>";
        exit;
    }
} else {
    echo "<p>لم يتم توفير معرف السيارة.</p>";
    exit;
}

// Check if pickup city is selected
if (isset($_POST['pickup_city'])) {
    $_SESSION['pickup_city'] = $_POST['pickup_city'];
}

// Check if delivery city is selected
if (isset($_POST['delivery_city'])) {
    $_SESSION['delivery_city'] = $_POST['delivery_city'];
}

// Check if begin date and end date are provided
if (isset($_POST['begin_date']) && isset($_POST['end_date'])) {
    $begin_date = $_POST['begin_date'];
    $end_date = $_POST['end_date'];

    // Check if end date is after or equal to begin date
    if (strtotime($end_date) > strtotime($begin_date)) {
        // Dates are valid, proceed to payment page
        header("Location: payment.php");
        exit;
    } else {
        // Dates are invalid, display error message
        echo "<p>لا يمكن أن يكون تاريخ الانتهاء في نفس تاريخ البدء أو قبله!</p>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@100..900&display=swap" rel="stylesheet">
    <title>الدفع</title>
    <style>
        body {
            font-family: 'Noto Kufi Arabic', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #e4e9f7;
            color: #000000;
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
        form {
            margin-top: 20px;
            text-align: center;
        }
        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            color: #555;
        }
        input[type="date"], input[type="number"], input[type="submit"], input[type="text"] {
            width: calc(100% - 22px); /* Adjusted for border width */
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        input[type="date"]:focus, input[type="number"]:focus, input[type="text"]:focus {
            outline: none;
            border-color: #000100;
        }
        input[type="submit"] {
            width: 100%;
            background-color: #331F38;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #2D0733;
        }
        .car-image {
            display: block;
            margin: 0 auto;
            width: 200px;
            height: auto;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .suggestions {
            position: absolute;
            width: 38%;
            max-height: 150px;
            overflow-y: auto;
            background-color: #fff;
            border: 1px solid #ccc;
            border-top: none;
            border-radius: 0 0 5px 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            z-index: 1;
        }
        .suggestions p {
            padding: 8px 12px;
            margin: 0;
            cursor: pointer;
        }
        .suggestions p:hover {
            background-color: #f3f3f3;
        }

        .car-details {
            text-align: right;
        }
    </style>
    <script>
        // JavaScript function to validate dates
        function validateDates() {
            var beginDate = document.getElementById('begin_date').value;
            var endDate = document.getElementById('end_date').value;
            if (beginDate >= endDate) {
                alert("لا يمكن أن يكون تاريخ الانتهاء في نفس تاريخ البدء أو قبله!");
                return false;
            }
            return true;
        }

        // JavaScript function to provide autocomplete suggestions for city inputs
        document.addEventListener("DOMContentLoaded", function() {
            var pickupCityInput = document.getElementById("pickup_city");
            var deliveryCityInput = document.getElementById("delivery_city");

            var pickupCitySuggestions = document.getElementById("pickup_city_suggestions");
            var deliveryCitySuggestions = document.getElementById("delivery_city_suggestions");

            pickupCityInput.addEventListener("focus", function() {
                var inputValue = this.value.trim().toLowerCase();
                fetchCitySuggestions(inputValue, pickupCitySuggestions);
            });

            deliveryCityInput.addEventListener("focus", function() {
                var inputValue = this.value.trim().toLowerCase();
                fetchCitySuggestions(inputValue, deliveryCitySuggestions);
            });

            // Function to fetch city suggestions from the server and display them
            function fetchCitySuggestions(inputValue, suggestionsContainer) {
                suggestionsContainer.innerHTML = ""; // Clear previous suggestions

                // Sample array of city names (replace this with your actual data)
                var cities = ["الرياض", "جدة", "مكة المكرمة", "المدينة المنورة", "الدمام", "الطائف", "تبوك", "بريدة", "خميس مشيط", "حائل", "الهفوف", "المبرز", "الخبر", "الجبيل", "نجران", "أبها", "ينبع", "القصيم", "الخفجي", "الخرج", "القطيف", "عرعر", "سكاكا", "جيزان", "عنيزة", "رفحاء", "الدوادمي", "ترتيل", "بيشة", "الظهران"];

                // Display all cities as suggestions
                cities.forEach(function(city) {
                    var p = document.createElement("p");
                    p.textContent = city;
                    p.addEventListener("click", function() {
                        var selectedCity = this.textContent;
                        if (suggestionsContainer === pickupCitySuggestions) {
                            pickupCityInput.value = selectedCity;
                        } else if (suggestionsContainer === deliveryCitySuggestions) {
                            deliveryCityInput.value = selectedCity;
                        }
                        suggestionsContainer.style.display = "none";
                    });
                    suggestionsContainer.appendChild(p);
                });

                suggestionsContainer.style.display = "block";
            }

            // Close suggestions when clicking outside the input fields
            document.addEventListener("click", function(event) {
                if (event.target !== pickupCityInput && event.target !== deliveryCityInput) {
                    pickupCitySuggestions.style.display = "none";
                    deliveryCitySuggestions.style.display = "none";
                }
            });
        });
    </script>
</head>
<body>
<div class="container">
    <h1>الدفع</h1>
    
    <?php if(isset($car)) : ?>
    <img src="<?php echo $car['image_path']; ?>" alt="صورة السيارة" class="car-image">
    <div class="car-details">
    <h2>بيانات السيارة</h2>
    <p> الماركة: <?php echo $car['company']; ?></p>
    <p>الموديل: <?php echo $car['model']; ?></p>
    <p>السنة: <?php echo $car['year']; ?></p>
    <p> السعر باليوم: <?php echo $car['price']; ?> ريال</p>
    </div>
    <form action="payment.php" method="POST" onsubmit="return validateDates();">
        <label for="begin_date">تاريخ البدء:</label>
        <input type="date" id="begin_date" name="begin_date" required>
        <label for="end_date">تاريخ الانتهاء:</label>
        <input type="date" id="end_date" name="end_date" required>
        
        <label for="pickup_city">مدينة الاستلام:</label>
        <input type="text" id="pickup_city" name="pickup_city" placeholder="اختر مدينة الاستلام" readonly required>
        <div id="pickup_city_suggestions" class="suggestions"></div>

        <label for="delivery_city">مدينة التسليم:</label>
        <input type="text" id="delivery_city" name="delivery_city" placeholder="اختر مدينة التسليم" readonly required>
        <div id="delivery_city_suggestions" class="suggestions"></div>

        
        <input type="hidden" name="car_id" value="<?php echo $car_id; ?>">
        <input type="hidden" name="price_per_day" value="<?php echo $car['price']; ?>">
        <input type="submit" value="المتابعة إلى الدفع">
    </form>

    <?php else : ?>
    <p>تفاصيل السيارة غير متاحة.</p>
    <?php endif; ?>
</div>
</body>
</html>
