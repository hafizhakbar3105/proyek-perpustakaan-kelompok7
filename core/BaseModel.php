<?php
abstract class BaseModel {
    protected ?int $id = null;
    protected string $created_at;
    protected string $updated_at;

    public function getId(): ?int {
        return $this->id;
    }

    abstract public function save(): bool;
    abstract public function delete(): bool;
}
