<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit();
}

$conn = new mysqli("localhost", "root", "", "tesda_db");
if ($conn->connect_error) {
  die("Nabigo ang koneksyon: " . $conn->connect_error);
}

$mensahe = "";
$edit_id = "";
$edit_pangalan = "";
$edit_kurso = "";

// CREATE
if (isset($_POST['ipasa'])) {
  $stmt = $conn->prepare("INSERT INTO trainees (pangalan, kurso) VALUES (?, ?)");
  $stmt->bind_param("ss", $_POST['pangalan'], $_POST['kurso']);
  if ($stmt->execute()) {
    $mensahe = "Matagumpay na naidagdag ang trainee!";
  }
  $stmt->close();
}

// DELETE
if (isset($_GET['burahin'])) {
  $stmt = $conn->prepare("DELETE FROM trainees WHERE id = ?");
  $stmt->bind_param("i", $_GET['burahin']);
  if ($stmt->execute()) {
    $mensahe = "Nabura na ang record!";
  }
  $stmt->close();
}

// KUNIN ANG IE-EDIT
if (isset($_GET['edit'])) {
  $edit_id = $_GET['edit'];
  $stmt = $conn->prepare("SELECT * FROM trainees WHERE id = ?");
  $stmt->bind_param("i", $edit_id);
  $stmt->execute();
  $res = $stmt->get_result();
  if ($row = $res->fetch_assoc()) {
    $edit_pangalan = $row['pangalan'];
    $edit_kurso = $row['kurso'];
  }
  $stmt->close();
}

// UPDATE
if (isset($_POST['i_update'])) {
  $stmt = $conn->prepare("UPDATE trainees SET pangalan = ?, kurso = ? WHERE id = ?");
  $stmt->bind_param("ssi", $_POST['pangalan'], $_POST['kurso'], $_POST['id']);
  if ($stmt->execute()) {
    $mensahe = "Matagumpay na na-update ang record!";
  }
  $stmt->close();
}

// READ
$resulta = $conn->query("SELECT * FROM trainees");
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Trainee Management System</title>
  <link href="bootstrap.min.css" rel="stylesheet">
</head>


 

<body class="bg-light">

  <!-- Top Navigation Bar -->
  <nav class="navbar navbar-dark bg-primary mb-4 shadow-sm">
    <div class="container">
      <span class="navbar-brand mb-0 h1">TESDA Trainee System</span>
      <div class="d-flex align-items-center text-white">
        <span class="me-3">Kumusta, <strong><?php echo htmlspecialchars($_SESSION['user']); ?></strong></span>
        <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="container">
    <?php if ($mensahe != ""): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo $mensahe; ?>
      </div>
    <?php endif; ?>

    <div class="row g-4">
      <!-- Kaliwang Bahagi: Form -->
      <div class="col-md-5">
        <div class="card shadow-sm">
          <div class="card-header bg-white fw-bold">
            <?php echo ($edit_id != "") ? "I-edit ang Detalye ng Trainee" : "Mag-register ng Bagong Trainee"; ?>
          </div>
          <div class="card-body">
            <form method="POST" action="index.php">
              <input type="hidden" name="id" value="<?php echo $edit_id; ?>">

              <div class="mb-3">
                <label class="form-label">Buong Pangalan</label>
                <input type="text" name="pangalan" class="form-control" required value="<?php echo htmlspecialchars($edit_pangalan); ?>" placeholder="Ilagay ang pangalan">
              </div>

              <div class="mb-3">
                <label class="form-label">Kurso</label>
                <input type="text" name="kurso" class="form-control" required value="<?php echo htmlspecialchars($edit_kurso); ?>" placeholder="Halimbawa: Web Dev NC III">
              </div>

              <?php if ($edit_id != ""): ?>
                <button type="submit" name="i_update" class="btn btn-success w-100">I-save ang Pagbabago</button>
                <a href="index.php" class="btn btn-secondary w-100 mt-2">Kanselahin</a>
              <?php else: ?>
                <button type="submit" name="ipasa" class="btn btn-primary w-100">I-save sa Database</button>
              <?php endif; ?>
            </form>
          </div>
        </div>
      </div>

      <!-- Kanang Bahagi: Listahan Table -->
      <div class="col-md-7">
        <div class="card shadow-sm">
          <div class="card-header bg-white fw-bold">
            Listahan ng mga Naka-register na Trainees
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                  <tr>
                    <th>ID</th>
                    <th>Buong Pangalan</th>
                    <th>Kurso</th>
                    <th>Aksyon</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if ($resulta->num_rows > 0): ?>
                    <?php while($row = $resulta->fetch_assoc()): ?>
                      <tr>
                        <td><?php echo $row["id"]; ?></td>
                        <td><?php echo htmlspecialchars($row["pangalan"]); ?></td>
                        <td><?php echo htmlspecialchars($row["kurso"]); ?></td>
                        <td>
                          <a href="index.php?edit=<?php echo $row["id"]; ?>" class="btn btn-warning btn-sm">Edit</a>
                          <a href="index.php?burahin=<?php echo $row["id"]; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Sigurado ka bang buburahin ito?')">Delete</a>
                        </td>
                      </tr>
                    <?php endwhile; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="4" class="text-center text-muted">Wala pang naka-register na trainee.</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
