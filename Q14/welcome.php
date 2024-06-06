<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    // If user is not logged in, redirect to login page
    header('Location: login.php');
    exit();
}

// Fetch user details from session
$userEmail = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 'Unknown';
$userName = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Unknown';

// Logout logic
if (isset($_POST['logout'])) {
    // Clear all session variables
    session_unset();
    // Destroy the session
    session_destroy();
    // Redirect to login page
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome, you are authenticated</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f7f7f7;">
    <div style="max-width: 600px; margin: 50px auto; padding: 20px; background-color: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
        <h2 style="color: #333; text-align: center; margin-bottom: 20px;">Welcome, you are authenticated</h2>
        
        <!-- Authentication information -->
        <div style="text-align: center; margin-bottom: 20px;">
            <p style="font-size: 16px; color: #555;">You are authenticated to access this page using Google authentication.</p>
        </div>
        
        <div style="text-align: center; margin-bottom: 20px;">
            <!-- User avatar -->
            <img src="<?php echo isset($_SESSION['user_avatar']) ? $_SESSION['user_avatar'] : 'https://via.placeholder.com/100'; ?>" alt="Avatar" style="width: 100px; height: 100px; border-radius: 50%; margin-bottom: 10px;">
            <!-- User name -->
            <div style="font-size: 20px; font-weight: bold; color: #555;"><?php echo $userName; ?></div>
            <!-- User email -->
            <div style="font-size: 16px; color: #777;"><?php echo $userEmail; ?></div>
        </div>
        <!-- Logout button -->
        <form method="post" style="text-align: center;">
            <button type="submit" name="logout" style="display: block; width: 100%; padding: 10px 0; background-color: #3498db; color: #fff; border: none; border-radius: 4px; cursor: pointer; transition: background-color 0.3s;">Logout</button>
        </form>
    </div>
</body>
</html>
