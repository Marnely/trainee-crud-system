<?php
$conn = new mysqli("localhost", "root", "", "tesda_db");
$mensahe = "";

if (isset($_POST['register'])) {
  $username = $_POST['username'];
  // I-encrypt ang password gamit ang password_hash() para sa seguridad
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

  $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
  if ($conn->query($sql) === TRUE) {
    $mensahe = "Matagumpay ang pagre-register! <a href='login.php'>Mag-login dito</a>";
  } else {
    $mensahe = "Error: " . $conn->error;
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Mag-register ng Account</title>
  <style>
    body { font-family: Arial, sans-serif; background-color: #f0f4f8; padding: 50px; }
    .box { background: white; padding: 25px; border-radius: 8px; width: 320px; margin: 0 auto; border: 1px solid #cbd5e1; }
    input { width: 93%; padding: 10px; margin: 8px 0 15px 0; border: 1px solid #94a3b8; border-radius: 4px; }
    button { background: #16a34a; color: white; border: none; padding: 10px; width: 100%; border-radius: 4px; cursor: pointer; }
  </style>
</head>
<body>
  <div class="box">
    <h3>Gumawa ng Account</h3>
    <?php if ($mensahe != "") { echo "<p style='color: green;'>$mensahe</p>"; } ?>
    <form method="POST">
      <label>Username:</label>
      <input type="text" name="username" required>
      <label>Password:</label>
      <input type="password" name="password" required>
      <button type="submit" name="register">Mag-register</button>
    </form>
    <p style="font-size: 14px; text-align: center;">May account na? <a href="login.php">Mag-login</a></p>
  </div>
</body>
</html>
