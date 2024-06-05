<?php
// Include config file
require_once "class/Database.php";

// Start the session
session_start();

// Check if the user is logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// If logout request is received, call the logout function
if(isset($_GET["logout"]) && $_GET["logout"] == "true") {
    Database::logout();
}

// Fetch user information from the database based on the session email
$user = Database::getUserInfo($_SESSION["email"]);

// Define variables for password update
$new_password = "";
$password_err = "";

// Process form submission for updating password, email and profile_pic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check CSRF token validity
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $register_err = "Invalid CSRF token.";
    } else {
        // Check if new password is provided
        if (isset($_POST["password"])) {
            $new_password = $_POST["password"];
        }

        // Update the user's profile with the new information
        $update_result = Database::updateProfile($user["email"], $new_password, $_FILES["profile_pic"]["name"], $_FILES["profile_pic"]["tmp_name"]);

        if ($update_result === false) {
            $register_err = "Failed to update profile.";
        } else {
            // Redirect to the same page to avoid form resubmission
            header("Location: user_info.php");
            exit;
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
    <link rel="stylesheet" href="https://unpkg.com/kursor/dist/kursor.css">
    <title>User Info</title>
</head>
<body>
<div class="background-video">
<video autoplay muted loop playsinline id="backgroundVideo">
    <source src="assets/video/video.mp4" type="video/mp4">
    Your browser does not support HTML5 video.
</video>
    </div>
    <div class="section-container">
    <section class="left">
    <div class="user-info">
        <!-- Display user information here -->
        <h1>Welcome, <?php echo htmlspecialchars($user["username"]); ?>!</h1>
        <p>Email: <?php echo htmlspecialchars($user["email"]); ?></p>
        <p>Registered on: <?php echo htmlspecialchars($user["registered_on"]); ?></p>
        <?php if (!empty($user["profile_pic"])): ?>
            <img class="profile_pic" src="<?php echo htmlspecialchars($user["profile_pic"]); ?>" alt="Profile Picture">
        <?php endif; ?>
        <!-- Add logout button -->
        <div class="logout_wrapper">
            <a href="user_info.php?logout=true">Logout</a>
        </div>
    </div>        
    </section >
    <!-- Form to update profile -->

    <section class="right">
    <div class="update-profile">
        <h2>Edit Profile</h2>
        <div class="form_wrapper"></div>
        <form action="user_info.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user["email"]); ?>" required>
            <label for="password">New Password:</label>
            <input type="password" id="password" name="password" placeholder="Leave blank to keep current password">

            <label for="profile_pic">Profile Picture:</label>
            <img id="profile_pic_preview" src="#" alt="Profile Picture Preview" style="display: none;">

            <label for="profile_pic" class="custom-file-upload">
            <input type="file" id="profile_pic" name="profile_pic" style="display: none;">
                Choose Profile Picture
            </label>
            
            <input type="submit" value="Update Profile">
        </form>
    </div>
    </section>
    </div>
    
    <div id="error_message" class="error_message">Error message goes here</div>


    

    <script src="https://cdn.jsdelivr.net/npm/kursor@0.0.14/dist/kursor.js"></script>
    <script src="assets/js/img_upload.js"></script>
    <script>
        new kursor({
            type: 4
        })
</script>
</body>
</html>
