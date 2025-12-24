<?php
abstract class FineStrategy {
    abstract public function getName(): string;
    abstract public function calculate(int $daysLate): int;
}

class StudentFine extends FineStrategy {
    public function getName(): string {
        return "Mahasiswa";
    }

    public function calculate(int $daysLate): int {
        return $daysLate * 1000;
    }
}

class PublicFine extends FineStrategy {
    public function getName(): string {
        return "Umum";
    }

    public function calculate(int $daysLate): int {
        return $daysLate * 2000;
    }
}
