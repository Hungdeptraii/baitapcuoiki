<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assest/style/style.css">
    <title>Register</title>

    <script>
        function validateInputLength(input, maxLength, errorElementId) {
            var errorElement = document.getElementById(errorElementId);
            if (input.value.length > maxLength) {
                errorElement.innerHTML = "Nhập quá giới hạn " + maxLength + " ký tự.";
                input.classList.add("input-error");
            } else {
                errorElement.innerHTML = "";
                input.classList.remove("input-error");
            }
        }
    </script>
</head>
<body>
      <div class="container">
        <div class="box form-box">

        <?php 
         
         include("../php/config.php");

         if (isset($_POST['submit'])) {
                $username = $_POST['username'];
                $email = $_POST['email'];
                $phoneNumber = $_POST['phoneNumber'];
                $address = $_POST['address'];
                $age = $_POST['age'];
                $password = $_POST['password'];

                // Validate input lengths
                if (strlen($username) > 16) {
                    die("Tên người dùng quá dài. Vui lòng nhập lại.");
                }
                if (strlen($email) > 200) {
                    die("Email quá dài. Vui lòng nhập lại.");
                }
                if (strlen($phoneNumber) > 15) {
                    die("Số điện thoại quá dài. Vui lòng nhập lại.");
                }
                if (strlen($address) > 255) {
                    die("Địa chỉ quá dài. Vui lòng nhập lại.");
                }
                if ($age > 99) {
                    die("Tuổi không hợp lệ. Vui lòng nhập lại.");
                }
                if (strlen($password) > 200) {
                    die("Mật khẩu quá dài. Vui lòng nhập lại.");
                }

                // Verifying the unique email and username
                $verify_query = mysqli_query($con, "SELECT email, username FROM users WHERE email='$email' OR username='$username'");

                if (mysqli_num_rows($verify_query) != 0) {
                    echo "<div class='message'>
                            <p>Email hoặc Tên người dùng đã được sử dụng, vui lòng thử cái khác!</p>
                        </div> <br>";
                    echo "<a href='javascript:self.history.back()'><button class='btn'>Quay lại</button></a>";
                } else {
                    $insert_query = "INSERT INTO users (username, email, address, phoneNumber, age, password) VALUES ('$username', '$email', '$address', '$phoneNumber', '$age', '$password')";
                    

                    if (mysqli_query($con, $insert_query)) {
                        echo "<div class='message'>
                                <p>Đăng ký thành công!</p>
                            </div> <br>";
                        echo "<a href='dangnhap.php'><button class='btn'>Đăng nhập ngay</button>";
                    } else {
                        echo "Lỗi: " . mysqli_error($con);
                    }
                }


            }else{
            
            ?>

            <header>Sign Up</header>
            <form action="" method="post">
    <div class="field input">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" autocomplete="off" required oninput="validateInputLength(this, 16, 'usernameError')">
        <span id="usernameError" class="error"></span>
    </div>

    <div class="field input">
        <label for="email">Email</label>
        <input type="text" name="email" id="email" autocomplete="off" required oninput="validateInputLength(this, 200, 'emailError')">
        <span id="emailError" class="error"></span>
    </div>
    <div class="field input">
        <label for="phoneNumber">SĐT</label>
        <input type="text" name="phoneNumber" id="phoneNumber" autocomplete="off" required oninput="validateInputLength(this, 15, 'phoneNumberError')">
        <span id="phoneNumberError" class="error"></span>
    </div>
    <div class="field input">
        <label for="address">Địa chỉ</label>
        <input type="text" name="address" id="address" autocomplete="off" required oninput="validateInputLength(this, 255, 'addressError')">
        <span id="addressError" class="error"></span>
    </div>
    <div class="field input">
        <label for="age">Age</label>
        <input type="number" name="age" id="age" autocomplete="off" required>
    </div>
    <div class="field input">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" autocomplete="off" required oninput="validateInputLength(this, 200, 'passwordError')">
        <span id="passwordError" class="error"></span>
    </div>

    <div class="field">
        <input type="submit" class="btn" name="submit" value="Register" required>
    </div>
    <div class="links">
        Already a member? <a href="dangnhap.php">Sign In</a>
                </div>
            </form>
        </div>

        <?php } ?>
      </div>

</body>
</html>