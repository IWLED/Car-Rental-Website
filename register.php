<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@100..900&display=swap" rel="stylesheet">
    <title>Register</title>
    <style>
        /* Your CSS styles */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: 'Noto Kufi Arabic', sans-serif;
        }
        body {
            background: #e4e9f7;
            text-align: right;
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
            padding: 25px 25px;
            border-radius: 20px;
            box-shadow: 0 0 128px 0 rgba(0,0,0,0.1),
                        0 32px 64px -48px rgba(0,0,0,0.5);
            align-items: center;
        }
        .form-box {
            width: 450px;
            margin: 0px 10px;
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
            text-align: center;
            height: 40px;
            width: 100%;
            font-size: 16px;
            padding: 0 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            outline: none;
        }
        .btn {
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
            text-align: center;
        }
        .message {
            color: purple;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="box form-box">
            <?php 
            include("includes/config.php");

            if(isset($_POST['submit'])){
                $username = $_POST['username'];
                $email = $_POST['email'];
                $password = $_POST['password'];
                $confirm_password = $_POST['confirm_password'];

                // Check if passwords match
                if ($password !== $confirm_password) {
                    echo "<div class='message'>
                            <p>Passwords do not match! Please try again.</p>
                          </div> <br>";
                    echo "<a href='javascript:self.history.back()'><button class='btn'>Go Back</button>";
                } else {
                    // Check if email contains '@' symbol
                    if(strpos($email, '@') === false) {
                        echo "<div class='message'>
                                <p>Please enter a valid email address!</p>
                              </div> <br>";
                        echo "<a href='javascript:self.history.back()'><button class='btn'>Go Back</button>";
                    } else {
                        // Verify unique email
                        $verify_query = mysqli_query($conn,"SELECT Email FROM users WHERE Email='$email'");
                        if(mysqli_num_rows($verify_query) != 0) {
                            echo "<div class='message'>
                                    <p>This email is used, Try another one please!</p>
                                  </div> <br>";
                            echo "<a href='javascript:self.history.back()'><button class='btn'>Go Back</button>";
                        } else {
                            // Encrypt the password
                            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                            // Insert user into database with encrypted password
                            mysqli_query($conn,"INSERT INTO users(Username,Email,Password) VALUES('$username','$email','$hashed_password')") or die("Error Occurred");
                            echo "<div class='message'>
                                    <p>Registration successful!</p>
                                  </div> <br>";
                            echo "<a href='login.php'><button class='btn'>Login Now</button>";
                        }
                    }
                }
            } else {
            ?>
            <header>انشاء حساب جديد</header>
            <form action="" method="post" onsubmit="return validateForm()">
                <div class="field input">
                    <label for="username">اسم المستخدم</label>
                    <input type="text" name="username" id="username" autocomplete="off" maxlength="20" required>
                </div>
                <div class="field input">
                    <label for="email">البريد الالكتروني</label>
                    <input type="email" name="email" id="email" autocomplete="off" maxlength="25" required>
                </div>
                <div class="field input">
                    <label for="password">كلمة المرور</label>
                    <input type="password" name="password" id="password" autocomplete="off" required>
                </div>
                <div class="field input">
                    <label for="confirm_password">تأكيد كلمة المرور</label>
                    <input type="password" name="confirm_password" id="confirm_password" autocomplete="off" required>
                </div>
                <div class="field">
                    <input type="submit" class="btn" name="submit" value="تسجيل">
                </div>
                <div class="links">
                    <a href="login.php">Sign In</a>  لديك حساب ؟
                </div>
            </form>
            <?php } ?>
        </div>
    </div>

    <script>
        function validateForm() {
            var emailInput = document.getElementById('email').value;
            var password = document.getElementById('password').value;
            var confirmPassword = document.getElementById('confirm_password').value;
            if (emailInput.indexOf('@') === -1) {
                alert('يرجى إدخال عنوان بريد إلكتروني صالح.');
                return false;
            }
            if (password !== confirmPassword) {
                alert('كلمة المرور غير مطابقة!');
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
