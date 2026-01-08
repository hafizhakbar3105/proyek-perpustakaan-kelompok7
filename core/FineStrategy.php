<?php
/**
 * Abstract Class FineStrategy
 * Ini bertindak sebagai 'Kontrak' atau 'Interface'.
 * Semua jenis denda wajib mengikuti aturan yang didefinisikan di sini.
 */
abstract class FineStrategy {
    // Mewajibkan setiap strategi untuk memberikan identitas nama (Mahasiswa/Umum)
    abstract public function getName(): string;
    
    // Mewajibkan setiap strategi memiliki rumus perhitungan berdasarkan jumlah hari keterlambatan
    abstract public function calculate(int $daysLate): int;
}

/**
 * Class StudentFine (Strategi Khusus Mahasiswa)
 * Implementasi perhitungan denda jika peminjam adalah seorang mahasiswa.
 */
class StudentFine extends FineStrategy {
    public function getName(): string {
        return "Mahasiswa";
    }

    /**
     * Rumus: Jumlah hari terlambat dikalikan Rp 1.000
     */
    public function calculate(int $daysLate): int {
        return $daysLate * 1000;
    }
}

/**
 * Class PublicFine (Strategi Khusus Umum)
 * Implementasi perhitungan denda jika peminjam berasal dari kategori umum.
 */
class PublicFine extends FineStrategy {
    public function getName(): string {
        return "Umum";
    }

    /**
     * Rumus: Lebih mahal, yaitu jumlah hari terlambat dikalikan Rp 2.000
     */
    public function calculate(int $daysLate): int {
        return $daysLate * 2000;
    }
}