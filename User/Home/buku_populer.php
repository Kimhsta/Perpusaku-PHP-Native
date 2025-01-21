<?php
require_once '../../Config/koneksi.php'; // Sesuaikan path dengan struktur folder
include '../header.php'; // Sesuaikan path dengan struktur folder

// Query untuk mengambil data buku dari database
$query = $conn->query("SELECT * FROM buku ORDER BY judul_buku ASC");
$buku = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Konten Buku Populer -->
<section class="container mt-5">
    <h1 class="text-center mb-4">Buku Populer</h1>
    <div class="row">
        <?php foreach ($buku as $b): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <!-- Cover Buku -->
                    <img src="<?= $b['cover'] ?>" class="card-img-top" alt="<?= $b['judul_buku'] ?>" style="height: 300px; object-fit: cover;">
                    <div class="card-body">
                        <!-- Judul Buku -->
                        <h5 class="card-title"><?= $b['judul_buku'] ?></h5>
                        <!-- Pengarang -->
                        <p class="card-text">Oleh: <?= $b['pengarang'] ?></p>
                        <!-- Penerbit -->
                        <p class="card-text">Penerbit: <?= $b['penerbit'] ?></p>
                        <!-- Tanggal Terbit -->
                        <p class="card-text">Terbit: <?= date('d M Y', strtotime($b['tanggal_terbit'])) ?></p>
                        <!-- Tombol Detail -->
                        <a href="#" class="btn btn-primary">Lihat Detail</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<script src="../Assets/scripts/home.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>