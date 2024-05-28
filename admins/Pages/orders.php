<?php
include("../../includes/config.php");
include("../assets/header.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if ($_POST['action'] == 'delete') {
        $orderId = $_POST['id'];

        $sql = "DELETE FROM orders WHERE id = ?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $orderId);
            if ($stmt->execute()) {
                echo "تم حذف الطلب بنجاح.";
            } else {
                echo "حدث خطأ أثناء حذف الطلب: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "حدث خطأ أثناء تحضير الاستعلام: " . $conn->error;
        }

        $conn->close();
        exit;
    } elseif ($_POST['action'] == 'send_message') {
        $userId = $_POST['user_id'];
        $orderId = $_POST['order_id'];
        $message = $_POST['message'];

        $sql = "INSERT INTO reports (user_id, order_id, report_text) VALUES (?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("iis", $userId, $orderId, $message);
            if ($stmt->execute()) {
                echo "تم إرسال الرسالة بنجاح.";
            } else {
                echo "حدث خطأ أثناء إرسال الرسالة: " . $stmt->error;
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>لوحة التحكم</title>
    <style>
        body {
            font-family: 'Noto Kufi Arabic', sans-serif;
            background: #e4e9f7;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
            white-space: nowrap;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="row">
        <table>
            <tr>
                <th>رقم الطلب</th>
                <th>المستخدم</th>
                <th>السيارة</th>
                <th>الموديل</th>
                <th>اللون</th>
                <th>السنة</th>
                <th>تاريخ الاستئجار</th>
                <th>تاريخ الإرجاع</th>
                <th>مدينة الاستلام</th>
                <th>مدينة التسليم</th>
                <th>السعر الإجمالي</th>
                <th>العمليات</th>
            </tr>
            <?php
            $sql = "SELECT o.id, o.user_id, o.car_id, o.rental_date, o.return_date, o.total_price, o.pickup_city, o.delivery_city, u.username, c.company, c.model, c.color, c.year 
                    FROM orders o 
                    INNER JOIN users u ON o.user_id = u.id 
                    INNER JOIN cars c ON o.car_id = c.id";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['username'] . "</td>";
                    echo "<td>" . $row['company'] . "</td>";
                    echo "<td>" . $row['model'] . "</td>";
                    echo "<td>" . $row['color'] . "</td>";
                    echo "<td>" . $row['year'] . "</td>";
                    echo "<td>" . $row['rental_date'] . "</td>";
                    echo "<td>" . $row['return_date'] . "</td>";
                    echo "<td>" . $row['pickup_city'] . "</td>";
                    echo "<td>" . $row['delivery_city'] . "</td>";
                    echo "<td>$" . $row['total_price'] . "</td>";
                    echo "<td>
                            <button class=\"delete-btn btn btn-danger\" data-id=\"" . $row['id'] . "\" data-toggle=\"modal\" data-target=\"#confirmationModal\">حذف الطلب</button>
                            <button class=\"send-message-btn btn btn-primary\" data-user-id=\"" . $row['user_id'] . "\" data-order-id=\"" . $row['id'] . "\" data-toggle=\"modal\" data-target=\"#messageModal\">إرسال رسالة</button>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='12'>لا يوجد طلبات.</td></tr>";
            }
            ?>
        </table>
    </div>
</div>

<div id="confirmationModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">تأكيد الحذف</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>هل أنت متأكد أنك تريد حذف هذا الطلب؟</p>
                <button class="btn btn-danger" id="confirm-delete">حذف</button>
                <button class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<div id="messageModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">إرسال رسالة</h4>
                <button type="button" class="close" data-dismiss="modal">&times;"></button>
            </div>
            <div class="modal-body">
                <form id="messageForm" method="POST">
                    <div class="form-group">
                        <label for="message">الرسالة:</label>
                        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                    </div>
                    <input type="hidden" id="userId" name="user_id" value="">
                    <input type="hidden" id="orderId" name="order_id" value="">
                    <input type="hidden" name="action" value="send_message">
                    <button type="submit" class="btn btn-primary">إرسال</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function(){
        $(".delete-btn").click(function(){
            var orderId = $(this).data("id");
            $("#confirm-delete").data("orderid", orderId);
        });

        $("#confirm-delete").click(function(){
            var orderId = $(this).data("orderid");
            $.ajax({
                url: '<?php echo $_SERVER["PHP_SELF"]; ?>',
                type: 'POST',
                data: { id: orderId, action: 'delete' },
                success: function(response) {
                    alert(response);
                    location.reload();
                }
            });
        });

        $(".send-message-btn").click(function(){
            var userId = $(this).data("user-id");
            var orderId = $(this).data("order-id");
            $("#userId").val(userId);
            $("#orderId").val(orderId);
        });

        $("#messageForm").submit(function(event){
            event.preventDefault();
            $.ajax({
                url: '<?php echo $_SERVER["PHP_SELF"]; ?>',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    alert(response);
                    $('#messageModal').modal('hide');
                }
            });
        });
    });
</script>
</body>
</html>
