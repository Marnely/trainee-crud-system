<?php
// 1. Koneksyon sa Database
$conn = new mysqli("localhost", "root", "", "tesda_db");
if ($conn->connect_error) {
  die("Nabigo ang koneksyon: " . $conn->connect_error);
}

$mensahe = "";
$edit_id = "";
$edit_pangalan = "";
$edit_kurso = "";

// 2. CREATE: Magdagdag ng bagong trainee
if (isset($_POST['ipasa'])) {
  $pangalan = $_POST['pangalan'];
  $kurso = $_POST['kurso'];
  $conn->query("INSERT INTO trainees (pangalan, kurso) VALUES ('$pangalan', '$kurso')");
  $mensahe = "Matagumpay na naidagdag ang trainee!";
}

// 3. DELETE: Burahin ang trainee gamit ang ID mula sa URL
if (isset($_GET['burahin'])) {
  $id = $_GET['burahin'];
  $conn->query("DELETE FROM trainees WHERE id = $id");
  $mensahe = "Nabura na ang record!";
}

// 4. Kunin ang detalye ng trainee na ie-edit
if (isset($_GET['edit'])) {
  $edit_id = $_GET['edit'];
  $res = $conn->query("SELECT * FROM trainees WHERE id = $edit_id");
  if ($row = $res->fetch_assoc()) {
    $edit_pangalan = $row['pangalan'];
    $edit_kurso = $row['kurso'];
  }
}

// 5. UPDATE: I-save ang binagong detalye
if (isset($_POST['i_update'])) {
  $id = $_POST['id'];
  $pangalan = $_POST['pangalan'];
  $kurso = $_POST['kurso'];
  $conn->query("UPDATE trainees SET pangalan='$pangalan', kurso='$kurso' WHERE id=$id");
  $mensahe = "Matagumpay na na-update ang record!";
}

// 6. READ: Kunin ang lahat ng records
$resulta = $conn->query("SELECT * FROM trainees");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Lesson 10: Kumpletong CRUD System</title>
  <style>
    body { font-family: Arial, sans-serif; background-color: #f0f4f8; padding: 30px; }
    .box { background: white; padding: 20px; border-radius: 8px; width: 550px; margin: 0 auto 20px auto; border: 1px solid #cbd5e1; }
    input { width: 96%; padding: 8px; margin: 8px 0 15px 0; border: 1px solid #94a3b8; border-radius: 4px; }
    button { background: #2563eb; color: white; border: none; padding: 10px; width: 100%; border-radius: 4px; cursor: pointer; }
    .update-btn { background: #16a34a; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { border: 1px solid #cbd5e1; padding: 10px; text-align: left; }
    th { background-color: #1e3a8a; color: white; }
    .btn-edit { background: #f59e0b; color: white; padding: 5px 8px; text-decoration: none; border-radius: 4px; font-size: 13px; }
    .btn-del { background: #ef4444; color: white; padding: 5px 8px; text-decoration: none; border-radius: 4px; font-size: 13px; }
  </style>
</head>
<body>

  <!-- Form para sa Pagdagdag o Pag-edit -->
  <div class="box">
    <h3><?php echo ($edit_id != "") ? "I-edit ang Detalye ng Trainee" : "Mag-register ng Trainee"; ?></h3>
    <?php if ($mensahe != "") { echo "<p style='color: green; font-weight: bold;'>$mensahe</p>"; } ?>
    
    <form method="POST" action="index.php">
      <input type="hidden" name="id" value="<?php echo $edit_id; ?>">

      <label>Buong Pangalan:</label>
      <input type="text" name="pangalan" required value="<?php echo $edit_pangalan; ?>" placeholder="Ilagay ang pangalan">

      <label>Kurso:</label>
      <input type="text" name="kurso" required value="<?php echo $edit_kurso; ?>" placeholder="Halimbawa: Web Dev NC III">

      <?php if ($edit_id != ""): ?>
        <button type="submit" name="i_update" class="update-btn">I-save ang Pagbabago</button>
      <?php else: ?>
        <button type="submit" name="ipasa">I-save sa Database</button>
      <?php endif; ?>
    </form>
  </div>

  <!-- Table ng mga Trainees -->
  <div class="box">
    <h3>Listahan ng mga Trainees</h3>
    <table>
      <tr>
        <th>ID</th>
        <th>Buong Pangalan</th>
        <th>Kurso</th>
        <th>Aksyon</th>
      </tr>
      <?php
      if ($resulta->num_rows > 0) {
        while($row = $resulta->fetch_assoc()) {
          echo "<tr>";
          echo "<td>" . $row["id"] . "</td>";
          echo "<td>" . $row["pangalan"] . "</td>";
          echo "<td>" . $row["kurso"] . "</td>";
          echo "<td>
                  <a class='btn-edit' href='index.php?edit=" . $row["id"] . "'>Edit</a>
                  <a class='btn-del' href='index.php?burahin=" . $row["id"] . "' onclick='return confirm(\"Sigurado ka bang buburahin ito?\")'>Delete</a>
                </td>";
          echo "</tr>";
        }
      } else {
        echo "<tr><td colspan='4'>Wala pang naka-register na trainee.</td></tr>";
      }
      ?>
    </table>
  </div>

</body>
</html>
