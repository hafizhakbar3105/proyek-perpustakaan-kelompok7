<?php
require_once __DIR__ . '/../models/Book.php';

$books = Book::all();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $book = new Book($_POST['kode'], $_POST['judul'], (int)$_POST['stok']);
    $book->save();
    header("Location: books.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Buku | SIP</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #f8fbff;
            font-family: 'Inter', sans-serif;
            color: #334155;
        }

        .main-card {
            background: #ffffff;
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
            padding: 2rem;
        }

        .form-control {
            border-radius: 10px;
            padding: 0.6rem 1rem;
            border: 1px solid #e2e8f0;
        }

        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
        }

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
            transform: translateY(-2px);
        }

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
            letter-spacing: 0.05em;
            padding: 1rem;
        }

        .table tbody tr {
            background-color: #ffffff;
            transition: 0.2s;
        }

        .table tbody td {
            padding: 1rem;
            border-top: 1px solid #f1f5f9;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

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

        .badge-stok {
            background: #e0f2fe;
            color: #0369a1;
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .back-link {
            text-decoration: none;
            color: #64748b;
            font-weight: 500;
            transition: 0.2s;
        }

        .back-link:hover {
            color: #0d6efd;
        }
    </style>
</head>
<body class="py-5">

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="index.php" class="back-link small mb-2 d-block">← Kembali ke Dashboard</a>
            <h3 class="fw-bold m-0">📚 Master Data Buku</h3>
        </div>
    </div>

    <div class="main-card mb-4">
        <h6 class="fw-bold mb-3 text-secondary">Tambah Buku Baru</h6>
        <form method="post" class="row g-3">
            <div class="col-md-3">
                <label class="form-label small fw-bold">Kode Buku</label>
                <input type="text" name="kode" class="form-control" placeholder="Contoh: B-001" required>
            </div>
            <div class="col-md-5">
                <label class="form-label small fw-bold">Judul Lengkap</label>
                <input type="text" name="judul" class="form-control" placeholder="Masukkan judul buku..." required>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold">Jumlah Stok</label>
                <input type="number" name="stok" class="form-control" placeholder="0" min="0">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-primary-custom w-100">Simpan Buku</button>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Judul Buku</th>
                    <th>Stok Tersedia</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($books)): ?>
                <tr>
                    <td colspan="4" class="text-center text-muted py-5">Belum ada data buku.</td>
                </tr>
                <?php endif; ?>

                <?php foreach ($books as $b): ?>
                <tr>
                    <td class="fw-bold text-primary"><?= htmlspecialchars($b['kode']) ?></td>
                    <td><?= htmlspecialchars($b['judul']) ?></td>
                    <td>
                        <span class="badge-stok">
                            <?= $b['stok'] ?> Ekspl.
                        </span>
                    </td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-light text-primary fw-bold">Detail</button>
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