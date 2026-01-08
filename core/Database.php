<?php
// Mengambil file konfigurasi yang berisi konstanta DB_HOST, DB_NAME, dll.
require_once __DIR__ . '/../config/config.php';

class Database {
    /**
     * Properti statis untuk menyimpan satu-satunya instance (objek) koneksi PDO.
     * ?PDO berarti nilainya bisa berupa objek PDO atau null.
     */
    private static ?PDO $instance = null;

    /**
     * Constructor dibuat private agar class ini TIDAK BISA dibuat objeknya dari luar.
     * Contoh: $db = new Database(); // Ini akan menghasilkan ERROR.
     * Ini adalah inti dari Singleton Pattern.
     */
    private function __construct() {}

    /**
     * Method statis untuk mengambil koneksi database.
     * Karena statis, kita memanggilnya dengan: Database::getConnection();
     */
    public static function getConnection(): PDO {
        // Cek apakah instance koneksi sudah pernah dibuat sebelumnya
        if (self::$instance === null) {
            try {
                // DSN (Data Source Name): Informasi alamat server, nama database, dan charset
                $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8";
                
                // Membuat objek PDO baru untuk koneksi ke MySQL
                // Parameter: DSN, Username, Password, dan Opsi Tambahan
                self::$instance = new PDO($dsn, DB_USER, DB_PASS, [
                    // Mengatur PDO agar menampilkan error dalam bentuk Exception
                    // Ini memudahkan kita untuk menangani error menggunakan try-catch
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    // Mengatur agar data yang diambil otomatis berbentuk array asosiatif
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);
            } catch (PDOException $e) {
                // Jika koneksi gagal, hentikan aplikasi dan tampilkan pesan error
                die("Koneksi Database Gagal: " . $e->getMessage());
            }
        }
        
        // Mengembalikan instance yang sudah ada (atau yang baru saja dibuat)
        return self::$instance;
    }
}