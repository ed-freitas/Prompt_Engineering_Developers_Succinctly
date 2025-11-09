<?php

class Document
{
    private int $id;
    private string $expirationDate;
    private ?string $notes;

    public function __construct(int $id, string $expirationDate, ?string $notes = null)
    {
        $this->id = $id;
        $this->expirationDate = $expirationDate;
        $this->notes = $notes;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getExpirationDate(): string
    {
        return $this->expirationDate;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }
}
