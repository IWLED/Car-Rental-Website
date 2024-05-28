<?php
include("../assets/header.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '../../includes/config.php';

    if ($_POST['action'] == 'delete') {
        $carId = $_POST['id'];
        $sql = "DELETE FROM cars WHERE id = ?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $carId);
            if ($stmt->execute()) {
                echo "تم حذف السيارة بنجاح.";
            } else {
                echo "حدث خطأ أثناء حذف السيارة: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "حدث خطأ أثناء تحضير الاستعلام: " . $conn->error;
        }

        $conn->close();
        exit;
    }

    if ($_POST['action'] == 'edit') {
        $carId = $_POST['id'];
        $company = $_POST['company']; // تعديل اسم العمود
        $model = $_POST['model'];
        $color = $_POST['color'];
        $year = $_POST['year'];
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];
        
        $existingImagePath = $_POST['existing_image_path'];  // المسار الحالي للصورة

        if (!empty($_FILES['image_path']['name'])) {
            $targetDir = "uploads/";
            $targetFile = $targetDir . basename($_FILES["image_path"]["name"]);
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            // التحقق من أن الملف هو صورة PNG
            if ($imageFileType != "png") {
                echo "الرجاء تحميل صورة بصيغة PNG فقط.";
                exit;
            }
        
            // التحقق من أن الملف هو صورة حقيقية
            $check = getimagesize($_FILES["image_path"]["tmp_name"]);
            if($check !== false) {
                if (move_uploaded_file($_FILES["image_path"]["tmp_name"], $targetFile)) {
                    $imagePath = $targetFile;  // تحديث المسار في حال تم رفع صورة جديدة
                } else {
                    echo "حدث خطأ أثناء رفع الصورة.";
                    exit;
                }
            } else {
                echo "الملف ليس صورة.";
                exit;
            }
        } else {
            // في حالة عدم تحميل صورة جديدة، يحافظ على المسار الحالي
            $imagePath = $existingImagePath;
        }

        $sql = "UPDATE cars SET company = ?, model = ?, color = ?, year = ?, price = ?, image_path = ?, quantity = ? WHERE id = ?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssssdsii", $company, $model, $color, $year, $price, $imagePath, $quantity, $carId); // تعديل نوع المتغير
            if ($stmt->execute()) {
                echo "تم تحديث بيانات السيارة بنجاح.";
            } else {
                echo "حدث خطأ أثناء تحديث بيانات السيارة: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "حدث خطأ أثناء تحضير الاستعلام: " . $conn->error;
        }

        $conn->close();
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>عرض وتعديل السيارات</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@100..900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<style>
body {
    font-family: 'Noto Kufi Arabic', sans-serif;
    margin: 0;
    padding: 0;
    background: #e4e9f7;
}

.container {
    display: flex;
    justify-content: center;
}

.car-table {
    width: 100%;
    border-collapse: collapse;
}

.car-table th, .car-table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: center;
}

.car-table th {
    background-color: #f2f2f2;
}

.car-table tr:nth-child(even) {
    background-color: #f2f2f2;
}

.car-table tr:hover {
    background-color: #ddd;
    cursor: pointer;
}

.modal-content, #editForm {
    text-align: right;
}

body, h2, label, select, input, button, .car h3, .car p {
    font-family: 'Noto Kufi Arabic', sans-serif;
}

.modal-content {
    text-align: center;
}

#editModal, #deleteModal {
    display: none;
}
</style>
</head>
<body>

