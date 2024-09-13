<?php
    include 'includes/header.php';
   include_once("connect.php");


   // Initialize variables to store form data
   $username = "";
   $email = "";
   $password = "";
   $confirmPassword = "";

    // Check if the registration form is submitted
   if(isset($_POST['register'])) {

    // Retrieve form data
    $username = trim($_POST['username']);
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Initialize an array to store validation errors
    $errors = array();

    //Validation of the input fields
    if($username == "") {
        $errors['username'] = "Username field required";
    }else if(strlen($username) < 3) {
        $errors['username'] = "Username should be at least 3 characters";
    }else if(!empty($username)) {
        $stmt = $con->prepare("SELECT username FROM ecommerce_table WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if($stmt->num_rows > 0) {
            $errors['username'] = "Username already exists";
        }
    }

    if($email == "") {
        $errors['email'] = "Email field required";
    }else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Input a valid email";
    }else if(!empty($email)) {
        $stmt = $con->prepare("SELECT email FROM ecommerce_table WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if($stmt->num_rows > 0) {
            $errors['email'] = "Email already exists";
        }
    }

    if($password == "") {
        $errors['password'] = "Password field required";
    }else if(strlen($password) < 4) {
        $errors['password'] = "Password must be at least 4 characters";
    }else if(!preg_match("#[A-Z]+#", $password)) {
        $errors['password'] = "Uppercase characters required";
    }else if(!preg_match("#[0-9]#", $password)) {
        $errors['password'] = "Please include numbers";
    }

    if($confirmPassword == "") {
        $errors['confirmPassword'] = "Confirm field password";
    }else if($confirmPassword != $password) {
        $errors['confirmPassword'] = "Password Mismatch!";
    }

    
    // If there are no errors, insert the user into the database
    if(empty($errors)) {
        $encpass = password_hash($password, PASSWORD_BCRYPT);
        
        $stmt = $con->prepare("INSERT INTO ecommerce_table(username, email, password) VALUES (?, ?, ?)");
        $stmt -> bind_param("sss", $username, $email, $encpass);

        // Execute the query
        if($stmt->execute()) {

            $username = "";
            $email = "";
            $password = "";
            $confirmPassword = "";

            echo "<script> alert('Account Created') </script>";
        }else {
            echo "<script> alert('Failed to create') </script>";
        }
        $stmt->close();
        $con->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SignUp</title>
    <link rel="stylesheet" href="./css/form.css">
</head>
<body>

    <div class="signup_container">
        <form action="" method="POST">
            <h1 class="text-center">Create Account</h1>

            <label for="username">Username</label>
            <input type="text" name="username" value="<?php echo isset($username) ? $username : ''; ?>">
            <p class="error"><?php if(isset($errors["username"])) echo $errors["username"]; ?></p>

            <label for="email">Email</label>
            <input type="text" name="email" value="<?php echo isset($email) ? $email : ''; ?>">
            <p class="error"><?php if(isset($errors["email"])) echo $errors["email"]; ?></p>

            <div class="password-input-container">
                <label for="password">Password</label>
                <input type="password" name="password" id="id_password" value="<?php echo isset($password) ? $password : ''; ?>">
                <i class="fas fa-eye-slash" id="togglePassword"></i>
            </div>
            <p class="error"><?php if(isset($errors['password'])) echo $errors['password']; ?></p>

            <div class="password-input-container">
                <label for="confirmPassword">Confirm Password</label>
                <input type="password" name="confirmPassword" id="id_confirm_password" value="<?php echo isset($confirmPassword) ? $confirmPassword : ''; ?>">
                <i class="fas fa-eye-slash password-toggle" id="toggleconfirmPassword"></i>
            </div>
            <p class="error"><?php if(isset($errors['confirmPassword'])) echo $errors['confirmPassword']; ?></p>

            <button type="submit" name="register" class="submitbtn"><span class="btn-text">Create</span></button>
            <p class="check">Already have an account? <a href="login.php"><span class="acc">Login</span></a></p>
        </form>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="eye.js"></script>

</body>
</html>