<?php
require_once '../../Config/koneksi.php';

if (isset($_GET['id_petugas'])) {
  $id_petugas = $_GET['id_petugas'];

  // First, delete the profile image from the server (optional)
  $query = $conn->prepare("SELECT profil_gambar FROM petugas WHERE id_petugas = :id_petugas");
  $query->bindParam(':id_petugas', $id_petugas, PDO::PARAM_INT);
  $query->execute();
  $row = $query->fetch(PDO::FETCH_ASSOC);

  if ($row && $row['profil_gambar']) {
    $imagePath = '../../Assets/uploads/' . $row['profil_gambar'];
    if (file_exists($imagePath)) {
      unlink($imagePath); // Delete the image
    }
  }

  // Then, delete the petugas from the database
  $deleteQuery = $conn->prepare("DELETE FROM petugas WHERE id_petugas = :id_petugas");
  $deleteQuery->bindParam(':id_petugas', $id_petugas, PDO::PARAM_INT);
  $deleteQuery->execute();

  // Redirect back to the main page after deletion
  header("Location: admin.php");
  exit();
}
?>
