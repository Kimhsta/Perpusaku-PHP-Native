<?php
require_once '../../Config/koneksi.php';

if (isset($_GET['kode_kembali'])) {
    $kode_kembali = $_GET['kode_kembali'];

    // Ambil data pengembalian berdasarkan kode_kembali
    $stmt = $conn->prepare("
        SELECT pengembalian.*, anggota.nama AS nama_anggota, buku.judul_buku
        FROM pengembalian
        JOIN peminjaman ON pengembalian.kode_pinjam = peminjaman.kode_pinjam
        JOIN anggota ON peminjaman.nim = anggota.nim
        JOIN buku ON peminjaman.kode_buku = buku.kode_buku
        WHERE pengembalian.kode_kembali = :kode_kembali
    ");
    $stmt->execute([':kode_kembali' => $kode_kembali]);
    $pengembalian = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pengembalian) {
        echo "Data pengembalian dengan kode $kode_kembali tidak ditemukan.";
        exit;
    }
} else {
    echo "Kode pengembalian tidak valid.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cetak Pengembalian</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 20px;
    }
    .container {
      max-width: 800px;
      margin: 0 auto;
      border: 1px solid #ddd;
      padding: 20px;
      border-radius: 5px;
    }
    .header, .footer {
      text-align: center;
    }
    .header img {
      max-width: 100px;
      margin-bottom: 10px;
    }
    .table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    .table th, .table td {
      border: 1px solid #ddd;
      padding: 8px;
    }
    .table th {
      background-color: #f2f2f2;
      text-align: left;
    }
    .badge {
      display: inline-block;
      padding: 5px 10px;
      font-size: 12px;
      color: white;
      border-radius: 3px;
    }
    .badge-success {
      background-color: #28a745;
    }
    .badge-warning {
      background-color: #ffc107;
    }
    .badge-danger {
      background-color: #dc3545;
    }
    .text-center {
      text-align: center;
    }
    .footer {
      margin-top: 20px;
      font-size: 12px;
      color: #555;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <img src="../../Assets/img/logo.png" alt="Logo Perpustakaan">
      <h2>Laporan Pengembalian</h2>
    </div>
    <p><strong>Kode Kembali:</strong> <?= htmlspecialchars($pengembalian['kode_kembali']); ?></p>
    <p><strong>Nama Anggota:</strong> <?= htmlspecialchars($pengembalian['nama_anggota']); ?></p>
    <p><strong>Judul Buku:</strong> <?= htmlspecialchars($pengembalian['judul_buku']); ?></p>
    <p><strong>Tanggal Kembali:</strong> <?= date('d-m-Y H:i', strtotime($pengembalian['tgl_kembali'] ?? 'now')); ?></p>
    <p><strong>Kondisi Buku:</strong> 
      <?php 
      if ($pengembalian['kondisi_buku'] === 'Bagus') {
          echo "<span class='badge badge-success'>Bagus</span>";
      } elseif ($pengembalian['kondisi_buku'] === 'Rusak') {
          echo "<span class='badge badge-warning'>Rusak</span>";
      } elseif ($pengembalian['kondisi_buku'] === 'Hilang') {
          echo "<span class='badge badge-danger'>Hilang</span>";
      }
      ?>
    </p>
    <p><strong>Denda:</strong> 
      <?php
      $denda = $pengembalian['denda'] ?? 0;
      if ($denda > 0) {
          // Jika ada denda dan buku rusak/hilang
          $statusDenda = "Keterlambatan";
          if ($pengembalian['kondisi_buku'] === 'Rusak' || $pengembalian['kondisi_buku'] === 'Hilang') {
              $statusDenda .= " dan Buku " . $pengembalian['kondisi_buku'];
          }
          echo "Rp" . number_format($denda, 2, ',', '.') . " - <br><strong>Karena $statusDenda</strong>";
      } else {
          // Jika tidak ada denda
          echo "Rp" . number_format($denda, 2, ',', '.') . " - <br><strong>Karena Tidak Ada Denda</strong>";
      }
      ?>
    </p>
    <p><strong>Status:</strong> <?= htmlspecialchars($pengembalian['status']); ?></p>
    <p><strong>Pembayaran:</strong> <?= htmlspecialchars($pengembalian['pembayaran']); ?></p>
    <button onclick="window.print()">Cetak</button>
    <div class="footer">
      <p>Dicetak pada <?= date('d-m-Y H:i'); ?></p>
      <p>Perpustakaan Universitas Duta Bangsa</p>
    </div>
  </div>
</body>
</html>
