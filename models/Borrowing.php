<?php
require_once __DIR__ . '/../core/BaseModel.php';
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/BorrowItem.php';

class Borrowing extends BaseModel
{
    private int $member_id;
    private string $tanggal;
    private array $items = [];

    public function __construct(int $member_id)
    {
        $this->member_id = $member_id;
        $this->tanggal   = date('Y-m-d');
    }

    public function addItem(BorrowItem $item): void
    {
        $this->items[] = $item;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function save(): bool
    {
        $db = Database::getConnection();
        $db->beginTransaction();

        try {
            $stmt = $db->prepare(
                "INSERT INTO borrowings (member_id, tanggal) VALUES (?,?)"
            );
            $stmt->execute([$this->member_id, $this->tanggal]);
            $this->id = (int)$db->lastInsertId();

            $stmtItem = $db->prepare(
                "INSERT INTO borrow_items (borrowing_id, book_id, judul)
                 VALUES (?,?,?)"
            );

            foreach ($this->items as $item) {
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
            $db->rollBack();
            throw $e;
        }
    }

    public function delete(): bool
    {
        return false;
    }

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
