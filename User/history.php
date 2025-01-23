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

<!-- Konten History Peminjaman -->
<section class="conten mt-4">
    <h3 class="mb-4 text-bold">Riwayat Peminjaman</h3>
    <div>
        <?php if (empty($history)): ?>
            <p class="text-center">Tidak ada riwayat peminjaman.</p>
        <?php else: ?>
            <div class="row">
                <?php foreach ($history as $h): ?>
                    <div class="col-12 mb-3">
                        <div class="card shadow-sm">
                            <div class="row g-0">
                                <div class="col-3">
                                    <img src="../Assets/uploads/<?= $h['cover'] ?>"
                                        alt="<?= $h['judul_buku'] ?>"
                                        class="img-fluid rounded-lg"
                                        style="width: 100%; height: auto; object-fit: contain; background-color: #f8f9fa;">
                                </div>
                                <div class="col-9">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= $h['judul_buku'] ?></h5>
                                        <p class="card-text text-muted">
                                            <small>Status:
                                                <span class="badge <?= $h['status_pengembalian'] === 'Lunas' ? 'bg-success' : 'bg-warning' ?>">
                                                    <?= $h['status_pengembalian'] ?? 'Belum Dikembalikan' ?>
                                                </span>
                                            </small>
                                        </p>
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal<?= $h['kode_pinjam'] ?>">
                                            Lihat Detail
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Detail Riwayat -->
                    <div class="modal fade" id="detailModal<?= $h['kode_pinjam'] ?>" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="detailModalLabel">Detail Riwayat</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Judul Buku:</strong> <?= $h['judul_buku'] ?></p>
                                    <p><strong>Tanggal Pinjam:</strong> <?= date('d M Y', strtotime($h['tgl_pinjam'])) ?></p>
                                    <p><strong>Estimasi Kembali:</strong> <?= date('d M Y', strtotime($h['estimasi_pinjam'])) ?></p>
                                    <p><strong>Tanggal Kembali:</strong> <?= $h['tgl_kembali'] ? date('d M Y', strtotime($h['tgl_kembali'])) : 'Belum Dikembalikan' ?></p>
                                    <p><strong>Kondisi Buku:</strong> <?= $h['kondisi_buku'] ?? '-' ?></p>
                                    <p><strong>Denda:</strong> <?= $h['denda'] ? 'Rp ' . number_format($h['denda'], 0, ',', '.') : 'Tidak Ada' ?></p>
                                    <p><strong>Pembayaran:</strong> <?= $h['pembayaran'] ?? '-' ?></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>