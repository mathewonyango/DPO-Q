<?php
require 'vendor/autoload.php';

session_start();

$client = new Google\Client();
$client->setClientId('33810375894-h54iabi62vanidumv4pv791oeoi3op11.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/DPO/Q14/login.php'); // Adjust the redirect URI
$client->addScope("email");
$client->addScope("profile");





if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    // If user is already logged in, redirect to welcome page
    header('Location: welcome.php');
    exit();
}

if (isset($_GET['code'])) {
    $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $userInfo = $client->verifyIdToken();

    // Verify user information
    if ($userInfo) {
        // Authentication successful, set session variables
        $_SESSION['user_logged_in'] = true;
        $_SESSION['user_email'] = $userInfo['email'];
        $_SESSION['user_name'] = $userInfo['name'];
        $_SESSION['user_avatar'] = isset($userInfo['picture']) ? $userInfo['picture'] : 'https://via.placeholder.com/100'; // Default avatar if not provided by Google
        // Assuming 'name' is the field containing the user's name in the Google API response
        // Additional processing if needed

        // Redirect to welcome page
        header('Location: welcome.php');
        exit();
    } else {
        // Authentication failed
        echo "Authentication failed.";
    }
} else {
    // Render the login page
    // Include HTML for login form and login button
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <div class="container" style="max-width: 600px; margin: 50px auto; padding: 20px; background-color: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); text-align: center;">
        <h2 class="title" style="color: #333; margin-bottom: 20px;">Login</h2>
        <p class="welcome-msg" style="color: #555; margin-bottom: 20px;">Welcome! Please log in with your Google account.</p>
        <!-- Login button -->
        <a href="<?php echo $client->createAuthUrl(); ?>" class="login-btn" style="display: inline-block; padding: 10px 20px; background-color: #3498db; color: #fff; text-decoration: none; border-radius: 4px; transition: background-color 0.3s;">Login with Google</a>
        
        <h2 class="title" style="color: #333; margin-top: 40px;">Who We Are</h2>
        <p class="info" style="color: #555; margin-bottom: 20px;">DPO is proud to be the largest and the fastest-growing African payment gateway. We offer small and medium-sized businesses, right up to global companies, the solution, the technology, the opportunity, and the support to make and receive online payments wherever and whenever they want.</p>
        
        
    </div>
</body>
</html>

<?php
}
?>