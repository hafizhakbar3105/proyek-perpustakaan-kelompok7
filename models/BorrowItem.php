<?php
class BorrowItem
{
    private int $book_id;
    private string $judul;

    public function __construct(int $book_id, string $judul)
    {
        $this->book_id = $book_id;
        $this->judul   = $judul;
    }

    public function getBookId(): int
    {
        return $this->book_id;
    }

    public function getJudul(): string
    {
        return $this->judul;
    }

    public function toArray(): array
    {
        return [
            'book_id' => $this->book_id,
            'judul'   => $this->judul
        ];
    }
}
