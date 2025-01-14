<?php
require_once '../../Config/koneksi.php';

if (isset($_GET['kode_pinjam'])) {
  $kode_pinjam = $_GET['kode_pinjam'];

  $stmt = $conn->prepare("
    SELECT p.kode_pinjam, a.nama_anggota
    FROM peminjaman p
    JOIN anggota a ON p.kode_anggota = a.kode_anggota
    WHERE p.kode_pinjam LIKE :kode_pinjam
  ");
  $stmt->execute([':kode_pinjam' => "%" . $kode_pinjam . "%"]);
  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

  echo json_encode($results); // Mengirimkan hasil pencarian dalam format JSON
}
?>
