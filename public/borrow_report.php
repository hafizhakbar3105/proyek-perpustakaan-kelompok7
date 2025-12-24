<?php
require_once __DIR__ . '/../models/Borrowing.php';
require_once __DIR__ . '/../core/FineStrategy.php';

$data = Borrowing::report();
$fine = new StudentFine();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Peminjaman | SIP</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #f8fbff;
            font-family: 'Inter', sans-serif;
            color: #334155;
        }

        .report-card {
            background: #ffffff;
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(13, 110, 253, 0.03);
            padding: 2rem;
        }

        /* Styling Tabel */
        .table {
            vertical-align: middle;
        }

        .table thead th {
            border: none;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            padding: 1.2rem 1rem;
            background: #fcfdfe;
        }

        .table tbody tr {
            border-bottom: 1px solid #f1f5f9;
            transition: 0.2s;
        }

        .table tbody tr:hover {
            background-color: #f0f7ff;
        }

        .table tbody td {
            padding: 1.2rem 1rem;
            color: #475569;
        }

        /* Badge & Currency */
        .date-text {
            color: #0d6efd;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .fine-amount {
            color: #dc3545;
            font-weight: 700;
            font-family: 'Courier New', Courier, monospace;
        }

        .back-link {
            text-decoration: none;
            color: #64748b;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: 0.2s;
        }

        .back-link:hover {
            color: #0d6efd;
        }

        .btn-export {
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 0.5rem 1rem;
        }
    </style>
</head>
<body class="py-5">

<div class="container">
    
    <div class="row align-items-end mb-4">
        <div class="col-md-6">
            <a href="index.php" class="back-link small mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                </svg>
                Kembali ke Dashboard
            </a>
            <h3 class="fw-bold m-0 mt-1">📊 Laporan Peminjaman</h3>
            <p class="text-muted small m-0">Rekapitulasi data transaksi dan denda anggota</p>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <button onclick="window.print()" class="btn btn-outline-primary btn-export">
                🖨️ Cetak Laporan
            </button>
        </div>
    </div>

    <div class="report-card">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 20%;">📅 Tanggal</th>
                        <th style="width: 50%;">👤 Nama Anggota</th>
                        <th style="width: 30%; text-align: right;">💰 Estimasi Denda (3 hari)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($data)): ?>
                    <tr>
                        <td colspan="3" class="text-center py-5 text-muted">
                            Tidak ada riwayat peminjaman yang ditemukan.
                        </td>
                    </tr>
                    <?php endif; ?>

                    <?php foreach ($data as $d): ?>
                    <tr>
                        <td>
                            <span class="date-text">
                                <?= date('d M Y', strtotime($d['tanggal'])) ?>
                            </span>
                        </td>
                        <td class="fw-semibold text-dark">
                            <?= htmlspecialchars($d['nama']) ?>
                        </td>
                        <td class="text-end">
                            <span class="fine-amount">
                                Rp <?= number_format($fine->calculate(3), 0, ',', '.') ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 p-3 rounded-4 bg-primary bg-opacity-10">
        <div class="d-flex gap-2 align-items-center text-primary small">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
            </svg>
            <span>Informasi denda dihitung berdasarkan strategi tarif standar <b>StudentFine</b>.</span>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>