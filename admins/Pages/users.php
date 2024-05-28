<?php
include("../assets/header.php");

// Check if data is sent via POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connect to the database
    include '../../includes/config.php';

    // If delete request is sent
    if ($_POST['action'] == 'delete') {
        // Extract the user id to be deleted
        $userId = $_POST['id'];

        // Execute query to delete the user from the database
        $sql = "DELETE FROM users WHERE id = $userId";

        if ($conn->query($sql) === TRUE) {
            echo "User deleted successfully.";
        } else {
            echo "Error deleting user: " . $conn->error;
        }

        // Close the connection
        $conn->close();
        exit; // Exit after deletion
    }

    // Extract data sent from the form
    $userId = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Update user data in the database
    $sql = "UPDATE users SET username = '$username', email = '$email', password = '$password' WHERE id = $userId";

    if ($conn->query($sql) === TRUE) {
        echo "User data updated successfully.";
    } else {
        echo "Error updating user data: " . $conn->error;
    }

    // Close the connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>تعديل بيانات المستخدمين</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@100..900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<style>
    body {
        font-family: 'Noto Kufi Arabic', sans-serif;
        background: #e4e9f7;
        margin: 0;
        padding: 0;
    }
    main {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 60px;
    }
    .main-box {
        display: flex;
        flex-direction: column;
        width: 80%;
        max-width: 800px; /* Adjust maximum width */
        margin: auto;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        overflow-x: auto;
    }
    .table {
        margin-top: 20px; /* Adjust margin-top */
        text-align: center; /* Center align table content */
        width: 100%; /* Make the table take full width */
    }
    .card {
        margin-top: 20px;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        display: inline-block; /* Adjust card display */
    }
    .card-title {
        text-align: center;
    }
    .form-group {
        text-align: right;
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
        white-space: nowrap; /* تحديد عدم الانقسام للكلمات */
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

<div class="container mt-3">
    <?php
    // Connect to the database
    include '../../includes/config.php';

    // Query to retrieve user data
    $sql = "SELECT id, username, email, password FROM users";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Display user data in a table
        echo "<table class='table table-bordered'>
            <thead>
                <tr>
                    <th>رقم العميل</th>
                    <th>اسم العميل</th>
                    <th>البريد الالكتروني</th>
                    <th>كلمة المرور</th>
                    <th>تعديل</th>
                    <th>حذف</th>
                </tr>
            </thead>
            <tbody>";
        
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>".$row["id"]."</td>
                <td id='username_".$row["id"]."'>".$row["username"]."</td>
                <td id='email_".$row["id"]."'>".$row["email"]."</td>
                <td id='password_".$row["id"]."'>".$row["password"]."</td>
                <td><button class='btn btn-primary edit-btn' data-id='".$row["id"]."'>تعديل</button></td>
                <td><button class='btn btn-danger delete-btn' data-id='".$row["id"]."'>حذف</button></td>
            </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "لا يوجد عملاء مسجلين.";
    }

    // Close the connection
    $conn->close();
    ?>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">تعديل بيانات العميل</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <div class="form-group">
                        <label for="editUsername">اسم العميل:</label>
                        <input type="text" class="form-control" id="editUsername" name="username">
                    </div>
                    <div class="form-group">
                        <label for="editEmail">البريد الالكتروني:</label>
                        <input type="email" class="form-control" id="editEmail" name="email">
                    </div>
                    <div class="form-group">
                        <label for="editPassword">كلمة المرور:</label>
                        <input type="password" class="form-control" id="editPassword" name="password">
                    </div>
                    <input type="hidden" id="editUserId" name="id">
                    <button type="submit" class="btn btn-secondary">حفظ التغييرات</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">تأكيد الحذف</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>هل أنت متأكد أنك تريد حذف هذا المستخدم؟</p>
                <button class="btn btn-danger" id="confirm-delete">حذف</button>
                <button class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
$(document).ready(function(){
    // When clicking the "Edit" button
    $(".edit-btn").click(function() {
        var userId = $(this).data('id');
        var username = $('#username_' + userId).text();
        var email = $('#email_' + userId).text();
        var password = $('#password_' + userId).text();
        $('#editUsername').val(username);
        $('#editEmail').val(email);
        $('#editPassword').val(password);
        $('#editUserId').val(userId);
        $('#editModal').modal('show');
    });

    // When clicking the "Delete" button
    $(".delete-btn").click(function() {
        var userId = $(this).data('id');
        $('#confirm-delete').data('id', userId); // Attach user id to the delete confirmation modal
        $('#deleteModal').modal('show');
    });

    // When clicking the "Delete" button in the delete confirmation modal
    $('#confirm-delete').click(function() {
        var userId = $(this).data('id');
        $.ajax({
            url: '<?php echo $_SERVER["PHP_SELF"]; ?>',
            type: 'POST',
            data: { id: userId, action: 'delete' },
            success: function(response) {
                // Close the delete confirmation modal and update the table
                $('#deleteModal').modal('hide');
                location.reload();
            }
        });
    });

    // When submitting the edit form
    $('#editForm').submit(function(e){
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            url: '<?php echo $_SERVER["PHP_SELF"]; ?>',
            type: 'POST',
            data: formData,
            success: function(response) {
                // Close the form modal and update the table data
                $('#editModal').modal('hide');
                location.reload();
            }
        });
    });
});
</script>

</body>
</html>
