<?php
// Native PHP (tanpa framework)
require_once __DIR__ . '/../core/BaseModel.php';
require_once __DIR__ . '/../core/Database.php';

// Class Book merupakan bagian dari konsep Class & Object
// extends BaseModel menunjukkan Inheritance dan penggunaan Abstract Class
class Book extends BaseModel
{
    // Atribut class Book (Enkapsulasi)
    private string $kode;
    private string $judul;
    private int $stok;

    // Constructor sebagai method pembentuk object Book
    public function __construct(string $kode, string $judul, int $stok)
    {
        $this->setKode($kode);
        $this->setJudul($judul);
        $this->setStok($stok);
    }

    // Getter sebagai bagian dari Enkapsulasi
    public function getKode(): string
    {
        return $this->kode;
    }

    public function getJudul(): string
    {
        return $this->judul;
    }

    public function getStok(): int
    {
        return $this->stok;
    }

    // Setter sebagai bagian dari Enkapsulasi
    public function setKode(string $kode): void
    {
        if (strlen($kode) < 3) {
            throw new InvalidArgumentException("Kode buku terlalu pendek");
        }
        $this->kode = $kode;
    }

    public function setJudul(string $judul): void
    {
        if (strlen($judul) < 3) {
            throw new InvalidArgumentException("Judul buku terlalu pendek");
        }
        $this->judul = $judul;
    }

    public function setStok(int $stok): void
    {
        if ($stok < 0) {
            throw new InvalidArgumentException("Stok tidak boleh negatif");
        }
        $this->stok = $stok;
    }

    // Method save merupakan bagian dari Polimorfisme (override dari BaseModel)
    public function save(): bool
    {
        $db = Database::getConnection();

        if ($this->id === null) {
            // Create (INSERT data)
            $stmt = $db->prepare(
                "INSERT INTO books (kode, judul, stok) VALUES (?,?,?)"
            );
            $ok = $stmt->execute([$this->kode, $this->judul, $this->stok]);
            if ($ok) {
                $this->id = (int)$db->lastInsertId();
            }
            return $ok;
        }

        // Update (UPDATE data)
        $stmt = $db->prepare(
            "UPDATE books SET kode=?, judul=?, stok=? WHERE id=?"
        );
        return $stmt->execute([
            $this->kode,
            $this->judul,
            $this->stok,
            $this->id
        ]);
    }

    // Method delete untuk menghapus data
    public function delete(): bool
    {
        if ($this->id === null) return false;

        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM books WHERE id=?");
        return $stmt->execute([$this->id]);
    }

    // Method static all untuk mengambil seluruh data buku
    public static function all(): array
    {
        $db = Database::getConnection();
        return $db->query(
            "SELECT * FROM books ORDER BY judul"
        )->fetchAll(PDO::FETCH_ASSOC);
    }
}
