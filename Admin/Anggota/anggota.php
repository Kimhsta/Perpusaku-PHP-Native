<?php
require_once '../../Config/koneksi.php';
include '../Layouts/header.php';

// Get the filter for member status
$memberFilter = isset($_GET['member_filter']) ? $_GET['member_filter'] : 'semua'; // Default is 'semua' (all)
$whereClause = '';
if ($memberFilter === 'aktif') {
  $whereClause = "WHERE anggota.status_mhs = 'Aktif'";
} elseif ($memberFilter === 'tidak_aktif') {
  $whereClause = "WHERE anggota.status_mhs = 'Tidak Aktif'";
}

$result = $conn->prepare("
    SELECT pengembalian.kode_kembali, pengembalian.tgl_kembali, pengembalian.kode_pinjam, 
           pengembalian.kondisi_buku, pengembalian.denda, pengembalian.status, 
           pengembalian.pembayaran, anggota.nama AS nama_anggota, 
           anggota.no_telp, anggota.status_mhs, buku.judul_buku 
    FROM pengembalian
    JOIN peminjaman ON pengembalian.kode_pinjam = peminjaman.kode_pinjam
    JOIN anggota ON peminjaman.nim = anggota.nim
    JOIN buku ON peminjaman.kode_buku = buku.kode_buku
    $whereClause
    LIMIT :limit OFFSET :offset
");

// Pagination logic
$limit = 10; // Jumlah data per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Halaman saat ini
$offset = ($page - 1) * $limit;

// Hitung total data
$totalQuery = $conn->query("SELECT COUNT(*) AS total FROM anggota");
$totalResult = $totalQuery->fetch(PDO::FETCH_ASSOC);
$totalRows = $totalResult['total'];
$totalPages = ceil($totalRows / $limit);

// Ambil data sesuai halaman dan filter status anggota
$result = $conn->prepare("
    SELECT * FROM anggota
    $whereClause
    LIMIT :limit OFFSET :offset
");
$result->bindValue(':limit', $limit, PDO::PARAM_INT);
$result->bindValue(':offset', $offset, PDO::PARAM_INT);
$result->execute();
?>

<section class="home-section">
  <div class="mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <!-- Judul -->
      <h2 class="fw-bold text-dark mb-0">Data Anggota</h2>

      <!-- Bagian Tombol dan Pencarian -->
      <div class="d-flex align-items-between gap-3">
        <!-- Filter Anggota -->
        <div class="dropdown rounded-3">
          <button class="btn btn-light border d-flex align-items-center"
            type="button" id="memberFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bx bx-filter-alt me-2"></i> Filter
          </button>
          <ul class="dropdown-menu" aria-labelledby="memberFilterDropdown">
            <li>
              <a class="dropdown-item d-flex align-items-center <?= $memberFilter === 'semua' ? 'active' : ''; ?>" href="?member_filter=semua">
                <i class="bx bx-check-circle me-2"></i> Semua
              </a>
            </li>
            <li>
              <a class="dropdown-item d-flex align-items-center <?= $memberFilter === 'aktif' ? 'active' : ''; ?>" href="?member_filter=aktif">
                <i class="bx bx-user-check me-2"></i> Aktif
              </a>
            </li>
            <li>
              <a class="dropdown-item d-flex align-items-center <?= $memberFilter === 'tidak_aktif' ? 'active' : ''; ?>" href="?member_filter=tidak_aktif">
                <i class="bx bx-user-x me-2"></i> Tidak Aktif
              </a>
            </li>
          </ul>
        </div>

        <!-- Input Pencarian -->
        <div class="input-group rounded-3" style="max-width: 200px;">
          <span class="input-group-text bg-primary text-white"><i class="fas fa-search"></i></span>
          <input type="text" class="form-control" id="search" placeholder="Cari Anggota..." onkeyup="searchTable()">
        </div>

        <!-- Tombol Tambah Anggota -->
        <div>
          <button class="btn btn-primary shadow-sm rounded-3" data-bs-toggle="modal" data-bs-target="#tambahAnggotaModal">
            <i class="fas fa-plus"></i> Tambah
          </button>
        </div>
      </div>
    </div>

    <div class="border border-secondary border-opacity-75 p-2 mb-2 rounded-3 overflow-hidden">
      <table class="table table-hover align-middle" id="anggotaTable">
        <thead class="bg-primary text-white">
          <tr>
            <th class="text-center">NIM</th>
            <th>Nama</th>
            <th>NO. Telp</th>
            <th>Jenis Kelamin</th>
            <th>Jurusan</th>
            <th>Kelas</th>
            <th>Tanggal Lahir</th>
            <th>Status</th>
            <th class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
            <tr style="font-size: 15px;">
              <td class="text-center"><?= $row['nim']; ?></td>
              <td style="font-weight: 600;"><?= $row['nama']; ?></td>
              <td><?= $row['no_telp']; ?></td>
              <td><?= $row['jenis_kelamin']; ?></td>
              <td><?= $row['jurusan']; ?></td>
              <td><?= $row['kelas']; ?></td>
              <td><?= $row['tgl_lahir']; ?></td>
              <td>
                <?php if ($row['status_mhs'] == 'Aktif') { ?>
                  <span class="badge rounded-4" style="background-color:  #e8f8e8; color: #38c172; padding: 10px 10px; font-weight: bold; display: inline-block; width: 100px; height: 28px; text-align: center;">Aktif</span>
                <?php } else { ?>
                  <span class="badge rounded-4" style="background-color: #e2e3e5; color: #6c757d; padding: 10px 10px; font-weight: bold; display: inline-block; width: 100px; height: 28px; text-align: center;">Tidak Aktif</span>
                <?php } ?>
              </td>


              <td class="text-center">
                <button class="btn btn-warning btn-sm rounded-2" data-bs-toggle="modal" data-bs-target="#editAnggotaModal" onclick="loadEditForm('<?= $row['nim']; ?>')">
                  <i class="fas fa-edit"></i> Edit
                </button>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>

      <!-- Navigasi Previous dan Next -->
      <nav class="mt-4">
        <ul class="pagination d-flex justify-content-between align-items-center me-4 ms-4">
          <!-- Tombol Previous -->
          <li class="page-item <?= ($page <= 1) ? 'disabled' : ''; ?>">
            <a class="page-link" href="?page=<?= $page - 1; ?>" aria-label="Previous">
              <span aria-hidden="true">&laquo; Previous</span>
            </a>
          </li>

          <!-- Nomor Halaman -->
          <div class="d-flex justify-content-center flex-grow-1">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
              <li class="page-item <?= ($i == $page) ? 'active' : ''; ?>">
                <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
              </li>
            <?php endfor; ?>
          </div>

          <!-- Tombol Next -->
          <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : ''; ?>">
            <a class="page-link" href="?page=<?= $page + 1; ?>" aria-label="Next">
              <span aria-hidden="true">Next &raquo;</span>
            </a>
          </li>
        </ul>
      </nav>
    </div>

    <!-- Modal Tambah Anggota -->
    <div class="modal fade" id="tambahAnggotaModal" tabindex="-1" aria-labelledby="tambahAnggotaLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="tambahAnggotaLabel">Tambah Anggota Baru</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" id="modalContent">
            <!-- Form akan dimuat di sini menggunakan AJAX -->
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Edit Anggota -->
    <div class="modal fade" id="editAnggotaModal" tabindex="-1" aria-labelledby="editAnggotaLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header bg-warning text-white">
            <h5 class="modal-title" id="editAnggotaLabel">Edit Data Anggota</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" id="editModalContent">
            <!-- Form akan dimuat di sini menggunakan AJAX -->
          </div>
        </div>
      </div>
    </div>
</section>

<script>
  // Ajax Edit Anggota
  function loadEditForm(nim) {
    const modalContent = document.getElementById('editModalContent');
    modalContent.innerHTML = '<p class="text-center text-muted">Loading...</p>';
    fetch(`edit_anggota.php?nim=${nim}`)
      .then(response => response.text())
      .then(data => {
        modalContent.innerHTML = data;
      })
      .catch(error => {
        modalContent.innerHTML = '<p class="text-danger">Gagal memuat data</p>';
      });
  }

  // Ajax Tambah Anggota
  document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('tambahAnggotaModal');
    const modalContent = document.getElementById('modalContent');
    modal.addEventListener('show.bs.modal', function() {
      fetch('add_anggota.php')
        .then(response => response.text())
        .then(data => {
          modalContent.innerHTML = data;
        })
        .catch(error => {
          modalContent.innerHTML = '<p class="text-danger">Gagal memuat form</p>';
        });
    });
  });

  // Daftar Kelas
  document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('tambahAnggotaModal');
    const modalContent = document.getElementById('modalContent');

    modal.addEventListener('show.bs.modal', function() {
      fetch('add_anggota.php')
        .then(response => response.text())
        .then(data => {
          modalContent.innerHTML = data;

          // Tambahkan event listener untuk dropdown jurusan dan kelas
          const jurusanToKelas = {
            "D4 Teknologi Rekayasa Perangkat Lunak": ["23A1"],
            "S1 Teknik Informatika": ["23A1", "23A2", "23A3", "23A4", "23A5", "23A6"],
            "S1 Sistem Informasi": ["23A1", "23A2"],
            "D3 Teknik Komputer": ["23A1"]
          };

          const jurusanDropdown = document.getElementById('jurusan');
          const kelasDropdown = document.getElementById('kelas');

          if (jurusanDropdown && kelasDropdown) {
            jurusanDropdown.addEventListener('change', function() {
              const jurusan = this.value;

              // Reset opsi dropdown kelas
              kelasDropdown.innerHTML = '<option value="" disabled selected>Pilih Kelas</option>';

              if (jurusanToKelas[jurusan]) {
                jurusanToKelas[jurusan].forEach(kelas => {
                  const option = document.createElement('option');
                  option.value = kelas;
                  option.textContent = kelas;
                  kelasDropdown.appendChild(option);
                });
              }
            });
          }
        })
        .catch(error => {
          modalContent.innerHTML = '<p class="text-danger">Gagal memuat form</p>';
          console.error('Error:', error);
        });
    });
  });

  // Fitur Searching
  function searchTable() {
    let input = document.getElementById("search").value.toLowerCase();
    let rows = document.querySelectorAll("#anggotaTable tbody tr");
    rows.forEach((row) => {
      let text = row.innerText.toLowerCase();
      row.style.display = text.includes(input) ? "" : "none";
    });
  }
</script>