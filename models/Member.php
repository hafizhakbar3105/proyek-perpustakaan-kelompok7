<?php
require_once __DIR__ . '/../core/BaseModel.php';
require_once __DIR__ . '/../core/Database.php';

class Member extends BaseModel
{
    private string $nama;
    private string $tipe; // mahasiswa / umum

    public function __construct(string $nama, string $tipe)
    {
        $this->setNama($nama);
        $this->setTipe($tipe);
    }

    // ===== ENKAPSULASI =====
    public function getNama(): string
    {
        return $this->nama;
    }

    public function getTipe(): string
    {
        return $this->tipe;
    }

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

    // ===== CRUD =====
    public function save(): bool
    {
        $db = Database::getConnection();

        if ($this->id === null) {
            $stmt = $db->prepare(
                "INSERT INTO members (nama, tipe) VALUES (?,?)"
            );
            $ok = $stmt->execute([$this->nama, $this->tipe]);
            if ($ok) {
                $this->id = (int)$db->lastInsertId();
            }
            return $ok;
        }

        $stmt = $db->prepare(
            "UPDATE members SET nama=?, tipe=? WHERE id=?"
        );
        return $stmt->execute([$this->nama, $this->tipe, $this->id]);
    }

    public function delete(): bool
    {
        if ($this->id === null) return false;

        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM members WHERE id=?");
        return $stmt->execute([$this->id]);
    }

    public static function all(): array
    {
        $db = Database::getConnection();
        return $db->query(
            "SELECT * FROM members ORDER BY nama"
        )->fetchAll(PDO::FETCH_ASSOC);
    }
}
