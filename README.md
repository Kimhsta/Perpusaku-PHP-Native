# **Perpusaku - Sistem Informasi Perpustakaan**  
Sistem Informasi Perpustakaan untuk Universitas Duta Bangsa Surakarta.

---

## **Deskripsi Proyek**  
Perpusaku adalah sistem informasi berbasis web yang dirancang untuk mempermudah pengelolaan perpustakaan. Sistem ini dibangun menggunakan **PHP Native** dan **Bootstrap 5**, dengan fitur-fitur utama yang dirancang untuk mendukung kebutuhan perpustakaan modern.

Proyek ini mencakup manajemen buku, peminjaman, pengembalian, dan pengelolaan pengguna yang dibagi menjadi tiga jenis:  
1. **Owner:** Pemilik sistem yang dapat menambahkan admin.  
2. **Admin:** Mengelola buku, peminjaman, dan pengembalian.  
3. **User:** Mahasiswa/peminjam yang dapat meminjam dan mengembalikan buku.

---

## **Fitur Utama**  
### **Untuk Owner:**
- Menambahkan, mengedit, dan menghapus akun admin.  
- Melihat laporan peminjaman dan pengembalian buku secara keseluruhan.  
- Mengelola data statistik perpustakaan.  

### **Untuk Admin:**
- Menambahkan, mengedit, dan menghapus data buku.  
- Mengelola data peminjaman dan pengembalian buku.  
- Melihat daftar peminjam aktif dan status pengembalian.  
- Mengatur denda untuk pengembalian buku terlambat.  

### **Untuk User:**
- Melakukan registrasi dan login.  
- Mencari buku berdasarkan kategori, judul, atau penulis.  
- Melihat detail buku (stok, deskripsi, tahun terbit).  
- Mengajukan peminjaman buku.  
- Melihat riwayat peminjaman dan status pengembalian.  

---

## **Teknologi yang Digunakan**  
- **Frontend:** Bootstrap 5 untuk antarmuka pengguna yang responsif dan modern.  
- **Backend:** PHP Native untuk pemrosesan data.  
- **Database:** MySQL untuk penyimpanan data buku, pengguna, dan transaksi.  
- **Server:** Hosting lokal atau layanan hosting berbasis Apache.  

---

## **Struktur Folder**  
```plaintext
Perpusaku/
├── assets/              # File CSS, JS, dan gambar
├── database/            # Skrip SQL untuk inisialisasi database
├── includes/            # File PHP untuk koneksi database dan fungsi umum
├── views/               # File HTML/PHP untuk antarmuka pengguna
├── controllers/         # File logika backend
├── index.php            # Halaman utama aplikasi
└── README.md            # Dokumentasi proyek
