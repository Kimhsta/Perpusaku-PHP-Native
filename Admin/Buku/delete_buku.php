<?php
require_once '../../Config/koneksi.php';

if (isset($_GET['kode_buku'])) {
    $kode_buku = $_GET['kode_buku'];

    try {
        // Hapus data buku
        $stmt = $conn->prepare("DELETE FROM buku WHERE kode_buku = :kode_buku");
        $stmt->bindParam(':kode_buku', $kode_buku, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Alert sukses dan kembali ke buku.php
            echo "<script>
                    alert('Buku berhasil dihapus.');
                    window.location.href = 'buku.php';
                  </script>";
        } else {
            // Alert gagal (buku tidak ditemukan)
            echo "<script>
                    alert('Buku tidak ditemukan atau sudah dihapus.');
                    window.location.href = 'buku.php';
                  </script>";
        }
    } catch (PDOException $e) {
        // Alert error jika terjadi kesalahan
        echo "<script>
                alert('Terjadi kesalahan: " . addslashes($e->getMessage()) . "');
                window.location.href = 'buku.php';
              </script>";
    }
} else {
    // Alert jika tidak ada kode buku
    echo "<script>
            alert('Kode buku tidak valid.');
            window.location.href = 'buku.php';
          </script>";
}
?>
