<?php
// Memasukkan file koneksi database agar kita bisa mengambil data statistik
require_once __DIR__ . '/../core/Database.php';

// Mendapatkan objek koneksi PDO dari class Database
$db = Database::getConnection();

// Menghitung total buku: Mengambil jumlah baris dari tabel 'books'
// fetchColumn() digunakan karena kita hanya mengambil satu nilai angka saja
$totalBooks   = $db->query("SELECT COUNT(*) FROM books")->fetchColumn();

// Menghitung total anggota yang terdaftar di tabel 'members'
$totalMembers = $db->query("SELECT COUNT(*) FROM members")->fetchColumn();

// Menghitung total transaksi peminjaman yang pernah dilakukan
$totalBorrow  = $db->query("SELECT COUNT(*) FROM borrowings")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | PerpusKu Premium</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        /* Mendefinisikan variabel warna (CSS Variables) agar mudah dikelola */
        :root {
            --primary-blue: #0061ff;
            --secondary-blue: #60efff;
            --dark-blue: #1e293b;
            --soft-bg: #f8fbff;
        }

        body {
            background-color: var(--soft-bg);
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--dark-blue);
        }
        
        /* Navbar dengan efek Glassmorphism (semi transparan dan blur) */
        .navbar-custom {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px); /* Efek blur di belakang navbar */
            border-bottom: 1px solid rgba(226, 232, 240, 0.5);
            padding: 1.2rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        /* Banner selamat datang dengan warna gradient linear */
        .welcome-gradient {
            background: linear-gradient(135deg, #0061ff 0%, #60efff 100%);
            border-radius: 24px;
            padding: 40px;
            color: white;
            margin-bottom: 40px;
            box-shadow: 0 20px 40px rgba(0, 97, 255, 0.15);
            position: relative;
            overflow: hidden;
        }

        /* Dekorasi lingkaran transparan di dalam banner welcome */
        .welcome-gradient::after {
            content: "";
            position: absolute;
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        /* Kartu Statistik: Memiliki transisi halus saat di-hover */
        .stat-card {
            border: none;
            border-radius: 24px;
            background: #ffffff;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            border: 1px solid #f1f5f9;
            padding: 30px !important;
        }

        .stat-card:hover {
            transform: translateY(-10px); /* Efek melayang ke atas */
            box-shadow: 0 30px 60px rgba(30, 41, 59, 0.1);
        }

        /* Lingkaran untuk menampung icon pada statistik */
        .icon-circle-new {
            width: 60px;
            height: 60px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 20px;
        }

        /* Kartu Navigasi: Menu interaktif yang berubah warna saat di-hover */
        .nav-card-modern {
            background: #ffffff;
            border-radius: 22px;
            border: 1px solid #f1f5f9;
            text-decoration: none !important;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 35px 20px;
            height: 100%;
        }

        .nav-card-modern .menu-icon {
            font-size: 40px;
            margin-bottom: 15px;
            transition: transform 0.3s ease;
        }

        /* Efek saat menu di-hover: background jadi biru dan icon berputar sedikit */
        .nav-card-modern:hover {
            background: var(--primary-blue);
            border-color: var(--primary-blue);
        }

        .nav-card-modern:hover .menu-icon {
            transform: scale(1.2) rotate(-5deg);
        }

        .nav-card-modern:hover .text-dark, 
        .nav-card-modern:hover .text-muted {
            color: white !important;
        }

        /* Tombol keluar dengan warna soft merah */
        .btn-logout {
            background: #fff1f2;
            color: #e11d48;
            border: none;
            padding: 8px 18px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-custom mb-5">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">
            <span class="fs-4 text-primary" style="letter-spacing: -1px;">📘 Perpus<span class="text-dark">ku.</span></span>
        </a>
        <div class="d-flex align-items-center">
            <div class="text-end me-3 d-none d-md-block">
                <div class="fw-bold small">Administrator</div>
                <div class="text-muted small" style="font-size: 11px;">Aktif • Online</div>
            </div>
            <div class="bg-primary rounded-circle me-3 shadow-sm" style="width: 42px; height: 42px; border: 3px solid white;"></div>
            <button class="btn btn-logout">Keluar</button>
        </div>
    </div>
</nav>

<div class="container">
    
    <div class="welcome-gradient">
        <div class="row align-items-center">
            <div class="col-md-7">
                <h1 class="fw-bold mb-2">Halo, Selamat Datang! 👋</h1>
                <p class="mb-0 opacity-75">Sistem informasi manajemen perpustakaan digital Anda. Pantau semua aktivitas buku dan anggota dalam satu dashboard cerdas.</p>
            </div>
            <div class="col-md-5 text-end d-none d-md-block">
                <div class="fs-1 fw-bold opacity-25">DASHBOARD V.2</div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card stat-card">
                <div class="icon-circle-new bg-primary bg-opacity-10 text-primary">📚</div>
                <div class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing: 1px;">Koleksi Buku</div>
                <h2 class="fw-bold m-0 text-gradient">
                    <?= number_format($totalBooks) ?> <small class="text-muted fs-6 fw-normal">Unit</small>
                </h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card">
                <div class="icon-circle-new bg-success bg-opacity-10 text-success">👥</div>
                <div class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing: 1px;">Total Anggota</div>
                <h2 class="fw-bold m-0 text-gradient">
                    <?= number_format($totalMembers) ?> <small class="text-muted fs-6 fw-normal">Orang</small>
                </h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card">
                <div class="icon-circle-new bg-info bg-opacity-10 text-info">🔄</div>
                <div class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing: 1px;">Peminjaman</div>
                <h2 class="fw-bold m-0 text-gradient">
                    <?= number_format($totalBorrow) ?> <small class="text-muted fs-6 fw-normal">Sesi</small>
                </h2>
            </div>
        </div>
    </div>

    <h5 class="fw-bold mb-4 d-flex align-items-center">
        <span class="me-2">⚡</span> Navigasi Cepat
    </h5>

    <div class="row g-4">
        <div class="col-6 col-md-3">
            <a href="books.php" class="nav-card-modern">
                <div class="menu-icon">📂</div>
                <div class="fw-bold text-dark">Data Buku</div>
                <div class="text-muted small">Kelola Stok</div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="members.php" class="nav-card-modern">
                <div class="menu-icon">👤</div>
                <div class="fw-bold text-dark">Anggota</div>
                <div class="text-muted small">Kelola Profil</div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="borrow_create.php" class="nav-card-modern">
                <div class="menu-icon">💳</div>
                <div class="fw-bold text-dark">Transaksi</div>
                <div class="text-muted small">Pinjam Buku</div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="borrow_report.php" class="nav-card-modern">
                <div class="menu-icon">📊</div>
                <div class="fw-bold text-dark">Laporan</div>
                <div class="text-muted small">Analitik Data</div>
            </a>
        </div>
    </div>

    <footer class="mt-5 pt-5 pb-5 text-center">
        <div class="p-4 rounded-4" style="background: white; border: 1px dashed #cbd5e1;">
            <p class="text-muted small mb-0">
                Sistem Informasi Perpustakaan &bull; Versi 2.0.4 Premium &bull; &copy; <?= date('Y') ?>
            </p>
        </div>
    </footer>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>