<?php
// Include config file
require_once "class/Database.php";

// Start session
session_start();

$email = $password = "";
$email_err = $password_err = $login_err = "";

// Generate a CSRF token if one doesn't exist
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check CSRF token validity
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $login_err = "Invalid CSRF token.";
    } else {
        // Check if both email and password are provided
        if (isset($_POST["email"]) && isset($_POST["password"])) {
            $email = htmlspecialchars($_POST['email']);
            $password = $_POST['password'];

            // Attempt to log in
            $login_result = Database::logIn($email, $password);

            if ($login_result === false) {
                // Login failed, display error message
                $login_err = "Invalid email or password.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style-7.css">
    <title>Login</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.3/gsap.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/kursor/dist/kursor.css">
</head>
<body>   
<video autoplay muted loop playsinline id="backgroundVideo">
    <source src="assets/video/video.mp4" type="video/mp4">
    Your browser does not support HTML5 video.
</video>
<div class="section-container">
    <section class="left">
        
        <div class="wrapper" id="register-wrapper">
            <h1>Register</h1>
            <p>Register now and get access to the best features</p>
            <div class="register-link">Already have an account? <a href="register.php">Register</a></div>
        </div>
    </section>
    <section class="right">
        <div class="wrapper_form" id="form-wrapper">
            <div>
                <h1>Login</h1>
            </div>
            <form action="login.php" method="post">
                <input type="email" id="email" name="email" placeholder="Email:" required><br>
                <input type="password" id="password" name="password" placeholder="Password:" required><br>
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                <input type="submit" value="Submit">
                
                <?php if (!empty($login_err)) { ?>
                    <div class="error_msg">
                        <p class="error"><?php echo htmlspecialchars($login_err); ?></p>
                    </div>
                    <?php } ?>
                </form>
            </div>
        </section>
    </div>
        <script src="assets/js/gsap-1.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/kursor@0.0.14/dist/kursor.js"></script>
        <script>
            new kursor({
            type: 4
        })
        </script>
</body>
</html>

<video autoplay muted loop id="backgroundVideo">