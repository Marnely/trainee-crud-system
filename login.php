<?php
session_start();
$conn = new mysqli("localhost", "root", "", "tesda_db");
$error = "";

if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Secure query gamit ang Prepared Statements
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (password_verify($password, $row['password'])) {
      $_SESSION['user'] = $username;
      header("Location: index.php");
      exit();
    } else {
      $error = "Maling password!";
    }
  } else {
    $error = "Hindi makita ang username na iyan!";
  }
  $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - TESDA Trainee System</title>
  <link href="bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="min-height: 100vh;">

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-4">
        <div class="card shadow-sm">
          <div class="card-header bg-primary text-white text-center fw-bold py-3">
            TESDA Trainee System - Login
          </div>
          <div class="card-body p-4">
            <?php if ($error != ""): ?>
              <div class="alert alert-danger py-2"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST">
              <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required placeholder="Ilagay ang username">
              </div>

              <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required placeholder="Ilagay ang password">
              </div>

              <button type="submit" name="login" class="btn btn-primary w-100">Pumasok (Login)</button>
            </form>

            <hr>
            <p class="text-center mb-0 small">
              Wala pang account? <a href="register.php" class="text-decoration-none">Mag-register dito</a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
