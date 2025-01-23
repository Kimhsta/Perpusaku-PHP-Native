<?php
require_once '../Config/koneksi.php'; // Sesuaikan path dengan struktur folder
include 'header.php'; // Sesuaikan path dengan struktur folder

// Query untuk mengambil data buku dari database
$query = $conn->query("SELECT * FROM buku ORDER BY judul_buku ASC");
$buku = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Content -->
<section class="search-bar d-flex mt-3">
    <input type="text" class="form-control" placeholder="Search" />
    <button class="btn"><i class="fas fa-search"></i></button>
</section>
<section
    class="banner mt-4 d-flex justify-content-between align-items-center">
    <h2 class="fw-bold fs-7">Minimal Literasi lah dek!</h2>
    <img src="../Assets/img/pngegg.png" alt="Books" height="200" />
</section>
<!-- Konten Buku Populer -->
<section class="conten mt-4">
    <h3 class="mb-4">Buku Populer</h3>
    <div class="row g-3">
        <?php foreach ($buku as $b): ?>
            <!-- Gunakan col-6 untuk tampilan mobile (2 buku per baris) dan col-md-4 untuk tampilan desktop -->
            <div class="col-6 col-md-4">
                <div class="card">
                    <!-- Cover Buku -->
                    <img src="../Assets/uploads/<?= $b['cover'] ?>" class="img-fluid" alt="<?= $b['judul_buku'] ?>" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <!-- Judul Buku -->
                        <h5 class="card-title" style="font-size: 1rem;"><?= $b['judul_buku'] ?></h5>
                        <!-- Deskripsi Buku -->
                        <p class="card-text" style="font-size: 0.9rem;"><?= $b['pengarang'] ?></p>
                        <!-- Tombol Detail -->
                        <a href="#" class="btn btn-primary btn-sm">Lihat Detail</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<script src="../Assets/scripts/home.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>