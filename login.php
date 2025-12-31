<?php
session_start();
include "db.php";

$error = "";

if(isset($_POST['login'])){
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result && $result->num_rows === 1){
        $user = $result->fetch_assoc();

        // Verify password
        if(password_verify($password, $user['password'])){
            $_SESSION['user'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            if($user['role'] === 'admin'){
                header("Location: admin/dashboard.php");
            } else {
                header("Location: billing.php");
            }
            exit();
        } else {
            $error = "Password does not match!";
        }
    } else {
        $error = "Username not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Canteen Login</title>
<style>
body {
    font-family: Poppins, sans-serif;
    background: #eef2f6;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}
.box {
    width: 340px;
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.box h2 {
    text-align: center;
    color: #203a43;
    margin-bottom: 20px;
}
input {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border: 1px solid #aaa;
    border-radius: 6px;
    font-size: 15px;
}
button {
    width: 100%;
    background: #203a43;
    border: none;
    padding: 12px;
    color: #fff;
    border-radius: 6px;
    cursor: pointer;
    margin-top: 16px;
    font-size: 16px;
}
button:hover { background: #2c5364; }
.error {
    color: red;
    text-align: center;
    margin-top: 10px;
}
</style>
</head>
<body>
<div class="box">
    <h2>Canteen Login</h2>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
        <?php if($error) echo "<div class='error'>$error</div>"; ?>
    </form>
</div>
</body>
</html>
