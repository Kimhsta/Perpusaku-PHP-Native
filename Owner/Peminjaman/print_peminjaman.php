<?php
require_once '../../Config/koneksi.php';

if (isset($_GET['kode_pinjam'])) {
    $kode_pinjam = $_GET['kode_pinjam'];

    // Ambil data peminjaman berdasarkan ID
    $query = $conn->prepare("
        SELECT peminjaman.kode_pinjam, anggota.nama AS nama_anggota, buku.judul_buku, 
        petugas.nama_petugas, peminjaman.tgl_pinjam, peminjaman.estimasi_pinjam, peminjaman.kondisi_buku_pinjam 
        FROM peminjaman
        INNER JOIN anggota ON peminjaman.nim = anggota.nim
        INNER JOIN buku ON peminjaman.kode_buku = buku.kode_buku
        INNER JOIN petugas ON peminjaman.id_petugas = petugas.id_petugas
        WHERE peminjaman.kode_pinjam = :kode_pinjam
    ");
    $query->execute([':kode_pinjam' => $kode_pinjam]);
    $data = $query->fetch(PDO::FETCH_ASSOC);

    if ($data) {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Print Peminjaman</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { text-align: center; margin-bottom: 20px; }
                .header h1 { margin: 0; }
                .details { border: 1px solid #ddd; padding: 10px; }
                .details table { width: 100%; }
                .details table th, .details table td { text-align: left; padding: 8px; }
                .details table th { background-color: #f4f4f4; }
            </style>
        </head>
        <body onload="window.print()">
            <div class="container">
                <div class="header">
                    <h1>Detail Peminjaman</h1>
                </div>
                <div class="details">
                    <table>
                        <tr><th>Kode Pinjam</th><td><?= $data['kode_pinjam']; ?></td></tr>
                        <tr><th>Nama Anggota</th><td><?= $data['nama_anggota']; ?></td></tr>
                        <tr><th>Judul Buku</th><td><?= $data['judul_buku']; ?></td></tr>
                        <tr><th>Nama Petugas</th><td><?= $data['nama_petugas']; ?></td></tr>
                        <tr><th>Tanggal Pinjam</th><td><?= $data['tgl_pinjam']; ?></td></tr>
                        <tr><th>Estimasi Kembali</th><td><?= $data['estimasi_pinjam']; ?></td></tr>
                        <tr><th>Kondisi Buku</th><td><?= $data['kondisi_buku_pinjam']; ?></td></tr>
                    </table>
                </div>
            </div>
        </body>
        </html>
        <?php
    } else {
        echo "Data tidak ditemukan.";
    }
} else {
    echo "ID peminjaman tidak ditemukan.";
}
?>
