<?php
require_once __DIR__ . '/../models/Book.php';
require_once __DIR__ . '/../models/Member.php';
require_once __DIR__ . '/../models/Borrowing.php';
require_once __DIR__ . '/../models/BorrowItem.php';

$books   = Book::all();
$members = Member::all();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $borrow = new Borrowing((int)$_POST['member_id']);

    foreach ($_POST['book_id'] as $i => $bookId) {
        if ($_POST['qty'][$i] > 0) {
            $borrow->addItem(
                new BorrowItem($bookId, $_POST['judul'][$i])
            );
        }
    }

    $borrow->save();
    header("Location: borrow_report.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi Peminjaman | SIP</title>
    
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
            overflow: hidden;
        }

        .section-title {
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            font-weight: 700;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
        }

        .section-title::after {
            content: "";
            flex: 1;
            height: 1px;
            background: #e2e8f0;
            margin-left: 1rem;
        }

        .form-select, .form-control {
            border-radius: 12px;
            padding: 0.75rem 1rem;
            border: 1px solid #e2e8f0;
        }

        .form-select:focus, .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
        }

        /* Table Styling */
        .table {
            vertical-align: middle;
        }
        
        .table thead th {
            background: #f8fbff;
            border: none;
            color: #475569;
            font-weight: 600;
            padding: 1rem;
        }

        .qty-input {
            max-width: 100px;
            text-align: center;
            font-weight: 600;
            border-color: #cbd5e1;
        }

        .btn-submit {
            background: #0d6efd;
            border: none;
            border-radius: 12px;
            padding: 1rem 2rem;
            font-weight: 700;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
        }

        .btn-submit:hover {
            background: #0b5ed7;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(13, 110, 253, 0.3);
        }

        .back-link {
            text-decoration: none;
            color: #64748b;
            transition: 0.2s;
        }

        .back-link:hover { color: #0d6efd; }
    </style>
</head>
<body class="py-5">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            <div class="mb-4 d-flex justify-content-between align-items-end">
                <div>
                    <a href="index.php" class="back-link small mb-2 d-block">← Kembali ke Dashboard</a>
                    <h3 class="fw-bold m-0">📝 Transaksi Peminjaman</h3>
                </div>
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold">
                    ID Transaksi: AUTO
                </span>
            </div>

            <form method="post">
                <div class="main-card p-4 p-md-5">
                    
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <div class="section-title">Pilih Anggota</div>
                            <label class="form-label small fw-semibold text-muted">Nama Peminjam</label>
                            <select name="member_id" class="form-select" required>
                                <option value="" disabled selected>Pilih nama anggota...</option>
                                <?php foreach ($members as $m): ?>
                                <option value="<?= $m['id'] ?>"><?= $m['nama'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="section-title">Daftar Buku Tersedia</div>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th style="width: 70%">Judul Buku</th>
                                    <th class="text-center">Jumlah Pinjam</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($books as $b): ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($b['judul']) ?></div>
                                        <div class="text-muted small">ID: <?= htmlspecialchars($b['id'] ?? 'N/A') ?></div>
                                        <input type="hidden" name="book_id[]" value="<?= $b['id'] ?>">
                                        <input type="hidden" name="judul[]" value="<?= $b['judul'] ?>">
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <input type="number" name="qty[]" value="0" min="0" class="form-control qty-input">
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-5 border-top pt-4 text-end">
                        <button type="submit" class="btn btn-submit text-white">
                            Proses Peminjaman & Simpan
                        </button>
                    </div>

                </div>
            </form>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>