<?php
require_once '../../Config/koneksi.php';

// Ambil ID Petugas dari URL
$id_petugas = isset($_GET['id_petugas']) ? $_GET['id_petugas'] : null;

if ($id_petugas) {
    // Query untuk mengambil data petugas berdasarkan ID
    $stmt = $conn->prepare("SELECT * FROM petugas WHERE id_petugas = :id_petugas");
    $stmt->bindValue(':id_petugas', $id_petugas, PDO::PARAM_INT);
    $stmt->execute();

    $petugas = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($petugas) {
        // Menampilkan data petugas dalam form
        echo '<form method="POST" id="editPetugasForm" enctype="multipart/form-data" action="update_petugas.php">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="nama_petugas" class="form-label">Nama Petugas</label>
                        <input type="text" class="form-control" id="nama_petugas" name="nama_petugas" value="' . htmlspecialchars($petugas['nama_petugas']) . '" required>
                    </div>
                    <div class="col-md-6">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="' . htmlspecialchars($petugas['username']) . '" required>
                    </div>
                    <div class="col-md-6">
                        <label for="no_telp" class="form-label">No. Telp</label>
                        <input type="text" class="form-control" id="no_telp" name="no_telp" value="' . htmlspecialchars($petugas['no_telp']) . '" required>
                    </div>
                    <div class="col-md-6">
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                        <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                            <option value="Laki-laki" ' . ($petugas['jenis_kelamin'] == 'Laki-laki' ? 'selected' : '') . '>Laki-laki</option>
                            <option value="Perempuan" ' . ($petugas['jenis_kelamin'] == 'Perempuan' ? 'selected' : '') . '>Perempuan</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="profil_gambar" class="form-label">Profil Gambar</label>
                        <input type="file" class="form-control" id="profil_gambar" name="profil_gambar">
                        <img src="../../Assets/uploads/' . htmlspecialchars($petugas['profil_gambar']) . '" alt="Profil Image" style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%; margin-top: 10px;">
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-warning">Update Petugas</button>
                </div>
                <input type="hidden" name="id_petugas" value="' . $petugas['id_petugas'] . '">
              </form>';
    } else {
        echo '<p class="text-danger">Petugas tidak ditemukan.</p>';
    }
} else {
    echo '<p class="text-danger">ID Petugas tidak valid.</p>';
}
?>
