


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assest/style/style.css">
    <title>Login</title>
</head>
<body>
    <div class="container">
        <div class="box form-box">
            <?php 
            // Bắt đầu phiên làm việc
            session_start();
            
            
            include("../php/config.php");
            if (isset($_POST['submit'])) {
                // Kiểm tra biến kết nối $con
                if (isset($con)) {
                    $email = mysqli_real_escape_string($con, $_POST['email']);
                    $password = mysqli_real_escape_string($con, $_POST['password']);

                    $result = mysqli_query($con, "SELECT * FROM users WHERE Email='$email' AND Password='$password'") or die("Select Error");
                    $row = mysqli_fetch_assoc($result);

                    if (is_array($row) && !empty($row)) {
                        $_SESSION['valid'] = $row['Email'];
                        $_SESSION['username'] = $row['Username'];
                        $_SESSION['age'] = $row['Age'];
                        $_SESSION['id'] = $row['Id'];
                        $_SESSION['phonenumber'] = $row['PhoneNumber'];
                        $_SESSION['address'] = $row['Address'];
                        if ($row['role'] == 1) {
                            header('Location: ../admin/admin.php'); 
                        } elseif ($row['role'] == 0) {
                            header('Location:home.php');
                        }
                        exit();
                    } else {
                        echo "<div class='message'>
                        <p>Wrong Username or Password</p>
                        </div><br>";
                        echo "<a href='dangnhap.php'><button class='btn'>Go Back</button></a>";
                    }
                    if (isset($_SESSION['valid'])) {
                        header("Location:home.php");
                    }
                } else {
                    echo "<div class='message'>
                    <p>Database connection error</p>
                    </div><br>";
                }
            } else {
            ?>
            <header>Login</header>
            <form action="" method="post">
                <div class="field input">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" autocomplete="off" required>
                </div>

                <div class="field">
                    <input type="submit" class="btn" name="submit" value="Login" required>
                </div>
                <div class="links">
                    Don't have account? <a href="register.php">Sign Up Now</a>
                </div>
            </form>
            <?php } ?>
        </div>
    </div>
</body>
</html>