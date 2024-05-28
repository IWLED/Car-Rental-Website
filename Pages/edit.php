<?php 
  session_start();
    require_once "../includes/header.php";
    include("../includes/config.php");
    if(!isset($_SESSION['valid'])){
        header("Location: login.php");
        exit(); // Make sure to stop execution after redirection
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@100..900&display=swap" rel="stylesheet">
    <title>Change Profile</title>
    <style>
        body {
            font-family: 'Noto Kufi Arabic', sans-serif;
            margin: 0;
            padding: 0;
            background: #e4e9f7;
        }
        .container{
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 90vh;
        }
        .form-box {
            width: 450px;
            margin: 0px 10px;
        }
        .box {
            background: #fdfdfd;
            display: flex;
            flex-direction: column;
            padding: 25px 25px;
            border-radius: 20px;
            box-shadow: 0 0 128px 0 rgba(0,0,0,0.1),
                        0 32px 64px -48px rgba(0,0,0,0.5);
        }
        .form-box header {
            font-size: 25px;
            font-weight: 600;
            padding-bottom: 10px;
            border-bottom: 1px solid #e6e6e6;
            margin-bottom: 10px;
        }
        .form-box form .field {
            display: flex;
            margin-bottom: 10px;
            flex-direction: column;
        }
        .form-box form .input input {
            height: 40px;
            width: 100%;
            font-size: 16px;
            padding: 0 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            outline: none;
        }
        .submit-btn {
            height: 35px;
            background: rgba(76,68,182,0.808);
            border: 0;
            border-radius: 5px;
            color: #fff;
            font-size: 15px;
            cursor: pointer;
            transition: all .3s;
            margin-top: 10px;
            padding: 0px 10px;
        }
        .btn:hover {
            opacity: 0.82;
        }
        .submit {
            width: 100%;
        }
        .links {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="box form-box">
            <?php 
               if(isset($_POST['submit'])){
                   $username = $_POST['username'];
                   $email = $_POST['email'];
                   $password = $_POST['password'];
                   $id = $_SESSION['id'];

                   // Hash the password before storing it
                   $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                   // Prepare and execute the SQL statement using prepared statements
                   $edit_query = mysqli_prepare($conn, "UPDATE users SET Username=?, Email=?, Password=? WHERE Id=?");
                   mysqli_stmt_bind_param($edit_query, "sssi", $username, $email, $hashed_password, $id);
                   $success = mysqli_stmt_execute($edit_query);

                   if($success){
                       echo "<div class='message'><p>Profile Updated!</p></div><br>";
                       echo "<a href='../home.php'><button class='submit-btn'>Go Home</button>";
                   } else {
                       echo "Error occurred while updating profile.";
                   }

                   // Close the prepared statement
                   mysqli_stmt_close($edit_query);
               } else {
                   $id = $_SESSION['id'];
                   $query = mysqli_query($conn, "SELECT * FROM users WHERE Id=$id");

                   while($result = mysqli_fetch_assoc($query)){
                       $res_Uname = $result['Username'];
                       $res_Email = $result['Email'];
                       $res_Password = $result['Password'];
                   }
            ?>
            <header>Change Profile</header>
            <form action="" method="post">
                <div class="field input">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" value="<?php echo $res_Uname; ?>" autocomplete="off" required>
                </div>
                <div class="field input">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" value="<?php echo $res_Email; ?>" autocomplete="off" required>
                </div>
                <div class="field input">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" value="<?php echo $res_Password; ?>" autocomplete="off" required>
                </div>
                <div class="field">
                    <input type="submit" class="submit-btn" name="submit" value="Update">
                </div>
            </form>
            <?php } ?>
        </div>
    </div>
</body>
</html>
