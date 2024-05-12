<?php
// src/Repository/AccountRepository.php

namespace App\Repository;

class AccountRepository
{
    private array $accounts = [];

    public function reset(): void
    {
        $this->accounts = [];
    }

    public function getBalance(string $accountId): ?int
    {
        return $this->accounts[$accountId]['balance'] ?? null;
    }
}