<?php
// Include config file
include_once "class/Database.php";

// Start session
session_start();

$conn = Database::getConnection();

$username = $email = $password = "";
$username_err = $email_err = $password_err = $register_err = "";

// Generate a CSRF token if one doesn't exist
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check CSRF token validity
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $register_err = "Invalid CSRF token.";
    } else {
        $username = htmlspecialchars($_POST['username']);
        $email = htmlspecialchars($_POST['email']);
        $password = $_POST['password'];

        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email_err = "Invalid email format";
        }

        // Other validations for username, password, etc.

        if (empty($email_err) && empty($username_err) && empty($password_err)) {
            // Attempt to register
            $register_result = Database::addUser($username, $password, $email);

            if ($register_result === false) {
                // Registration failed, display error message
                $register_err = "Username or email already taken.";
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
    <title>Register</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.3/gsap.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/kursor/dist/kursor.css">
</head>
<body>
<div class="section-container"> 
<video autoplay muted loop playsinline id="backgroundVideo">
    <source src="assets/video/video.mp4" type="video/mp4">
    Your browser does not support HTML5 video.
</video>
    <section class="left">

        <div class="wrapper" id="register-wrapper">
            <h1>Register</h1>
            <p>Register now and get access to the best features</p>
            <div class="register-link">Already have an account? <a href="login.php">Log in</a></div>
        </div>
    </section>
    <section class="right">
        <div class="wrapper_form" id="form-wrapper">
            <div>
                <h1>Register</h1>
            </div>
            <form action="register.php" method="post">
                <input type="text" id="username" name="username" placeholder="Username:" required>
                <input type="email" id="email" name="email" placeholder="Email:" required>
                <input type="password" id="password" name="password" placeholder="Password:" required><br>
                <!-- Password strength meter -->
                <div id="password-strength-status">
                    <div id="password-strength-bar"></div>
                    <span id="password-strength-text">Weak</span>
                </div>
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                <input type="submit" value="Submit">
                <div class="error_msg">
                    <span class="error"><?php echo htmlspecialchars($register_err); ?></span><br>
                </div>
            </form>
        </div>
    </section>
</div>
    <script src="assets/js/password-strength.js"></script>
    <script src="assets/js/gsap-1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/kursor@0.0.14/dist/kursor.js"></script>
    <script>
        new kursor({
            type: 4
        })
    </script>
</body>
</html>
