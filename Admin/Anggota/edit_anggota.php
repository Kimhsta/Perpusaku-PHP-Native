<?php
require_once '../../Config/koneksi.php';

$nim = $_GET['nim'] ?? '';

// Query untuk mendapatkan data anggota berdasarkan NIM
$stmt = $conn->prepare("SELECT * FROM Anggota WHERE nim = ?");
$stmt->execute([$nim]);
$anggota = $stmt->fetch(PDO::FETCH_ASSOC) ?? [
    'nama' => '',
    'jenis_kelamin' => '',
    'kelas' => '',
    'tgl_lahir' => '',
    'jurusan' => '',
    'status_mhs' => '',
    'no_telp' => ''
];
?>

<form method="POST" action="update_anggota.php">
  <div class="row g-3">
    <div class="col-md-6">
      <label for="nama" class="form-label">Nama</label>
      <input type="text" class="form-control" id="nama" name="nama" value="<?= htmlspecialchars($anggota['nama']); ?>" required>
    </div>
    <div class="col-md-6">
      <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
      <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
        <option value="Laki-Laki" <?= $anggota['jenis_kelamin'] === 'Laki-Laki' ? 'selected' : ''; ?>>Laki-Laki</option>
        <option value="Perempuan" <?= $anggota['jenis_kelamin'] === 'Perempuan' ? 'selected' : ''; ?>>Perempuan</option>
      </select>
    </div>
    <div class="col-md-6">
      <label for="kelas" class="form-label">Kelas</label>
      <input type="text" class="form-control" id="kelas" name="kelas" value="<?= htmlspecialchars($anggota['kelas']); ?>" required>
    </div>
    <div class="col-md-6">
      <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
      <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir" value="<?= htmlspecialchars($anggota['tgl_lahir']); ?>" required>
    </div>
    <div class="col-md-6">
      <label for="jurusan" class="form-label">Jurusan</label>
      <input type="text" class="form-control" id="jurusan" name="jurusan" value="<?= htmlspecialchars($anggota['jurusan']); ?>" required>
    </div>
    <div class="col-md-6">
      <label for="status_mhs" class="form-label">Status</label>
      <select class="form-select" id="status_mhs" name="status_mhs" required>
        <option value="Aktif" <?= $anggota['status_mhs'] === 'Aktif' ? 'selected' : ''; ?>>Aktif</option>
        <option value="Tidak Aktif" <?= $anggota['status_mhs'] === 'Tidak Aktif' ? 'selected' : ''; ?>>Tidak Aktif</option>
      </select>
    </div>
    <div class="col-md-6">
      <label for="no_telp" class="form-label">No_telpon</label>
      <input type="text" class="form-control" id="no_telp" name="no_telp" value="<?= htmlspecialchars($anggota['no_telp']); ?>" required>
    </div>
  </div>
  <div class="d-flex justify-content-end mt-4">
    <button type="submit" class="text-white btn btn-warning">Simpan Perubahan</button>
  </div>
  <input type="hidden" name="nim" value="<?= htmlspecialchars($nim); ?>">
</form>
