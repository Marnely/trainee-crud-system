<?php
session_start(); // Sinisimulan ang session para matandaan ang user
$conn = new mysqli("localhost", "root", "", "tesda_db");
$error = "";

if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $result = $conn->query("SELECT * FROM users WHERE username = '$username'");
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // I-verify kung tugma ang tinype na password sa naka-encrypt na password sa database
    if (password_verify($password, $row['password'])) {
      $_SESSION['user'] = $username;
      header("Location: index.php"); // Ilipat sa main dashboard
      exit();
    } else {
      $error = "Maling password!";
    }
  } else {
    $error = "Hindi makita ang username na iyan!";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login System</title>
  <style>
    body { font-family: Arial, sans-serif; background-color: #f0f4f8; padding: 50px; }
    .box { background: white; padding: 25px; border-radius: 8px; width: 320px; margin: 0 auto; border: 1px solid #cbd5e1; }
    input { width: 93%; padding: 10px; margin: 8px 0 15px 0; border: 1px solid #94a3b8; border-radius: 4px; }
    button { background: #2563eb; color: white; border: none; padding: 10px; width: 100%; border-radius: 4px; cursor: pointer; }
  </style>
</head>
<body>
  <div class="box">
    <h3>Mag-login sa System</h3>
    <?php if ($error != "") { echo "<p style='color: red;'>$error</p>"; } ?>
    <form method="POST">
      <label>Username:</label>
      <input type="text" name="username" required>
      <label>Password:</label>
      <input type="password" name="password" required>
      <button type="submit" name="login">Pumasok (Login)</button>
    </form>
    <p style="font-size: 14px; text-align: center;">Wala pang account? <a href="register.php">Mag-register</a></p>
  </div>
</body>
</html>
