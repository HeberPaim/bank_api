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
}