<?php
$pageName = "Login";
session_start();

include_once(__DIR__ . "/../includes/dbConnect.php");
// Check if the user is already logged in
if (isset($_SESSION['username'])) {
    header('Location: dashboard.php');
}
// Check if any users exist in the database
$check_users = $db->query("SELECT * FROM users");
if ($check_users->num_rows == 0) {
    // set session variable to flag that we are setting up fresh install
    $_SESSION['setup'] = true;
    // If no users exist, redirect to the setup.php page to create the first user
    header('Location: register.php');
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $login_stmt = $db->prepare("SELECT * FROM users WHERE username = ?");

        $login_stmt->bind_param("s", $username);
        $login_stmt->execute();
        $login_results = $login_stmt->get_result();

        if (mysqli_num_rows($login_results) > 0) {
            $user = mysqli_fetch_assoc($login_results);

            if (!password_verify($password, $user['password']) || $user['username'] !== $username) {
                echo "Invalid username or password.";
            } else {
                session_regenerate_id();
                // Here you can set the user data in the session to keep the user logged in
                $_SESSION['username'] = $username;

                $current_timestamp = date('Y-m-d H:i:s');
                // Update the last_login field with the current timestamp
                $update_stmt = $db->prepare("UPDATE users SET last_login = ? WHERE username = ?");
                $update_stmt->bind_param("ss", $username, $current_timestamp);
                $update_stmt->execute();


                // Then redirect to the desired page
                header('Location: dashboard.php');
            }
        }
    }
}

// check for alert message and sets it and then unsets from session
$alert = "";
if (isset($_SESSION['msg'])) {

    $alert = $_SESSION['msg'];
    unset($_SESSION['msg']);
}



include_once(__DIR__ . "/../includes/header.php");

//page content starts here
?>

<h2>Login Form</h2>
<?= $alert ?>
<form class="pure-form" action="index.php" method="post">

    <fieldset>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" placeholder="Username">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Password">

        <button type="submit" class="pure-button pure-button-primary">Sign in</button>

    </fieldset>

</form>

<a href="/register.php">Register</a>
<?php
//page content ends here
include_once(__DIR__ . "/../includes/footer.php");
?>