<?php
// Include file koneksi database dan header
require_once '../Config/koneksi.php'; // Sesuaikan path dengan struktur folder
include 'header.php'; // Sesuaikan path dengan struktur folder

// Ambil NIM dari session
$nim = $_SESSION['nim'];

// Query untuk mengambil history peminjaman dan pengembalian
$query = $conn->prepare("
    SELECT 
        p.kode_pinjam,
        p.tgl_pinjam,
        p.estimasi_pinjam,
        p.status AS status_peminjaman,
        pg.tgl_kembali,
        pg.kondisi_buku,
        pg.denda,
        pg.status AS status_pengembalian,
        pg.pembayaran,
        b.judul_buku,
        b.cover
    FROM 
        peminjaman p
    LEFT JOIN 
        pengembalian pg ON p.kode_pinjam = pg.kode_pinjam
    LEFT JOIN 
        buku b ON p.kode_buku = b.kode_buku
    WHERE 
        p.nim = :nim
    ORDER BY 
        p.tgl_pinjam DESC
");
$query->bindParam(':nim', $nim);
$query->execute();
$history = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Content -->
<section class="search-bar d-flex mt-3">
    <input type="text" class="form-control" placeholder="Search" />
    <button class="btn"><i class="fas fa-search"></i></button>
</section>
<section class="banner mt-2 d-flex justify-content-between align-items-center">
    <h2 class="fw-bold fs-7">History Peminjaman</h2>
    <img src="../Assets/img/pngegg.png" alt="Books" height="200" />
</section>

<!-- Konten History Peminjaman -->
<section class="conten mt-2">
    <h3 class="mb-4">Riwayat Peminjaman dan Pengembalian</h3>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul Buku</th>
                    <th>Tanggal Pinjam</th>
                    <th>Estimasi Kembali</th>
                    <th>Tanggal Kembali</th>
                    <th>Kondisi Buku</th>
                    <th>Denda</th>
                    <th>Status Pengembalian</th>
                    <th>Pembayaran</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($history)): ?>
                    <tr>
                        <td colspan="9" class="text-center">Tidak ada riwayat peminjaman.</td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; ?>
                    <?php foreach ($history as $h): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <img src="../Assets/uploads/<?= $h['cover'] ?>" alt="<?= $h['judul_buku'] ?>" style="width: 50px; height: 70px; object-fit: cover;">
                                <?= $h['judul_buku'] ?>
                            </td>
                            <td><?= date('d M Y', strtotime($h['tgl_pinjam'])) ?></td>
                            <td><?= date('d M Y', strtotime($h['estimasi_pinjam'])) ?></td>
                            <td>
                                <?= $h['tgl_kembali'] ? date('d M Y', strtotime($h['tgl_kembali'])) : 'Belum Dikembalikan' ?>
                            </td>
                            <td><?= $h['kondisi_buku'] ?? '-' ?></td>
                            <td><?= $h['denda'] ? 'Rp ' . number_format($h['denda'], 0, ',', '.') : 'Tidak Ada' ?></td>
                            <td>
                                <span class="badge <?= $h['status_pengembalian'] === 'Lunas' ? 'bg-success' : 'bg-warning' ?>">
                                    <?= $h['status_pengembalian'] ?? 'Belum Lunas' ?>
                                </span>
                            </td>
                            <td><?= $h['pembayaran'] ?? '-' ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>