<?php
require_once '../../Config/koneksi.php';

// Ambil ID petugas dari parameter URL
$id_petugas = isset($_GET['id_petugas']) ? $_GET['id_petugas'] : null;

if ($id_petugas) {
    // Query untuk mengambil data petugas berdasarkan ID
    $query = $conn->prepare("SELECT * FROM petugas WHERE id_petugas = :id_petugas");
    $query->bindParam(':id_petugas', $id_petugas, PDO::PARAM_INT);
    $query->execute();
    $petugas = $query->fetch(PDO::FETCH_ASSOC);

    if ($petugas) {
        ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Petugas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .print-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
        }
        .profile-img img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #ddd;
        }
        .info-table th, .info-table td {
            padding: 10px;
        }

        /* Hide elements during print */
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="print-container shadow">
            <h1 class="text-center mb-4">Detail Data Petugas</h1>
            <div class="text-center mb-4 profile-img">
                <img src="../../Assets/uploads/<?= $petugas['profil_gambar']; ?>" alt="Foto Profil">
            </div>
            <table class="table table-bordered info-table">
                <tbody>
                    <tr>
                        <th class="bg-primary text-white">ID</th>
                        <td><?= $petugas['id_petugas']; ?></td>
                    </tr>
                    <tr>
                        <th class="bg-primary text-white">Nama</th>
                        <td><?= $petugas['nama_petugas']; ?></td>
                    </tr>
                    <tr>
                        <th class="bg-primary text-white">Username</th>
                        <td><?= $petugas['username']; ?></td>
                    </tr>
                    <tr>
                        <th class="bg-primary text-white">No. Telp</th>
                        <td><?= $petugas['no_telp']; ?></td>
                    </tr>
                    <tr>
                        <th class="bg-primary text-white">Jenis Kelamin</th>
                        <td><?= $petugas['jenis_kelamin']; ?></td>
                    </tr>
                </tbody>
            </table>
            <div class="text-center mt-4 no-print">
                <button class="btn btn-primary" onclick="window.print()">
                    <i class="fas fa-print"></i> Print
                </button>
                <a href="admin.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

        <?php
    } else {
        echo "<div class='alert alert-danger text-center'>Data petugas tidak ditemukan.</div>";
    }
} else {
    echo "<div class='alert alert-danger text-center'>ID petugas tidak diberikan.</div>";
}
?>
