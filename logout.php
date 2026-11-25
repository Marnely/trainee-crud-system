<?php
session_start();
session_destroy(); // Binubura ang session
header("Location: login.php");
exit();
?>
