<?php
require_once __DIR__ . '/../core/BaseModel.php';
require_once __DIR__ . '/../core/Database.php';

class Book extends BaseModel
{
    private string $kode;
    private string $judul;
    private int $stok;

    public function __construct(string $kode, string $judul, int $stok)
    {
        $this->setKode($kode);
        $this->setJudul($judul);
        $this->setStok($stok);
    }

    // ===== ENKAPSULASI (GETTER & SETTER) =====
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

    // ===== CRUD =====
    public function save(): bool
    {
        $db = Database::getConnection();

        if ($this->id === null) {
            $stmt = $db->prepare(
                "INSERT INTO books (kode, judul, stok) VALUES (?,?,?)"
            );
            $ok = $stmt->execute([$this->kode, $this->judul, $this->stok]);
            if ($ok) {
                $this->id = (int)$db->lastInsertId();
            }
            return $ok;
        }

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

    public function delete(): bool
    {
        if ($this->id === null) return false;

        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM books WHERE id=?");
        return $stmt->execute([$this->id]);
    }

    public static function all(): array
    {
        $db = Database::getConnection();
        return $db->query(
            "SELECT * FROM books ORDER BY judul"
        )->fetchAll(PDO::FETCH_ASSOC);
    }
}
