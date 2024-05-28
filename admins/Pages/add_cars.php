<?php
// Include config.php for database connection
include_once("../../includes/config.php");

include("../assets/header.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اضافة سيارة</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@100..900&display=swap" rel="stylesheet">
    <style>
        /* CSS Styles */
        body {
            font-family: 'Noto Kufi Arabic', sans-serif;
            margin: 0;
            padding: 0;
            background: #e4e9f7;
        }
        .container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 90vh;
        }
        .box {
            background: #fdfdfd;
            display: flex;
            flex-direction: column;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .field {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"], input[type="number"], input[type="file"] {
            width: calc(100% - 20px); /* A slight reduction in width to accommodate padding */
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box; /* Ensure the input width includes padding and border */
        }
        .submit-btn {
            height: 35px;
            background: rgba(76,68,182,0.808);
            border: 0;
            border-radius: 5px;
            color: #fff;
            font-size: 15px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 10px;
        }
        .submit-btn:hover {
            background-color: #0056b3;
        }
        /* Error message style */
        .error-msg {
            color: red;
            margin-top: 5px;
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
            justify-content: center;
            align-items: center;
            text-decoration: none; /* Remove underline */
        }
    </style>
</head>
<body> 
    
    <div class="container">
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data" class="box">
            <center><header><h2>اضافة سيارة</h2></header></center>
            <div class="field">
                <label for="company">ماركة السيارة</label>
                <input type="text" id="company" name="company" value="<?php echo isset($_POST['company']) ? htmlspecialchars($_POST['company']) : ''; ?>" required>
            </div>
            <div class="field">
                <label for="model">موديل السيارة</label>
                <input type="text" id="model" name="model" value="<?php echo isset($_POST['model']) ? htmlspecialchars($_POST['model']) : ''; ?>" required>
            </div>
            <div class="field">
                <label for="color">لون السيارة</label>
                <input type="text" id="color" name="color" value="<?php echo isset($_POST['color']) ? htmlspecialchars($_POST['color']) : ''; ?>" required>
            </div>
            <div class="field">
                <label for="year">موديل سنة</label>
                <input type="number" id="year" name="year" value="<?php echo isset($_POST['year']) ? htmlspecialchars($_POST['year']) : ''; ?>" required>
            </div>
            <div class="field">
                <label for="price">السعر اليومي</label>
                <input type="number" id="price" name="price" value="<?php echo isset($_POST['price']) ? htmlspecialchars($_POST['price']) : ''; ?>" required>
            </div>
            <div class="field">
                <label for="quantity">الكمية</label>
                <input type="number" id="quantity" name="quantity" value="<?php echo isset($_POST['quantity']) ? htmlspecialchars($_POST['quantity']) : ''; ?>" required>
            </div>
            <div class="field">
                <label for="image">رفع صورة السيارة</label>
                <input type="file" name="image" id="image" required>
            </div>
            <div class="field">
                <center><input type="submit" value="حفظ" class="btn"></center>
            </div>
            <?php
            // Display error message if any field is missing
            if ($_SERVER["REQUEST_METHOD"] == "POST" && (!isset($_POST['company'], $_POST['model'], $_POST['color'], $_POST['year'], $_POST['price'], $_POST['quantity'], $_FILES['image']) || empty($_FILES['image']['name']))) {
                echo '<span class="error-msg">All fields are required, and an image must be selected.</span>';
            }
            ?>
        </form>
    </div>
    <?php
    // Database connection setup is now done in config.php

    // Form submission check
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['company'], $_POST['model'], $_POST['color'], $_POST['year'], $_POST['price'], $_POST['quantity'], $_FILES['image'])) {
        $company = $_POST['company'];
        $model = $_POST['model'];
        $color = $_POST['color'];
        $year = $_POST['year'];
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];

        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }

        if ($_FILES["image"]["size"] > 500000) {
            $uploadOk = 0;
        }

        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
            $uploadOk = 0;
        }

        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $sql = "INSERT INTO cars (company, model, color, year, price, image_path, quantity) VALUES ('$company', '$model', '$color', '$year', '$price', '$target_file', '$quantity')";
                if ($conn->query($sql) === TRUE) {
                    echo "<script>alert('New record created successfully');</script>";
                }
                else {
                    echo "<script>alert('Error: " . $sql . "<br>" . $conn->error . "');</script>";
                }
            } else {
                echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
            }
        } else {
            echo "<script>alert('Sorry, your file was not uploaded.');</script>";
        }
    }
    ?>
</body>
</html>