<center><h2>السيارات المتاحة</h2></center>
<main>
<div class="container">
    <table class="car-table">
        <thead>
            <tr>
                <th>رقم السيارة</th>
                <th>شركة السيارة</th>
                <th>الموديل</th>
                <th>اللون</th>
                <th>السنة</th>
                <th>السعر</th>
                <th>الكمية</th>
                <th>مسار الصورة</th>
                <th>التحكم</th>
            </tr>
        </thead>
        <tbody>
            <?php
            require_once "../../includes/config.php";

            $sql = "SELECT * FROM cars";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['company']; ?></td>
                        <td><?php echo $row['model']; ?></td>
                        <td><?php echo $row['color']; ?></td>
                        <td><?php echo $row['year']; ?></td>
                        <td><?php echo $row['price']; ?></td>
                        <td><?php echo $row['quantity']; ?></td>
                        <td><?php echo $row['image_path']; ?></td>
                        <td>
                            <button class="btn edit-btn btn-primary" data-id="<?php echo $row['id']; ?>" data-company="<?php echo $row['company']; ?>" data-model="<?php echo $row['model']; ?>" data-color="<?php echo $row['color']; ?>" data-year="<?php echo $row['year']; ?>" data-price="<?php echo $row['price']; ?>" data-quantity="<?php echo $row['quantity']; ?>" data-image="<?php echo $row['image_path']; ?>">تعديل</button>
                            <button class="btn delete-btn btn-danger" data-id="<?php echo $row['id']; ?>">حذف</button>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='8'>لا توجد سيارات.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
</main>

<!-- Delete Modal -->
<div id="deleteModal" class="modal fade" role="dialog">
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">تأكيد الحذف</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <p>هل أنت متأكد أنك تريد حذف هذه السيارة؟</p>
            <button class="btn btn-danger" id="confirm-delete">حذف</button>
            <button class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
        </div>
    </div>
</div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal fade" role="dialog">
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">تعديل بيانات السيارة</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <form id="editForm" enctype="multipart/form-data">
                <input type="hidden" id="edit-id" name="id">
                <input type="hidden" id="existing-image-path" name="existing_image_path">
                <div class="form-group">
                    <label for="edit-company">شركة السيارة</label>
                    <input type="text" class="form-control" id="edit-company" name="company">
                </div>
                <div class="form-group">
                    <label for="edit-model">الموديل</label>
                    <input type="text" class="form-control" id="edit-model" name="model">
                </div>
                <div class="form-group">
                    <label for="edit-color">اللون</label>
                    <input type="text" class="form-control" id="edit-color" name="color">
                </div>
                <div class="form-group">
                    <label for="edit-year">السنة</label>
                    <input type="number" class="form-control" id="edit-year" name="year">
                </div>
                <div class="form-group">
                    <label for="edit-price">السعر باليوم</label>
                    <input type="number" class="form-control" id="edit-price" name="price">
                </div>
                <div class="form-group">
                    <label for="edit-quantity">الكمية</label>
                    <input type="number" class="form-control" id="edit-quantity" name="quantity">
                </div>
                <div class="form-group">
                    <label for="edit-image_path">صورة السيارة (PNG فقط)</label>
                    <input type="file" class="form-control" id="edit-image_path" name="image_path">
                </div>
                <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
            </form>
        </div>
    </div>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
$(document).ready(function(){

    $(".delete-btn").click(function() {
        var carId = $(this).data('id');
        $('#deleteModal').modal('show');
        $('#confirm-delete').off('click').on('click', function() {
            $.ajax({
                url: 'cars.php',
                type: 'POST',
                data: { id: carId, action: 'delete' },
                success: function(response) {
                    $('#deleteModal').modal('hide');
                    location.reload();
                }
            });
        });
    });

    $(".edit-btn").click(function() {
        var carId = $(this).data('id');
        $('#edit-id').val(carId);
        $('#edit-company').val($(this).data('company'));
        $('#edit-model').val($(this).data('model'));
        $('#edit-color').val($(this).data('color'));
        $('#edit-year').val($(this).data('year'));
        $('#edit-price').val($(this).data('price'));
        $('#edit-quantity').val($(this).data('quantity'));
        $('#existing-image-path').val($(this).data('image'));

        $('#editModal').modal('show');
    });

    $("#editForm").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append('action', 'edit');

        $.ajax({
            url: 'cars.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#editModal').modal('hide');
                location.reload();
            }
        });
    });
});
</script>
</body>
</html>
