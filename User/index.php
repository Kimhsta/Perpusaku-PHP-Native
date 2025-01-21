<?php
session_start();
require_once '../Config/koneksi.php';

// Query untuk mendapatkan jumlah anggota, buku, peminjaman, dan pengembalian
$anggotaResult = $conn->query("SELECT * FROM anggota");
$bukuResult = $conn->query("SELECT * FROM buku");
$peminjamanResult = $conn->query("SELECT * FROM peminjaman");
$pengembalianResult = $conn->query("SELECT * FROM pengembalian");

if (!isset($_SESSION['nim'])) {
  header('Location: login.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SIMPUS - Digital Reading Club</title>
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css"
    rel="stylesheet" />
  <link
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    rel="stylesheet" />
  <style>
    :root {
      --primary: #7371fc;
      --background-light: rgba(255, 255, 255, 0.95);
      --text-color: #333;
    }

    body {
      padding-bottom: 100px;
      background-color: #f8f9fa;
      display: flex;
      min-height: 100vh;
      /* Ganti height menjadi min-height */
      margin: 0;
      flex-direction: column;
      /* Tambahkan ini agar elemen bisa di-stack secara vertikal */
    }

    .menu-icon {
      cursor: pointer;
      color: var(--text-color);
      font-size: 24px;
    }

    .hamburger-menu {
      display: none;
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      background-color: var(--background-light);
      border-radius: 15px 15px 0 0;
      box-shadow: 0 -8px 16px rgba(0, 0, 0, 0.2);
      overflow: hidden;
      z-index: 1000;
      animation: slideUp 0.3s ease;
    }

    .hamburger-menu .menu-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 20px;
      background-color: var(--primary);
      color: white;
      font-size: 18px;
    }

    .hamburger-menu .menu-header .close-menu {
      cursor: pointer;
      font-size: 20px;
      font-weight: bold;
    }

    .hamburger-menu a {
      display: block;
      padding: 15px 20px;
      text-decoration: none;
      color: var(--text-color);
      font-size: 16px;
      border-bottom: 1px solid #f0f0f0;
      transition: background-color 0.3s ease;
    }

    .hamburger-menu a:hover {
      background-color: #f1f1f1;
      color: var(--primary);
    }

    .menu-overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      z-index: 999;
    }

    @keyframes slideUp {
      from {
        transform: translateY(100%);
      }

      to {
        transform: translateY(0);
      }
    }

    .search-bar {
      margin: 20px;
      margin-bottom: 50px;
    }

    .search-bar input {
      border-radius: 5px 0 0 5px;
    }

    .search-bar button {
      background-color: #d2f902;
      border-radius: 0 5px 5px 0;
    }

    .banner {
      background-color: var(--primary);
      border-radius: 10px;
      margin: 20px;
      padding: 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      color: white;
      height: 150px;
    }

    .banner img {
      transform: scaleX(-1);
      position: relative;
      top: -25px;
      height: 200px;
    }

    .card {
      margin: 8px;
    }

    .card img {
      margin: 20px;
      height: px;
      object-fit: cover;
    }

    .card-title {
      color: var(--primary);
    }

    .btn-primary {
      background-color: var(--primary);
      border-color: var(--primary);
    }

    .btn-primary:hover {
      background-color: #5c5ae1;
      border-color: #5c5ae1;
    }

    .menu-overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      z-index: 999;
    }

    .bottom-nav {
      position: fixed;
      bottom: 0;
      left: 0;
      width: 100%;
      background-color: white;
      border-top: 1px solid #ddd;
      z-index: 10;
    }

    .bottom-nav a {
      color: #888;
      text-decoration: none;
      text-align: center;
      padding: 10px 0;
      flex: 1;
      transition: color 0.3s ease;
    }

    .bottom-nav a.active {
      color: var(--primary);
      font-weight: bold;
    }
  </style>
</head>

<body>
  <!-- Header -->
  <header
    class="d-flex justify-content-between align-items-center p-3 border-bottom">
    <div class="logo">
      <img src="../Assets/Img/logo.png" alt="Logo" height="40" />
    </div>
    <div class="menu-icon">
      <i class="fas fa-bars fa-lg"></i>
    </div>
    <!-- Hamburger Menu -->
    <div class="hamburger-menu">
      <div class="menu-header">
        <h5>Kategori</h5>
        <span class="close-menu">&times;</span>
      </div>
      <a href="#">All Categories</a>
      <a href="#">Teori Komunikasi</a>
      <a href="#">Public Relations</a>
      <a href="#">Jurnalistik</a>
      <a href="#">Komunikasi Digital</a>
    </div>
    <div class="menu-overlay"></div>
  </header>

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


  <!-- Bottom Navigation -->
  <nav class="bottom-nav d-flex justify-content-around py-2">
    <a href="#" class="nav-link active" data-id="home">
      <i class="fas fa-home fa-lg"></i>
      <div>Home</div>
    </a>
    <a href="#" class="nav-link" data-id="borrowing">
      <i class="fas fa-book fa-lg"></i>
      <div>Borrowing</div>
    </a>
    <a href="#" class="nav-link" data-id="account">
      <i class="fas fa-user fa-lg"></i>
      <div>Account</div>
    </a>
  </nav>

  <script src="../Assets/scripts/home.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>