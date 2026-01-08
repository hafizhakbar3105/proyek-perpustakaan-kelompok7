<?php
// Memasukkan file model Member agar aplikasi bisa mengakses data anggota dari database
require_once __DIR__ . '/../models/Member.php';

// Mengambil semua daftar anggota yang tersimpan di database untuk ditampilkan di tabel
$members = Member::all();

// Mengecek apakah ada data yang dikirim melalui formulir (Metode POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Membuat objek baru dari class Member dengan data nama dan tipe dari input user
    $member = new Member($_POST['nama'], $_POST['tipe']);
    
    // Menyimpan data anggota baru tersebut ke dalam database
    $member->save();
    
    // Setelah data tersimpan, halaman akan dimuat ulang (refresh) agar daftar anggota terbaru muncul
    header("Location: members.php");
    exit; // Menghentikan proses script setelah instruksi redirect
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Anggota | SIP</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        /* Pengaturan tema visual dasar halaman */
        body {
            background-color: #f8fbff;
            font-family: 'Inter', sans-serif;
            color: #334155;
        }

        /* Container putih untuk formulir agar terlihat rapi dan elegan */
        .main-card {
            background: #ffffff;
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
            padding: 2rem;
        }

        /* Menyeragamkan tampilan input teks dan pilihan dropdown */
        .form-control, .form-select {
            border-radius: 10px;
            padding: 0.6rem 1rem;
            border: 1px solid #e2e8f0;
        }

        /* Tombol simpan dengan efek transisi hover (UX: interaksi tombol) */
        .btn-primary-custom {
            background-color: #0d6efd;
            border: none;
            border-radius: 10px;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-primary-custom:hover {
            background-color: #0b5ed7;
            transform: translateY(-2px); /* Tombol sedikit naik saat disentuh mouse */
        }

        /* Desain Tabel Modern: Baris memiliki jarak (spacing) dan sudut melengkung */
        .table {
            border-collapse: separate;
            border-spacing: 0 8px;
        }

        .table thead th {
            border: none;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            padding: 1rem;
        }

        .table tbody tr {
            background-color: #ffffff;
        }

        .table tbody td {
            padding: 1rem;
            border-top: 1px solid #f1f5f9;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        /* Styling spesifik untuk pojok kiri dan kanan baris tabel agar membulat */
        .table tbody td:first-child {
            border-left: 1px solid #f1f5f9;
            border-top-left-radius: 12px;
            border-bottom-left-radius: 12px;
        }

        .table tbody td:last-child {
            border-right: 1px solid #f1f5f9;
            border-top-right-radius: 12px;
            border-bottom-right-radius: 12px;
        }

        /* Desain Label (Badge) untuk membedakan kategori anggota secara visual */
        .badge-member {
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: capitalize;
        }
        /* Warna biru untuk Mahasiswa, Kuning/Cokelat untuk Umum */
        .badge-mahasiswa { background: #e0f2fe; color: #0369a1; }
        .badge-umum { background: #fef3c7; color: #92400e; }

        .back-link {
            text-decoration: none;
            color: #64748b;
            font-weight: 500;
        }
        .back-link:hover { color: #0d6efd; }
    </style>
</head>
<body class="py-5">

<div class="container">
    <div class="mb-4">
        <a href="index.php" class="back-link small mb-2 d-block">← Kembali ke Dashboard</a>
        <h3 class="fw-bold m-0">👤 Master Anggota</h3>
    </div>

    <div class="main-card mb-4">
        <h6 class="fw-bold mb-3 text-secondary">Registrasi Anggota Baru</h6>
        <form method="post" class="row g-3">
            <div class="col-md-6">
                <label class="form-label small fw-bold">Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" placeholder="Masukkan nama anggota..." required>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold">Tipe Anggota</label>
                <select name="tipe" class="form-select">
                    <option value="mahasiswa">Mahasiswa</option>
                    <option value="umum">Umum</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary-custom w-100">Simpan</button>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama Anggota</th>
                    <th>Status / Tipe</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($members)): ?>
                <tr>
                    <td colspan="3" class="text-center text-muted py-5">Belum ada anggota terdaftar.</td>
                </tr>
                <?php endif; ?>

                <?php foreach ($members as $m): ?>
                <tr>
                    <td class="fw-semibold"><?= htmlspecialchars($m['nama']) ?></td>
                    <td>
                        <span class="badge-member <?= $m['tipe'] == 'mahasiswa' ? 'badge-mahasiswa' : 'badge-umum' ?>">
                            <?= htmlspecialchars($m['tipe']) ?>
                        </span>
                    </td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-light text-primary fw-bold">Edit</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>