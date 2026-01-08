<?php
// Native PHP (tanpa framework)
require_once __DIR__ . '/../core/BaseModel.php';
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/BorrowItem.php';

// Class Borrowing merupakan bagian dari konsep Class & Object
// extends BaseModel menunjukkan Inheritance dan penggunaan Abstract Class
class Borrowing extends BaseModel
{
    // Atribut class Borrowing (Enkapsulasi)
    private int $member_id;
    private string $tanggal;
    private array $items = [];

    // Constructor untuk membuat object Borrowing
    public function __construct(int $member_id)
    {
        $this->member_id = $member_id;
        $this->tanggal   = date('Y-m-d');
    }

    // Method addItem untuk menambahkan objek BorrowItem
    public function addItem(BorrowItem $item): void
    {
        $this->items[] = $item;
    }

    // Getter items sebagai bagian dari Enkapsulasi
    public function getItems(): array
    {
        return $this->items;
    }

    // Method save merupakan bagian dari Polimorfisme (override dari BaseModel)
    // Digunakan untuk menyimpan data peminjaman dan detailnya
    public function save(): bool
    {
        $db = Database::getConnection();
        $db->beginTransaction();

        try {
            // Insert data peminjaman
            $stmt = $db->prepare(
                "INSERT INTO borrowings (member_id, tanggal) VALUES (?,?)"
            );
            $stmt->execute([$this->member_id, $this->tanggal]);
            $this->id = (int)$db->lastInsertId();

            // Insert detail buku yang dipinjam
            $stmtItem = $db->prepare(
                "INSERT INTO borrow_items (borrowing_id, book_id, judul)
                 VALUES (?,?,?)"
            );

            foreach ($this->items as $item) {
                // Mengambil data dari objek BorrowItem
                $data = $item->toArray();
                $stmtItem->execute([
                    $this->id,
                    $data['book_id'],
                    $data['judul']
                ]);
            }

            $db->commit();
            return true;
        } catch (Exception $e) {
            // Rollback jika terjadi error
            $db->rollBack();
            throw $e;
        }
    }

    // Method delete (belum diimplementasikan)
    public function delete(): bool
    {
        return false;
    }

    // Method static report untuk menampilkan laporan peminjaman
    public static function report(): array
    {
        $db = Database::getConnection();
        $stmt = $db->query(
            "SELECT b.id, m.nama, b.tanggal
             FROM borrowings b
             JOIN members m ON m.id = b.member_id
             ORDER BY b.tanggal DESC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
