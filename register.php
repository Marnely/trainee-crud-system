<?php
$conn = new mysqli("localhost", "root", "", "tesda_db");
$mensahe = "";

if (isset($_POST['register'])) {
  $username = $_POST['username'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

  $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
  $stmt->bind_param("ss", $username, $password);

  if ($stmt->execute()) {
    $mensahe = "Matagumpay ang pagre-register! <a href='login.php' class='alert-link'>Mag-login dito</a>";
  } else {
    $mensahe = "Error: " . $stmt->error;
  }
  $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - TESDA Trainee System</title>
  <link href="bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="min-height: 100vh;">

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-4">
        <div class="card shadow-sm">
          <div class="card-header bg-success text-white text-center fw-bold py-3">
            Gumawa ng Bagong Account
          </div>
          <div class="card-body p-4">
            <?php if ($mensahe != ""): ?>
              <div class="alert alert-success py-2"><?php echo $mensahe; ?></div>
            <?php endif; ?>

            <form method="POST">
              <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required placeholder="Pumili ng username">
              </div>

              <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required placeholder="Pumili ng password">
              </div>

              <button type="submit" name="register" class="btn btn-success w-100">Mag-register</button>
            </form>

            <hr>
            <p class="text-center mb-0 small">
              May account na? <a href="login.php" class="text-decoration-none">Mag-login dito</a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
