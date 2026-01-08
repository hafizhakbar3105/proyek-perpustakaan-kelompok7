<?php
// Native PHP (tanpa framework)

// Class BorrowItem merupakan bagian dari konsep Class & Object
class BorrowItem
{
    // Atribut class BorrowItem (Enkapsulasi)
    private int $book_id;
    private string $judul;

    // Constructor untuk membuat object BorrowItem
    public function __construct(int $book_id, string $judul)
    {
        $this->book_id = $book_id;
        $this->judul   = $judul;
    }

    // Getter book_id sebagai bagian dari Enkapsulasi
    public function getBookId(): int
    {
        return $this->book_id;
    }

    // Getter judul sebagai bagian dari Enkapsulasi
    public function getJudul(): string
    {
        return $this->judul;
    }

    // Method toArray untuk mengubah object menjadi array
    public function toArray(): array
    {
        return [
            'book_id' => $this->book_id,
            'judul'   => $this->judul
        ];
    }
}
