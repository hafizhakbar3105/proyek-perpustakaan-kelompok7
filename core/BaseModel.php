<?php
/**
 * Abstract class berarti kelas ini tidak bisa diinstansiasi langsung (tidak bisa: new BaseModel()).
 * Kelas ini hanya berfungsi sebagai cetak biru (blueprint) untuk kelas-kelas lain (Book, Member, dll).
 */
abstract class BaseModel {
    
    // Properti $id dibuat protected agar bisa diakses oleh kelas turunannya.
    // ?int berarti variabel ini boleh berisi integer atau null (nullable).
    protected ?int $id = null;

    // Properti untuk mencatat waktu data dibuat (untuk keperluan audit database).
    protected string $created_at;

    // Properti untuk mencatat waktu terakhir data diubah.
    protected string $updated_at;

    /**
     * Getter untuk ID. 
     * Karena properti $id dilindungi (protected), kelas luar harus menggunakan method ini 
     * untuk mengambil ID objek tersebut.
     */
    public function getId(): ?int {
        return $this->id;
    }

    /**
     * Method Abstract: save()
     * Kelas induk tidak menentukan "bagaimana" cara menyimpan data, 
     * karena cara simpan Buku dan Anggota pasti berbeda.
     * Namun, ini "mewajibkan" setiap kelas turunan untuk memiliki method save().
     */
    abstract public function save(): bool;

    /**
     * Method Abstract: delete()
     * Sama seperti save, ini mewajibkan setiap kelas turunan (seperti Book/Member) 
     * memiliki logika penghapusan datanya masing-masing.
     */
    abstract public function delete(): bool;
}