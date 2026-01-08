<?php
// Native PHP (tanpa framework)
require_once __DIR__ . '/../core/BaseModel.php';
require_once __DIR__ . '/../core/Database.php';

// Class Member merupakan bagian dari konsep Class & Object
// extends BaseModel menunjukkan Inheritance dan penggunaan Abstract Class
class Member extends BaseModel
{
    // Atribut class Member (Enkapsulasi)
    private string $nama;
    private string $tipe; // mahasiswa / umum

    // Constructor untuk membuat object Member
    public function __construct(string $nama, string $tipe)
    {
        $this->setNama($nama);
        $this->setTipe($tipe);
    }

    // Getter sebagai bagian dari Enkapsulasi
    public function getNama(): string
    {
        return $this->nama;
    }

    public function getTipe(): string
    {
        return $this->tipe;
    }

    // Setter sebagai bagian dari Enkapsulasi
    public function setNama(string $nama): void
    {
        if (strlen($nama) < 3) {
            throw new InvalidArgumentException("Nama anggota terlalu pendek");
        }
        $this->nama = $nama;
    }

    public function setTipe(string $tipe): void
    {
        if (!in_array($tipe, ['mahasiswa', 'umum'])) {
            throw new InvalidArgumentException("Tipe anggota tidak valid");
        }
        $this->tipe = $tipe;
    }

    // Method save merupakan bagian dari Polimorfisme (override dari BaseModel)
    public function save(): bool
    {
        $db = Database::getConnection();

        if ($this->id === null) {
            // Create (INSERT data)
            $stmt = $db->prepare(
                "INSERT INTO members (nama, tipe) VALUES (?,?)"
            );
            $ok = $stmt->execute([$this->nama, $this->tipe]);
            if ($ok) {
                $this->id = (int)$db->lastInsertId();
            }
            return $ok;
        }

        // Update (UPDATE data)
        $stmt = $db->prepare(
            "UPDATE members SET nama=?, tipe=? WHERE id=?"
        );
        return $stmt->execute([$this->nama, $this->tipe, $this->id]);
    }

    // Method delete untuk menghapus data anggota
    public function delete(): bool
    {
        if ($this->id === null) return false;

        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM members WHERE id=?");
        return $stmt->execute([$this->id]);
    }

    // Method static all untuk mengambil seluruh data anggota
    public static function all(): array
    {
        $db = Database::getConnection();
        return $db->query(
            "SELECT * FROM members ORDER BY nama"
        )->fetchAll(PDO::FETCH_ASSOC);
    }
}
