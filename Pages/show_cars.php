<?php

session_start();
// Include config file
require_once "../includes/config.php";

// Include header file
require_once "../includes/header.php";

// Redirect to login page if user is not logged in
if (!isset($_SESSION['valid'])) {
    header("Location: ../login.php");
    exit;
}

// Define base SQL query to retrieve cars data
$sql = "SELECT * FROM cars";

// Check if filters are applied
if (isset($_GET['company']) && !empty($_GET['company'])) {
    $company = $_GET['company'];
    $sql .= " WHERE company = '$company'";
}

if (isset($_GET['color']) && !empty($_GET['color'])) {
    $color = $_GET['color'];
    // Check if other filters have been applied
    $sql .= isset($_GET['company']) && !empty($_GET['company']) ? " AND" : " WHERE";
    $sql .= " color = '$color'";
}

if (isset($_GET['min_price']) && isset($_GET['max_price']) && !empty($_GET['min_price']) && !empty($_GET['max_price'])) {
    $min_price = $_GET['min_price'];
    $max_price = $_GET['max_price'];
    // Check if other filters have been applied
    $sql .= isset($_GET['company']) || isset($_GET['color']) ? " AND" : " WHERE";
    $sql .= " price BETWEEN $min_price AND $max_price";
}

// Execute SQL query
$result = mysqli_query($conn, $sql);

// Check if any cars are found
if (mysqli_num_rows($result) > 0) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Display Cars</title>
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

            /* Additional CSS for cards */
            .car {
                background-color: #fff;
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                margin: 20px;
                padding: 20px;
                width: 300px;
                text-align: center;
                display: flex;
                flex-direction: column; /* تحديد اتجاه العناصر إلى العمود */
            }

            .car img {
                width: 100%;
                border-radius: 10px;
                margin-bottom: 10px;
                flex-grow: 1; /* جعل الصورة تمتد لتملأ المساحة المتبقية */
            }

            .car h3 {
                font-size: 20px;
                margin-bottom: 10px;
            }

            .car p {
                margin-bottom: 5px;
            }

            /* Styling for filters */
            .filters {
                text-align: center;
                margin-bottom: 20px;
            }

            .filters label,
            .filters select,
            .filters input {
                margin: 5px;
            }

            .filters button {
                background-color: #4c44b6;
                color: #fff;
                border: none;
                border-radius: 5px;
                padding: 10px 20px;
                font-size: 16px;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }

            .filters button:hover {
                background-color: #0056b3;
            }

            /* Style for car list container */
            .car-list {
                clear: both; /* Ensure proper alignment */
                display: flex;
                flex-wrap: wrap; /* تحديد انتقال العناصر للسطر التالي عند الحاجة */
                justify-content: center; /* توسيط البطاقات على الأفقي */
                gap: 20px; /* تحديد المسافة بين العناصر */
                direction: rtl; /* تأكيد الاتجاه من اليمين إلى اليسار */
                unicode-bidi: embed; /* تثبيت النص داخل المربع */
            }

            /* Applying the Noto Kufi Arabic font */
            body, h2, label, select, input, button, .car h3, .car p {
                font-family: 'Noto Kufi Arabic', sans-serif;
            }
        </style>
    </head>
    <body>

    <center><h2>السيارات المتاحة</h2></center>
    <form method="GET">
        <div class="filters">
            <label for="company">Car Manufacturer:</label>
            <select name="company" id="company">
                <option value="">All</option>
                <option value="Toyota">Toyota</option>
                <option value="Honda">Honda</option>
                <option value="Ford">Ford</option>
                <option value="Nissan">Nissan</option>
                <!-- Add more options as needed -->
            </select>

            <label for="color">Color:</label>
            <select name="color" id="color">
                <option value="">All</option>
                <option value="Red">Red</option>
                <option value="Blue">Blue</option>
                <option value="Black">Black</option>
                <option value="White">White</option>
                <!-- Add more options as needed -->
            </select>

            <label for="min_price">Min Price:</label>
            <input type="number" name="min_price" id="min_price">

            <label for="max_price">Max Price:</label>
            <input type="number" name="max_price" id="max_price">

            <button type="submit">Filter</button>
        </div>
    </form>
    <main>
        <div class="container">
            <div class="car-list">
                <?php
                // Fetch and display cars data
                while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <div class="car">
                        <img src="<?php echo $row['image_path']; ?>" alt="Car Image">
                        <h3><?php echo $row['company']; ?></h3>
                        <p><strong>الموديل:</strong> <?php echo $row['model']; ?></p>
                        <p><strong>اللون:</strong> <?php echo $row['color']; ?></p>
                        <p><strong>السنة:</strong> <?php echo $row['year']; ?></p>
                        <p><strong>السعر باليوم:</strong> <?php echo $row['price']; ?> ريال</p>
                        <p><strong>الكمية المتاحة:</strong> <?php echo $row['quantity']; ?></p>
                        <?php if ($row['quantity'] > 0): ?>
                            <a href="checkout.php?id=<?php echo $row['id']; ?>" class="btn rent-btn">استئجار</a>
                        <?php else: ?>
                            <p>نفذت الكمية</p>
                        <?php endif; ?>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </main>
    </body>
    </html>
    <?php
} else {
    echo "No cars found.";
}

// Close database connection
mysqli_close($conn);
?>
